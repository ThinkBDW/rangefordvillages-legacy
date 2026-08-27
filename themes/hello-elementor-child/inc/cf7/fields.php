<?php
/**
 * Contact Form 7: dynamic field values
 *
 * Moved verbatim from functions.php during the takeover refactor
 * (was lines 1759-1785, 2135-2235, 3408-3432). No behaviour change in the move commit.
 *
 * @package hello-elementor-child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// --- functions.php lines 1759-1785: type dash->space (form_tag filter is BROKEN, see cleanup commit) ---
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


// --- functions.php lines 2135-2235: dead dynamic_field_values + dynamic_village_field_values ---
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


// --- functions.php lines 3408-3432: add_referral_datetime + add_hidden_page_id_script ---
add_filter('wpcf7_posted_data', 'add_referral_datetime');

function add_referral_datetime($posted_data) {

    // Always set the current date and time in the hidden field
    $posted_data['current-date'] = current_time('Y-m-d H:i:s'); // Change format as needed
    return $posted_data;
}

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


