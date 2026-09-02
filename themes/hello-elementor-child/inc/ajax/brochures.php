<?php
/**
 * AJAX: brochures
 *
 * @package hello-elementor-child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// --- get_brochure_link ---
function get_brochure_link() {

    if ( empty($_POST['post_id_brochure']) ) {
        wp_die();
    }

    $post_id_brochure    = intval($_POST['post_id_brochure']);
    $post_id_brochure_id = isset($_POST['termId']) ? intval($_POST['termId']) : 0;

    $url_brochure = '';

    if ( get_field('villages_download_brochure_type', $post_id_brochure) === 'file' ) {

        $file = get_field('villages_download_brochure', $post_id_brochure);

        // ACF file field can be array OR string
        if ( is_array($file) && !empty($file['url']) ) {
            $url_brochure = $file['url'];
        } elseif ( is_string($file) ) {
            $url_brochure = $file;
        }

    } else {

        $url_brochure = get_field('villages_download_brochure_link', $post_id_brochure);

    }

    // Fallback brochure field
    if ( empty($url_brochure) ) {
        $fallback = get_field('brochure', $post_id_brochure);

        if ( is_array($fallback) && !empty($fallback['url']) ) {
            $url_brochure = $fallback['url'];
        } elseif ( is_string($fallback) ) {
            $url_brochure = $fallback;
        }
    }

    if ( !empty($url_brochure) || $post_id_brochure_id ) {
        echo "<h6 class='elementor-heading-title elementor-size-default click-popup-button'>
                If the popup doesn't open click the button below
              </h6>";
        echo '<a class="elementor-button elementor-button-link elementor-size-sm brochure-download" 
                 href="' . esc_url($url_brochure) . '" 
                 target="_blank">
                 Download Lifestyle Brochure
              </a>';
    }

    wp_die();
}

// Hook the function to handle both AJAX requests
add_action('wp_ajax_get_brochure_link', 'get_brochure_link');
add_action('wp_ajax_nopriv_get_brochure_link', 'get_brochure_link');


