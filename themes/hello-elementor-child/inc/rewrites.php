<?php
/**
 * Rewrite rules and query vars
 *
 * @package hello-elementor-child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// --- custom_rewrite_rule + custom_query_vars ---
// Rewrite Rules
function custom_rewrite_rule($rules)
{
    global $wp_rewrite;
    $thank_you_page_id = get_page_by_path('thank-you')->ID;
    $new_rules = array(
        // Rule for Make enquiry
        'villages/([^/]+)/([^/]+)/([^/]+)/thank-you/?$' => 'index.php?page_id=' . get_page_by_path('thank-you')->ID . '&propertiesvillage=' . $wp_rewrite->preg_index(1) . '&property=' . $wp_rewrite->preg_index(2) . '&contactType=' . $wp_rewrite->preg_index(3),

        // Rule for Book Appointment
        'villages/([^/]+)/book-appointment/thank-you/?$' => 'index.php?page_id=' . get_page_by_path('thank-you')->ID . '&propertiesvillage=' . $wp_rewrite->preg_index(1) . '&contactType=book-appointment',

        // Rule for Arrange Visit
        'villages/([^/]+)/arrange-a-visit/thank-you/?$' => 'index.php?page_id=' . get_page_by_path('thank-you')->ID . '&propertiesvillage=' . $wp_rewrite->preg_index(1) . '&contactType=arrange-a-visit',

        // Rule for Request a call back
        'villages/([^/]+)/request-a-call-back/thank-you/?$' => 'index.php?page_id=' . get_page_by_path('thank-you')->ID . '&propertiesvillage=' . $wp_rewrite->preg_index(1) . '&contactType=request-a-call-back',

        // Rule for Request a brochure
        'villages/([^/]+)/request-brochure/thank-you/?$' => 'index.php?page_id=' . get_page_by_path('thank-you')->ID . '&propertiesvillage=' . $wp_rewrite->preg_index(1) . '&contactType=request-brochure',

        // New rule for term-redirechomewood
        'village/([^/]+)/([^/]+)/thank-you/?$' => 'index.php?page_id=' . get_page_by_path('thank-you')->ID . '&village=' . $wp_rewrite->preg_index(1) . '&type=' . $wp_rewrite->preg_index(2),

        // New rule for term-strawberry
        'village/([^/]+)/([^/]+)/thank-you/?$' => 'index.php?page_id=' . get_page_by_path('thank-you')->ID . '&village=' . $wp_rewrite->preg_index(1) . '&type=' . $wp_rewrite->preg_index(2),

        'villages/([^/]+)/thank-you/?$' => 'index.php?page_id=' . $thank_you_page_id . '&village=' . $wp_rewrite->preg_index(1),


    );
    return $new_rules + $rules;
}
add_filter('rewrite_rules_array', 'custom_rewrite_rule');

// Query Vars
function custom_query_vars($vars)
{
    $vars[] = 'propertiesvillage';
    $vars[] = 'property';
    $vars[] = 'contactType';
    $vars[] = 'village';
    $vars[] = 'type';
    return $vars;
}
add_filter('query_vars', 'custom_query_vars');

