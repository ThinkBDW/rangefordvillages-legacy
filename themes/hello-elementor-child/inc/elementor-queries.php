<?php
/**
 * Elementor custom query hooks
 *
 * @package hello-elementor-child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// --- elementor/query/show_home1 ---
function my_query_by_post_meta($query)
{

    // Get current meta Query
    $meta_query = $query->get('meta_query');

    // If there is no meta query when this filter runs, it should be initialized as an empty array.
    if (!$meta_query) {
        $meta_query = [];
    }

    // Append our meta query
    $meta_query[] = [
        'key' => 'show_in_home_page',
        'value' => 'showinhomepage',
        'compare' => 'LIKE',
    ];

    $query->set('meta_query', $meta_query);
}
add_action('elementor/query/show_home1', 'my_query_by_post_meta');






// --- elementor/query/gallery_parent + dead comment ---
function my_query_by_different_order($query)
{
    $query->set('post_parent', 0);
}
add_action('elementor/query/gallery_parent', 'my_query_by_different_order');


// function my_query_by_different_order_bruchure( $query ) {
//     $query->set( 'post_parent', 0 );

// }
// add_action( 'elementor/query/brochure_parent', 'my_query_by_different_order_bruchure' ); 



// --- elementor/query/exclude-feature ---
function my_query_by_post_meta_exclude( $query ) {

    // Get current meta Query
    $meta_query = $query->get( 'meta_query' );

    // If there is no meta query when this filter runs, it should be initialized as an empty array.
    if ( ! $meta_query ) {
        $meta_query = [];
    }

    // Append our meta query
    $meta_query[] = [
        'key' => 'feature_update',
        'value' => 'featureupdate',
        'compare' => 'NOT LIKE',

    ];

    $query->set( 'meta_query', $meta_query );

}
add_action( 'elementor/query/exclude-feature', 'my_query_by_post_meta_exclude' );

