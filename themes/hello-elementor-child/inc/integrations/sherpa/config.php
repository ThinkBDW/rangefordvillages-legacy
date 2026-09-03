<?php
/**
 * Sherpa CRM per-form configuration.
 *
 * Keyed by Contact Form 7 form ID. Ported from the 16 contact-form-to-any-api
 * connectors (audit/data/sherpa-connectors.txt) plus the dynamic routing that
 * lived in the theme (functions.php:3433-3545, now legacy-routing.php).
 *
 * Each entry has:
 *
 *   community  int, or an array describing how to resolve it:
 *                by_field  posted field holding the routing value
 *                values    value => community ID
 *                default   fallback community ID
 *   map        CF7 field name => Sherpa field name
 *   note       free text, for the admin screen
 *
 * The CF7 field names are auto-generated and inconsistent between forms
 * ('FirstName' vs 'text-178' vs 'text-523'). They are ported verbatim on
 * purpose: renaming them would change 17 live forms and this map at the same
 * time, so it is deliberately a separate, verifiable step later.
 *
 * @package hello-elementor-child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
 * The six Sherpa communities.
 *
 *   1 Wadswick Green      4 Homewood Grove
 *   2 Mickle Hill         5 Strawberry Fields
 *   3 Siddington Park     6 Future Villages
 */

// Village dropdown value => community. Used by the brochure and contact forms
// that let the visitor choose. Ported from functions.php:3461-3492; the map
// itself now lives in rv_sherpa_village_communities() (bootstrap.php), because
// the page-context resolver needs the same list.
//
// The default is Homewood Grove, inherited. It applies when the dropdown is
// absent, empty or carries something unmapped -- see form 637 below, which has
// no dropdown at all.
$rv_by_village = array(
	'by_field' => 'your-field-name',
	'values'   => rv_sherpa_village_communities(),
	'default'  => 4,
);

// The standard field map shared by the six per-village contact forms.
$rv_village_form_map = array(
	'text-178'                           => 'primaryContactFirstName',
	'text-316'                           => 'primaryContactLastName',
	'tel-366'                            => 'primaryContactCellPhone',
	'email-518'                          => 'primaryContactEmail',
	'address-line-1'                     => 'residentContactAddress1',
	'town-city'                          => 'residentContactCity',
	'postcode'                           => 'residentContactPostalCode',
	'howdidyouhear'                      => 'howdidyouhear',
	'textarea-256'                       => 'referralNote',
	'vendorName'                         => 'vendorName',
	'sourceName'                         => 'sourceName',
	'sourceCategory'                     => 'sourceCategory',
	'current-date'                       => 'referralDateTime',
	'residentContactFirstName'           => 'residentContactFirstName',
	'residentContactLastName'            => 'residentContactLastName',
	'primaryContactResidentRelationship' => 'primaryContactResidentRelationship',
);

// The brochure forms use an older, differently-named field set.
$rv_brochure_map = array(
	'FirstName'                          => 'primaryContactFirstName',
	'LastName'                           => 'primaryContactLastName',
	'email-104'                          => 'primaryContactEmail',
	'tel-689'                            => 'primaryContactCellPhone',
	'address-line-1'                     => 'residentContactAddress1',
	'town-city'                          => 'residentContactCity',
	'postcode'                           => 'residentContactPostalCode',
	'your-field-name'                    => 'sourceCommunityId',
	'post_id_brochure'                   => 'post_id_brochure',
	'howdidyouhear'                      => 'howdidyouhear',
	'vendorName'                         => 'vendorName',
	'sourceName'                         => 'sourceName',
	'sourceCategory'                     => 'sourceCategory',
	'current-date'                       => 'referralDateTime',
	'residentContactFirstName'           => 'residentContactFirstName',
	'residentContactLastName'            => 'residentContactLastName',
	'primaryContactResidentRelationship' => 'primaryContactResidentRelationship',
);

