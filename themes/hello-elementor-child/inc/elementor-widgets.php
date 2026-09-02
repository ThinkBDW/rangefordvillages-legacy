<?php
/**
 * Elementor widget registration
 *
 * @package hello-elementor-child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// --- Custom_Slider_Widget registration ---

add_action('elementor/widgets/widgets_registered', 'register_custom_slider_widget');

function register_custom_slider_widget($widgets_manager)
{
    // __DIR__ was the theme root before this code moved into inc/, so the path
    // is now anchored explicitly rather than relative to this file.
    require_once get_stylesheet_directory() . '/custom-slider-widget.php';
    $widgets_manager->register(new \Custom_Slider_Widget());
}
// Include the Elementor custom module
// include_once get_stylesheet_directory() . '/gallery-module.php';

