<?php
/**
 * Sherpa CRM: legacy source attribution fields
 *
 * Moved verbatim from functions.php during the takeover refactor
 * (was lines 3243-3276). No behaviour change in the move commit.
 *
 * @package hello-elementor-child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// --- functions.php lines 3243-3276: populate_hidden_fields_with_acf_values ---
function populate_hidden_fields_with_acf_values() {
    // Check if the ACF plugin is active

    // Get the ACF field values
    $sherpa_vendorname = get_field('sherpa_vendorname','option');
    $sherpa_source_category = get_field('sherpa_source_category','option');
    $sherpa_source_name = get_field('sherpa_source_name','option');

    ?>
    <script type="text/javascript">
        jQuery(document).ready(function($) {
            console.log('loaded');
            // Set the hidden fields with ACF values
            var vendorNameField = $('input[name="vendorName"]');
            if (vendorNameField.length) {
                vendorNameField.val('<?php echo esc_js($sherpa_vendorname); ?>');
            }

            var sourceCategoryField = $('input[name="sourceCategory"]');
            if (sourceCategoryField.length) {
                sourceCategoryField.val('<?php echo esc_js($sherpa_source_category); ?>');
            }

            var sourceNameField = $('input[name="sourceName"]');
            if (sourceNameField.length) {
                sourceNameField.val('<?php echo esc_js($sherpa_source_name); ?>');
            }
        });
    </script>
    <?php
}

add_action('wp_footer', 'populate_hidden_fields_with_acf_values');

