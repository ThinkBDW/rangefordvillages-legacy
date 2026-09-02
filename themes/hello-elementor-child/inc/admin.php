<?php
/**
 * wp-admin tweaks
 *
 * @package hello-elementor-child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// --- change_post_menu_label ---
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

