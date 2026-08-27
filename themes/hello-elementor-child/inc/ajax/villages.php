<?php
/**
 * AJAX: villages
 *
 * Moved verbatim from functions.php during the takeover refactor
 * (was lines 4066-4120). No behaviour change in the move commit.
 *
 * @package hello-elementor-child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// --- functions.php lines 4066-4120: get_village_thankyou_url ---
add_action('wp_ajax_get_village_thankyou_url', 'get_village_thankyou_url');
add_action('wp_ajax_nopriv_get_village_thankyou_url', 'get_village_thankyou_url');

function get_village_thankyou_url() {
    if ( empty($_POST['village_name']) ) {
        wp_send_json_error(['message' => 'No village name provided']);
    }

    $village_name = sanitize_text_field($_POST['village_name']);

    $village_posts = get_posts([
        'post_type'      => 'villages',
        'title'          => $village_name,
        'posts_per_page' => 1,
        'post_status'    => 'publish',
    ]);

    if (empty($village_posts)) {
        $village_posts = get_posts([
            'post_type'      => 'villages',
            's'              => $village_name,
            'posts_per_page' => 1,
            'post_status'    => 'publish',
        ]);
    }

    if (empty($village_posts)) {
        wp_send_json_error(['message' => 'Village not found']);
    }

    $village_post = $village_posts[0];
    $url = get_field('thank_you_page_url', $village_post->ID);

    if ($url) {
        wp_send_json_success([
            'url'     => $url,
            'term_id' => $village_post->ID
        ]);
    } else {
        wp_send_json_error(['message' => 'No thank you URL found']);
    }
}








/**
 * Mega Menu Village Panels
 * Each shortcode returns a panel with ACF-controlled background image.
 */

