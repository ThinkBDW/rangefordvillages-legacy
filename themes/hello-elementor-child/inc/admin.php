<?php
/**
 * wp-admin tweaks
 *
 * Moved verbatim from functions.php during the takeover refactor
 * (was lines 1361-1376). No behaviour change in the move commit.
 *
 * @package hello-elementor-child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// --- functions.php lines 1361-1376: change_post_menu_label ---
/*Post Menu Name Change */
function change_post_menu_label()
{
    global $menu;
    global $submenu;

    // Change the label for "Posts" in the admin menu
    $menu[5][0] = 'News & Events';

    // Change the label for "Posts" in the sub-menu
    $submenu['edit.php'][5][0] = 'All News & Events';
    $submenu['edit.php'][10][0] = 'Add News & Event';
}

add_action('admin_menu', 'change_post_menu_label');

