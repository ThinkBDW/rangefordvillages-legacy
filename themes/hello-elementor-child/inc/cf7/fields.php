<?php
/**
 * Contact Form 7: dynamic field values
 *
 * @package hello-elementor-child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// --- type dash->space (form_tag filter is BROKEN, see cleanup commit) ---
// Removed: customize_book_appointment_value() on wpcf7_form_tag.
//
// Its signature was ( $value, $tag ), but the wpcf7_form_tag filter passes
// ( $tag, $replace ) -- so $tag received a boolean and $tag->name raised
// "Attempt to read property on bool" for every form tag on every page load
// (9,467 occurrences in the inherited debug.log). The dash->space
// substitution it was meant to perform never ran.
//
// It was also redundant: cf7_change_booking_type_format() below does the same
// substitution on wpcf7_posted_data, which is what reaches the CRM and the
// notification email. 'type' is a hidden field, so no displayed value relied
// on the broken filter.

// Define the function to replace dashes with spaces in posted data
function cf7_change_booking_type_format($posted_data)
{
    if (isset($posted_data['type'])) {
        // Replace the dash with a space in the 'type' form field
        $posted_data['type'] = str_replace('-', ' ', $posted_data['type']);
    }
    return $posted_data;
}

// Add a filter to modify the posted data before Contact Form 7 uses it
add_filter('wpcf7_posted_data', 'cf7_change_booking_type_format');


// --- dead dynamic_field_values + dynamic_village_field_values ---
function dynamic_village_field_values($tag, $unused) {
    $qo = get_queried_object();

    // Only target the specific dropdown
    if ($tag['name'] !== 'your-field-name') {
        return $tag;
    }

    // Reset the options so we don’t duplicate
    $tag['values'] = [];
    $tag['labels'] = [];

    // Get village posts
    $args = array(
        'numberposts' => -1,
        'post_type'   => 'villages',
        'orderby'     => 'title',
        'order'       => 'ASC',
        'exclude'     => [20184],
    );

    // get_queried_object() returns WP_Post_Type on post-type archives, WP_Term
    // on taxonomy archives and WP_User on author archives -- none of which have
    // post_name. Guarding on WP_Post silences the warning this raised on every
    // archive request.
    if ($qo instanceof WP_Post && $qo->post_name === 'coming-soon') {
        $args['meta_query'] = array(
            array(
                'key'     => 'future_village',
                'value'   => '1',
                'compare' => '=',
            ),
        );

        $args['orderby'] = 'menu_order';
        $args['order']   = 'ASC';
    }

    $custom_posts = get_posts($args);
    $mapping      = [];

    if (!$custom_posts) {
        return $tag; // no villages
    }

    // Insert default/placeholder option
    $tag['values'][] = '';
    $tag['labels'][] = 'Village interested in';

    foreach ($custom_posts as $custom_post) {
        $village_name = $custom_post->post_title;
        $village_email = get_field('contact_form_email', $custom_post->ID);

        if (!empty($village_email)) {
            // support multiple comma-separated addresses
            $emails       = array_map('trim', explode(',', $village_email));
            $email_string = implode(',', $emails);

            // Add option to dropdown
            $tag['values'][] = $village_name;
            $tag['labels'][] = $village_name;

            // Store mapping for email routing
            $mapping[$village_name] = $email_string;
        }
    }

    set_transient('village_email_mapping', $mapping, 12 * HOUR_IN_SECONDS);

    return $tag;
}
add_filter('wpcf7_form_tag', 'dynamic_village_field_values', 10, 2);


// --- add_referral_datetime + add_hidden_page_id_script ---
add_filter('wpcf7_posted_data', 'add_referral_datetime');

function add_referral_datetime($posted_data) {

    // Always set the current date and time in the hidden field
    $posted_data['current-date'] = current_time('Y-m-d H:i:s'); // Change format as needed
    return $posted_data;
}

/**
 * Fill the village field from the page, when the submission carries none.
 *
 * Form 637 is the general contact form and sits on roughly twenty pages,
 * including all five village pages, 'Homewood Grove - Care' and six
 * 'FEES & CHARGES' pages. It declares no village form-tag at all, so
 * 'your-field-name' was never posted and every one of its leads took the
 * routing default -- community 4, Homewood Grove -- whichever village the
 * visitor happened to be reading about. Its notification recipient fell through
 * the same way: wpcf7_custom_email_recipient() in mail.php lists form 637 among
 * the forms it routes by 'your-field-name', looks the field up, finds nothing,
 * and logs "Email mapping not found for form 637 value: N/A" on every
 * submission.
 *
 * js/custom.js tried to fix this in the browser and could not: it looks for the
 * field only inside '#wpcf7-f637-p20184-o3', and its slug table keys on
 * 'east-grinstead-west-sussex' and 'homewood-grove-care' -- neither a current
 * slug, and the latter not a community anyway. Doing it here instead means it
 * works with JavaScript disabled, works for the fees pages the JS never
 * covered, and cannot drift when a village is renamed, because the village
 * titles come from the villages posts themselves.
 *
 * Deliberately narrow, on two counts. A value the visitor actually chose is
 * never overwritten, so the forms that carry the real dropdown are unaffected.
 * And it only touches forms whose Sherpa routing is actually keyed on
 * 'your-field-name': the budget calculator routes on 'page-id' and the event
 * form on 'venue-title', and injecting a field they do not use would be noise
 * today and a trap later, since mail.php picks its recipient from the FIRST of
 * these fields it finds.
 *
 * Runs on wpcf7_posted_data, so both the CRM routing (RV_Sherpa_Router) and the
 * recipient mapping (mail.php) see it -- they both read
 * WPCF7_Submission::get_posted_data().
 *
 * @param array $posted_data CF7 posted data.
 * @return array
 */
function rv_cf7_fill_village_from_context( $posted_data ) {
	if ( ! function_exists( 'rv_sherpa_village_for_post' ) ) {
		return $posted_data;
	}

	$existing = $posted_data['your-field-name'] ?? '';

	if ( is_array( $existing ) ) {
		$existing = reset( $existing );
	}

	if ( '' !== trim( (string) $existing ) ) {
		return $posted_data;
	}

	$submission = WPCF7_Submission::get_instance();

	if ( ! $submission ) {
		return $posted_data;
	}

	$form = $submission->get_contact_form();

	if ( ! $form ) {
		return $posted_data;
	}

	// Only forms routed on this field.
	$config    = rv_sherpa_config();
	$community = $config[ $form->id() ]['community'] ?? null;

	if ( ! is_array( $community ) || 'your-field-name' !== ( $community['by_field'] ?? '' ) ) {
		return $posted_data;
	}

	// CF7 records the post the form was rendered in as submission meta; it is
	// not part of the posted data, because CF7 strips its own _wpcf7* keys.
	$container = (int) $submission->get_meta( 'container_post_id' );

	if ( ! $container ) {
		return $posted_data;
	}

	$village = rv_sherpa_village_for_post( $container );

	if ( '' === $village ) {
		return $posted_data;
	}

	$posted_data['your-field-name'] = $village;

	return $posted_data;
}
add_filter( 'wpcf7_posted_data', 'rv_cf7_fill_village_from_context' );

function add_hidden_page_id_script() {
    ?>
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            var pageId = <?php echo get_the_ID(); ?>;
            var hiddenField = document.querySelector('input[name="page-id"]');
            if (hiddenField) {
                hiddenField.value = pageId;
            }
        });
    </script>
    <?php
}
add_action('wp_footer', 'add_hidden_page_id_script');


