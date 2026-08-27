<?php
/**
 * Front-end asset enqueues
 *
 * Moved verbatim from functions.php during the takeover refactor
 * (was lines 9-29, 298-319). No behaviour change in the move commit.
 *
 * @package hello-elementor-child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// --- functions.php lines 9-29: Child Theme Configurator auto-generated block ---
// BEGIN ENQUEUE PARENT ACTION
// AUTO GENERATED - Do not modify or remove comment markers above or below:

if (!function_exists('chld_thm_cfg_locale_css')) :
    function chld_thm_cfg_locale_css($uri)
    {
        if (empty($uri) && is_rtl() && file_exists(get_template_directory() . '/rtl.css'))
            $uri = get_template_directory_uri() . '/rtl.css';
        return $uri;
    }
endif;
add_filter('locale_stylesheet_uri', 'chld_thm_cfg_locale_css');

if (!function_exists('child_theme_configurator_css')) :
    function child_theme_configurator_css()
    {
        wp_enqueue_style('chld_thm_cfg_child', trailingslashit(get_stylesheet_directory_uri()) . 'style.css', array('hello-elementor', 'hello-elementor', 'hello-elementor-theme-style'));
    }
endif;
add_action('wp_enqueue_scripts', 'child_theme_configurator_css', 10);


// --- functions.php lines 298-319: enqueue_custom_scripts() ---

function enqueue_custom_scripts()
{
    wp_enqueue_script('slick-js', get_stylesheet_directory_uri() . '/js/slick.min.js', array('jquery'), '1.0', true);
    wp_enqueue_style('slick-min-css', get_stylesheet_directory_uri() . '/css/slick.min.css');
    wp_enqueue_style('slick-css', get_stylesheet_directory_uri() . '/css/slick.css');
    wp_enqueue_script('custom-scripts', get_stylesheet_directory_uri() . '/js/custom.js', array('jquery'), '1.0', true);
    wp_enqueue_script('custom-scripts-new', get_stylesheet_directory_uri() . '/js/custom-new.js', array('jquery'), time(), true);
    wp_enqueue_script('chart-js', get_stylesheet_directory_uri() . '/js/chart.js', array('jquery'), '1.0', true);
    wp_enqueue_style('new-css', get_stylesheet_directory_uri() . '/new-style.css', array('hello-elementor', 'hello-elementor', 'hello-elementor-theme-style'));
    wp_enqueue_style('paladin-css', get_stylesheet_directory_uri() . '/css/paladin.css');



    // Localize the script with new data
    $script_data = array(
        'ajax_url' => admin_url('admin-ajax.php'),
    );
    wp_localize_script('custom-scripts', 'script_data', $script_data);
}

add_action('wp_enqueue_scripts', 'enqueue_custom_scripts');