return array(

	// --- Brochure forms: visitor picks the village ------------------------

	// was connector 17746
	10765 => array(
		'community' => $rv_by_village,
		'map'       => $rv_brochure_map,
		'note'      => 'Single Page Brochure. Highest volume form (1,789 logged submissions).',
	),

	// was connector 32775. The old code wrote this form's routing into
	// connector 17746 instead of its own, so it never routed dynamically AND
	// it corrupted form 10765's endpoint. Fixed here.
	32660 => array(
		'community' => $rv_by_village,
		'map'       => $rv_brochure_map,
		'note'      => 'Single Page Brochure (copy). Routing was previously written to the wrong connector.',
	),

	// was connector 17935
	7807  => array(
		'community' => $rv_by_village,
		'map'       => array_diff_key(
			$rv_brochure_map,
			array( 'address-line-1' => '', 'town-city' => '', 'postcode' => '', 'post_id_brochure' => '' )
		),
		'note'      => 'Brochure Form.',
	),

	// --- Contact forms ----------------------------------------------------

	// was connector 17932
	2564  => array(
		'community' => $rv_by_village,
		'map'       => array(
			'text-178'                           => 'primaryContactFirstName',
			'text-370'                           => 'primaryContactLastName',
			'tel-366'                            => 'primaryContactCellPhone',
			'email-518'                          => 'primaryContactEmail',
			'howdidyouhear'                      => 'howdidyouhear',
			'vendorName'                         => 'vendorName',
			'sourceName'                         => 'sourceName',
			'sourceCategory'                     => 'sourceCategory',
			'current-date'                       => 'referralDateTime',
			'residentContactFirstName'           => 'residentContactFirstName',
			'residentContactLastName'            => 'residentContactLastName',
			'primaryContactResidentRelationship' => 'primaryContactResidentRelationship',
		),
		'note'      => 'Contact Us page form.',
	),

	// was connector 27992. Routes on its own field name, not the usual one.
	16015 => array(
		'community' => array(
			'by_field' => 'propertiesvillage',
			'values'   => $rv_by_village['values'],
			'default'  => 1,
		),
		'map'       => array(
			'text-178'                           => 'primaryContactFirstName',
			'text-370'                           => 'primaryContactLastName',
			'tel-366'                            => 'primaryContactCellPhone',
			'email-518'                          => 'primaryContactEmail',
			'howdidyouhear'                      => 'howdidyouhear',
			'textarea-256'                       => 'referralNote',
			'vendorName'                         => 'vendorName',
			'sourceName'                         => 'sourceName',
			'sourceCategory'                     => 'sourceCategory',
			'current-date'                       => 'referralDateTime',
			'residentContactFirstName'           => 'residentContactFirstName',
			'residentContactLastName'            => 'residentContactLastName',
			'primaryContactResidentRelationship' => 'primaryContactResidentRelationship',
		),
		'note'      => 'Contact Us page form with assist (713 logged submissions).',
	),

	// was connector 28005. The old code wrote this form's routing to connector
	// 17934, which does not exist -- so every lead fell through to the static
	// community 6 (Future Villages) regardless of the village chosen. Fixed.
	637   => array(
		'community' => $rv_by_village,
		'map'       => array(
			'text-178'                           => 'primaryContactFirstName',
			'text-316'                           => 'primaryContactLastName',
			'tel-366'                            => 'primaryContactCellPhone',
			'email-518'                          => 'primaryContactEmail',
			'howdidyouhear'                      => 'howdidyouhear',
			'vendorName'                         => 'vendorName',
			'sourceName'                         => 'sourceName',
			'sourceCategory'                     => 'sourceCategory',
			'current-date'                       => 'referralDateTime',
			'residentContactFirstName'           => 'residentContactFirstName',
			'residentContactLastName'            => 'residentContactLastName',
			'primaryContactResidentRelationship' => 'primaryContactResidentRelationship',
		),
		'note'      => 'General contact form, on ~20 pages. No village field of its own: the village is filled from the page (rv_cf7_fill_village_from_context). Routing was also broken (dead connector 17934).',
	),

	// --- Per-village contact forms: community is fixed --------------------

	23664 => array( 'community' => 1, 'map' => $rv_village_form_map, 'note' => 'Wadswick Green contact form.' ),
	23647 => array( 'community' => 2, 'map' => $rv_village_form_map, 'note' => 'Mickle Hill contact form.' ),
	23656 => array( 'community' => 3, 'map' => $rv_village_form_map, 'note' => 'Siddington Park contact form.' ),
	23633 => array( 'community' => 4, 'map' => $rv_village_form_map, 'note' => 'Homewood Grove contact form.' ),
	23430 => array( 'community' => 5, 'map' => $rv_village_form_map, 'note' => 'Strawberry Fields contact form.' ),

	/*
	 * was connector 23425 -- no referralNote field on this one.
	 *
	 * The community was fixed at 6, but this form DOES render the village
	 * dropdown, and on /future-villages/ that dropdown offers all eight
	 * villages (dynamic_village_field_values only narrows to future villages
	 * on the 'coming-soon' page). So a visitor who picked 'Wadswick Green'
	 * here had their choice silently discarded and their enquiry sent to
	 * Rangeford Future Villages. It now routes on the choice, and falls back
	 * to 6 rather than the shared default of 4 -- an unanswered dropdown on
	 * the Future Villages page means Future Villages.
	 */
	23421 => array(
		'community' => array(
			'by_field' => 'your-field-name',
			'values'   => rv_sherpa_village_communities(),
			'default'  => 6,
		),
		'map'       => array_diff_key( $rv_village_form_map, array( 'textarea-256' => '' ) ),
		'note'      => 'Future Villages contact form. Routes on the village dropdown, defaulting to Future Villages.',
	),

	// was connector 25907
	25885 => array(
		'community' => 6,
		'map'       => array_diff_key( $rv_village_form_map, array( 'textarea-256' => '' ) ),
		'note'      => 'Future Villages - Child.',
	),

	// --- Other ------------------------------------------------------------

	// was connector 17937. Routes on the fees page the calculator sits on.
	// Ported from functions.php:3500-3518.
	12311 => array(
		'community' => array(
			'by_field' => 'page-id',
			'values'   => array(
				12994 => 1, // Wadswick Green fees
				12974 => 2, // Mickle Hill fees
				13004 => 3, // Siddington Park fees
				12910 => 4, // Homewood Grove fees
				18555 => 5, // Strawberry Fields fees
			),
			'default'  => 4,
		),
		'map'       => array(
			'text-523'                           => 'primaryContactFirstName',
			'text-470'                           => 'primaryContactLastName',
			'tel-191'                            => 'primaryContactCellPhone',
			'email-899'                          => 'primaryContactEmail',
			'howdidyouhear'                      => 'howdidyouhear',
			'vendorName'                         => 'vendorName',
			'sourceName'                         => 'sourceName',
			'sourceCategory'                     => 'sourceCategory',
			'current-date'                       => 'referralDateTime',
			'residentContactFirstName'           => 'residentContactFirstName',
			'residentContactLastName'            => 'residentContactLastName',
			'primaryContactResidentRelationship' => 'primaryContactResidentRelationship',
		),
		'note'      => 'Save your calculation (budget calculator). Strawberry Fields page 18555 was missing from the old routing.',
	),

	// was connector 28731. Routed on the event venue. The old mapping omitted
	// howdidyouhear entirely -- restored here.
	22416 => array(
		'community' => array(
			'by_field' => 'venue-title',
			'values'   => $rv_by_village['values'],
			'default'  => 3,
		),
		'map'       => array(
			'text-178'                           => 'primaryContactFirstName',
			'text-370'                           => 'primaryContactLastName',
			'tel-366'                            => 'primaryContactCellPhone',
			'email-518'                          => 'primaryContactEmail',
			'howdidyouhear'                      => 'howdidyouhear',
			'vendorName'                         => 'vendorName',
			'sourceName'                         => 'sourceName',
			'sourceCategory'                     => 'sourceCategory',
			'current-date'                       => 'referralDateTime',
			'residentContactFirstName'           => 'residentContactFirstName',
			'residentContactLastName'            => 'residentContactLastName',
			'primaryContactResidentRelationship' => 'primaryContactResidentRelationship',
		),
		'note'      => 'Event Enquiry Form. howdidyouhear restored (was missing from connector 28731).',
	),

	/*
	 * Form 13096 ("Sherpa Form", was connector 12312) is deliberately absent.
	 *
	 * Its mapping sent neither primaryContactEmail nor primaryContactCellPhone,
	 * so every lead reached Sherpa with no way to contact the enquirer. It is
	 * embedded on no published page and its last submission was 2024-09-27, so
	 * it is retired rather than ported. If it is ever revived, it needs a
	 * complete field map first.
	 *
	 * Form 11060 ("Sign up to our newsletter") has no Sherpa connector by
	 * design -- it is a mailing-list signup, not a lead.
	 */
);
