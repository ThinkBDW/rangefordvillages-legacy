<?php
/**
 * Custom post types and taxonomies
 *
 * Moved verbatim from functions.php during the takeover refactor
 * (was lines 30-297). No behaviour change in the move commit.
 *
 * @package hello-elementor-child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// --- functions.php lines 30-297: my_custom_post_product(): 5 CPTs + 4 taxonomies ---
// Properties Custom Post Type

function my_custom_post_product()
{
    $labels = array(
        'name' => _x('Properties', 'post type general name'),
        'singular_name' => _x('Property', 'post type singular name'),
        'add_new' => _x('Add New', 'propertie'),
        'add_new_item' => __('Add New Property'),
        'edit_item' => __('Edit Property'),
        'new_item' => __('New Property'),
        'all_items' => __('All Properties'),
        'view_item' => __('View Property'),
        'search_items' => __('Search Properties'),
        'not_found' => __('No property found'),
        'not_found_in_trash' => __('No properties found in the Trash'),
        // 'parent_item_colon' => ’,
        'menu_name' => 'Properties'
    );
    $args = array(
        'labels' => $labels,
        'has_archive' => false,
        'rewrite' => array('slug' => 'retirement-property-for-sale'),
        'hierarchical' => true,
        'description' => 'Holds our properties and properties specific data',
        'public' => true,
        'menu_position' => 5,
        'supports' => array('title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'custom-fields', 'page-attributes'),
    );
    register_post_type('properties', $args);

    // Add Amenities Taxonomy
    $taxonomy_labels = array(
        'name' => _x('Amenities', 'taxonomy general name'),
        'singular_name' => _x('Amenity', 'taxonomy singular name'),
        'search_items' => __('Search Amenities'),
        'popular_items' => __('Popular Amenities'),
        'all_items' => __('All Amenities'),
        'edit_item' => __('Edit Amenity'),
        'update_item' => __('Update Amenity'),
        'add_new_item' => __('Add New Amenity'),
        'new_item_name' => __('New Amenity Name'),
        'separate_items_with_commas' => __('Separate amenities with commas'),
        'add_or_remove_items' => __('Add or remove amenities'),
        'choose_from_most_used' => __('Choose from the most used amenities'),
        'menu_name' => __('Amenities'),
    );

    $taxonomy_args = array(
        'labels' => $taxonomy_labels,
        'hierarchical' => true,
        'public' => true,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'amenities'),
    );

    register_taxonomy('amenities', 'properties', $taxonomy_args);


    // Villages Custom Post Type


    $labels = array(
        'name' => _x('Villages', 'post type general name'),
        'singular_name' => _x('Village', 'post type singular name'),
        'add_new' => _x('Add New', 'villages'),
        'add_new_item' => __('Add New Village'),
        'edit_item' => __('Edit Village'),
        'new_item' => __('New Village'),
        'all_items' => __('All Villages'),
        'view_item' => __('View Village'),
        'search_items' => __('Search Villages'),
        'not_found' => __('No villages found'),
        'not_found_in_trash' => __('No villages found in the Trash'),
        // 'parent_item_colon' => ’,
        'menu_name' => 'Villages'
    );
    $args = array(
        'labels' => $labels,
        'has_archive' => false,
        'hierarchical' => true,
        'description' => 'Holds our villages and villages specific data',
        'public' => true,
        'menu_position' => 5,
        'supports' => array('title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'custom-fields'),
    );
    register_post_type('villages', $args);

    // Add Village Taxonomy
    $taxonomy_labels = array(
        'name' => _x('Village Development', 'taxonomy general name'),
        'singular_name' => _x('Village Development', 'taxonomy singular name'),
        'search_items' => __('Search Village Development'),
        'popular_items' => __('Popular Village Development'),
        'all_items' => __('All Village Development'),
        'edit_item' => __('Edit Village Development'),
        'update_item' => __('Update Village Development'),
        'add_new_item' => __('Add New Village Development'),
        'new_item_name' => __('New Village Development Name'),
        'separate_items_with_commas' => __('Separate village development with commas'),
        'add_or_remove_items' => __('Add or remove village development'),
        'choose_from_most_used' => __('Choose from the most used village development'),
        'menu_name' => __('Village Development'),
    );

    $taxonomy_args = array(
        'labels' => $taxonomy_labels,
        'hierarchical' => true,
        'public' => true,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        //'rewrite'           => array( 'slug' => 'village-development' ),
    );

    register_taxonomy('development', 'villages', $taxonomy_args);


    /*Gallery Custom post type */
    $labels = array(
        'name' => _x('Galleries', 'post type general name'),
        'singular_name' => _x('Gallery', 'post type singular name'),
        'add_new' => _x('Add New', 'galleries'),
        'add_new_item' => __('Add New Gallery'),
        'edit_item' => __('Edit Gallery'),
        'new_item' => __('New Gallery'),
        'all_items' => __('All Galleries'),
        'view_item' => __('View Gallery'),
        'search_items' => __('Search Galleries'),
        'not_found' => __('No gallery found'),
        'not_found_in_trash' => __('No galleries found in the Trash'),
        // 'parent_item_colon' => ’,
        'menu_name' => 'Galleries'
    );
    $args = array(
        'labels' => $labels,
        'has_archive' => false,
        'hierarchical' => true,
        'description' => 'Holds our galleries and galleries specific data',
        'public' => true,
        'menu_position' => 5,
        'supports' => array('title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'custom-fields', 'page-attributes'),
    );
    register_post_type('galleries', $args);


    // Add Gallery Taxonomy
    $taxonomy_labels = array(
        'name' => _x('Gallery Type', 'taxonomy general name'),
        'singular_name' => _x('Gallery Type', 'taxonomy singular name'),
        'search_items' => __('Search Gallery Type'),
        'popular_items' => __('Popular Gallery Type'),
        'all_items' => __('All Gallery Type'),
        'edit_item' => __('Edit Gallery Type'),
        'update_item' => __('Update Gallery Type'),
        'add_new_item' => __('Add New Gallery Type'),
        'new_item_name' => __('New Gallery Type Name'),
        'separate_items_with_commas' => __('Separate gallery type with commas'),
        'add_or_remove_items' => __('Add or remove gallery type'),
        'choose_from_most_used' => __('Choose from the most used gallery type'),
        'menu_name' => __('Gallery Type'),
    );

    $taxonomy_args = array(
        'labels' => $taxonomy_labels,
        'hierarchical' => true,
        'public' => true,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        //'rewrite'           => array( 'slug' => 'village-development' ),
    );


    register_taxonomy('gallerytype', 'galleries', $taxonomy_args);




    /*Brochure Custom post type */
    $labels = array(
        'name' => _x('Brochures', 'post type general name'),
        'singular_name' => _x('Brochure', 'post type singular name'),
        'add_new' => _x('Add New', 'Brochures'),
        'add_new_item' => __('Add New Brochure'),
        'edit_item' => __('Edit Brochure'),
        'new_item' => __('New Brochure'),
        'all_items' => __('All Brochures'),
        'view_item' => __('View Brochure'),
        'search_items' => __('Search Brochures'),
        'not_found' => __('No Brochure found'),
        'not_found_in_trash' => __('No Brochures found in the Trash'),
        // 'parent_item_colon' => ’,
        'menu_name' => 'Brochures'
    );
    $args = array(
        'labels' => $labels,
        'has_archive' => true,
        'hierarchical' => true,
        'description' => 'Holds our brochures and brochures specific data',
        'public' => true,
        'menu_position' => 5,
        'supports' => array('title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'custom-fields', 'page-attributes'),
    );
    register_post_type('brochures', $args);


    // Add Brochure Taxonomy
    $taxonomy_labels = array(
        'name' => _x('Brochure Type', 'taxonomy general name'),
        'singular_name' => _x('Brochure Type', 'taxonomy singular name'),
        'search_items' => __('Search Brochure Type'),
        'popular_items' => __('Popular Brochure Type'),
        'all_items' => __('All Brochure Type'),
        'edit_item' => __('Edit Brochure Type'),
        'update_item' => __('Update Brochure Type'),
        'add_new_item' => __('Add New Brochure Type'),
        'new_item_name' => __('New Brochure Type Name'),
        'separate_items_with_commas' => __('Separate brochure type with commas'),
        'add_or_remove_items' => __('Add or remove brochure type'),
        'choose_from_most_used' => __('Choose from the most used brochure type'),
        'menu_name' => __('Brochure Type'),
    );

    $taxonomy_args = array(
        'labels' => $taxonomy_labels,
        'hierarchical' => true,
        'public' => true,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite'           => array('slug' => 'brochure-library'),
    );

    register_taxonomy('brochureytype', 'brochures', $taxonomy_args);


    /**Testimonial Post Type**/
    $labels = array(
        'name' => _x('Testimonial', 'post type general name'),
        'singular_name' => _x('Testimonial', 'post type singular name'),
        'add_new' => _x('Add New', 'testimonial'),
        'add_new_item' => __('Add New Testimonial'),
        'edit_item' => __('Edit Testimonial'),
        'new_item' => __('New Testimonial'),
        'all_items' => __('All Testimonial'),
        'view_item' => __('View Testimonial'),
        'search_items' => __('Search testimonial'),
        'not_found' => __('No testimonial found'),
        'not_found_in_trash' => __('No testimonial found in the Trash'),
        // 'parent_item_colon' => ’,
        'menu_name' => 'Testimonials'
    );
    $args = array(
        'labels' => $labels,
        'has_archive' => false,
        //'rewrite' => array('slug' => 'retirement-property-for-sale'),
        'hierarchical' => true,
        'description' => 'Holds ourtestimonial and testimonials specific data',
        'public' => true,
        'menu_position' => 5,
        'supports' => array('title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'custom-fields', 'page-attributes'),
    );
    register_post_type('testimonial', $args);
}
add_action('init', 'my_custom_post_product');
