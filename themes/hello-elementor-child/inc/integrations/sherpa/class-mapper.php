<?php
/**
 * Turn CF7 posted data into a Sherpa Create Lead payload.
 *
 * Field requirements come from Sherpa's own integration guide
 * (audit/April 2022 Website Integration Setup Process and Tips.pdf):
 *
 *   - primaryContactFirstName and primaryContactLastName are required.
 *   - residentContactFirstName and residentContactLastName are required and
 *     "can be null, but the field must be submitted".
 *   - vendorName, sourceCategory and sourceName are required.
 *   - referralDateTime must be UTC ("zulu") time. The old integration sent
 *     current_time('Y-m-d H:i:s'), which is *site-local* time in a non-ISO
 *     format -- so every referral timestamp in Sherpa has been an hour out
 *     during British Summer Time, and in the wrong format year-round.
 *
 *   "If any of these fields are missing from your request, your test lead will
 *    not be successful."
 *
 * @package hello-elementor-child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class RV_Sherpa_Mapper {

	/**
	 * Fields Sherpa requires to be present, even if empty.
	 *
	 * @var string[]
	 */
	const REQUIRED_FIELDS = array(
		'primaryContactFirstName',
		'primaryContactLastName',
		'residentContactFirstName',
		'residentContactLastName',
		'vendorName',
		'sourceCategory',
		'sourceName',
		'referralDateTime',
	);

	/**
	 * Build the payload.
	 *
	 * @param int   $form_id     CF7 form ID.
	 * @param array $posted_data CF7 posted data.
	 * @return array {
	 *     @type array    $payload  The JSON body to POST.
	 *     @type string[] $warnings Non-fatal problems worth recording.
	 * }
	 */
	public static function build( $form_id, array $posted_data ) {
		$config   = rv_sherpa_config();
		$map      = $config[ $form_id ]['map'] ?? array();
		$payload  = array();
		$warnings = array();

		foreach ( $map as $cf7_field => $sherpa_field ) {
			if ( ! array_key_exists( $cf7_field, $posted_data ) ) {
				continue;
			}

			$value = $posted_data[ $cf7_field ];

			// CF7 delivers select, checkbox and radio values as arrays.
			if ( is_array( $value ) ) {
				$value = implode( ', ', array_filter( array_map( 'strval', $value ) ) );
			}

			$value = trim( (string) $value );

			if ( '' === $value ) {
				continue;
			}

			$payload[ $sherpa_field ] = $value;
		}

		// referralDateTime must be UTC ISO 8601. Derive it ourselves rather
		// than trusting the form's hidden 'current-date' field, which the theme
		// populates with site-local time.
		$payload['referralDateTime'] = gmdate( 'Y-m-d\TH:i:s\Z' );

		// Sherpa requires these keys to exist even when blank.
		foreach ( self::REQUIRED_FIELDS as $field ) {
			if ( ! array_key_exists( $field, $payload ) ) {
				$payload[ $field ] = '';
			}
		}

		// Attribution defaults, per the integration guide's "Company Website".
		foreach ( array( 'vendorName', 'sourceCategory', 'sourceName' ) as $field ) {
			if ( '' === $payload[ $field ] ) {
				$payload[ $field ] = 'Company Website';
				$warnings[]        = "{$field} was empty; defaulted to 'Company Website'";
			}
		}

		if ( '' === $payload['primaryContactFirstName'] && '' === $payload['primaryContactLastName'] ) {
			$warnings[] = 'no primary contact name in the submission';
		}

		// Sherpa deduplicates on email or phone, so a lead with neither is
		// uncontactable and unmatchable. This is exactly the defect that made
		// connector 12312 useless.
		$has_email = ! empty( $payload['primaryContactEmail'] );
		$has_phone = ! empty( $payload['primaryContactCellPhone'] );

		if ( ! $has_email && ! $has_phone ) {
			$warnings[] = 'CRITICAL: neither primaryContactEmail nor primaryContactCellPhone is set -- the lead will be uncontactable';
		}

		/**
		 * Filter the outgoing Sherpa payload.
		 *
		 * @param array $payload     Payload.
		 * @param int   $form_id     CF7 form ID.
		 * @param array $posted_data Posted data.
		 */
		$payload = apply_filters( 'rv_sherpa_payload', $payload, $form_id, $posted_data );

		return array(
			'payload'  => $payload,
			'warnings' => $warnings,
		);
	}

	/**
	 * Pull display fields out of a payload, for the admin list.
	 *
	 * @param array $payload Payload.
	 * @return array name, email, phone.
	 */
	public static function contact_summary( array $payload ) {
		$name = trim(
			( $payload['primaryContactFirstName'] ?? '' ) . ' ' . ( $payload['primaryContactLastName'] ?? '' )
		);

		return array(
			'name'  => $name,
			'email' => $payload['primaryContactEmail'] ?? '',
			'phone' => $payload['primaryContactCellPhone'] ?? '',
		);
	}
}
