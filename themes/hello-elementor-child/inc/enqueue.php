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
        wp_enqueue_style('chld_thm_cfg_child', trailingslashit(get_stylesheet_directory_uri()) . 'style.css', array('hello-elementor', 'hello-elementor-theme-style'));
    }
endif;
add_action('wp_enqueue_scripts', 'child_theme_configurator_css', 10);


// --- functions.php lines 298-319: enqueue_custom_scripts() ---

function enqueue_custom_scripts()
{
    wp_enqueue_script('slick-js', get_stylesheet_directory_uri() . '/js/slick.min.js', array('jquery'), '1.0', true);
    wp_enqueue_style('slick-min-css', get_stylesheet_directory_uri() . '/css/slick.min.css');
    // css/slick.css (the unminified copy of the same sheet) was enqueued as
    // well -- identical rules shipped twice. Removed at A3. css/slick-theme.css
    // was never enqueued at all and stays that way: adding it now would
    // restyle every carousel.
    wp_enqueue_script('custom-scripts', get_stylesheet_directory_uri() . '/js/custom.js', array('jquery'), '1.0', true);
    // time() as the version defeated all browser and CDN caching -- a new
    // asset URL on every page load (AUDIT.md section 6). filemtime() busts
    // the cache only when the file actually changes.
    wp_enqueue_script('custom-scripts-new', get_stylesheet_directory_uri() . '/js/custom-new.js', array('jquery'), filemtime(get_stylesheet_directory() . '/js/custom-new.js'), true);
    // js/chart.js is only consumed by the budget-calculator plugin's
    // custom-calculator.js, but was enqueued on every page of the site --
    // 200 KB of JS on pages with no chart (AUDIT.md section 6).
    if (rv_is_budget_calculator_page()) {
        wp_enqueue_script('chart-js', get_stylesheet_directory_uri() . '/js/chart.js', array('jquery'), '1.0', true);
    }
    wp_enqueue_style('new-css', get_stylesheet_directory_uri() . '/new-style.css', array('hello-elementor', 'hello-elementor-theme-style'));
    wp_enqueue_style('paladin-css', get_stylesheet_directory_uri() . '/css/paladin.css');



    // --- Code recovered from the database at A3 (PROGRESS.md section 3) ---

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

// Site CSS recovered at A3 from the Customizer's Additional CSS (core
// custom_css post #19440 -- NOT the custom-css-js plugin, despite the
// audit's original attribution) plus HFCM snippet #1. Both originals
// printed at or near the END of wp_head, after every enqueued stylesheet,
// so equal-specificity ties resolved in their favour. Priority 999 queues
// this file after everything else (including Elementor's page CSS) to
// preserve that cascade position as closely as an enqueued file can.
// The originals are retired by emptying the Customizer entry and
// deactivating the HFCM row -- both content, both restorable from this
// file if the pre-cutover visual QA finds a difference.
function rv_enqueue_recovered_css() {
    wp_enqueue_style('rv-recovered-css', get_stylesheet_directory_uri() . '/css/recovered.css', array('new-css'), filemtime(get_stylesheet_directory() . '/css/recovered.css'));
}
add_action('wp_enqueue_scripts', 'rv_enqueue_recovered_css', 999);
