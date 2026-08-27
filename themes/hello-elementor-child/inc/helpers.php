<?php
/**
 * Shared helpers
 *
 * Moved verbatim from functions.php during the takeover refactor
 * (was lines 4121-4142). No behaviour change in the move commit.
 *
 * @package hello-elementor-child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// --- functions.php lines 4121-4142: mm_village_panel() ---
function mm_village_panel($acf_field_name, $html_callback) {
    $image = get_field($acf_field_name, 'option');

    if (is_array($image)) {
        $image = $image['url'];
    }

    $bg = $image ? esc_url($image) : '';

    ob_start();
    echo '<div class="dropdown-inner" style="background-image: url(' . $bg . ');">';
    echo call_user_func($html_callback);
    echo '</div>';

    return ob_get_clean();
}

/*** OUR VILLIAGES MENUS ***/

/**
 * HOMEWOOD GROVE
 */
