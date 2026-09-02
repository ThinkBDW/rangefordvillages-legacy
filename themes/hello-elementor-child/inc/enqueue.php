<?php
/**
 * Front-end asset enqueues
 *
 * @package hello-elementor-child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// --- Child Theme Configurator auto-generated block ---
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
        wp_enqueue_style('chld_thm_cfg_child', trailingslashit(get_stylesheet_directory_uri()) . 'style.css', array('hello-elementor', 'hello-elementor-theme-style'));
    }
endif;
add_action('wp_enqueue_scripts', 'child_theme_configurator_css', 10);


// --- enqueue_custom_scripts() ---

function enqueue_custom_scripts()
{
    wp_enqueue_script('slick-js', get_stylesheet_directory_uri() . '/js/slick.min.js', array('jquery'), '1.0', true);
    // Only the minified slick sheet: css/slick.css is the identical
    // unminified copy, and css/slick-theme.css has never been enqueued --
    // adding it would restyle every carousel.
    wp_enqueue_style('slick-min-css', get_stylesheet_directory_uri() . '/css/slick.min.css');
    wp_enqueue_script('custom-scripts', get_stylesheet_directory_uri() . '/js/custom.js', array('jquery'), '1.0', true);
    // filemtime, not time(): a time() version mints a new URL on every
    // page load, so no browser or CDN ever caches the file.
    wp_enqueue_script('custom-scripts-new', get_stylesheet_directory_uri() . '/js/custom-new.js', array('jquery'), filemtime(get_stylesheet_directory() . '/js/custom-new.js'), true);
    // js/chart.js is only consumed by the budget-calculator plugin's
    // custom-calculator.js -- 200 KB with no reader on any other page.
    if (rv_is_budget_calculator_page()) {
        wp_enqueue_script('chart-js', get_stylesheet_directory_uri() . '/js/chart.js', array('jquery'), '1.0', true);
    }
    wp_enqueue_style('new-css', get_stylesheet_directory_uri() . '/new-style.css', array('hello-elementor', 'hello-elementor-theme-style'));
    wp_enqueue_style('paladin-css', get_stylesheet_directory_uri() . '/css/paladin.css');



    // --- Code recovered from the database (Elementor Pro Custom Code) ---

    // The recovered JS must NOT run while the original Elementor Pro Custom
    // Code posts are still published: the map would initialise twice and the
    // events handlers would double-bind. One switch shared with the GTM
    // output in inc/analytics.php.
    if (!rv_legacy_snippets_active()) {
        // Custom Code post #19421 (elementor_body_end), condition
        // include/singular/villages + include/singular/properties.
        // mapData/mainLocation come from the maps shortcode
        // (inc/shortcodes/maps.php).
        if (is_singular(array('villages', 'properties'))) {
            wp_enqueue_script('rv-map', get_stylesheet_directory_uri() . '/js/map.js', array(), filemtime(get_stylesheet_directory() . '/js/map.js'), true);
            // The loader tag the snippet carried, reproduced exactly: async,
            // loading=async, callback=initMap. rv-map is a dependency so
            // initMap is defined before the API can call it.
            wp_enqueue_script('rv-google-maps-api', 'https://maps.googleapis.com/maps/api/js?key=AIzaSyAFKJ4-I6u4mnhPKJGvBaPnQFGksvZ2v8w&loading=async&callback=initMap', array('rv-map'), null, array('in_footer' => true, 'strategy' => 'async'));
        }

        // Custom Code post #19743 (elementor_body_end), condition
        // include/archive/tribe_events_archive -- The Events Calendar's
        // archive views, which include the category archives the script
        // exists to highlight.
        if (is_post_type_archive('tribe_events') || is_tax('tribe_events_cat')) {
            wp_enqueue_script('rv-events', get_stylesheet_directory_uri() . '/js/events.js', array('jquery'), filemtime(get_stylesheet_directory() . '/js/events.js'), true);
        }
    }

    // Localize the script with new data
    $script_data = array(
        'ajax_url' => admin_url('admin-ajax.php'),
    );
    wp_localize_script('custom-scripts', 'script_data', $script_data);
}

add_action('wp_enqueue_scripts', 'enqueue_custom_scripts');

// Site CSS recovered from the database: the Customizer's Additional CSS
// (custom_css post #19440) plus a header-footer-code-manager block. Both
// originals printed at or near the END of wp_head, after every enqueued
// stylesheet, so equal-specificity ties resolved in their favour;
// priority 999 queues this file after everything else (including
// Elementor's page CSS) to preserve that position as closely as an
// enqueued file can. The retired originals are restorable from this file.
function rv_enqueue_recovered_css() {
    wp_enqueue_style('rv-recovered-css', get_stylesheet_directory_uri() . '/css/recovered.css', array('new-css'), filemtime(get_stylesheet_directory() . '/css/recovered.css'));
}
add_action('wp_enqueue_scripts', 'rv_enqueue_recovered_css', 999);

// Brochure modals -- replaces popup-maker (inc/brochure-modal.php explains
// why the popmake-* class names are kept). Site-wide because the triggers are
// spread across Elementor content, the mega menu and single-properties.php.
// js/custom.js hands off to window.rvBrochureModal, but only from a click
// handler, and the API is assigned as soon as this file executes -- so load
// order between the two does not matter.
function rv_enqueue_brochure_modal() {
    wp_enqueue_style('rv-brochure-modal', get_stylesheet_directory_uri() . '/css/brochure-modal.css', array(), filemtime(get_stylesheet_directory() . '/css/brochure-modal.css'));
    wp_enqueue_script('rv-brochure-modal', get_stylesheet_directory_uri() . '/js/brochure-modal.js', array('jquery'), filemtime(get_stylesheet_directory() . '/js/brochure-modal.js'), true);
}
add_action('wp_enqueue_scripts', 'rv_enqueue_brochure_modal');

// Cookie consent (vanilla-cookieconsent, vendored). Site-wide: it is what
// legitimises every cookie-setting tag in GTM, so it must be on every page
// the container is. js/cookie-consent.js explains the GTM contract.
function rv_enqueue_cookie_consent() {
    wp_enqueue_style('rv-cookieconsent', get_stylesheet_directory_uri() . '/css/cookieconsent.css', array(), '3.1.0');
    wp_enqueue_style('rv-cookieconsent-theme', get_stylesheet_directory_uri() . '/css/cookie-consent-theme.css', array('rv-cookieconsent'), filemtime(get_stylesheet_directory() . '/css/cookie-consent-theme.css'));
    wp_enqueue_script('rv-cookieconsent', get_stylesheet_directory_uri() . '/js/vendor/cookieconsent.umd.js', array(), '3.1.0', true);
    wp_enqueue_script('rv-cookie-consent-config', get_stylesheet_directory_uri() . '/js/cookie-consent.js', array('rv-cookieconsent'), filemtime(get_stylesheet_directory() . '/js/cookie-consent.js'), true);
}
add_action('wp_enqueue_scripts', 'rv_enqueue_cookie_consent');
