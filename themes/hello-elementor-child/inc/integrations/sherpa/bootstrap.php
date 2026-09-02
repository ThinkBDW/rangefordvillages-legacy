<?php
/**
 * Sherpa CRM integration.
 *
 * Replaces the contact-form-to-any-api plugin and the theme's
 * custom_cf7_update_post_meta() routing hack (see legacy-routing.php).
 *
 * Why this exists, from the evidence in the inherited install:
 *
 *  1. ROUTING WAS A RACE. The old code chose a Sherpa community endpoint per
 *     submission and wrote it into the *plugin's* cf7anyapi_base_url postmeta
 *     before the plugin fired. That is shared mutable state on a per-request
 *     path: two concurrent submissions for different villages could send a lead
 *     to the wrong community. Two of the seven wired forms were also pointed at
 *     the wrong connector entirely -- form 637 at connector 17934, which does
 *     not exist, and form 32660 at connector 17746, which belongs to form
 *     10765.
 *
 *  2. FAILURES WERE SILENT. wp_cf7anyapi_logs records what actually happened:
 *     in 2026 alone, 670 leads succeeded, 291 were duplicates (benign) and
 *     **69 returned HTTP 500 and were dropped with no retry and no alert**.
 *     Those 500s are spread evenly across every month -- roughly ten lost
 *     enquiries a month, systemically, not one outage.
 *
 *  3. TIMESTAMPS WERE WRONG. Sherpa's integration guide requires
 *     referralDateTime in UTC ("zulu") time. The old code sent
 *     current_time('Y-m-d H:i:s'), i.e. site-local time in a non-ISO format.
 *
 * The design consequences:
 *
 *  - Every lead is written to the database BEFORE any HTTP call, so a lead can
 *    never be lost to a broken queue, a CRM outage or a rollback.
 *  - Dispatch is queued via Action Scheduler with bounded retries, so a
 *    transient 500 is retried instead of discarded.
 *  - Every attempt is recorded with its HTTP status and response body, and
 *    permanent failures raise an alert.
 *  - Routing is resolved in code from an explicit config, with no shared state.
 *
 * @package hello-elementor-child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Schema version for the lead store; bump to trigger a table upgrade.
 *
 * 1.1.0 adds crm_reference and redacted_at for the retention policy in
 * class-store.php.
 */
define( 'RV_SHERPA_DB_VERSION', '1.1.0' );

/** Sherpa company ID. Constant across all six communities. */
define( 'RV_SHERPA_COMPANY_ID', 27 );

/**
 * API base URL.
 *
 * Production is the .co.uk region. Sherpa's sandbox is documented at
 * sandbox.sherpacrm.com (see "April 2022 Website Integration Setup Process and
 * Tips.pdf" in the audit folder) with company 250 / community 15.
 *
 * Override per environment in wp-config.php:
 *     define( 'RV_SHERPA_API_BASE', 'https://sandbox.sherpacrm.com/v1' );
 *     define( 'RV_SHERPA_COMPANY_ID_OVERRIDE', 250 );
 */
if ( ! defined( 'RV_SHERPA_API_BASE' ) ) {
	define( 'RV_SHERPA_API_BASE', 'https://members.sherpacrm.co.uk/v1' );
}

/**
 * Dry run.
 *
 * When true, leads are mapped, validated and stored exactly as normal, and the
 * request that *would* have been sent is logged -- but nothing is POSTed. This
 * is how the integration is reconciled against the outgoing plugin before
 * cutover without writing into the production CRM.
 *
 * Defaults to on anywhere that is not production, so a developer cannot
 * accidentally push test leads into a live CRM.
 */
if ( ! defined( 'RV_SHERPA_DRY_RUN' ) ) {
	define( 'RV_SHERPA_DRY_RUN', 'production' !== wp_get_environment_type() );
}

require_once __DIR__ . '/class-store.php';
require_once __DIR__ . '/class-router.php';
require_once __DIR__ . '/class-mapper.php';
require_once __DIR__ . '/class-client.php';
require_once __DIR__ . '/class-dispatcher.php';
require_once __DIR__ . '/class-admin.php';
require_once __DIR__ . '/hooks.php';

/**
 * Load the form configuration.
 *
 * @return array Keyed by CF7 form ID.
 */
function rv_sherpa_config() {
	static $config = null;

	if ( null === $config ) {
		$config = require __DIR__ . '/config.php';

		/**
		 * Filter the Sherpa form configuration.
		 *
		 * @param array $config Keyed by CF7 form ID.
		 */
		$config = apply_filters( 'rv_sherpa_config', $config );
	}

	return $config;
}

/**
 * Resolve the API token.
 *
 * Must come from wp-config.php or the environment -- never the database. The
 * previous plugin stored a bearer token in plaintext in wp_postmeta, which is
 * both readable by any admin-area code and carried into every database export.
 *
 * @return string Empty string when unset.
 */
function rv_sherpa_token() {
	if ( defined( 'SHERPA_API_TOKEN' ) && SHERPA_API_TOKEN ) {
		return (string) SHERPA_API_TOKEN;
	}

	$env = getenv( 'SHERPA_API_TOKEN' );

	/**
	 * Filter the Sherpa API token.
	 *
	 * Provided so tests and environment-specific bootstraps can supply a token
	 * without a constant. Do not use this to read one out of the database.
	 *
	 * @param string $token Token, or empty string.
	 */
	return (string) apply_filters( 'rv_sherpa_token', $env ? (string) $env : '' );
}

/**
 * How long each terminal status may keep its personal data, in days.
 *
 * Delivered leads are not in this list because they are not swept -- they are
 * anonymised in the same request that delivered them (see
 * RV_Sherpa_Dispatcher::handle_send()). What is here is everything that has a
 * reason to hold data for a while longer:
 *
 *   failed    90 days. This is the only remaining copy of the enquiry, and
 *             recovering it is a manual job someone has to get round to. Ninety
 *             days is generous on purpose; a lead nobody has actioned in three
 *             months is not going to be actioned.
 *   dry-run    7 days. Dry run exists to verify mapping before cutover, which
 *             needs the payload -- but only briefly, and never for long enough
 *             to matter if dry run is ever left on somewhere real.
 *   skipped    7 days. Same reasoning.
 *
 * Set a value to -1 to keep indefinitely. Nothing does, by design.
 *
 * @return array status => days.
 */
function rv_sherpa_retention_days() {
	return apply_filters(
		'rv_sherpa_retention_days',
		array(
			RV_Sherpa_Store::STATUS_FAILED  => 90,
			RV_Sherpa_Store::STATUS_DRY_RUN => 7,
			RV_Sherpa_Store::STATUS_SKIPPED => 7,
		)
	);
}

/**
 * Whether a delivered lead has its personal data erased on the spot.
 *
 * Filterable only so that a cutover reconciliation run can hold data long
 * enough to compare the new integration's output against the outgoing plugin's.
 * It should be true everywhere else, and it is true by default.
 *
 * @param int $form_id CF7 form ID.
 * @return bool
 */
function rv_sherpa_anonymise_on_delivery( $form_id = 0 ) {
	return (bool) apply_filters( 'rv_sherpa_anonymise_on_delivery', true, $form_id );
}

/**
 * Install or upgrade the lead store.
 */
function rv_sherpa_maybe_install() {
	if ( get_option( 'rv_sherpa_db_version' ) === RV_SHERPA_DB_VERSION ) {
		return;
	}

	RV_Sherpa_Store::install();
	update_option( 'rv_sherpa_db_version', RV_SHERPA_DB_VERSION );
}
add_action( 'admin_init', 'rv_sherpa_maybe_install' );
