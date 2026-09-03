<?php
/**
 * Resolve a Sherpa community from a form submission.
 *
 * This replaces custom_cf7_update_post_meta(), which decided the community and
 * then wrote the resulting endpoint into the contact-form-to-any-api plugin's
 * postmeta so the plugin would pick it up. That approach had three failures:
 *
 *   - It was a race. The endpoint was global state shared by all visitors, so
 *     two concurrent submissions for different villages could cross-route.
 *   - Two forms wrote to the wrong connector -- 637 to connector 17934 (which
 *     does not exist) and 32660 to connector 17746 (form 10765's).
 *   - It wrote a debug file into the *parent* theme directory on every
 *     submission using `fopen(...) or die()`, so a read-only theme directory
 *     would have killed every lead submission mid-request.
 *
 * Resolution here is pure: config in, community ID out, no state touched.
 *
 * @package hello-elementor-child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class RV_Sherpa_Router {

	/**
	 * Resolve the community for a submission.
	 *
	 * @param int   $form_id     CF7 form ID.
	 * @param array $posted_data CF7 posted data.
	 * @return array {
	 *     @type int|null $community Community ID, or null when unroutable.
	 *     @type string   $reason    How it was resolved, for the audit trail.
	 * }
	 */
	public static function resolve( $form_id, array $posted_data ) {
		$config = rv_sherpa_config();

		if ( ! isset( $config[ $form_id ] ) ) {
			return array(
				'community' => null,
				'reason'    => "form {$form_id} is not configured",
			);
		}

		$community = $config[ $form_id ]['community'];

		// Fixed community.
		if ( is_scalar( $community ) ) {
			return array(
				'community' => (int) $community,
				'reason'    => 'fixed in config',
			);
		}

		$field   = $community['by_field'] ?? '';
		$values  = $community['values'] ?? array();
		$default = isset( $community['default'] ) ? (int) $community['default'] : null;

		$raw = $posted_data[ $field ] ?? '';

		// CF7 gives select and checkbox fields as arrays.
		if ( is_array( $raw ) ) {
			$raw = reset( $raw );
		}

		$raw = trim( (string) $raw );

		if ( '' === $raw ) {
			return array(
				'community' => $default,
				'reason'    => "'{$field}' was empty; used default",
			);
		}

		if ( isset( $values[ $raw ] ) ) {
			return array(
				'community' => (int) $values[ $raw ],
				'reason'    => "'{$field}' = '{$raw}'",
			);
		}

		// Numeric keys (page IDs) arrive as strings from posted data.
		if ( is_numeric( $raw ) && isset( $values[ (int) $raw ] ) ) {
			return array(
				'community' => (int) $values[ (int) $raw ],
				'reason'    => "'{$field}' = {$raw}",
			);
		}

		// Tolerate case and whitespace differences in village names rather than
		// silently falling through to the default -- a mis-cased option label
		// should not send a lead to the wrong village.
		foreach ( $values as $candidate => $id ) {
			if ( 0 === strcasecmp( trim( (string) $candidate ), $raw ) ) {
				return array(
					'community' => (int) $id,
					'reason'    => "'{$field}' = '{$raw}' (matched case-insensitively)",
				);
			}
		}

		return array(
			'community' => $default,
			'reason'    => "'{$field}' = '{$raw}' is unmapped; used default",
		);
	}

	/**
	 * Build the endpoint for a community.
	 *
	 * @param int $community Community ID.
	 * @return string
	 */
	public static function endpoint( $community ) {
		$company = defined( 'RV_SHERPA_COMPANY_ID_OVERRIDE' )
			? (int) RV_SHERPA_COMPANY_ID_OVERRIDE
			: (int) RV_SHERPA_COMPANY_ID;

		// Sherpa's sandbox exposes a single community (15), so a non-production
		// environment can force every route into it while still exercising the
		// real resolution logic above. The resolved community is still recorded
		// against the lead, so routing remains verifiable.
		if ( defined( 'RV_SHERPA_COMMUNITY_OVERRIDE' ) ) {
			$community = (int) RV_SHERPA_COMMUNITY_OVERRIDE;
		}

		return sprintf(
			'%s/companies/%d/communities/%d/leads',
			untrailingslashit( RV_SHERPA_API_BASE ),
			$company,
			(int) $community
		);
	}

	/**
	 * Human-readable community name, for the admin screen.
	 *
	 * @param int $community Community ID.
	 * @return string
	 */
	public static function community_name( $community ) {
		// Verbatim from GET /companies/27/communities (checked 2026-09-02), so
		// the admin screen and the notification emails call each community
		// what the CRM calls it. Community 6 is 'Rangeford Future Villages'
		// there, not 'Future Villages'.
		$names = array(
			1 => 'Wadswick Green',
			2 => 'Mickle Hill',
			3 => 'Siddington Park',
			4 => 'Homewood Grove',
			5 => 'Strawberry Fields',
			6 => 'Rangeford Future Villages',
		);

		return $names[ (int) $community ] ?? sprintf( 'Community %d', (int) $community );
	}
}
