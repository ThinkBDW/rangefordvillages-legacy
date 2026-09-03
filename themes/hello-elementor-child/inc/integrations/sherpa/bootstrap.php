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
 * Village name -> Sherpa community.
 *
 * The single authority for this mapping. It lives here rather than in
 * config.php because three separate things need it: the per-form routing in
 * config.php, the page-context fallback that fills the village field for forms
 * which do not carry one (inc/cf7/fields.php), and anything else that has a
 * village name and needs a community.
 *
 * The keys are `villages` post TITLES, because that is what the dropdown
 * offers: dynamic_village_field_values() rebuilds its options from the villages
 * posts on every render, so a renamed village changes the posted value with no
 * code change anywhere. That is exactly how 'Kingscote Place' came to be
 * unmapped -- the East Grinstead site was renamed and this map, ported verbatim
 * from connector 17746, still only knew 'East Grinstead, West Sussex'. Every
 * enquiry choosing it fell through to the default, community 4 Homewood Grove,
 * instead of 6. Both titles are kept: the old one is still carried by cached
 * HTML and by any replayed submission.
 *
 * 'Homewood Grove - Care' is deliberately absent. It is a separate villages
 * post for the care offering, not a separate community, and it is excluded from
 * the dropdown; the page-context resolver matches it to 'Homewood Grove' by
 * substring.
 *
 * Community IDs confirmed against the live API on 2026-09-02
 * (GET /companies/27/communities).
 *
 * @return array<string,int> Village title => community ID.
 */
function rv_sherpa_village_communities() {
	/**
	 * Filter the village name -> community map.
	 *
	 * @param array<string,int> $map Village title => community ID.
	 */
	return apply_filters(
		'rv_sherpa_village_communities',
		array(
			'Wadswick Green'              => 1,
			'Mickle Hill'                 => 2,
			'Siddington Park'             => 3,
			'Homewood Grove'              => 4,
			'Strawberry Fields'           => 5,
			'Bramston Park, Hampshire'    => 6,
			'Elstree, Hertfordshire'      => 6,
			'Kingscote Place'             => 6,
			'East Grinstead, West Sussex' => 6,
		)
	);
}

/**
 * The village a page speaks for, as a title this map can route.
 *
 * Exists because form 637 -- the general contact form, embedded on roughly
 * twenty pages including five village pages and six fees pages -- declares no
 * village field at all, so every one of its leads took the default community
 * regardless of which village the visitor was reading about. js/custom.js tried
 * to fill a hidden field for exactly this reason, but keyed on
 * 'east-grinstead-west-sussex' and 'homewood-grove-care', neither of which is a
 * current slug, so it never matched anything.
 *
 * Resolved server-side from the page the form was rendered in, which cannot
 * drift the way a slug table does.
 *
 * @param int $post_id Post the form was rendered in.
 * @return string Village title, or '' when the page speaks for no village.
 */
function rv_sherpa_village_for_post( $post_id ) {
	$post = get_post( $post_id );

	if ( ! $post ) {
		return '';
	}

	$titles = array_keys( rv_sherpa_village_communities() );

	// Longest first, so a title that contains a shorter one cannot be
	// shadowed by it.
	usort(
		$titles,
		static function ( $a, $b ) {
			return strlen( $b ) - strlen( $a );
		}
	);

	foreach ( $titles as $title ) {
		if ( 0 === strcasecmp( $post->post_title, $title ) ) {
			return $title;
		}
	}

	// Substring, which is what catches 'Homewood Grove - Care' and the fees
	// pages ('FEES & CHARGES - Wadswick Green - Almond Close and Ash Lane').
	foreach ( $titles as $title ) {
		if ( false !== stripos( $post->post_title, $title ) ) {
			return $title;
		}
	}

	return '';
}

/**
 * Source attribution sent with every lead.
 *
 * These three values are assigned authoritatively and the posted fields are
 * ignored -- the same treatment referralDateTime gets in the mapper, and for
 * the same reason: what the browser submits cannot be trusted to be valid.
 *
 * WHAT SHERPA ACTUALLY VALIDATES
 *
 * Sherpa checks the (sourceCategory, sourceName) pair against the inquiry
 * source OPTIONS configured for the community -- the per-community list that
 * sits beneath the categories -- and raises an alert on every lead whose pair
 * does not resolve to one. Resolving the CATEGORY is not enough: lead 12595
 * stored inquiry_source_category_id "1" with inquiry_source_id null and
 * still alerted. The alert is raised CRM-side; the API answers HTTP 200 with
 * a lead id either way, and the endpoint that would list the valid options
 * (.../inquiry-sources) answers 403 "Token not authorized" with our token,
 * so the option list is readable only in the CRM UI.
 *
 * WHY ('Internet', 'Company Website') -- CONFIRMED IN THE CRM, 2026-09-03
 *
 * It is the pair live production has sent since the integration was set up:
 * the ACF options behind the legacy wp_footer script (deleted 2026-09-02,
 * options_sherpa_* in wp_options) hold sourceCategory 'Internet' and
 * sourceName 'Company Website', and Sherpa's setup guide (audit/April 2022
 * Website Integration Setup Process and Tips.pdf, p.2) says Sherpa configures
 * a matching source name CRM-side at onboarding. So the community carries a
 * dated source NAMED 'Company Website' UNDER category 'Internet', and that
 * is the only pair that resolves. Probed one lead per candidate:
 *
 *   12595  ('Company Website', 'Company Website')  alert
 *   12596  ('Company Website', '')                 alert
 *   12597  ('Internet', '')                        alert
 *   12598  ('Internet', 'Company Website')         CLEAN -- resolved as
 *          Initial Inquiry Source 'Internet', Tactic/Campaign 'Company
 *          Website'
 *
 * The category taxonomy (every category carries a type: none, dated, named or
 * referral) says what KIND of name a category expects -- 'Internet' is dated,
 * which is why a campaign-like name belongs under it -- but the taxonomy
 * alone cannot say which pairs exist. Only the community's option list can,
 * and matching it is what clears the alert.
 *
 * vendorName is separate and unrelated to the above: it names the
 * integration, and 'Company Website' is one of this company's three
 * configured vendors ('Company Website', 'Facebook', 'Zapier').
 *
 * WHY NOT USE THE VISITOR'S "HOW DID YOU HEAR ABOUT US" ANSWER
 *
 * Tempting, because that dropdown was plainly built from the category list --
 * ten of its eleven options are categories verbatim (the eleventh, 'Drive By/
 * Signage', differs by one space). But a category on its own does not resolve
 * to an option (see above), so routing the answer here would trade the one
 * pair known to resolve for eleven pairs that mostly will not. The live site
 * shows exactly this failure: its footer script let the dropdown overwrite
 * sourceCategory, and a live lead seen on 2026-09-03 carries the alert with
 * ('Family/Friend Referral', 'Company Website'). The enquiry did come from
 * the company website; how the enquirer first heard of Rangeford is a
 * different question, and it still reaches Sherpa in its own 'howdidyouhear'
 * field on every form.
 *
 * @return array{vendorName:string,sourceCategory:string,sourceName:string}
 */
function rv_sherpa_source_fields() {
	/**
	 * Filter the source attribution triple.
	 *
	 * The place to change these if the CRM's configured inquiry sources change.
	 *
	 * @param array $fields vendorName, sourceCategory, sourceName.
	 */
	return apply_filters(
		'rv_sherpa_source_fields',
		array(
			'vendorName'     => 'Company Website',
			'sourceCategory' => 'Internet',
			'sourceName'     => 'Company Website',
		)
	);
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
