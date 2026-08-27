<?php
// Exit if accessed directly
if (!defined('ABSPATH'))
    exit;

// Functionality for filtering retirement properties
require_once('library/retirement-properties.php');

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
function count_filtered_properties($args)
{
    // Clone the args to avoid modifying the original query arguments
    $count_args = $args;
    // Set 'posts_per_page' to -1 to count all posts without pagination
    $count_args['posts_per_page'] = -1;
    // Remove pagination argument to get accurate count
    unset($count_args['paged']);

    // Create a new query to count posts
    $count_query = new WP_Query($count_args);
    // Get the total number of posts
    $total_posts = $count_query->found_posts;
    // Reset post data
    wp_reset_postdata();

    return $total_posts;
}



// Function to retrieve the total filtered posts count
function count_filtered_properties_callback()
{
    // Call the function to get the total count of filtered properties
    $total_filtered_posts = count_filtered_properties($args);

    // Return the value as JSON
    wp_send_json_success(array('total_filtered_posts' => $total_filtered_posts));
}

// Register the AJAX action
add_action('wp_ajax_get_total_filtered_posts', 'count_filtered_properties_callback');
add_action('wp_ajax_nopriv_get_total_filtered_posts', 'count_filtered_properties_callback');

// Start session if not already started
if (!session_id()) {
    session_start();
}

// Function to handle adding/removing properties to/from the wishlist
function handle_wishlist()
{
    $property_id = isset($_POST['property_id']) ? $_POST['property_id'] : 0;
    $wishlist = isset($_SESSION['wishlist']) ? $_SESSION['wishlist'] : array();

    // Toggle property in the wishlist
    if (in_array($property_id, $wishlist)) {
        $wishlist = array_diff($wishlist, array($property_id));
    } else {
        $wishlist[] = $property_id;
    }

    $_SESSION['wishlist'] = $wishlist;

    // Return updated wishlist count
    echo count($wishlist);

    wp_die(); // Always include this line to terminate immediately and return a proper response
}

add_action('wp_ajax_handle_wishlist', 'handle_wishlist');
add_action('wp_ajax_nopriv_handle_wishlist', 'handle_wishlist');

/*Whishlist count shortcode  */
add_shortcode("whishlist_count", "whishlist_count_number");
function whishlist_count_number()
{ ?>
    <div class="wishlist-count-container">
        <span style="color:#fff;" class="wishlist-count">
            <?php echo isset($_SESSION['wishlist']) ? count($_SESSION['wishlist']) : 0; ?>
        </span>
        <!-- <img src="https://rangefordvillages-co-uk.stackstaging.com/wp-content/uploads/2023/12/Vector-1.png" alt="Wishlist"> -->
    </div>
<?php }



function custom_html_shortcode()
{
    // Set up query parameters
    $args = array(
        'post_type' => 'post',
        'posts_per_page' => -1,
        'meta_query' => array(
            array(
                'key' => 'feature_update',
                'value' => 'featureupdate',
                'compare' => 'LIKE',
            ),
        ),
    );

    // Execute the query
    $query = new WP_Query($args);

    // Start output buffering
    ob_start(); ?>

    <section class="slider-wrapper home-hero-slider">
        <div class="slider">

            <?php
            if ($query->have_posts()) :
                while ($query->have_posts()) :
                    $query->the_post();

                    $event_background_image = get_field("event_background_image");
                    $slide_subtitle = get_field("slide_subtitle");
                    $feature_slider_content = get_field("feature_slider_content");
                    $link_button_url = get_field('link_button_url');

                    // Handle image (array or string)
                    $bg_url = '';
                    if (is_array($event_background_image)) {
                        $bg_url = $event_background_image['url'] ?? '';
                    } elseif (is_string($event_background_image)) {
                        $bg_url = $event_background_image;
                    }

                    // Handle link field (array or string)
                    $link_url = '';
                    $link_title = '';

                    if (is_array($link_button_url)) {
                        $link_url = $link_button_url['url'] ?? '';
                        $link_title = $link_button_url['title'] ?? '';
                    } elseif (is_string($link_button_url)) {
                        $link_url = $link_button_url;
                    }
                    ?>

                    <div>
                        <div class="section-story" style="background-image:url(<?php echo esc_url($bg_url); ?>);">
                            <div class="e-con e-flex">
                                <div class="e-con-inner">
                                    <div class="top-content">
                                        <h6>
                                            <?php echo esc_html__('Featured updates', 'text-domain'); ?>
                                        </h6>
                                        <h2>
                                            <?php echo esc_html(wp_trim_words(get_the_title(), 7, '...')); ?>
                                        </h2>
                                    </div>

                                    <div class="bottom-section">
                                        <?php if ($slide_subtitle) : ?>
                                            <h4>
                                                <?php echo esc_html(wp_trim_words($slide_subtitle, 7, '...')); ?>
                                            </h4>
                                        <?php endif; ?>

                                        <?php if ($feature_slider_content) :
                                            echo wp_kses_post($feature_slider_content);
                                        endif; ?>

                                        <?php if ($link_url) : ?>
                                            <a class="elementor-button elementor-button-link elementor-size-sm" href="<?php echo esc_url($link_url); ?>">
                                                <span class="elementor-button-content-wrapper">
                                                    <span class="elementor-button-text">
                                                        <?php echo $link_title ? esc_html($link_title) : 'Read more'; ?>
                                                    </span>
                                                </span>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                <?php
                endwhile;
                wp_reset_postdata();
            else :
                echo esc_html__('No featured updates found.', 'text-domain');
            endif;
            ?>

        </div>

        <div class="e-con e-flex slider-bar">
            <div class="e-con-inner">
                <div class="slider-progress">
                    <div class="progress"></div>
                </div>
            </div>
        </div>
    </section>

    <?php
    return ob_get_clean();
}

add_shortcode('hero_slider_shortcode', 'custom_html_shortcode');

/*Home page properties shortcode */
add_shortcode('home_properties', 'home_page_properties');
function home_page_properties($atts)
{
    // Shortcode attributes with default values
    $atts = shortcode_atts(
        array(
            'set'            => '', // can be 'top' or 'bottom'
            'offset'         => 0,
            'posts_per_page' => 2,
        ),
        $atts,
        'home_properties'
    );

    // Get the properties from ACF fields
    $top_properties = get_field('top_properties', 'option');
    $bottom_properties = get_field('bottom_properties', 'option');

    $property_ids_top = !empty($top_properties) ? wp_list_pluck($top_properties, 'ID') : array();
    $property_ids_bottom = !empty($bottom_properties) ? wp_list_pluck($bottom_properties, 'ID') : array();

    // Determine which properties to query based on the 'set' attribute
    $property_ids = array();
    if ($atts['set'] === 'top' && !empty($property_ids_top)) {
        $property_ids = $property_ids_top;
        // Disable offset if both top and bottom properties are selected
        if (!empty($property_ids_bottom)) {
            $atts['offset'] = 0;
        }
    } elseif ($atts['set'] === 'bottom' && !empty($property_ids_bottom)) {
        $property_ids = $property_ids_bottom;
        // Disable offset if both top and bottom properties are selected
        if (!empty($property_ids_top)) {
            $atts['offset'] = 0;
        }
    }

    // Set up query arguments
    $args = array(
        'post_type' => 'properties', // Change this to your custom post type if needed
        'posts_per_page' => intval($atts['posts_per_page']),
        'post_status' => 'publish',
        'offset' => intval($atts['offset']),
        /*'meta_query' => array(
                'relation' => 'AND',
                array(
                    'key' => 'bed',
                    'type' => 'NUMERIC',
                ),
                array(
                    'key' => 'price',
                    'type' => 'NUMERIC',
                ),
            ),
            'orderby' => array(
                'bed' => 'DESC',
                'price' => 'ASC',
                'title' => 'ASC',
            ),*/

    );

    if (!empty($property_ids)) {
        $offset = max(0, intval($atts['offset']));
        $limit = intval($atts['posts_per_page']);
        if ($limit > 0) {
            $property_ids = array_slice($property_ids, $offset, $limit);
        } elseif ($offset > 0) {
            $property_ids = array_slice($property_ids, $offset);
        }
        if (!empty($property_ids)) {
            $args['post__in'] = $property_ids;
            $args['orderby'] = 'post__in';
            $args['posts_per_page'] = count($property_ids);
            unset($args['offset']);
        } else {
            $args['post__in'] = array(0);
            $args['posts_per_page'] = 1;
        }
    }

    // Execute the query
    $query = new WP_Query($args);

    // Start output buffering
    ob_start();
    ?>
    <div class="property-home">
        <div class="property-tiles-container-2 ">
            <?php
            if ($query->have_posts()) :
                while ($query->have_posts()) :
                    $query->the_post();
                    $featured_image_url = get_the_post_thumbnail_url();
                    ?>
                    <div class="property-tile">
                        <div class="wishlist-icon">
                            <!-- Add your wishlist icon here -->
                        </div>
                        <?php $properties_gallery = get_field('properties_gallery');
                        if ($properties_gallery) {
                            ?>
                            <div class="slick-slider-image properties-image">
                                <?php foreach ($properties_gallery as $image) : ?>
                                    <img src="<?php echo esc_url($image['url']); ?>" alt="<?php echo esc_attr($image['alt']); ?>" class="properties-image-main" />
                                <?php endforeach; ?>
                            </div>
                        <?php } else { ?>
                            <a href="<?php the_permalink(); ?>" class="properties-image"><img src="<?php echo esc_url($featured_image_url); ?>" alt="<?php the_title_attribute(); ?>" class="properties-image-main"></a>
                        <?php } ?>
                        <div class="content">
                            <?php $location = get_field('location'); ?>
                            <h5><?php the_title(); ?></h5>
                            <?php $reserved = get_field('reserved'); ?>
                            <?php if ($reserved && in_array('yes', $reserved)) {
                                echo '<h5 class="reserved">Reserved</h5>';
                            } else {
                                $price = get_field('price');
                                if (!empty($price)) {
                                    if (is_array($price)) {
                                        $price = implode('', $price);
                                    }
                                    $price = (string) $price;
                                    if (strpos($price, ',') !== false) {
                                        echo '<h6 class="price">£ ' . esc_html($price) . '</h6>';
                                    } else {
                                        $formatted_price = number_format((float) preg_replace('/[^\d.]/', '', $price));
                                        echo '<h6 class="price">£ ' . esc_html($formatted_price) . '</h6>';
                                    }
                                }
                            } ?>
                            <?php rangeford_render_property_fees_note($location); ?>
                            <ul class="icon-listing">
                                <?php if ($location) { ?>
                                    <li><img src="<?php echo esc_url(rangeford_acf_image_url(get_field('location_icon'))); ?>" alt=""><?php echo esc_html($location); ?></li>
                                <?php } ?>
                                <?php if ($bed = get_field('bed')) { ?>
                                    <li><img src="<?php echo esc_url(rangeford_acf_image_url(get_field('bed_icon'))); ?>" alt=""><?php echo esc_html($bed); ?> Bedroom(s)</li>
                                <?php } ?>
                                <?php if ($properties_type = get_field('properties_type')) { ?>
                                    <li><img src="<?php echo esc_url(rangeford_acf_image_url(get_field('properties_type_icon'))); ?>" alt=""><?php echo esc_html($properties_type); ?></li>
                                <?php } ?>
                                <?php if ($bathroom = get_field('bathroom')) { ?>
                                    <li><img src="<?php echo esc_url(rangeford_acf_image_url(get_field('bathroom_icon'))); ?>" alt=""><?php echo esc_html($bathroom); ?> Bathroom(s)</li>
                                <?php } ?>
                                <?php if ($properties_sale_text = get_field('properties_sale_text')) { ?>
                                    <li><img src="<?php echo esc_url(rangeford_acf_image_url(get_field('properties_sale_icon'))); ?>" alt=""><?php echo esc_html($properties_sale_text); ?></li>
                                <?php } ?>
                                <?php
                                $floor_text = get_field('property_floor') ?: get_field('dormer_bungalow_text');
                                $floor_icon = rangeford_acf_image_url(get_field('property_floor_icon'));
                                if (!$floor_icon) {
                                    $floor_icon = rangeford_acf_image_url(get_field('dormer_bungalow_icon'));
                                }
                                if ($floor_text) { ?>
                                    <li><img src="<?php echo esc_url($floor_icon); ?>" alt=""><?php echo esc_html($floor_text); ?></li>
                                <?php } ?>
                            </ul>
                        </div>
                        <div class="full-details">
                            <a href="<?php the_permalink(); ?>" class="elementor-button elementor-button-link elementor-size-sm">View full details</a>
                            <div class="wishlist-icon-section" data-property-id="<?php echo get_the_ID(); ?>">
                                <img class="without-fill" src="/wp-content/uploads/2024/05/save-property-line-icon-1.svg">
                                <img style="display:none;" class="fill-whish-list" src="/wp-content/uploads/2024/05/save-property-line-icon-fill-1.svg" alt="Wishlist">
                                <span class="wishlist-text without-fill">Save Property</span>
                                <span style="display:none;" class="fill-whish-list" class="wishlist-text">Remove Property</span>
                            </div>
                        </div>
                    </div>
                <?php
                endwhile;
                wp_reset_postdata();
            else :
                echo 'No properties found';
            endif; ?>
        </div>
    </div>
    <?php
    // Return the buffered content
    return ob_get_clean();
}
add_shortcode('village_properties', 'vilages_page_properties');
function vilages_page_properties($atts)
{
    global $post;
    // print_r($post);
    // echo 'ID'.get_the_ID();
    $post_id = $post->ID;
    // Shortcode attributes with default values
    $atts = shortcode_atts(
        array(
            'offset'         => 0,
            'posts_per_page' => 2,
            'ajax'           => false, // New attribute for AJAX load more
        ),
        $atts,
        'village_properties'
    );

    // Set up query parameters
    $feature_update = get_field('feature_update');
    $properties_list = get_field('properties_list');

    // // Make sure $properties_list is an array of post IDs
    $property_ids = array();
    if (!empty($properties_list)) {
        foreach ($properties_list as $p) {
            $property_ids[] = $p->ID; // Assuming $properties_list contains post objects
        }
    }



    if($_SERVER['REMOTE_ADDR'] == '84.64.205.130'){
        // echo '<pre>'; print_r($property_ids); echo '</pre>'; die();
    }

    // Get the current post title
    $current_post_title = get_the_title();
    $current_post_location = get_field('location', $post_id);
    ?>
    <span id="current-post-title" style="display:none;"><?php echo $current_post_title; ?></span>
    <?php

    $args = array(
        'post_type'      => 'properties',
        'posts_per_page' => intval($atts['posts_per_page']),
        'post_status'    => 'publish',
        'offset'         => intval($atts['offset']),
        'meta_query'     => array(
            'relation'     => 'AND',
            'bed_clause'   => array(
                'key'   => 'bed',
                'type'  => 'NUMERIC',
            ),
            array(
                'key'     => 'location',
                'value'   => $current_post_title,
                'compare' => '=',
            ),
            'price_clause' => array(
                'key'   => 'price',
                'type'  => 'NUMERIC',
            ),
        ),
        'orderby' => array(
            'bed_clause'   => 'DESC',
            'price_clause' => 'ASC',
            'title'        => 'ASC',
        ),
    );

    if($property_ids){
        $args['post__in'] = $property_ids;
    }

    // $args = array(
    //     'post_type'      => 'properties',
    //     'posts_per_page' => intval($atts['posts_per_page']),
    //     'post_status'    => 'publish',
    //     'orderby'        => 'post__in',
    //     'offset'         => intval($atts['offset']),
    //     's'              => $current_post_title, // This filters based on title
    //     'meta_query'     => array(
    //         array(
    //             'key'     => 'location',
    //             'value'   => $current_post_location,
    //             'compare' => 'LIKE'
    //         )
    //     )
    // );		
    // Execute the query
    // echo '<pre>'; print_r($args); echo '</pre>'; die();
    $query = new WP_Query($args);
    // Start output buffering
    ob_start();
    ?>
    <div class="property-home">
        <div class="property-tiles-container-2 UIIIII <?php if ($atts['ajax']) { ?>loadmore-village-properties<?php } ?>">
            <?php
            if ($query->have_posts()) :
                while ($query->have_posts()) : $query->the_post();
                    $featured_image_url = get_the_post_thumbnail_url();

                    // Check if the location matches the current post title
                    $location = get_field('location');
                    if ($location && stripos($location, $current_post_title) !== false) {
                        ?>
                        <div class="property-tile">
                            <div class="wishlist-icon">
                                <!-- Wishlist icon HTML -->
                            </div>

                            <?php $properties_gallery = get_field('properties_gallery');
                            if ($properties_gallery) { ?>
                                <div class="slick-slider-image properties-image">
                                    <?php foreach ($properties_gallery as $image) : ?>
                                        <img src="<?php echo esc_url($image['url']); ?>" alt="<?php echo esc_attr($image['alt']); ?>" class="properties-image-main" />
                                    <?php endforeach; ?>
                                </div>
                            <?php } else { ?>
                                <a href="<?php the_permalink(); ?>" class="properties-image"><img src="<?php echo esc_url($featured_image_url); ?>" alt="<?php the_title_attribute(); ?>" class="properties-image-main"></a>
                            <?php } ?>

                            <div class="content">
                                <h5><?php the_title(); ?></h5>

                                <?php $reserved = get_field('reserved'); ?>
                                
								<?php if ($reserved && in_array('yes', $reserved)) {

									echo '<h5 class="reserved">Reserved</h5>';

								} else {

									$price = get_field('price');

									// Handle array values from ACF
									if (is_array($price)) {
										$price = implode('', $price);
									}

									if (!empty($price)) {

										// Convert to string safely
										$price = (string) $price;

										// Remove £ commas spaces etc
										$clean_price = preg_replace('/[^\d.]/', '', $price);

										if ($clean_price !== '' && is_numeric($clean_price)) {

											echo '<h6 class="price">£ ' . number_format((float)$clean_price) . '</h6>';

										} else {

											echo '<h6 class="price">' . esc_html($price) . '</h6>';

										}

									}

								} ?>
                                <?php rangeford_render_property_fees_note($location); ?>

                                <ul class="icon-listing">
                                    <?php if ($location) { ?>
                                        <li><img src="<?php echo get_field('location_icon'); ?>">
                                            <?php echo $location; ?>
                                        </li>
                                    <?php } ?>
                                    <?php if ($bed = get_field('bed')) { ?>
                                        <li><img src="<?php echo get_field('bed_icon'); ?>">
                                            <?php echo $bed; ?> Bedroom(s)
                                        </li>
                                    <?php } ?>
                                    <?php if ($properties_type = get_field('properties_type')) { ?>
                                        <li><img src="<?php echo get_field('properties_type_icon'); ?>">
                                            <?php echo $properties_type; ?>
                                        </li>
                                    <?php } ?>
                                    <?php if ($bathroom = get_field('bathroom')) { ?>
                                        <li><img src="<?php echo get_field('bathroom_icon'); ?>">
                                            <?php echo $bathroom; ?> Bathroom(s)
                                        </li>
                                    <?php } ?>
                                    <?php
                                    $properties_sale_text = get_field('properties_sale_text');
                                    if ($properties_sale_text) { ?>
                                        <li><img src="<?php echo esc_url(rangeford_acf_image_url(get_field('properties_sale_icon'))); ?>" alt="">
                                            <?php echo esc_html($properties_sale_text); ?>
                                        </li>
                                    <?php } ?>
                                    <?php
                                    $floor_text = get_field('property_floor') ?: get_field('dormer_bungalow_text');
                                    $floor_icon = rangeford_acf_image_url(get_field('property_floor_icon'));
                                    if (!$floor_icon) {
                                        $floor_icon = rangeford_acf_image_url(get_field('dormer_bungalow_icon'));
                                    }
                                    if ($floor_text) { ?>
                                        <li><img src="<?php echo esc_url($floor_icon); ?>" alt="">
                                            <?php echo esc_html($floor_text); ?>
                                        </li>
                                    <?php } ?>
                                </ul>
                            </div>

                            <div class="full-details">
                                <a href="<?php the_permalink(); ?>" class="elementor-button elementor-button-link elementor-size-sm">View full details</a>
                                <div class="wishlist-icon-section" data-property-id="<?php echo get_the_ID(); ?>">
                                    <img class="without-fill" src="/wp-content/uploads/2024/05/save-property-line-icon-1.svg">
                                    <img style="display:none;" class="fill-whish-list" src="/wp-content/uploads/2024/05/save-property-line-icon-fill-1.svg" alt="Wishlist">
                                    <span class="wishlist-text without-fill">Save Property</span>
                                    <span style="display:none;" class="fill-whish-list wishlist-text">Remove Property</span>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                endwhile;
                wp_reset_postdata();
            else :
                // echo 'No properties found';
            endif;
            ?>
        </div>

        <?php $total_posts = $query->found_posts;
        if ($atts['ajax'] && $total_posts > ($atts['offset'] + $atts['posts_per_page'])) : ?>
            <div class="village-loadmore">
                <button id="load-more-btn"
                        data-offset="<?php echo esc_attr($atts['offset'] + $atts['posts_per_page']); ?>"
                        data-posts-per-page="<?php echo esc_attr($atts['posts_per_page']); ?>"
                        data-ajax-url="<?php echo esc_attr(admin_url('admin-ajax.php')); ?>">
                    Load More
                </button>
            </div>
        <?php endif; ?>
    </div>
    <?php
    return ob_get_clean();
}



add_action('wp_ajax_nopriv_load_more_properties', 'load_more_properties');
add_action('wp_ajax_load_more_properties', 'load_more_properties');

function load_more_properties()
{
    $offset = isset($_POST['offset']) ? intval($_POST['offset']) : 0;
    $posts_per_page = isset($_POST['posts_per_page']) ? intval($_POST['posts_per_page']) : 2;

    // Get the current post's title
    $current_post_title = isset($_POST['current_post_title']) ? sanitize_text_field($_POST['current_post_title']) : '';

    // Get the current post's location
    $current_post_location = get_field('location', get_the_ID());
    // echo $current_post_location;
    $property_ids = isset($_POST['property_ids']) ? $_POST['property_ids'] : array();

    $properties_list = get_field('properties_list', $_POST['current_post_id']);
    $complete_properties_list = $properties_list;
    $properties_list = array_filter($properties_list, function($item) use($property_ids){
        return !in_array($item->ID, $property_ids);
    });

    // // Make sure $properties_list is an array of post IDs
    $property_ids_in = array();
    if (!empty($properties_list)) {
        foreach ($properties_list as $p) {
            $property_ids_in[] = $p->ID; // Assuming $properties_list contains post objects
        }
    }

    // Set up query parameters


    $args = array(
        'post_type'      => 'properties',
        'posts_per_page' => $posts_per_page,
        'post_status'    => 'publish',
        'meta_query'     => array(
            'relation'     => 'AND',
            'bed_clause'   => array(
                'key'   => 'bed',
                'type'  => 'NUMERIC',
            ),
            array(
                'key'     => 'location',
                'value'   => $current_post_title,
                'compare' => '=',
            ),
            'price_clause' => array(
                'key'   => 'price',
                'type'  => 'NUMERIC',
            ),
        ),
        'orderby' => array(
            'bed_clause'   => 'DESC',
            'price_clause' => 'ASC',
            'title'        => 'ASC',
        ),
    );

    if(!$property_ids_in){
        // $args['post__not_in'] = $property_ids;
        $args['offset'] = $offset;
    }else{
        $args['post__in'] = $property_ids_in;
    }


    // Execute the query
    $query = new WP_Query($args);

    if ($query->have_posts()) :
        ob_start();
        while ($query->have_posts()) : $query->the_post();
            $featured_image_url = get_the_post_thumbnail_url();
            ?>
            <div class="property-tile">
                <div class="wishlist-icon">
                    <!-- Add your wishlist icon here -->
                    <!-- <div class="properties-extra-info">
                                    <?php $properties_extra_info = get_field("properties_extra_info"); ?>
                                    <h5><?php echo $properties_extra_info;  ?></h5>

                                </div>
                                <div class="wishlist-icon-section" data-property-id="<?php echo get_the_ID(); ?>">
                                    <img class="without-fill"
                                        src=" /wp-content/uploads/2024/01/digE7fMmMetJLZvmsQ8iVBlzURp5xXF1.svg"
                                        alt="Wishlist">
                                    <img style="display:none;" class="fill-whish-list"
                                        src="/wp-content/uploads/2023/12/Vector-1.png"
                                        alt="Wishlist">
                                </div> -->
                </div>
                <?php $properties_gallery = get_field('properties_gallery');
                if ($properties_gallery) {
                    ?>
                    <div class="slick-slider-image properties-image">
                        <?php foreach ($properties_gallery as $image) : ?>
                            <img src="<?php echo esc_url($image['url']); ?>" alt="<?php echo esc_attr($image['alt']); ?>" class="properties-image-main" />
                        <?php endforeach; ?>
                    </div>
                <?php } else { ?>
                    <a href="<?php the_permalink(); ?>" class="properties-image"><img src="<?php echo esc_url($featured_image_url); ?>" alt="<?php the_title_attribute(); ?>" class="properties-image-main"></a>
                <?php }
                ?>
                <div class="content">


                    <?php
                    $price = get_field('price');
                    $location = get_field('location');
                    $bed = get_field('bed');
                    $properties_type = get_field('properties_type');
                    $bathroom = get_field('bathroom');
                    $location_icon = get_field('location_icon');
                    $bed_icon = get_field('bed_icon');
                    $properties_type_icon = get_field('properties_type_icon');
                    $bathroom_icon = get_field('bathroom_icon');
                    $properties_sale_icon = rangeford_acf_image_url(get_field('properties_sale_icon'));
                    $properties_sale_text = get_field('properties_sale_text');
                    $floor_text = get_field('property_floor') ?: get_field('dormer_bungalow_text');
                    $floor_icon = rangeford_acf_image_url(get_field('property_floor_icon'));
                    if (!$floor_icon) {
                        $floor_icon = rangeford_acf_image_url(get_field('dormer_bungalow_icon'));
                    }
                    ?>
                    <h5>
                        <?php the_title(); ?>
                    </h5>
                    <?php $reserved = get_field('reserved'); ?>
                    <?php if ($reserved && in_array('yes', $reserved)) {
                        echo '<h5 class="reserved">Reserved</h5>';
                    } else {
                        if (strpos($price, ',') !== false) {
                            //$formatted_number = number_format($price);
                            echo '<h6 class="price">£ ' . $price . '</h6>';
                        } else {
                            $formatted_price = number_format($price);
                            echo '<h6 class="price">£ ' . $formatted_price . '</h6>';
                        }
                    } ?>
                    <?php rangeford_render_property_fees_note($location); ?>
                    <ul class="icon-listing">

                        <?php if ($location) { ?>
                            <li><img src="<?php echo $location_icon; ?>">
                                <?php echo $location; ?>
                            </li>
                        <?php } ?>
                        <?php if ($bed) { ?>
                            <li><img src="<?php echo $bed_icon; ?>">
                                <?php echo $bed; ?> Bedroom(s)
                            </li>
                        <?php } ?>
                        <?php if ($properties_type) { ?>
                            <li><img src="<?php echo $properties_type_icon; ?>">
                                <?php echo $properties_type; ?>
                            </li>
                        <?php } ?>
                        <?php if ($bathroom) { ?>
                            <li><img src="<?php echo $bathroom_icon; ?>">
                                <?php echo $bathroom; ?> Bathroom(s)
                            </li>
                        <?php } ?>
                        <?php if ($properties_sale_text) { ?>
                            <li><img src="<?php echo esc_url($properties_sale_icon); ?>" alt="">
                                <?php echo esc_html($properties_sale_text); ?>
                            </li>
                        <?php } ?>
                        <?php if ($floor_text) { ?>
                            <li><img src="<?php echo esc_url($floor_icon); ?>" alt="">
                                <?php echo esc_html($floor_text); ?>
                            </li>
                        <?php } ?>
                    </ul>

                </div>


                <div class="full-details">
                    <a href="<?php the_permalink(); ?>" class="elementor-button elementor-button-link elementor-size-sm">View full details</a>
                    <div class="wishlist-icon-section" data-property-id="<?php echo get_the_ID(); ?>">
                        <img class="without-fill" src="/wp-content/uploads/2024/05/save-property-line-icon-1.svg">
                        <img style="display:none;" class="fill-whish-list" src="/wp-content/uploads/2024/05/save-property-line-icon-fill-1.svg" alt="Wishlist">
                        <span class="wishlist-text without-fill">Save Property</span>
                        <span style="display:none;" class="fill-whish-list" class="wishlist-text">Remove Property</span>
                    </div>
                </div>

            </div>
        <?php
        endwhile;
        wp_reset_postdata();
        $response = ob_get_clean();
        // Check if there are more properties to load
        if ((count($query->posts) < $posts_per_page) || ($complete_properties_list && count($property_ids_in) <= $posts_per_page)) {
            // Indicate no more posts
            $response .= '<script type="text/javascript">jQuery("#load-more-btn").hide();</script>';
        }
        echo $response;
    else :
        echo '<script type="text/javascript">jQuery("#load-more-btn").hide();</script>';
    endif;
    wp_die();
}


/*Home page properties shortcode */
add_shortcode('home_properties_three_row', 'home_page_properties_three');
function home_page_properties_three()
{
    // Set up query parameters
    $feature_update = get_field('feature_update');

    $args = array(
        'post_type' => 'properties', // Change this to your custom post type if needed
        'posts_per_page' => 3,
        'post_status' => 'publish',
        'offset' => 2,  // Skip the first 2 posts
        //'order'          => 'DESC',

    );

    // Execute the query
    $query = new WP_Query($args);

    // Start output buffering
    ob_start();

    ?>
    <div class="property-home-three-column">
        <div class="property-tiles-container-2">
            <?php
            if ($query->have_posts()) :
                while ($query->have_posts()) :
                    $query->the_post();

                    $featured_image_url = get_the_post_thumbnail_url();
                    ?>
                    <div class="property-tile">
                        <div class="wishlist-icon">
                            <!-- Add your wishlist icon here -->
                            <!-- <div class="properties-extra-info">
                                <?php $properties_extra_info = get_field("properties_extra_info"); ?>
                                <h5><?php echo $properties_extra_info;  ?></h5>

                            </div>
                            <div class="wishlist-icon-section" data-property-id="<?php echo get_the_ID(); ?>">
                                <img class="without-fill"
                                    src=" /wp-content/uploads/2024/01/digE7fMmMetJLZvmsQ8iVBlzURp5xXF1.svg"
                                    alt="Wishlist">
                                <img style="display:none;" class="fill-whish-list"
                                    src="/wp-content/uploads/2023/12/Vector-1.png"
                                    alt="Wishlist">
                            </div> -->
                        </div>
                        <?php $properties_gallery = get_field('properties_gallery');
                        if ($properties_gallery) {
                            ?>
                            <div class="slick-slider-image properties-image">
                                <?php foreach ($properties_gallery as $image) : ?>
                                    <img src="<?php echo esc_url($image['url']); ?>" alt="<?php echo esc_attr($image['alt']); ?>" class="properties-image-main" />
                                <?php endforeach; ?>
                            </div>
                        <?php } else { ?>
                            <a href="<?php the_permalink(); ?>" class="properties-image"><img src="<?php echo esc_url($featured_image_url); ?>" alt="<?php the_title_attribute(); ?>" class="properties-image-main"></a>
                        <?php }
                        ?>
                        <div class="content">
                            <?php
                            $price = get_field('price');
                            $location = get_field('location');
                            ?>
                            <h5>
                                <?php the_title(); ?>
                            </h5>
                            <?php $reserved = get_field('reserved'); ?>
                            <?php if ($reserved && in_array('yes', $reserved)) {
                                echo '<h5 class="reserved">Reserved</h5>';
                            } else {
                                if (!empty($price)) {
                                    if (is_array($price)) {
                                        $price = implode('', $price);
                                    }
                                    $price = (string) $price;
                                    if (strpos($price, ',') !== false) {
                                        echo '<h6 class="price">£ ' . esc_html($price) . '</h6>';
                                    } else {
                                        $formatted_price = number_format((float) preg_replace('/[^\d.]/', '', $price));
                                        echo '<h6 class="price">£ ' . esc_html($formatted_price) . '</h6>';
                                    }
                                }
                            } ?>
                            <?php rangeford_render_property_fees_note($location); ?>
                            <ul class="icon-listing">
                                <?php if ($location) { ?>
                                    <li><img src="<?php echo esc_url(rangeford_acf_image_url(get_field('location_icon'))); ?>" alt=""><?php echo esc_html($location); ?></li>
                                <?php } ?>
                                <?php if ($bed = get_field('bed')) { ?>
                                    <li><img src="<?php echo esc_url(rangeford_acf_image_url(get_field('bed_icon'))); ?>" alt=""><?php echo esc_html($bed); ?> Bedroom(s)</li>
                                <?php } ?>
                                <?php if ($properties_type = get_field('properties_type')) { ?>
                                    <li><img src="<?php echo esc_url(rangeford_acf_image_url(get_field('properties_type_icon'))); ?>" alt=""><?php echo esc_html($properties_type); ?></li>
                                <?php } ?>
                                <?php if ($bathroom = get_field('bathroom')) { ?>
                                    <li><img src="<?php echo esc_url(rangeford_acf_image_url(get_field('bathroom_icon'))); ?>" alt=""><?php echo esc_html($bathroom); ?> Bathroom(s)</li>
                                <?php } ?>
                                <?php if ($properties_sale_text = get_field('properties_sale_text')) { ?>
                                    <li><img src="<?php echo esc_url(rangeford_acf_image_url(get_field('properties_sale_icon'))); ?>" alt=""><?php echo esc_html($properties_sale_text); ?></li>
                                <?php } ?>
                                <?php
                                $floor_text = get_field('property_floor') ?: get_field('dormer_bungalow_text');
                                $floor_icon = rangeford_acf_image_url(get_field('property_floor_icon'));
                                if (!$floor_icon) {
                                    $floor_icon = rangeford_acf_image_url(get_field('dormer_bungalow_icon'));
                                }
                                if ($floor_text) { ?>
                                    <li><img src="<?php echo esc_url($floor_icon); ?>" alt=""><?php echo esc_html($floor_text); ?></li>
                                <?php } ?>
                            </ul>

                        </div>
                        <div class="full-details">
                            <a href="<?php the_permalink(); ?>" class="elementor-button elementor-button-link elementor-size-sm">View full details</a>
                            <div class="wishlist-icon-section" data-property-id="<?php echo get_the_ID(); ?>">
                                <img class="without-fill" src=" /wp-content/uploads/2024/01/digE7fMmMetJLZvmsQ8iVBlzURp5xXF1.svg" alt="Wishlist">
                                <img style="display:none;" class="fill-whish-list" src="/wp-content/uploads/2023/12/Vector-1.png" alt="Wishlist">
                            </div>
                        </div>

                    </div>
                <?php


                endwhile;
                wp_reset_postdata();

            else :
                echo 'No properties found';
            endif; ?>
        </div>
    </div>
    <?php
    // Return the buffered content
    return ob_get_clean();
}



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





// / Current Year
function current_year_shortcode()
{
    $current_datetime = date('Y');
    return $current_datetime;
}
add_shortcode('current_year', 'current_year_shortcode');


add_action('elementor/widgets/widgets_registered', 'register_custom_slider_widget');

function register_custom_slider_widget($widgets_manager)
{
    require_once(__DIR__ . '/custom-slider-widget.php');
    $widgets_manager->register(new \Custom_Slider_Widget());
}
// Include the Elementor custom module
// include_once get_stylesheet_directory() . '/gallery-module.php';


// Add lastmodified post date

function last_modified_date_shortcode()
{
    // Start output buffering to capture any output
    ob_start();

    // Get the post ID
    $post_id = get_the_ID();

    // Get the modified date for a specific post with the desired format
    $modified_date = get_post_modified_time('M j, Y', false, $post_id);

    // Display the formatted date
    echo '<p class="update-date">Updated ' . $modified_date . '</p>';

    // Get the output buffer contents and clean the buffer
    $output = ob_get_clean();
    return $output;
}

// Register the shortcode
add_shortcode('last_modified_date', 'last_modified_date_shortcode');

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


// / Gallery Lightbox
function gallery_light_box()
{
    ob_start();
    ?>
    <div class="properties-content single-gallery-popup">
        <div class="lightbox" style="display: none;">
            <span class="back-property"><img src="/wp-content/uploads/2024/01/Vector-4.svg"> Back to galleries</span>
            <div class="slider-popup-image">
                <?php
                $gallery_single = get_field('gallery_single');
                while (have_rows('gallery_and_video')) : the_row();
                    $image_gallery = get_sub_field('image_gallery');
                    $gallery_video = get_sub_field('gallery_video');
                    ?>
                    <?php if ($image_gallery) { ?>
                        <img src="<?php echo $image_gallery; ?>" alt="<?php echo esc_attr($image['alt']); ?>" />
                    <?php }
                    if ($gallery_video) { ?>
                        <video width="100%" height="100%" controls>
                            <source src="<?php echo $gallery_video['url']; ?>" type="video/mp4">
                        </video>
                    <?php  } ?>
                <?php endwhile; ?>
            </div>
            <div class="property-slider-thumbs">
                <?php
                while (have_rows('gallery_and_video')) : the_row(); ?>
                    <?php
                    $image_gallery = get_sub_field('image_gallery');
                    $gallery_video = get_sub_field('gallery_video');
                    ?>
                    <img src="<?php echo $image_gallery; ?>" alt="<?php echo esc_attr($image['alt']); ?>" />
                <?php endwhile; ?>
            </div>
        </div>
    </div>
    <?php
    $output = ob_get_clean();
    return $output;
}
add_shortcode('gallery_box', 'gallery_light_box');


/*Properties list in the villages */
add_shortcode('village_category', 'vilages_category_list');
function vilages_category_list()
{
    ob_start();
    // Fetch all terms for 'gallerytype' taxonomy
    $terms = get_terms(array(
        'taxonomy' => 'gallerytype',
        'hide_empty' => false,
    ));

    // Check if any terms are found
    if (!empty($terms) && !is_wp_error($terms)) {
        echo '<div class="container-set">';
        echo '<div class="flex-row">';

        foreach ($terms as $term) {
            // Retrieve the image field from ACF. Replace 'your_image_field_name' with the actual field name.
            $image = get_field('gallery_type_image', $term); // Make sure to adjust with your actual field name. The return type can be an array, URL or ID based on your ACF settings.

            // Check if the image is actually set
            if (!empty($image)) {
                // Use the appropriate array keys if your image field returns an array
                $image_url = isset($image['url']) ? $image['url'] : $image; // If your return type is URL or ID, adjust this line accordingly.

                // Term link (archive page)
                $term_link = get_term_link($term);

                echo '<div class="col-lg-6">';
                echo '<div class="gallery-box">';

                // If image is an array, use $image['sizes']['large'] for example, or $image['url'] if it's a URL return type.
                echo '<img src="' . esc_url($image_url) . '" alt="' . esc_attr($term->name) . '" class="gallery-image" />';

                echo '<h4>' . esc_html($term->name) . '</h4>';
                echo '<a class="elementor-button elementor-button-link elementor-size-sm" href="' . esc_url($term_link) . '" rel="nofollow noreferrer noopener">';
                echo '<span class="elementor-button-content-wrapper">';
                echo '<span class="elementor-button-text">Read more</span>';
                echo '</span></a>';

                echo '</div>'; // .gallery-box
                echo '</div>'; // .col-lg-6
            }
        }

        echo '</div>'; // .flex-row
        echo '</div>'; // .container-set
    }

    // Return the buffered content
    return ob_get_clean();
}
function my_query_by_different_order($query)
{
    $query->set('post_parent', 0);
}
add_action('elementor/query/gallery_parent', 'my_query_by_different_order');


// function my_query_by_different_order_bruchure( $query ) {
//     $query->set( 'post_parent', 0 );

// }
// add_action( 'elementor/query/brochure_parent', 'my_query_by_different_order_bruchure' ); 


/*Broucher category list */
add_shortcode('broucher_category', 'broucher_category_list');
function broucher_category_list()
{
    ob_start();
    $terms_per_page = 6; // Number of terms to display per page
    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1; // Get the current page number
    $terms = get_terms(array(
        'taxonomy' => 'brochureytype',
        'hide_empty' => false,
        'number' => $terms_per_page,
        'offset' => ($paged - 1) * $terms_per_page // Calculate the offset based on the current page
    ));

    // Check if any terms are found
    if (!empty($terms) && !is_wp_error($terms)) {
        echo '<div class="container-set">';
        echo '<div class="flex-row g-15">';

        foreach ($terms as $term) {
            // Retrieve the image field from ACF. Replace 'your_image_field_name' with the actual field name.
            $image = get_field('gallery_type_image', $term); // Make sure to adjust with your actual field name. The return type can be an array, URL or ID based on your ACF settings.

            // Check if the image is actually set
            if (!empty($image)) {
                // Use the appropriate array keys if your image field returns an array
                $image_url = isset($image['url']) ? $image['url'] : $image; // If your return type is URL or ID, adjust this line accordingly.

                // Term link (archive page)
                $term_link = get_term_link($term);

                echo '<div class="col-lg-6">';
                echo '<div class="gallery-box">';

                // If image is an array, use $image['sizes']['large'] for example, or $image['url'] if it's a URL return type.
                echo '<img src="' . esc_url($image_url) . '" alt="' . esc_attr($term->name) . '" class="gallery-image" />';

                echo '<h4>' . esc_html($term->name) . '</h4>';
                // echo '<a class="elementor-button elementor-button-link elementor-size-sm" href="' . esc_url($term_link) . '" rel="nofollow noreferrer noopener">';
                echo '<a class="elementor-button elementor-button-link elementor-size-sm brochure-view-button" href="#" data-pdf-url="' . esc_attr($term_link) . '" data-term-id="' . esc_attr($term->term_id) . '">';

                echo '<span class="elementor-button-content-wrapper">';
                echo '<span class="elementor-button-text">View Brochure</span>';
                echo '</span></a>';

                echo '</div>'; // .gallery-box
                echo '</div>'; // .col-lg-6
            }
        }

        echo '</div>'; // .flex-row
        echo '</div>'; // .container-set
        // Pagination
        $total_terms = wp_count_terms('brochureytype');
        $total_pages = ceil($total_terms / $terms_per_page);
        $pagination_args = array(
            'base'         => get_pagenum_link(1) . '%_%',
            'format'       => '/page/%#%',
            'total'        => $total_pages,
            'current'      => $paged,
            'prev_text' => __(''),
            'next_text' => __(''),
            // 'type'         => 'list',
        );
        echo '<div class="pagination1">';
        echo paginate_links($pagination_args);
        echo '</div>';
        echo '<script>';
        echo 'jQuery(document).ready(function($) {';
        echo '$(".brochure-view-button").on("click", function() {';
        echo 'window.lastClickedBrochureButton = $(this);'; // Store reference to last clicked brochure button.
        echo '});';
        echo '});';
        echo '</script>';
        ?>

        <?php
    }

    // Return the buffered content
    return ob_get_clean();
}

/*Village contact page link */
add_shortcode('village_contact', 'vilages_conatct_link');
function vilages_conatct_link()
{
    ob_start();
    // Get the current post ID
    $post_id = get_the_ID();
    // Get the post title using the post ID
    $post_title = get_the_title($post_id);

    // URL to the contact page with query parameters for the post ID and type
    $contact_page_url = add_query_arg(
        array(
            'village' => urlencode($post_title),
            'type' => 'book-appointment'
        ),
        get_permalink(get_page_by_path('contact-us'))
    );

    // Output the button
    echo '<a href="' . esc_url($contact_page_url) . '" class="btn btn-primary book-appointment">Book Appointment</a>';
    return ob_get_clean();
}
/*Arrange a visit contact page link */
add_shortcode('arrange_contact', 'arrange_conatct_link');
function arrange_conatct_link()
{
    ob_start();
    // Get the current post ID
    $post_id_arrange = get_the_ID();
    $post_title_arrange = get_the_title($post_id_arrange);

    // URL to the contact page with query parameters for the post ID and type
    $contact_page_url_arrange = add_query_arg(
        array(
            'village' => urlencode($post_title_arrange),
            'type' => 'arrange-a-visit'
        ),
        get_permalink(get_page_by_path('contact-us'))
    );
    // Output the button
    echo '<a class="book-appointment" href="' . esc_url($contact_page_url_arrange) . '" class="btn btn-primary">Arrange A Visit</a>';
    return ob_get_clean();
}

/*Request a call back page link */
add_shortcode('request_contact', 'request_conatct_link');
function request_conatct_link()
{
    ob_start();
    // Get the current post ID
    $post_id_reques = get_the_ID();
    $post_title_reques = get_the_title($post_id_reques);

    $contact_page_url_reques = add_query_arg(
        array(
            'village' => urlencode($post_title_reques),
            'type' => 'request-a-call-back'
        ),
        get_permalink(get_page_by_path('contact-us'))
    );

    // Output the button
    echo '<a class="book-appointment" href="' . esc_url($contact_page_url_reques) . '" class="btn btn-primary">Request A Call Back</a>';
    return ob_get_clean();
}

add_shortcode('village_form_contact', function(){
    ob_start();
    ?>
    <a class="book-appointment btn btn-primary" href="#" onclick="event.preventDefault(); document.querySelector('.village-contact-form').scrollIntoView({ behavior: 'smooth' })">Get in touch</a>
    <?php
    return ob_get_clean();
});

/*Download brochure  page link */
add_shortcode('brochure_contact', 'brochure_conatct_link');
function brochure_conatct_link($atts)
{
    ob_start();

    // Get the current post ID
    $post_id_brochure = get_the_ID();
    if(get_field('villages_download_brochure_type',$post_id_brochure) == "file" ){
        $villages_download_brochure = get_field('villages_download_brochure', $post_id_brochure);
        $url_brochure = $villages_download_brochure['url'];
    }else{
        $url_brochure = get_field('villages_download_brochure_link',$post_id_brochure);
    }

    $post_id = get_the_ID();
    // Get the post title using the post ID
    $post_title = get_the_title($post_id);

    // Shortcode attributes
    $shortcode_atts = shortcode_atts(array(
        'button_text' => 'Download Lifestyle Brochure', // Default button text
        'class'        => 'pum-trigger popmake-10766',
    ), $atts);

    // URL to the contact page with query parameters for the post ID and type
    $contact_page_url_broucher = add_query_arg(
        array(
            'village' => urlencode($post_title),
            'type' => 'request-brochure'
        ),
        get_permalink(get_page_by_path('contact-us'))
    );

    ?>
    <?php if ($url_brochure) { ?>
    <a class="book-appointment btn btn-primary single-brochure-view-button <?php echo esc_attr($shortcode_atts['class']); ?>" href="#" data-popmake="10766" data-term-url="<?php echo $url_brochure; ?>" data-term-redirect="<?php echo $contact_page_url_broucher; ?>" data-term-id="<?php echo $post_id_brochure; ?>"><?php echo esc_html($shortcode_atts['button_text']); ?></a>

<?php } ?>
    <!-- <a class="book-appointment btn btn-primary  <?php echo esc_attr($shortcode_atts['class']); ?>" href="<?php echo $url_brochure; ?>"><?php echo esc_html($shortcode_atts['button_text']); ?></a> -->
    <?php
    return ob_get_clean();
}

add_shortcode('secondary_brochure_contact', 'secondary_brochure_contact_link');
function secondary_brochure_contact_link($atts)
{
    ob_start();

    $shortcode_atts = shortcode_atts(array(
        'button_text' => '',
        'class'       => 'pum-trigger popmake-32688',
        'field'       => 'villages_secondary_brochure_link',
        'label_field' => 'villages_secondary_brochure_label',
        'post_id'     => 0,
    ), $atts);

    $post_id_brochure = $shortcode_atts['post_id'] ? intval($shortcode_atts['post_id']) : get_the_ID();
    $url_brochure = get_field($shortcode_atts['field'], $post_id_brochure);

    if (is_array($url_brochure)) {
        $url_brochure = $url_brochure['url'] ?? '';
    }

    $label = get_field($shortcode_atts['label_field'], $post_id_brochure);
    $button_text = $shortcode_atts['button_text'] ?: ($label ?: 'Download Walnut Lane Brochure');

    $contact_page_url_broucher = add_query_arg(
        array(
            'village' => urlencode(get_the_title($post_id_brochure)),
            'type' => 'request-brochure',
        ),
        get_permalink(get_page_by_path('contact-us'))
    );

    if ($url_brochure) {
        ?>
        <a class="book-appointment btn btn-primary single-brochure-view-button <?php echo esc_attr($shortcode_atts['class']); ?>" href="#" data-popmake="32688" data-term-url="<?php echo esc_url($url_brochure); ?>" data-term-redirect="<?php echo esc_url($contact_page_url_broucher); ?>" data-term-id="<?php echo esc_attr($post_id_brochure); ?>"><?php echo esc_html($button_text); ?></a>
        <?php
    }

    return ob_get_clean();
}


add_shortcode('village_thank', 'vilages_thank_link');

function vilages_thank_link()
{
    ob_start();
    /*if (isset($_GET['village'])) {
        $ref_post_id = intval($_GET['village']);  ?>
        <input type="hidden" name="referred_post_id" value="<?php echo $ref_post_id; ?>">
        <?php $appointment_detail = get_field('thank_you_page_url', $ref_post_id); ?>
        <input type="hidden" name="referred_post_id_new" value="<?php echo $appointment_detail; ?>">
         <?php 
    }*/

    if (isset($_GET['village'])) {
        $ref_post_title = sanitize_text_field($_GET['village']);
        // Get the post object by the title, specifying the correct custom post type ('villages' in this case)
        $ref_post = get_page_by_title($ref_post_title, OBJECT, 'villages');
        $post_id_village = $ref_post->ID;

        ?>
        <input type="hidden" name="referred_post_id" value="<?php echo $post_id_village; ?>">
        <?php $appointment_detail = get_field('thank_you_page_url', $post_id_village); ?>
        <input type="hidden" name="referred_post_id_new" value="<?php echo $appointment_detail; ?>">
        <?php
    }
    return ob_get_clean();
}

// Define the function to replace dashes with spaces
function customize_book_appointment_value($value, $tag)
{
    // Only run on the 'type' field
    if ('type' === $tag->name) {
        // Replace dashes with spaces
        $value = str_replace('-', ' ', $value);
    }
    return $value;
}

// Add a filter to modify the 'type' value when Contact Form 7 collects it
add_filter('wpcf7_form_tag', 'customize_book_appointment_value', 10, 2);

// Define the function to replace dashes with spaces in posted data
function cf7_change_booking_type_format($posted_data)
{
    if (isset($posted_data['type'])) {
        // Replace the dash with a space in the 'type' form field
        $posted_data['type'] = str_replace('-', ' ', $posted_data['type']);
    }
    return $posted_data;
}

// Add a filter to modify the posted data before Contact Form 7 uses it
add_filter('wpcf7_posted_data', 'cf7_change_booking_type_format');

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

function villages_thank_url_shortcode() {
    // Fetch the 'thank_you_page_url' ACF field value
    $thank_you_page_url = get_field('thank_you_page_url');

    // Return the input field with the value
    return '<input type="hidden" value="' . esc_url($thank_you_page_url) . '" class="villages-thank-url">';
}

// Register the shortcode [villages_thank_url]
add_shortcode('villages_thank_url', 'villages_thank_url_shortcode');

/*Contact for submit after redirect to Thank you page */
/*
add_action('wp_footer', function () {
    if (is_singular('villages')) {
        ?>
        <script type="text/javascript">
            document.addEventListener('wpcf7mailsent', function(event) {
                var thankYouUrlInput = document.querySelector('.villages-thank-url');
                if (thankYouUrlInput && thankYouUrlInput.value) {
                    window.location.href = thankYouUrlInput.value;
                } else {
                    window.location.href = '/thank-you';
                }
            });
        </script>
        <?php
    } else {
        redirect_cf7_with_referer();
    }
})*/

// Contact Form 7 redirect logic
add_action('wp_footer', function () {

    if (is_singular('villages')) { ?>
        <script type="text/javascript">
        document.addEventListener('wpcf7mailsent', function(event) {

            // CF7 form ID
            var formId = event.detail.contactFormId;

            // Current village slug from URL (/villages/{slug}/)
            var pathParts = window.location.pathname.split('/').filter(Boolean);
            var villageSlug = pathParts[1];

            // --- Brochure form redirect ---
            if ((formId === 10765 || formId === 32660) && villageSlug) {
                location.href = '/villages/' + villageSlug + '/thank-you/?brochure-request';
            }
            // --- Default redirect for other forms ---
            else {
                var thankYouUrlInput = document.querySelector('.villages-thank-url');
                if (thankYouUrlInput && thankYouUrlInput.value) {
                    location.href = thankYouUrlInput.value;
                } else {
                    location.href = '/thank-you';
                }
            }

        });
        </script>
    <?php
    } else {
        redirect_cf7_with_referer();
    }

});



function redirect_cf7_with_referer()
{
    if (isset($_GET['village']) && isset($_GET['type'])) { ?>
        <script type="text/javascript">
            document.addEventListener('wpcf7mailsent', function(event) {
                var inputs = event.detail.inputs;
                var village = '';
                var type = '';
                for (var i = 0; i < inputs.length; i++) {
                    if ('village' == inputs[i].name) {
                        // Convert to lowercase and replace spaces with hyphens
                        village = inputs[i].value.toLowerCase().replace(/\s+/g, '-');
                    }

                    if ('type' == inputs[i].name) {
                        type = inputs[i].value;
                    }
                }
                location = '/villages/' + village + '/' + type + '/' + 'thank-you';
            }, false);
        </script>

        <?php
    } else if (isset($_GET['propertiesvillage']) && isset($_GET['property']) && isset($_GET['contactType'])) { ?>
        <script type="text/javascript">
            document.addEventListener('wpcf7mailsent', function(event) {
                var inputs = event.detail.inputs;
                var village = '';
                var property = '';
                var contactType = '';

                for (var i = 0; i < inputs.length; i++) {
                    if ('propertiesvillage' == inputs[i].name) {
                        // Convert to lowercase and replace spaces with hyphens
                        village = inputs[i].value.toLowerCase().replace(/\s+/g, '-');
                    }
                    if ('property' == inputs[i].name) {
                        property = inputs[i].value.toLowerCase().replace(/\s+/g, '-');
                    }
                    if ('contactType' == inputs[i].name) {
                        contactType = inputs[i].value;
                    }
                }
                location = '/villages/' + village + '/' + property + '/' + '/make-enquiry/thank-you';
            }, false);
        </script>

    <?php } else { ?>

        <script type="text/javascript">
            console.log(document.querySelector('.villages-thank-url'));
            document.addEventListener('wpcf7mailsent', function(event) {
                var thankYouUrlInput = document.querySelector('.villages-thank-url'); // Check for the hidden input
                console.log(thankYouUrlInput);
                if (window.lastClickedBrochureButton) {
                    var termUrl = window.lastClickedBrochureButton.data('term-url');
                    if (termUrl) {
                        window.open(termUrl, '_blank'); // Opens the link in a new tab

                    }
                    /*Thank you 1 */
                    var redirectUrlproperties = window.lastClickedBrochureButton.data('term-redirectproperty');
                    if (redirectUrlproperties) {
                        // Parse the redirectUrl to extract village and type
                        var urlParams = new URLSearchParams(redirectUrlproperties.split('?')[1]);

                        var village = urlParams.get('propertiesvillage').toLowerCase().replace(/\s+|\+/g, '-');
                        var property = urlParams.get('property').toLowerCase().replace(/\s+|\+/g, '-');
                        var type = urlParams.get('type');
                        var termId = window.lastClickedBrochureButton.data('term-id'); // Get the term ID

                        // Store the post_id_brochure in localStorage
                        localStorage.setItem('post_id_brochure_id', termId);
                        // Construct the new location
                        var newLocationproperties = '/villages/' + village + '/' + property + '/' + '/request-brochure/thank-you';
                        //console.log(newLocationproperties);
                        location.href = newLocationproperties; // Redirect to the new location
                    }
                    /** Thank you 2*/
                    var redirectUrl = window.lastClickedBrochureButton.data('term-redirect');
                    if (redirectUrl) {
                        var urlParams = new URLSearchParams(redirectUrl.split('?')[1]);
                        var village = urlParams.get('village').toLowerCase().replace(/\s+|\+/g, '-');
                        var type = urlParams.get('type');
                        var termId = window.lastClickedBrochureButton.data('term-id'); // Get the term ID

                        // Store the post_id_brochure in localStorage
                        localStorage.setItem('post_id_brochure_id', termId);


                        var newLocation = '/villages/' + village + '/' + type + '/thank-you';
                        location.href = newLocation; // Redirect to the new location
                    }
                    /**/
                    var redirectUrlhome = window.lastClickedBrochureButton.data('term-redirechomewood');
                    var termId = window.lastClickedBrochureButton.data('term-id');
                    if (redirectUrlhome) {
                        var urlParamshome = new URLSearchParams(redirectUrlhome.split('?')[1]);
                        var villagehome = urlParamshome.get('village').toLowerCase().replace(/\s+|\+/g, '-');
                        var typehome = urlParamshome.get('type');
                        var termUrl1 = window.lastClickedBrochureButton.data('term-url');
                        localStorage.setItem('post_id_brochure_id', termId);
                        // Construct the new location
                        var newLocationhome = '/village/' + villagehome + '/' + typehome + '/thank-you';
                        location.href = newLocationhome; // Redirect to the new location
                    }

                    var redirectUrlhome = window.lastClickedBrochureButton.data('term-strawberry');
                    var termId = window.lastClickedBrochureButton.data('term-id');
                    if (redirectUrlhome) {
                        var urlParamshome = new URLSearchParams(redirectUrlhome.split('?')[1]);
                        var villagehome = urlParamshome.get('village').toLowerCase().replace(/\s+|\+/g, '-');
                        var typehome = urlParamshome.get('type');
                        localStorage.setItem('post_id_brochure_id', termId);
                        // Construct the new location
                        var newLocationhome = '/village/' + villagehome + '/' + typehome + '/thank-you';
                        location.href = newLocationhome; // Redirect to the new location
                    }
                    var redirectmickle = window.lastClickedBrochureButton.data('term-redirectmickle');
                    var termId = window.lastClickedBrochureButton.data('term-id');
                    if (redirectmickle) {
                        var urlredirectmickle = new URLSearchParams(redirectmickle.split('?')[1]);
                        var villagemickle = urlredirectmickle.get('village').toLowerCase().replace(/\s+|\+/g, '-');
                        var typemickle = urlredirectmickle.get('type');
                        // Construct the new location
                        localStorage.setItem('post_id_brochure_id', termId);
                        var newLocationmickle = '/village/' + villagemickle + '/' + typemickle + '/thank-you';
                        location.href = newLocationmickle; // Redirect to the new location
                    }
                    var redirectsiddin = window.lastClickedBrochureButton.data('term-redirectsiddin');
                    var termId = window.lastClickedBrochureButton.data('term-id');
                    if (redirectsiddin) {
                        var urlredirectsiddin = new URLSearchParams(redirectsiddin.split('?')[1]);
                        var villagesiddin = urlredirectsiddin.get('village').toLowerCase().replace(/\s+|\+/g, '-');
                        var typesiddin = urlredirectsiddin.get('type');
                        localStorage.setItem('post_id_brochure_id', termId);
                        // Construct the new location
                        var newLocationsiddin = '/village/' + villagesiddin + '/' + typesiddin + '/thank-you';
                        location.href = newLocationsiddin; // Redirect to the new location
                    }

                    var redirectgreen = window.lastClickedBrochureButton.data('term-redirectgreen');
                    var termId = window.lastClickedBrochureButton.data('term-id');
                    if (redirectgreen) {
                        var urlredirectgreen = new URLSearchParams(redirectgreen.split('?')[1]);
                        var villagegreen = urlredirectgreen.get('village').toLowerCase().replace(/\s+|\+/g, '-');
                        var typesiddingreen = urlredirectgreen.get('type');
                        localStorage.setItem('post_id_brochure_id', termId);
                        // Construct the new location
                        var newLocationgreen = '/village/' + villagegreen + '/' + typesiddingreen + '/thank-you';
                        location.href = newLocationgreen; // Redirect to the new location
                    }
                    var $submittedPopup = jQuery(event.target).closest('[id^="popmake-"]');
                    if ($submittedPopup.length) {
                        $submittedPopup.find('.wpcf7-response-output').hide();
                        $submittedPopup.find('.popmake-close').trigger('click');
                    } else {
                        jQuery('#popmake-10766 .wpcf7-form sent .wpcf7-response-output').hide();
                        jQuery('#popmake-10766').find('.popmake-close').trigger('click');
                    }
                } else if(thankYouUrlInput && thankYouUrlInput.value) {
                    location.href = thankYouUrlInput.value; // Redirect to the URL from the input

                } else {
                    var redirectUrl1 = '/thank-you';
                    location = redirectUrl1;
                }
            }, false);
        </script>

    <?php }
}
// Add this to your theme's functions.php file or a custom plugin
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


/*function dynamic_field_values($tag, $unused)
{

    if ($tag['name'] != 'your-field-name')
        return $tag;

    $args = array(
        'numberposts'   => -1,
        'post_type'     => 'villages',
        'orderby'       => 'title',
        'order'         => 'ASC',
    );

    $custom_posts = get_posts($args);

    if (!$custom_posts)
        return $tag;
    // Insert a blank item as the first option
    array_unshift($tag['values'], '');
    array_unshift($tag['labels'], 'Village interested in');

    foreach ($custom_posts as $custom_post) {

        $tag['raw_values'][] = $custom_post->post_title;
        $tag['values'][] = $custom_post->post_title;
        $tag['labels'][] = $custom_post->post_title;
    }

    return $tag;
}

add_filter('wpcf7_form_tag', 'dynamic_field_values', 10, 2);*/
function dynamic_village_field_values($tag, $unused) {
    $qo = get_queried_object();

    // Only target the specific dropdown
    if ($tag['name'] !== 'your-field-name') {
        return $tag;
    }

    // Reset the options so we don’t duplicate
    $tag['values'] = [];
    $tag['labels'] = [];

    // Get village posts
    $args = array(
        'numberposts' => -1,
        'post_type'   => 'villages',
        'orderby'     => 'title',
        'order'       => 'ASC',
        'exclude'     => [20184],
    );

    if ($qo && $qo->post_name === 'coming-soon') {
        $args['meta_query'] = array(
            array(
                'key'     => 'future_village',
                'value'   => '1',
                'compare' => '=',
            ),
        );

        $args['orderby'] = 'menu_order';
        $args['order']   = 'ASC';
    }

    $custom_posts = get_posts($args);
    $mapping      = [];

    if (!$custom_posts) {
        return $tag; // no villages
    }

    // Insert default/placeholder option
    $tag['values'][] = '';
    $tag['labels'][] = 'Village interested in';

    foreach ($custom_posts as $custom_post) {
        $village_name = $custom_post->post_title;
        $village_email = get_field('contact_form_email', $custom_post->ID);

        if (!empty($village_email)) {
            // support multiple comma-separated addresses
            $emails       = array_map('trim', explode(',', $village_email));
            $email_string = implode(',', $emails);

            // Add option to dropdown
            $tag['values'][] = $village_name;
            $tag['labels'][] = $village_name;

            // Store mapping for email routing
            $mapping[$village_name] = $email_string;
        }
    }

    set_transient('village_email_mapping', $mapping, 12 * HOUR_IN_SECONDS);

    return $tag;
}
add_filter('wpcf7_form_tag', 'dynamic_village_field_values', 10, 2);

function wpcf7_custom_email_recipient($contact_form) {
    $form_id = $contact_form->id();
    $submission = WPCF7_Submission::get_instance();

    if (!$submission) {
        error_log('Submission instance not found.');
        return;
    }

    $data = $submission->get_posted_data();
    $mapping = get_transient('village_email_mapping');

    // --- Forms with your-field-name dropdown ---
    if (in_array($form_id, [637, 2564, 16015, 10765, 32660])) {

        if (isset($data['your-field-name'])) {
            $selected_value = is_array($data['your-field-name'])
                ? trim($data['your-field-name'][0])
                : trim($data['your-field-name']);

            if ($mapping && array_key_exists($selected_value, $mapping)) {
                $email_addresses = $mapping[$selected_value];
            }
        }elseif(isset ($data['propertiesvillage'])){
            $selected_value = $data['propertiesvillage'];
            if ($mapping && array_key_exists($selected_value, $mapping)) {
                $email_addresses = $mapping[$selected_value];
            }
        }
    }

    // --- Form 22416 with venu-title hidden field ---
    if ($form_id == 22416) {
        if (isset($data['venue-title'])) {
            $selected_value = trim($data['venue-title']);

            if ($mapping && array_key_exists($selected_value, $mapping)) {
                $email_addresses = $mapping[$selected_value];
            }
        }
    }

    // If we got addresses, process them
    if (!empty($email_addresses)) {
        $emails = array_map('trim', explode(',', $email_addresses));
        $valid_emails = array_filter($emails, function ($email) {
            return filter_var($email, FILTER_VALIDATE_EMAIL);
        });

        if (!empty($valid_emails)) {
            $recipient_string = implode(',', $valid_emails);
            $mail = $contact_form->prop('mail');
            $mail['recipient'] = $recipient_string;
            $contact_form->set_properties(['mail' => $mail]);
        } else {
            error_log('No valid emails found for: ' . $selected_value);
        }
    } else {
        error_log("Email mapping not found for form $form_id value: " . ($selected_value ?? 'N/A'));
    }
}
add_action('wpcf7_before_send_mail', 'wpcf7_custom_email_recipient');

add_shortcode('budget_calculator', 'recent_posts_function');
function recent_posts_function()
{
    ob_start(); ?>

    <div class="budget-calculator">
        <!-- test -->
        <div class="bottom-text">
            <p>The information provided is indicative only and should not be taken as guaranteed expenditure. Costs will vary based on individual circumstances, usage and options selected. We recommend that customers take independent legal and financial advice before making the decision to buy one of our properties.
            </p>
        </div>
        <div class="flex-row dropdown-row">
            <div class="col-md-4">
                <div class="custom-dropdown">
                    <h6>How many bedrooms?</h6><select name="property_type_dropdown">
                        <option value="">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="custom-dropdown">
                    <h6> How many people</h6><select name="property_type_dropdown">
                        <option value="">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="custom-dropdown">
                    <h6>Do you need a parking space?</h6><select name="property_type_dropdown">
                        <option value="">Yes</option>
                        <option value="">No</option>

                    </select>
                </div>
            </div>

        </div>
        <div class="flex-row radio-row">

            <div class="radio">
                <input type="radio" id="Weekly" name="condition" value="Weekly">
                <label for="Weekly">Weekly</label>
            </div>
            <div class="radio">
                <input type="radio" id="Monthly" name="condition" value="Monthly">
                <label for="Monthly">Monthly</label>
            </div>
            <div class="radio">
                <input type="radio" id="Yearly" name="condition" value="Yearly">
                <label for="Yearly">Yearly</label>
            </div>

        </div>

        <!-- home  -->
        <div class="range-slider-section home">
            <div class="title">
                <div class="flex-row table-row">
                    <div class="col">
                        <h5>SERVICES/UTILITY</h5>
                    </div>
                    <div class="col">
                        <h5>YOU PAY PER YEAR</h5>
                    </div>
                    <div class="col">
                        <h5>LIKELY COST AT RANGEFORD PER YEAR</h5>
                    </div>
                </div>
            </div>
            <div class="description">
                <h4>HOME</h4>
                <p>Adjust the slider to your current outgoings</p>
                <div class="flex-row table-row">
                    <div class="col">
                        <h5>Utilities (gas, electricity and water)</h5>
                    </div>

                    <div class="col">
                        <div class="flex-row range-slider-row">
                            <div class="range-wrap">
                                <div class="range-value"></div>
                                <input type="range" class="range-slider" min="500" max="3000" value="2300">
                            </div>
                            <span class="slider-value" title="YOU PAY PER YEAR">£<span class="range-slider-value">2300</span></span>
                            <div class="inspired-price">
                                <h5 class="title-mob">LIKELY COST AT RANGEFORD PER YEAR</h5>
                                <div class="popup-row">
                                    <input type="text" data-id="0" id="inspired_0" name="inspired_home_0" class="inspired_input" value="£840" disabled="">
                                    <div class="tooltip" onclick="toggleTooltip(this)">
                                        <i class="fa fa-info-circle"></i>
                                        <span class="tooltiptext">Your individual heating costs are reduced as communal areas are kept warm.</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex-row table-row">
                    <div class="col">
                        <h5>Media(TV, satellite)</h5>
                    </div>

                    <div class="col">
                        <div class="flex-row range-slider-row">
                            <div class="range-wrap">
                                <div class="range-value"></div>
                                <input type="range" class="range-slider" min="500" max="3000" value="2300">
                            </div>
                            <span class="slider-value" title="YOU PAY PER YEAR">£<span class="range-slider-value">2300</span></span>
                            <div class="inspired-price">
                                <h5 class="title-mob">LIKELY COST AT RANGEFORD PER YEAR</h5>
                                <div class="popup-row">
                                    <input type="text" data-id="0" id="inspired_0" name="inspired_home_0" class="inspired_input" value="£840" disabled="">
                                    <div class="tooltip" onclick="toggleTooltip(this)">
                                        <i class="fa fa-info-circle"></i>
                                        <span class="tooltiptext">Your individual heating costs are reduced as communal areas are kept warm.</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex-row table-row">
                    <div class="col">
                        <h5>Broadline</h5>
                    </div>

                    <div class="col">
                        <div class="flex-row range-slider-row">
                            <div class="range-wrap">
                                <div class="range-value"></div>
                                <input type="range" class="range-slider" min="500" max="3000" value="2300">
                            </div>
                            <span class="slider-value" title="YOU PAY PER YEAR">£<span class="range-slider-value">2300</span></span>
                            <div class="inspired-price">
                                <h5 class="title-mob">LIKELY COST AT RANGEFORD PER YEAR</h5>
                                <div class="popup-row">
                                    <input type="text" data-id="0" id="inspired_0" name="inspired_home_0" class="inspired_input" value="£840" disabled="">
                                    <div class="tooltip" onclick="toggleTooltip(this)">
                                        <i class="fa fa-info-circle"></i>
                                        <span class="tooltiptext">Your individual heating costs are reduced as communal areas are kept warm.</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex-row table-row">
                    <div class="col">
                        <h5>House alarm cost</h5>
                    </div>

                    <div class="col">
                        <div class="flex-row range-slider-row">
                            <div class="range-wrap">
                                <div class="range-value"></div>
                                <input type="range" class="range-slider" min="500" max="3000" value="2300">
                            </div>
                            <span class="slider-value" title="YOU PAY PER YEAR">£<span class="range-slider-value">2300</span></span>
                            <div class="inspired-price">
                                <h5 class="title-mob">LIKELY COST AT RANGEFORD PER YEAR</h5>
                                <div class="popup-row">
                                    <input type="text" data-id="0" id="inspired_0" name="inspired_home_0" class="inspired_input" value="£840" disabled="">
                                    <div class="tooltip" onclick="toggleTooltip(this)">
                                        <i class="fa fa-info-circle"></i>
                                        <span class="tooltiptext">Your individual heating costs are reduced as communal areas are kept warm.</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex-row table-row">
                    <div class="col">
                        <h5>Council Tax</h5>
                    </div>

                    <div class="col">
                        <div class="flex-row range-slider-row">
                            <div class="range-wrap">
                                <div class="range-value"></div>
                                <input type="range" class="range-slider" min="500" max="3000" value="2300">
                            </div>
                            <span class="slider-value" title="YOU PAY PER YEAR">£<span class="range-slider-value">2300</span></span>
                            <div class="inspired-price">
                                <h5 class="title-mob">LIKELY COST AT RANGEFORD PER YEAR</h5>
                                <div class="popup-row">
                                    <input type="text" data-id="0" id="inspired_0" name="inspired_home_0" class="inspired_input" value="£840" disabled="">
                                    <div class="tooltip" onclick="toggleTooltip(this)">
                                        <i class="fa fa-info-circle"></i>
                                        <span class="tooltiptext">Your individual heating costs are reduced as communal areas are kept warm.</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex-row table-row">
                    <div class="col">
                        <h5>Insurance (Building, Contents)</h5>
                    </div>

                    <div class="col">
                        <div class="flex-row range-slider-row">
                            <div class="range-wrap">
                                <div class="range-value"></div>
                                <input type="range" class="range-slider" min="500" max="3000" value="2300">
                            </div>
                            <span class="slider-value" title="YOU PAY PER YEAR">£<span class="range-slider-value">2300</span></span>
                            <div class="inspired-price">
                                <h5 class="title-mob">LIKELY COST AT RANGEFORD PER YEAR</h5>
                                <div class="popup-row">
                                    <input type="text" data-id="0" id="inspired_0" name="inspired_home_0" class="inspired_input" value="£840" disabled="">
                                    <div class="tooltip" onclick="toggleTooltip(this)">
                                        <i class="fa fa-info-circle"></i>
                                        <span class="tooltiptext">Your individual heating costs are reduced as communal areas are kept warm.</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- HOME MAINTENANCE -->
        <div class="range-slider-section home-maintanance">
            <div class="description">
                <h4>HOME MAINTENANCE</h4>
                <p>Adjust the slider to your current outgoings</p>

                <div class="flex-row table-row">
                    <div class="col">
                        <h5>Utilities (gas, electricity and water)</h5>
                    </div>

                    <div class="col">
                        <div class="flex-row range-slider-row">
                            <div class="range-wrap">
                                <div class="range-value"></div>
                                <input type="range" class="range-slider" min="500" max="3000" value="2300">
                            </div>
                            <span class="slider-value" title="YOU PAY PER YEAR">£<span class="range-slider-value">2300</span></span>
                            <div class="inspired-price">
                                <h5 class="title-mob">LIKELY COST AT RANGEFORD PER YEAR</h5>
                                <div class="popup-row">
                                    <input type="text" data-id="0" id="inspired_0" name="inspired_home_0" class="inspired_input" value="£840" disabled="">
                                    <div class="tooltip" onclick="toggleTooltip(this)">
                                        <i class="fa fa-info-circle"></i>
                                        <span class="tooltiptext">Your individual heating costs are reduced as communal areas are kept warm.</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex-row table-row">
                    <div class="col">
                        <h5>Media(TV, satellite)</h5>
                    </div>

                    <div class="col">
                        <div class="flex-row range-slider-row">
                            <div class="range-wrap">
                                <div class="range-value"></div>
                                <input type="range" class="range-slider" min="500" max="3000" value="2300">
                            </div>
                            <span class="slider-value" title="YOU PAY PER YEAR">£<span class="range-slider-value">2300</span></span>
                            <div class="inspired-price">
                                <h5 class="title-mob">LIKELY COST AT RANGEFORD PER YEAR</h5>
                                <div class="popup-row">
                                    <input type="text" data-id="0" id="inspired_0" name="inspired_home_0" class="inspired_input" value="£840" disabled="">
                                    <div class="tooltip" onclick="toggleTooltip(this)">
                                        <i class="fa fa-info-circle"></i>
                                        <span class="tooltiptext">Your individual heating costs are reduced as communal areas are kept warm.</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex-row table-row">
                    <div class="col">
                        <h5>Broadline</h5>
                    </div>

                    <div class="col">
                        <div class="flex-row range-slider-row">
                            <div class="range-wrap">
                                <div class="range-value"></div>
                                <input type="range" class="range-slider" min="500" max="3000" value="2300">
                            </div>
                            <span class="slider-value" title="YOU PAY PER YEAR">£<span class="range-slider-value">2300</span></span>
                            <div class="inspired-price">
                                <h5 class="title-mob">LIKELY COST AT RANGEFORD PER YEAR</h5>
                                <div class="popup-row">
                                    <input type="text" data-id="0" id="inspired_0" name="inspired_home_0" class="inspired_input" value="£840" disabled="">
                                    <div class="tooltip" onclick="toggleTooltip(this)">
                                        <i class="fa fa-info-circle"></i>
                                        <span class="tooltiptext">Your individual heating costs are reduced as communal areas are kept warm.</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex-row table-row">
                    <div class="col">
                        <h5>House alarm cost</h5>
                    </div>

                    <div class="col">
                        <div class="flex-row range-slider-row">
                            <div class="range-wrap">
                                <div class="range-value"></div>
                                <input type="range" class="range-slider" min="500" max="3000" value="2300">
                            </div>
                            <span class="slider-value" title="YOU PAY PER YEAR">£<span class="range-slider-value">2300</span></span>
                            <div class="inspired-price">
                                <h5 class="title-mob">LIKELY COST AT RANGEFORD PER YEAR</h5>
                                <div class="popup-row">
                                    <input type="text" data-id="0" id="inspired_0" name="inspired_home_0" class="inspired_input" value="£840" disabled="">
                                    <div class="tooltip" onclick="toggleTooltip(this)">
                                        <i class="fa fa-info-circle"></i>
                                        <span class="tooltiptext">Your individual heating costs are reduced as communal areas are kept warm.</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- TRANSPORT  -->
        <div class="range-slider-section transport">
            <div class="description">
                <h4>TRANSPORT</h4>
                <p>Adjust the slider to your current outgoings</p>

                <div class="flex-row table-row">
                    <div class="col">
                        <h5>Utilities (gas, electricity and water)</h5>
                    </div>

                    <div class="col">
                        <div class="flex-row range-slider-row">
                            <div class="range-wrap">
                                <div class="range-value"></div>
                                <input type="range" class="range-slider" min="500" max="3000" value="2300">
                            </div>
                            <span class="slider-value" title="YOU PAY PER YEAR">£<span class="range-slider-value">2300</span></span>
                            <div class="inspired-price">
                                <h5 class="title-mob">LIKELY COST AT RANGEFORD PER YEAR</h5>
                                <div class="popup-row">
                                    <input type="text" data-id="0" id="inspired_0" name="inspired_home_0" class="inspired_input" value="£840" disabled="">
                                    <div class="tooltip" onclick="toggleTooltip(this)">
                                        <i class="fa fa-info-circle"></i>
                                        <span class="tooltiptext">Your individual heating costs are reduced as communal areas are kept warm.</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex-row table-row">
                    <div class="col">
                        <h5>Media(TV, satellite)</h5>
                    </div>

                    <div class="col">
                        <div class="flex-row range-slider-row">
                            <div class="range-wrap">
                                <div class="range-value"></div>
                                <input type="range" class="range-slider" min="500" max="3000" value="2300">
                            </div>
                            <span class="slider-value" title="YOU PAY PER YEAR">£<span class="range-slider-value">2300</span></span>
                            <div class="inspired-price">
                                <h5 class="title-mob">LIKELY COST AT RANGEFORD PER YEAR</h5>
                                <div class="popup-row">
                                    <input type="text" data-id="0" id="inspired_0" name="inspired_home_0" class="inspired_input" value="£840" disabled="">
                                    <div class="tooltip" onclick="toggleTooltip(this)">
                                        <i class="fa fa-info-circle"></i>
                                        <span class="tooltiptext">Your individual heating costs are reduced as communal areas are kept warm.</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex-row table-row">
                    <div class="col">
                        <h5>Broadline</h5>
                    </div>

                    <div class="col">
                        <div class="flex-row range-slider-row">
                            <div class="range-wrap">
                                <div class="range-value"></div>
                                <input type="range" class="range-slider" min="500" max="3000" value="2300">
                            </div>
                            <span class="slider-value" title="YOU PAY PER YEAR">£<span class="range-slider-value">2300</span></span>
                            <div class="inspired-price">
                                <h5 class="title-mob">LIKELY COST AT RANGEFORD PER YEAR</h5>
                                <div class="popup-row">
                                    <input type="text" data-id="0" id="inspired_0" name="inspired_home_0" class="inspired_input" value="£840" disabled="">
                                    <div class="tooltip" onclick="toggleTooltip(this)">
                                        <i class="fa fa-info-circle"></i>
                                        <span class="tooltiptext">Your individual heating costs are reduced as communal areas are kept warm.</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex-row table-row">
                    <div class="col">
                        <h5>House alarm cost</h5>
                    </div>

                    <div class="col">
                        <div class="flex-row range-slider-row">
                            <div class="range-wrap">
                                <div class="range-value"></div>
                                <input type="range" class="range-slider" min="500" max="3000" value="2300">
                            </div>
                            <span class="slider-value" title="YOU PAY PER YEAR">£<span class="range-slider-value">2300</span></span>
                            <div class="inspired-price">
                                <h5 class="title-mob">LIKELY COST AT RANGEFORD PER YEAR</h5>
                                <div class="popup-row">
                                    <input type="text" data-id="0" id="inspired_0" name="inspired_home_0" class="inspired_input" value="£840" disabled="">
                                    <div class="tooltip" onclick="toggleTooltip(this)">
                                        <i class="fa fa-info-circle"></i>
                                        <span class="tooltiptext">Your individual heating costs are reduced as communal areas are kept warm.</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </div>


        <!-- DISCRETIONARY SPEND -->

        <div class="range-slider-section discretionary-spend">
            <div class="description">
                <h4>DISCRETIONARY SPEND</h4>
                <p>Adjust the slider to your current outgoings</p>

                <div class="flex-row table-row">
                    <div class="col">
                        <h5>Utilities (gas, electricity and water)</h5>
                    </div>

                    <div class="col">
                        <div class="flex-row range-slider-row">
                            <div class="range-wrap">
                                <div class="range-value"></div>
                                <input type="range" class="range-slider" min="500" max="3000" value="2300">
                            </div>
                            <span class="slider-value" title="YOU PAY PER YEAR">£<span class="range-slider-value">2300</span></span>
                            <div class="inspired-price">
                                <h5 class="title-mob">LIKELY COST AT RANGEFORD PER YEAR</h5>
                                <div class="popup-row">
                                    <input type="text" data-id="0" id="inspired_0" name="inspired_home_0" class="inspired_input" value="£840" disabled="">
                                    <div class="tooltip" onclick="toggleTooltip(this)">
                                        <i class="fa fa-info-circle"></i>
                                        <span class="tooltiptext">Your individual heating costs are reduced as communal areas are kept warm.</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex-row table-row">
                    <div class="col">
                        <h5>Media(TV, satellite)</h5>
                    </div>

                    <div class="col">
                        <div class="flex-row range-slider-row">
                            <div class="range-wrap">
                                <div class="range-value"></div>
                                <input type="range" class="range-slider" min="500" max="3000" value="2300">
                            </div>
                            <span class="slider-value" title="YOU PAY PER YEAR">£<span class="range-slider-value">2300</span></span>
                            <div class="inspired-price">
                                <h5 class="title-mob">LIKELY COST AT RANGEFORD PER YEAR</h5>
                                <div class="popup-row">
                                    <input type="text" data-id="0" id="inspired_0" name="inspired_home_0" class="inspired_input" value="£840" disabled="">
                                    <div class="tooltip" onclick="toggleTooltip(this)">
                                        <i class="fa fa-info-circle"></i>
                                        <span class="tooltiptext">Your individual heating costs are reduced as communal areas are kept warm.</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex-row table-row">
                    <div class="col">
                        <h5>Broadline</h5>
                    </div>

                    <div class="col">
                        <div class="flex-row range-slider-row">
                            <div class="range-wrap">
                                <div class="range-value"></div>
                                <input type="range" class="range-slider" min="500" max="3000" value="2300">
                            </div>
                            <span class="slider-value" title="YOU PAY PER YEAR">£<span class="range-slider-value">2300</span></span>
                            <div class="inspired-price">
                                <h5 class="title-mob">LIKELY COST AT RANGEFORD PER YEAR</h5>
                                <div class="popup-row">
                                    <input type="text" data-id="0" id="inspired_0" name="inspired_home_0" class="inspired_input" value="£840" disabled="">
                                    <div class="tooltip" onclick="toggleTooltip(this)">
                                        <i class="fa fa-info-circle"></i>
                                        <span class="tooltiptext">Your individual heating costs are reduced as communal areas are kept warm.</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex-row table-row">
                    <div class="col">
                        <h5>House alarm cost</h5>
                    </div>

                    <div class="col">
                        <div class="flex-row range-slider-row">
                            <div class="range-wrap">
                                <div class="range-value"></div>
                                <input type="range" class="range-slider" min="500" max="3000" value="2300">
                            </div>
                            <span class="slider-value" title="YOU PAY PER YEAR">£<span class="range-slider-value">2300</span></span>
                            <div class="inspired-price">
                                <h5 class="title-mob">LIKELY COST AT RANGEFORD PER YEAR</h5>
                                <div class="popup-row">
                                    <input type="text" data-id="0" id="inspired_0" name="inspired_home_0" class="inspired_input" value="£840" disabled="">
                                    <div class="tooltip" onclick="toggleTooltip(this)">
                                        <i class="fa fa-info-circle"></i>
                                        <span class="tooltiptext">Your individual heating costs are reduced as communal areas are kept warm.</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>


        <!-- SERVICE CHARGE -->

        <div class="range-slider-section services-charge">

            <div class="description">
                <h4>SERVICE CHARGE</h4>
                <p>Adjust the slider to your current outgoings</p>


                <div class="flex-row table-row">
                    <div class="col">
                        <h5>Utilities (gas, electricity and water)</h5>
                    </div>

                    <div class="col">
                        <div class="flex-row range-slider-row">
                            <div class="range-wrap">
                                <div class="range-value"></div>
                                <input type="range" class="range-slider" min="500" max="3000" value="2300">
                            </div>
                            <span class="slider-value" title="YOU PAY PER YEAR">£<span class="range-slider-value">2300</span></span>
                            <div class="inspired-price">
                                <h5 class="title-mob">LIKELY COST AT RANGEFORD PER YEAR</h5>
                                <div class="popup-row">
                                    <input type="text" data-id="0" id="inspired_0" name="inspired_home_0" class="inspired_input" value="£840" disabled="">
                                    <div class="tooltip" onclick="toggleTooltip(this)">
                                        <i class="fa fa-info-circle"></i>
                                        <span class="tooltiptext">Your individual heating costs are reduced as communal areas are kept warm.</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </div>


        <!-- CARE COSTS  -->

        <div class="range-slider-section care-cost">
            <div class="description">
                <h4>CARE COSTS</h4>
                <p>Adjust the slider to your current outgoings</p>


                <div class="flex-row table-row">
                    <div class="col">
                        <h5>Utilities (gas, electricity and water)</h5>
                    </div>

                    <div class="col">
                        <div class="flex-row range-slider-row">
                            <div class="range-wrap">
                                <div class="range-value"></div>
                                <input type="range" class="range-slider" min="500" max="3000" value="2300">
                            </div>
                            <span class="slider-value" title="YOU PAY PER YEAR">£<span class="range-slider-value">2300</span></span>
                            <div class="inspired-price">
                                <h5 class="title-mob">LIKELY COST AT RANGEFORD PER YEAR</h5>
                                <div class="popup-row">
                                    <input type="text" data-id="0" id="inspired_0" name="inspired_home_0" class="inspired_input" value="£840" disabled="">
                                    <div class="tooltip" onclick="toggleTooltip(this)">
                                        <i class="fa fa-info-circle"></i>
                                        <span class="tooltiptext">Your individual heating costs are reduced as communal areas are kept warm.</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- Chart section  -->
        <div class="chart">
            <div class="flex-row chart-row">
                <div class="col-lg-6">
                    <canvas id="ChartOne" class="piechart"></canvas>
                </div>
                <div class="col-lg-6">
                    <div class="input-container">
                        <div class="headings">

                            <h4>WHAT YOU PAY CURRENTLY</h4>
                            <h3>£13,890per year</h3>
                        </div>
                        <div class="flex-row">
                            <div class="col-md-6 home">
                                <div class="value">
                                    £
                                    <input type="number" id="Home" value="3567" readonly>
                                </div>
                                <label for="Home">Home</label>
                            </div>
                            <div class="col-md-6 home-maintanance">
                                <div class="value">
                                    £
                                    <input type="number" id="HomeMaintanance" value="2569" readonly>
                                </div>
                                <label for="HomeMaintanance">Home Maintanance</label>
                            </div>
                            <div class="col-md-6 transport">
                                <div class="value">
                                    £
                                    <input type="number" id="Transport" value="2420" readonly>
                                </div>
                                <label for="Transport">Transport</label>
                            </div>
                            <div class="col-md-6 discretionary-spend">
                                <div class="value">
                                    £
                                    <input type="number" id="DiscretionarySpend" value="3300" readonly>
                                </div>
                                <label for="DiscretionarySpend">Discretionary Spend</label>
                            </div>
                            <div class="col-md-6 services-charge">
                                <div class="value">
                                    £
                                    <input type="number" id="ServicesCharge" value="2440" readonly>
                                </div>
                                <label for="ServicesCharge">Services Charge</label>
                            </div>
                            <div class="col-md-6 care-cost">
                                <div class="value">
                                    £
                                    <input type="number" id="CareCost" value="2001" readonly>
                                </div>
                                <label for="CareCost">Care Cost</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex-row chart-row mt-20">
                <div class="col-lg-6">
                    <canvas id="Charttwo" class="piechart"></canvas>
                </div>
                <div class="col-lg-6">
                    <div class="input-container">
                        <div class="headings">

                            <h4>WHAT YOU PAY CURRENTLY</h4>
                            <h3>£13,890per year</h3>
                        </div>
                        <div class="flex-row">
                            <div class="col-md-6 home">
                                <div class="value">
                                    £
                                    <input type="number" id="Home2" value="800" readonly>
                                </div>
                                <label for="Home">Home</label>
                            </div>
                            <div class="col-md-6 home-maintanance">
                                <div class="value">
                                    £
                                    <input type="number" id="HomeMaintanance2" value="750" readonly>
                                </div>
                                <label for="HomeMaintanance">Home Maintanance</label>
                            </div>
                            <div class="col-md-6 transport">
                                <div class="value">
                                    £
                                    <input type="number" id="Transport2" value="625" readonly>
                                </div>
                                <label for="Transport">Transport</label>
                            </div>
                            <div class="col-md-6 discretionary-spend">
                                <div class="value">
                                    £
                                    <input type="number" id="DiscretionarySpend2" value="950" readonly>
                                </div>
                                <label for="DiscretionarySpend">Discretionary Spend</label>
                            </div>
                            <div class="col-md-6 services-charge">
                                <div class="value">
                                    £
                                    <input type="number" id="ServicesCharge2" value="730" readonly>
                                </div>
                                <label for="ServicesCharge">Services Charge</label>
                            </div>
                            <div class="col-md-6 care-cost">
                                <div class="value">
                                    £
                                    <input type="number" id="CareCost2" value="883" readonly>
                                </div>
                                <label for="CareCost">Care Cost</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form -->
            <div class="form-section">
                <h4>Save your calculation</h4>
                <p>Please complete the form, and we will email the result of the budget calculator.</p>
                <?php
                // The Contact Form 7 shortcode you copied
                $contact_form_shortcode = '[contact-form-7 id="75100c5" title="Save your calculation"]';

                // Output the contact form
                echo do_shortcode($contact_form_shortcode);
                ?>
            </div>

        </div> <!-- // Chart section  -->


    </div>





    <?php
    return ob_get_clean();
}




// function custom_post_permalink($permalink, $post, $leavename) {
//     // Ensure this code runs only for the default 'post' post type
//     if ($post->post_type == 'post') {
//         // Get the post's categories
//         $category = get_the_category($post->ID);

//         if ($category) {
//             // Get the first category's slug
//             $category_slug = $category[0]->slug;
//             // Construct the new permalink structure
//             $permalink = home_url('/' . $category_slug . '/' . $post->post_name . '/');

//         } else {
//             // If no category, use 'uncategorized'
//             $permalink = home_url('/uncategorized/' . $post->post_name . '/');
//         }
//     }
//     return $permalink;
// }
// add_filter('post_link', 'custom_post_permalink', 10, 3);

// function custom_post_rewrite_rules($rules) {
//     $new_rules = array(
//         '([^/]+)/([^/]+)/?$' => 'index.php?category_name=$matches[1]&name=$matches[2]',
//     );
//     return $new_rules + $rules;
// }
// add_filter('rewrite_rules_array', 'custom_post_rewrite_rules');
// functions.php or a custom plugin
add_action('wpcf7_before_send_mail', 'custom_add_hidden_fields_to_email');

function custom_add_hidden_fields_to_email($contact_form) {
    $submission = WPCF7_Submission::get_instance();

    if ($submission) {
        $posted_data = $submission->get_posted_data();
        $email_body = '';
        if (isset($posted_data['hidden_dynamic_values_new'])) {
            $dynamic_values_new = json_decode($posted_data['hidden_dynamic_values_new'], true);

            $email_body .= "\n\n\n" . $dynamic_values_new['title'] . "\n\n\n";
            $email_body .= "<b>Total Value:</b>" . $dynamic_values_new['totalValue'] . "\n\n";
            $email_body .= "<b>Details</b>:\n\n\n";

            foreach ($dynamic_values_new['details'] as $label_new => $value_new) {
                $email_body .= $label_new . ': £' . $value_new . "\n";
            }
        }

        // Adding chart images to email body
        if (isset($posted_data['hidden_chart_image1'])) {
            $chart_image1 = $posted_data['hidden_chart_image1'];
            $email_body .= "\n<b>Chart 1</b>:\n<img src=\"$chart_image1\" alt=\"Chart 1\">\n";
        }

        if (isset($posted_data['hidden_dynamic_values'])) {
            $dynamic_values = json_decode($posted_data['hidden_dynamic_values'], true);

            $email_body .= $dynamic_values['title'] . "\n\n\n";
            $email_body .= "<b>Total Value:</b>" . $dynamic_values['totalValue'] . "\n\n";
            $email_body .= "<b>Details</b>:\n\n\n";

            foreach ($dynamic_values['details'] as $label => $value) {
                $email_body .= $label . ': £' . $value . "\n";
            }
        }

        if (isset($posted_data['hidden_chart_image2'])) {
            $chart_image2 = $posted_data['hidden_chart_image2'];
            $email_body .= "\n<b>Chart 2</b>:\n<img src=\"$chart_image2\" alt=\"Chart 2\">\n";
        }

        if (!empty($email_body)) {
            $mail = $contact_form->prop('mail');
            $mail['body'] .= "\n\n" . $email_body;

            $contact_form->set_properties(array(
                'mail' => $mail
            ));
        }
    }
}




// add_action('wp_ajax_save_chart_image', 'save_chart_image');
// add_action('wp_ajax_nopriv_save_chart_image', 'save_chart_image');

// function save_chart_image() {
//     if (isset($_POST['image'])) {
//         $image_data = $_POST['image'];

//         // Remove the base64 encoding part of the image
//         $image_data = str_replace('data:image/png;base64,', '', $image_data);
//         $image_data = str_replace(' ', '+', $image_data);

//         $decoded_image = base64_decode($image_data);
//         $upload_dir = get_stylesheet_directory() . '/images';
//         $image_filename = 'chart_image_' . time() . '.png';
//         $image_path = $upload_dir . '/' . $image_filename;

//         // Create the directory if it doesn't exist
//         if (!file_exists($upload_dir)) {
//             mkdir($upload_dir, 0755, true);
//         }

//         if (file_put_contents($image_path, $decoded_image)) {
//             $response = array(
//                 'success' => true,
//                 'filepath' => $image_path,
//                 'relative_filepath' => get_stylesheet_directory_uri() . '/images/' . $image_filename
//             );
//         } else {
//             $response = array(
//                 'success' => false,
//                 'message' => 'Failed to save image',
//             );
//         }

//         echo json_encode($response);
//         wp_die(); // This is required to terminate immediately and return a proper response
//     }
// }

// function populate_hidden_field_with_acf_value($form) {

//         // Get the ACF field value
//         $sherpa_vendorname = get_field('sherpa_vendorname','option'); // Ensure this matches your ACF field name
//         $sherpa_source_category = get_field('sherpa_source_category','option'); // Ensure this matches your ACF field name
//         $sherpa_source_name = get_field('sherpa_source_name','option'); // Ensure this matches your ACF field name

//         // Find the hidden field and set its value
//         $form = str_replace('[hidden vendorName]', '[hidden vendorName "' . esc_attr($sherpa_vendorname) . '"]', $form);
//         $form = str_replace('[hidden sourceCategory]', '[hidden sourceCategory "' . esc_attr($sherpa_source_category) . '"]', $form);
//         $form = str_replace('[hidden sourceName]', '[hidden sourceName "' . esc_attr($sherpa_source_name) . '"]', $form);

//     return $form;
// }
// add_filter('wpcf7_form_elements', 'populate_hidden_field_with_acf_value');


function populate_hidden_fields_with_acf_values() {
    // Check if the ACF plugin is active

    // Get the ACF field values
    $sherpa_vendorname = get_field('sherpa_vendorname','option');
    $sherpa_source_category = get_field('sherpa_source_category','option');
    $sherpa_source_name = get_field('sherpa_source_name','option');

    ?>
    <script type="text/javascript">
        jQuery(document).ready(function($) {
            console.log('loaded');
            // Set the hidden fields with ACF values
            var vendorNameField = $('input[name="vendorName"]');
            if (vendorNameField.length) {
                vendorNameField.val('<?php echo esc_js($sherpa_vendorname); ?>');
            }

            var sourceCategoryField = $('input[name="sourceCategory"]');
            if (sourceCategoryField.length) {
                sourceCategoryField.val('<?php echo esc_js($sherpa_source_category); ?>');
            }

            var sourceNameField = $('input[name="sourceName"]');
            if (sourceNameField.length) {
                sourceNameField.val('<?php echo esc_js($sherpa_source_name); ?>');
            }
        });
    </script>
    <?php
}

add_action('wp_footer', 'populate_hidden_fields_with_acf_values');

function get_category_button_shortcode() {
    // Check if we're on a single post page
    if (is_single()) {
        // Get the post ID
        $post_id = get_the_ID();

        // Get the categories for the current post
        $categories = get_the_category($post_id);

        // Initialize button URL
        $button_url = '';

        // Loop through the categories
        foreach ($categories as $category) {
            if ($category->slug === 'news') {
                $button_url = '/news/';
                $button_text = 'Back to News';
                break;
            } elseif ($category->slug === 'events') {
                $button_url = '/events/';
                $button_text = 'Back to Events';
                break;
            }
        }

        // Default URL if no matching category is found
        if (empty($button_url)) {
            $button_url = '#'; // Or any default URL
        }

        // Generate button HTML
        // $output = '<a href="' . esc_url($button_url) . '" class="category-button">Go to Category</a>';
        $output = ' <a class="elementor-button elementor-button-link elementor-size-sm" href="' . esc_url($button_url) . '">
						<span class="elementor-button-content-wrapper">
						<span class="elementor-button-icon elementor-align-icon-left">
				<svg aria-hidden="true" class="e-font-icon-svg e-fas-chevron-left" viewBox="0 0 320 512" xmlns="http://www.w3.org/2000/svg"><path d="M34.52 239.03L228.87 44.69c9.37-9.37 24.57-9.37 33.94 0l22.67 22.67c9.36 9.36 9.37 24.52.04 33.9L131.49 256l154.02 154.75c9.34 9.38 9.32 24.54-.04 33.9l-22.67 22.67c-9.37 9.37-24.57 9.37-33.94 0L34.52 272.97c-9.37-9.37-9.37-24.57 0-33.94z"></path></svg>			</span>
						<span class="elementor-button-text">'.$button_text.'</span>
		</span>
					</a>';


        return $output;
    }

    return 'This shortcode only works on single post pages.';
}

// Register the shortcode with WordPress
add_shortcode('category_button', 'get_category_button_shortcode');

/*Exclude the Feature event post */
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


// add_action('wpcf7_before_send_mail', 'custom_change_api_url_based_on_post_id', 10, 1);

// function custom_change_api_url_based_on_post_id($contact_form) {
//     // Get the post ID
//     $post_id = get_the_ID();

//     // Check if the form is the specific one you want to target
//     $form_id = $contact_form->id();

//     if ($form_id == '10765') { // Replace YOUR_FORM_ID with your actual form ID
//         if ($post_id) {
//             // Set default company ID and community ID
//             $company_id = 27;  // Default company ID
//             $community_id = 2;  // Default community ID

//             // Optionally change company_id and community_id based on post ID
//             // You can replace this with logic to fetch the company and community IDs based on post_id
//             if ($post_id == '651') {  // Replace 123 with your post ID
//                 $company_id = 27; // Change company ID for specific post
//                 $community_id = 2; // Change community ID for specific post
//             }

//             // Modify the API URL dynamically based on company and community ID
//             $new_api_url = 'https://members.sherpacrm.co.uk/v1/companies/' . $company_id . '/communities/' . $community_id . '/leads';

//             // Update the API URL (assuming Any API plugin uses option to store URL)
//             update_option('cf7anyapi_base_url' . $form_id, $new_api_url);
//         }
//     }
// }




// function custom_cf7_update_post_meta( $contact_form ) {
//     $submission = WPCF7_Submission::get_instance();
//     if ( $submission ) {
//         $data = $submission->get_posted_data();

//         // Assuming 'post_id' is passed in the form data or obtained from context

//             $post_id = intval( $data['post_id'] ); // Get the post ID

//             // Construct the new API URL based on the post ID
//             $new_url = 'https://members.sherpacrm.co.uk/v1/companies/27/communities/2/leads';


//             // Update the post meta with the new URL
//             update_post_meta( 17746, 'cf7anyapi_base_url', $new_url );

//     }
// }

// add_action( 'wpcf7_before_send_mail', 'custom_cf7_update_post_meta' );


add_filter('wpcf7_posted_data', 'add_referral_datetime');

function add_referral_datetime($posted_data) {

    // Always set the current date and time in the hidden field
    $posted_data['current-date'] = current_time('Y-m-d H:i:s'); // Change format as needed
    return $posted_data;
}

function add_hidden_page_id_script() {
    ?>
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            var pageId = <?php echo get_the_ID(); ?>;
            var hiddenField = document.querySelector('input[name="page-id"]');
            if (hiddenField) {
                hiddenField.value = pageId;
            }
        });
    </script>
    <?php
}
add_action('wp_footer', 'add_hidden_page_id_script');


function custom_cf7_update_post_meta( $contact_form ) {
    $submission = WPCF7_Submission::get_instance();
    if ( $submission ) {
        $data = $submission->get_posted_data();
        $form_id = $contact_form->id();

        // Assuming 'post_id' is passed in the form data
        $post_id = intval( $data['post_id'] ); // Get the post ID

        // Get the selected value from the dropdown
        $selected_page_ID = $data['page-id'];
        $selected_value_array  = $data['your-field-name']; // Replace 'your-field-name' with your actual dropdown name

        // Ensure $selected_value exists
        $selected_value = isset($selected_value_array[0]) ? $selected_value_array[0] : '';
        if ($form_id == 22416) {
            $selected_value = trim($data['venue-title'] ?? '');
        }

        if($form_id == 16015){
            $selected_value = trim($data['propertiesvillage'] ?? '');
        }

        // Initialize the new URL variable
        $new_url = '';

        // Switch case for the first condition (selected value from dropdown)
        if ( !empty($selected_value) ) {
            switch ( $selected_value ) {
                case 'Homewood Grove':
                    $new_url = 'https://members.sherpacrm.co.uk/v1/companies/27/communities/4/leads';
                    break;
                case 'Mickle Hill':
                    $new_url = 'https://members.sherpacrm.co.uk/v1/companies/27/communities/2/leads';
                    break;
                case 'Siddington Park':
                    $new_url = 'https://members.sherpacrm.co.uk/v1/companies/27/communities/3/leads';
                    break;
                case 'Strawberry Fields':
                    $new_url = 'https://members.sherpacrm.co.uk/v1/companies/27/communities/5/leads';
                    break;
                case 'Wadswick Green':
                    $new_url = 'https://members.sherpacrm.co.uk/v1/companies/27/communities/1/leads';
                    break;
                case 'Bramston Park, Hampshire':
                    $new_url = 'https://members.sherpacrm.co.uk/v1/companies/27/communities/6/leads';
                    break;
                case 'East Grinstead, West Sussex':
                    $new_url = 'https://members.sherpacrm.co.uk/v1/companies/27/communities/6/leads';
                    break;
                case 'Elstree, Hertfordshire':
                    $new_url = 'https://members.sherpacrm.co.uk/v1/companies/27/communities/6/leads';
                    break;

                // Add more cases for additional options if necessary
                default:
                    // Handle any default case or set a fallback URL
                    $new_url = 'https://members.sherpacrm.co.uk/v1/companies/27/communities/4/leads'; // Optional default URL
                    break;
            }
        }




        // Additional switch case for the second condition (selected page ID)
        if ( !empty($selected_page_ID) ) {
            switch ( $selected_page_ID ) {
                case '12974':
                    $new_url = 'https://members.sherpacrm.co.uk/v1/companies/27/communities/2/leads';
                    break;
                case '12910':
                    $new_url = 'https://members.sherpacrm.co.uk/v1/companies/27/communities/4/leads';
                    break;
                case '13004':
                    $new_url = 'https://members.sherpacrm.co.uk/v1/companies/27/communities/3/leads';
                    break;
                case '12994':
                    $new_url = 'https://members.sherpacrm.co.uk/v1/companies/27/communities/1/leads';
                    break;
                // Add more cases for additional options if necessary
                default:
                    // Handle any default case or set a fallback URL
                    $new_url = 'https://members.sherpacrm.co.uk/v1/companies/27/communities/4/leads'; // Optional default URL
                    break;
            }
        }


        $myfile = fopen(get_template_directory()."/_url.log", "w") or die("Unable to open file!");
        fwrite($myfile, "<pre>".print_r($new_url, true));
        fclose($myfile);

        // Update the meta based on the form ID
        if ($form_id == 10765 || $form_id == 32660) {
            update_post_meta( 17746, 'cf7anyapi_base_url', $new_url );
        } elseif ($form_id == 16015) {
            update_post_meta( 27992, 'cf7anyapi_base_url', $new_url );
        } elseif($form_id == 2564){
            update_post_meta( 17932, 'cf7anyapi_base_url', $new_url );
        } elseif($form_id == 637){
            update_post_meta( 17934, 'cf7anyapi_base_url', $new_url );
        } elseif($form_id == 7807){
            update_post_meta( 17935, 'cf7anyapi_base_url', $new_url );
        } elseif($form_id == 12311){
            update_post_meta( 17937, 'cf7anyapi_base_url', $new_url );
        } elseif($form_id == 22416){
            update_post_meta( 28731, 'cf7anyapi_base_url', $new_url );
        }
    }
}

add_action( 'wpcf7_before_send_mail', 'custom_cf7_update_post_meta' );



function custom_google_map_repeater_shortcodes($atts) {
    // Parse shortcode attributes
    $atts = shortcode_atts(array(
        'page_id' => get_the_ID(), // Default to the current page ID
    ), $atts);

    $page_id = $atts['page_id'];

    // Get the repeater field data
    $locations = get_field('location_info', $page_id); // Replace with your ACF repeater field name

    if (!$locations) {
        return;
    }

    // Prepare map data as JSON
    $map_data = array();
    $categories = array(); // To store unique categories
    $main_location = null; // Variable to store main location

    foreach ($locations as $index => $location) {
        // Store the first location as the main location
        if (!empty($location['loc_latitude']) && !empty($location['loc_longitude']) && !empty($location['loc_label'])) {
            if ($index === 0 && $main_location === null) {
                $main_location = array(
                    'lat' => floatval($location['loc_latitude']),
                    'lng' => floatval($location['loc_longitude']),
                    'category' => sanitize_text_field($location['loc_category']),
                    'label' => sanitize_text_field($location['loc_label'])
                );
            }

            $map_data[] = array(
                'lat' => floatval($location['loc_latitude']),
                'lng' => floatval($location['loc_longitude']),
                'category' => sanitize_text_field($location['loc_category']),
                'label' => sanitize_text_field($location['loc_label'])
            );

            // Collect unique categories
            $categories[] = sanitize_text_field($location['loc_category']);
        }
    }

    // Remove duplicate categories
    $unique_categories = array_unique($categories);

    // Encode data for JavaScript
    $map_data_json = wp_json_encode($map_data);
    $main_location_json = wp_json_encode($main_location);

    // Generate filter buttons with "All" button at the beginning
    $filter_buttons = '<div class="map-block e-con e-flex"><div class="e-con-inner"><div class="custom-map-block"><div class="map-filters-block"><div id="map-filters">';
    $filter_buttons .= '<button class="filter-button" data-category="all">All</button>'; // All button
    foreach ($unique_categories as $category) {
        if($category != "Select Category"){
            $filter_buttons .= '<button class="filter-button" data-category="' . esc_attr($category) . '">' . esc_html($category) . '</button>';
        }
    }
    $filter_buttons .= '</div></div>';

    return $filter_buttons . '<div class="custom-map-wrap"><div id="custom-google-map" style="width: 100%; height: 500px;"></div>
    
    </div></div></div></div>
    
    <script>
        var mapData = ' . $map_data_json . ';
        var mainLocation = ' . $main_location_json . ';
    </script>';
}
add_shortcode('custom_google_map', 'custom_google_map_repeater_shortcode');

function custom_google_map_repeater_shortcode($atts) {
    // Parse shortcode attributes
    $atts = shortcode_atts(array(
        'page_id' => get_the_ID(), // Default to the current page ID
        'type'    => '' // Default type is empty
    ), $atts);

    $page_id = $atts['page_id'];
    $map_data = array();
    $categories = array();
    $main_location = null;

    // Fetch the default location repeater for the given page
    $locations = get_field('location_info', $page_id);

    // If type is "property", fetch village-related locations
    if ($atts['type'] === 'property') {
        $village_name = get_field('location', $page_id); // Adjust field name accordingly

        if ($village_name) {
            // Query to get the village post ID using WP_Query
            $village_query = new WP_Query(array(
                'post_type'      => 'villages', // Adjust if needed
                'title'          => $village_name,
                'posts_per_page' => 1,
                'fields'         => 'ids' // Get only IDs for performance
            ));

            if ($village_query->have_posts()) {
                $village_post_id = $village_query->posts[0]; // Get the first matching village post ID

                // Fetch village location repeater field
                $village_locations = get_field('location_info', $village_post_id);

                // Remove the first location from village locations if it has no category assigned
                if (!empty($village_locations) && $village_locations[0]['loc_category'] == "Select Category") {
                    array_shift($village_locations);
                }
                // Merge village locations into main locations array
                $locations = array_merge($locations ?: [], $village_locations);

            }
            wp_reset_postdata(); // Reset query
        }
    }
    if (empty($locations)) {
        return;
    }
    // Process locations (whether they are property-based, village-based, or default)
    if (!empty($locations)) {
        foreach ($locations as $index => $location) {
            if (!empty($location['loc_latitude']) && !empty($location['loc_longitude']) && !empty($location['loc_label'])) {
                $category_value = $location['loc_category']['value'] ?? ''; // Use 'value'
                $category_label = $location['loc_category']['label'] ?? ''; // Use 'label'
                // Store the first valid location as the main location
                if ($index === 0 && $main_location === null) {
                    $main_location = array(
                        'lat' => floatval($location['loc_latitude']),
                        'lng' => floatval($location['loc_longitude']),
                        'category' => sanitize_text_field($category_value),
                        'label' => sanitize_text_field($location['loc_label'])
                    );
                }

                // Store all valid locations
                $map_data[] = array(
                    'lat' => floatval($location['loc_latitude']),
                    'lng' => floatval($location['loc_longitude']),
                    'category' => sanitize_text_field($category_value),
                    'label' => sanitize_text_field($location['loc_label'])
                );

                // Store category with both value and label
                if (!empty($category_value) && !empty($category_label)) {
                    $categories[$category_value] = sanitize_text_field($category_label);
                }
                // Collect unique categories
                // $categories[] = sanitize_text_field($location['loc_category']);
            }
        }
    }

    // Remove duplicate categories
    // $unique_categories = array_unique($categories);
    $unique_categories = array_unique(array_keys($categories));

    // Encode data for JavaScript
    $map_data_json = wp_json_encode($map_data);
    $main_location_json = wp_json_encode($main_location);

    // Generate filter buttons with "All" button at the beginning
    $filter_buttons = '<div class="map-block e-con e-flex"><div class="e-con-inner"><div class="custom-map-block"><div class="map-filters-block"><div id="map-filters">';
    $filter_buttons .= '<button class="filter-button" data-category="all">All</button>'; // "All" button
    foreach ($unique_categories as $category_value) {
        if ($category_value != "Select Category") {
            $filter_buttons .= '<button class="filter-button" data-category="' . esc_attr($category_value) . '">' . esc_html($categories[$category_value]) . '</button>';
        }
    }
    $filter_buttons .= '</div></div>';

    return $filter_buttons . '<div class="custom-map-wrap"><div id="custom-google-map" style="width: 100%; height: 500px;"></div></div></div></div></div>
    
    <script>
        var mapData = ' . $map_data_json . ';
        var mainLocation = ' . $main_location_json . ';
    </script>';
}

function get_property_main_location_url($atts) {
    // Parse shortcode attributes
    $atts = shortcode_atts(array(
        'page_id'     => get_the_ID(), // Default to current page ID
    ), $atts);

    $page_id = $atts['page_id'];

    // Get the location repeater field
    $locations = get_field('location_info', $page_id);

    if (!empty($locations) && isset($locations[0])) {
        $first_location = $locations[0]; // Get only the first location

        // If first location has no category, return Google Maps URL
        if (empty($first_location['loc_category']) && !empty($first_location['loc_latitude']) && !empty($first_location['loc_longitude'])) {
            $lat = floatval($first_location['loc_latitude']);
            $lng = floatval($first_location['loc_longitude']);

            return "https://www.google.com/maps?q={$lat},{$lng}";
        }
    }
    if(get_field('location', $page_id)){
        $village_name = get_field('location', $page_id).", Rangeford Village";
    }
    else{
        $village_name = "Rangeford Villages";
    }
    // If no valid location, return fallback Google Maps search URL
    $fallback_query = $village_name;
    return "https://www.google.com/maps/search/?q={$fallback_query}";
}

add_shortcode('property_main_location_url', 'get_property_main_location_url');


// add_action(
//     'tribe_template_after_include:events/v2/components/events-bar',
//     function() {
//         $terms = get_terms( [ 'taxonomy' => Tribe__Events__Main::TAXONOMY ] );

//         if ( empty( $terms ) || is_wp_error( $terms ) ) {
//             return;
//         }

//         echo '<div class="the-events-calendar-category-list"><ul>';

//         foreach ( $terms as $single_term ) {
//             $url  = esc_url( get_term_link( $single_term ) );
//             $name = esc_html( get_term_field( 'name', $single_term ) );

//             echo "<li><a href='$url'>$name</a> </li>";
//         }

//         echo '</ul></div>';
//     }
// );
// add_action(
//     'tribe_template_after_include:events/v2/components/events-bar',
//     function() {
//         $terms = get_terms( [ 'taxonomy' => Tribe__Events__Main::TAXONOMY ] );

//         if ( empty( $terms ) || is_wp_error( $terms ) ) {
//             return;
//         }

//         // Get the current view from the URL
//         $current_view = tribe_is_month() ? 'month' : 'list';

//         echo '<div class="the-events-calendar-category-list"><ul>';

//         foreach ( $terms as $single_term ) {
//             $term_slug = $single_term->slug;
//             $term_name = esc_html( get_term_field( 'name', $single_term ) );

//             // Get the base URL for the category
//             $base_url = esc_url( get_term_link( $single_term ) );

//             // Check if we're on month view or list view
//             if ( $current_view === 'month' ) {
//                 // For month view, append /month/ (add year-month if necessary)
//                 $year_month = date('Y-m'); // Current year-month
//                 $category_url = trailingslashit( home_url( "/events-new/category/{$term_slug}/{$year_month}/" ) );
//             } else {
//                 // For list view, append /list/
//                 $category_url = trailingslashit( home_url( "/events-new/category/{$term_slug}/list/" ) );
//             }

//             echo "<li><a href='$category_url'>$term_name</a></li>";
//         }

//         echo '</ul></div>';
//     }
// );


// add_action(
//     'tribe_template_after_include:events/v2/components/events-bar',
//     function() {
//         $terms = get_terms( [ 'taxonomy' => Tribe__Events__Main::TAXONOMY ] );

//         if ( empty( $terms ) || is_wp_error( $terms ) ) {
//             return;
//         }

//         // Get the current view from the URL
//         $current_view = tribe_is_month() ? 'month' : 'list';

//         echo '<div class="the-events-calendar-category-list"><ul>';

//         foreach ( $terms as $single_term ) {
//             $term_slug = $single_term->slug;
//             $term_name = esc_html( get_term_field( 'name', $single_term ) );

//             // Get the base URL for the category
//             $base_url = esc_url( get_term_link( $single_term ) );

//             // Check if we're on month view or list view
//             if ( $current_view === 'month' ) {
//                 // For month view, append /month/ (add year-month if necessary)
//                 $year_month = date('Y-m'); // Current year-month
//                 $category_url = trailingslashit( home_url( "/events-new/category/{$term_slug}/{$year_month}/" ) );
//             } else {
//                 // For list view, append /list/
//                 $category_url = trailingslashit( home_url( "/events-new/category/{$term_slug}/list/" ) );
//             }

//             echo "<li><a href='$category_url'>$term_name</a></li>";
//         }

//         echo '</ul></div>';
//     }
// );


/*
add_action(
    'tribe_template_after_include:events/v2/components/events-bar',
    function() {
        $terms = get_terms( [ 'taxonomy' => Tribe__Events__Main::TAXONOMY ] );
        if ( empty( $terms ) || is_wp_error( $terms ) ) {
            return;
        }
        // Get current query parameters
        $current_query_args = $_GET; // Capture current query parameters (category, day, month, year, etc.)
        // Extract the currently selected category if present
        $selected_category = isset( $current_query_args['tribe_events_cat'] ) ? esc_attr( $current_query_args['tribe_events_cat'] ) : '';
        echo '<div class="the-events-calendar-category-list"><div class="tribe-events-category-list-btn"><span>Add Filter</span><span><svg width="16" height="19" viewBox="0 0 16 19" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M15 3.90909L10.5278 7.94163C10.2536 8.2212 10.1 8.60261 10.1 9V17L6.6 14.8182V9C6.59996 8.80322 6.56216 8.60837 6.48876 8.4266C6.41536 8.24482 6.3078 8.07969 6.17222 7.94063L1 2.45455V1H13.6857" stroke="#C2BB86" stroke-width="1.41" stroke-linecap="square"/></svg></span></div><ul>';
        foreach ( $terms as $single_term ) {
            $url = esc_url( get_term_link( $single_term ) );
            // Add current query arguments to the category links
            if ( ! empty( $current_query_args ) ) {
                $url = add_query_arg( $current_query_args, $url );
            }
            // Highlight the selected category
            $class = ( $selected_category === $single_term->slug ) ? ' class="selected-category"' : '';
            $name = esc_html( get_term_field( 'name', $single_term ) );
            echo "<li><a href='$url' $class>$name</a></li>";
        }
        echo '</ul></div>';
    }
);
*/

// Add a hook to modify day, month, and year filter links to preserve the selected category
add_filter( 'tribe_events_bar_month_selector', function( $html ) {
    if ( isset( $_GET['tribe_events_cat'] ) ) {
        $category = esc_attr( $_GET['tribe_events_cat'] );
        $html = preg_replace( '/<option value="([^"]+)"/', '<option value="$1&tribe_events_cat=' . $category . '"', $html );
    }
    return $html;
} );
add_filter( 'tribe_events_bar_day_selector', function( $html ) {
    if ( isset( $_GET['tribe_events_cat'] ) ) {
        $category = esc_attr( $_GET['tribe_events_cat'] );
        $html = preg_replace( '/<option value="([^"]+)"/', '<option value="$1&tribe_events_cat=' . $category . '"', $html );
    }
    return $html;
} );
add_filter( 'tribe_events_bar_year_selector', function( $html ) {
    if ( isset( $_GET['tribe_events_cat'] ) ) {
        $category = esc_attr( $_GET['tribe_events_cat'] );
        $html = preg_replace( '/<option value="([^"]+)"/', '<option value="$1&tribe_events_cat=' . $category . '"', $html );
    }
    return $html;
} );



// Shortcode to display related events based on the Village slug using Event Categories
function display_events_by_village_category() {
    // Get the current Village slug from the URL
    $village_slug = get_post_field('post_name', get_queried_object_id());

    // Query events that belong to the Event Category matching the Village slug
    $related_events = tribe_get_events([
        'posts_per_page' => 5, // Number of events to display
        'tax_query' => [
            [
                'taxonomy' => 'tribe_events_cat', // The Events Calendar's default taxonomy
                'field'    => 'slug',
                'terms'    => $village_slug, // Match Event Category to Village slug
            ],
        ],
    ]);

    // Check if any events exist
    if (empty($related_events)) {
        return;
    }

    // Build the HTML output
    $output = '<div class="village-events e-flex e-con-boxed e-con"><div class="e-con-innner">';
    $output .= '<h3>Upcoming Events</h3>';

    // Start the loop for related events
    foreach ($related_events as $event) {
        global $post; // Declare global $post variable
        $post = $event; // Set the current event as the global post
        setup_postdata($post); // Prepare post data for Elementor and WordPress functions

        // Render Elementor template dynamically
        $output .= do_shortcode('[elementor-template id="19627"]');

        wp_reset_postdata(); // Reset the global post object to avoid conflicts
    }

    $output .= '</div></div>';

    return $output;
}

// Register the shortcode
add_shortcode('events_by_village_category', 'display_events_by_village_category');

add_action( 'tribe_tickets_ticket_email_styles', function () {
    ?>
    <style>
        /* Example: Customize header image styles */
        td.tec-tickets__email-table-main-header {  padding: 15px 5px 10px 5px;}

        /* Add more styles as needed */
    </style>
    <?php
} );


function the_events_calendar_category_list_shortcode() {
    // Fetch event categories
    $terms = get_terms( [ 'taxonomy' => Tribe__Events__Main::TAXONOMY ] );

    if ( empty( $terms ) || is_wp_error( $terms ) ) {
        return ''; // Return nothing if no categories found
    }
    // Get current URL to check for active category
    $current_url = $_SERVER['REQUEST_URI']; // Current page URL
    // Get current query parameters 
    $current_query_args = $_GET;
    $selected_category = isset( $current_query_args['tribe_events_cat'] ) ? esc_attr( $current_query_args['tribe_events_cat'] ) : '';

    // Detect active view: Month, List, or Day
    $is_month_view = isset( $current_query_args['tribe-bar-view'] ) && $current_query_args['tribe-bar-view'] === 'month';
    $is_day_view = isset( $current_query_args['tribe-bar-date'] );

    // Get the selected day (default to today if missing)
    $selected_day = $is_day_view ? esc_attr( $current_query_args['tribe-bar-date'] ) : '';

    ob_start(); // Start output buffering
    ?>

    <div class="tribe-events-category-list">
        <button class="tribe-events-category-list-btn" aria-expanded="false" aria-controls="category-list">
            <span>Add Filter</span>
            <span>
                <svg width="16" height="19" viewBox="0 0 16 19" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M15 3.90909L10.5278 7.94163C10.2536 8.2212 10.1 8.60261 10.1 9V17L6.6 14.8182V9C6.59996 8.80322 6.56216 8.60837 6.48876 8.4266C6.41536 8.24482 6.3078 8.07969 6.17222 7.94063L1 2.45455V1H13.6857" stroke="#C2BB86" stroke-width="1.41" stroke-linecap="square"/>
                </svg>
            </span>
        </button>


        <ul id="category-list" role="menu">
            <?php foreach ( $terms as $single_term ) :
                // Get base category URL
                $category_link = get_term_link( $single_term );

                // Ensure correct URL structure
                if ( strpos( $category_link, '/category/' ) !== false ) {
                    if ( $is_month_view ) {
                        $category_link = trailingslashit( $category_link ) . 'month/';
                    } elseif ( $is_day_view ) {
                        // Ensure "today" or specific day format
                        $day_segment = ( $selected_day === date('Y-m-d') ) ? 'today' : $selected_day;
                        $category_link = trailingslashit( $category_link ) . 'day/' . $day_segment . '/';
                    } else {
                        // Ensure List View stays clean
                        $category_link = trailingslashit( str_replace( [ '/month/', '/day/' ], '', $category_link ) );
                    }
                }

                // Ensure no unwanted query parameters
                $category_link = remove_query_arg( [ 'tribe-bar-view', 'tribe-bar-date' ], $category_link );

                // Highlight selected category
                $class = ( $selected_category === $single_term->slug ) ? ' class="selected-category"' : '';
                ?>
                <li role="menuitem">
                    <a href="<?php echo esc_url( $category_link ); ?>" <?php echo $class; ?> tabindex="0">
                        <?php echo esc_html( $single_term->name ); ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
        <!-- Clear button (hidden by default) -->
        <div class="tribe-events-category-clear-btn" style="display: <?php echo (strpos($current_url, '/category/') !== false) ? 'inline-block' : 'none'; ?>;">
            <button id="clearCategoryFilter">Clear Filter</button>
        </div>
    </div>

    <?php
    return ob_get_clean(); // Return the buffered output
}
add_shortcode( 'event_categories', 'the_events_calendar_category_list_shortcode' );


/** GT */

// Village map - powered by settings in Villages Settings
require_once('library/shortcode-village-map.php');

add_action('tribe_events_after_template', function() {
    echo '<div class="custom-events-footer" style="padding: 20px; background: #eee; text-align: center;display:none;">
            <h3>Join Us for More Events!</h3>
            <p>Follow us on social media for updates.</p>
          </div>';
});

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

function mm_village_panel($acf_field_name, $html_callback) {
    $image = get_field($acf_field_name, 'option');

    if (is_array($image)) {
        $image = $image['url'];
    }

    $bg = $image ? esc_url($image) : '';

    ob_start();
    echo '<div class="dropdown-inner" style="background-image: url(' . $bg . ');">';
    echo call_user_func($html_callback);
    echo '</div>';

    return ob_get_clean();
}

/*** OUR VILLIAGES MENUS ***/

/**
 * HOMEWOOD GROVE
 */
add_shortcode('mm_homewood_grove', function() {
    return mm_village_panel('homewood_grove_bg', function() {

        // PUT *YOUR* HTML FOR THIS VILLAGE HERE:
        return '
            <div class="location">
                <img src="/wp-content/uploads/2024/09/blue-hg-small.svg" alt="">
                Chertsey, Surrey
            </div>
            <h3>Homewood<br>Grove</h3>
            <div class="button-row">
                <a class="elementor-button elementor-button-link elementor-size-sm" href="/villages/homewood-grove/"><span class="elementor-button-content-wrapper"><span class="elementor-button-text">Explore village </span></span></a>
                <a href="#" data-term-redirechomewood="https://rangefordvillages.co.uk/contact-us?village=Homewood+Grove&type=request-brochure" data-term-url="https://online.flipbuilder.com/Rangeford_Villages/extr/" data-term-id="643" target="_blank" class="download-bro single-brochure-view-button elementor-button elementor-button-link elementor-size-sm homewood"><span class="elementor-button-content-wrapper"><span class="elementor-button-text">Download the brochure</span></span></a>
            </div>
        ';

    });
});

/**
 * MICKLE HILL
 */
add_shortcode('mm_mickle_hill', function() {
    return mm_village_panel('mickle_hill_bg', function() {

        // PUT YOUR HTML FOR MICKLE HILL HERE:
        return '
            <div class="location">
                <img src="/wp-content/uploads/2024/09/blue-m-small.svg" alt="">
                Mickle Hill, Pickering
            </div>
            <h3>Mickle<br>Hill</h3>
            <div class="button-row">
                <a class="elementor-button elementor-button-link elementor-size-sm" href="/villages/mickle-hill/"><span class="elementor-button-content-wrapper"><span class="elementor-button-text">Explore village </span></span></a>
                <a href="#" data-term-redirectmickle="https://rangefordvillages.co.uk/contact-us?village=Mickle+Hill&type=request-brochure" data-term-url="https://online.flipbuilder.com/Rangeford_Villages/sgzx/" target="_blank" data-term-id="651" class="download-bro single-brochure-view-button elementor-button elementor-button-link elementor-size-sm micklehill"><span class="elementor-button-content-wrapper"><span class="elementor-button-text">Download the brochure</span></span></a>
            </div>
        ';

    });
});

/**
 * SIDDINGTON PARK
 */
add_shortcode('mm_siddington_park', function() {
    return mm_village_panel('siddington_park_bg', function() {

        // PUT YOUR HTML FOR SIDDINGTON PARK HERE:
        return '
            <div class="location">
                <img src="/wp-content/uploads/2024/09/blue-sp-small.svg" alt="">
                Cirencester, Gloucestershire
            </div>
            <h3>Siddington <br>Park</h3>
            <div class="button-row">
                <a class="elementor-button elementor-button-link elementor-size-sm" href="https://rangefordvillages.co.uk/villages/siddington-park/"><span class="elementor-button-content-wrapper"><span class="elementor-button-text">Explore village</span></span></a>
                <a href="#" data-term-redirectsiddin="https://rangefordvillages.co.uk/contact-us?village=Siddington+Park&type=request-brochure" data-term-url="https://online.flipbuilder.com/Rangeford_Villages/rsyg/" target="_blank" data-term-id="645" class="download-bro single-brochure-view-button elementor-button elementor-button-link elementor-size-sm siddingtonpark"><span class="elementor-button-content-wrapper"><span class="elementor-button-text">Download the brochure</span></span></a>
            </div>
        ';

    });
});

/**
 * STRAWBERRY FIELDS
 */
add_shortcode('mm_strawberry_fields', function() {
    return mm_village_panel('strawberry_fields_bg', function() {

        // PUT YOUR HTML FOR STRAWBERRY FIELDS HERE:
        return '
            <div class="location">
                <img src="/wp-content/uploads/2024/09/blue-sf-small.svg" alt="">
                Stapleford, Cambridgeshire
            </div>
            <h3>Strawberry<br>fields</h3>
            <div class="button-row">
                <a class="elementor-button elementor-button-link elementor-size-sm" href="/villages/strawberry-fields/"><span class="elementor-button-content-wrapper"><span class="elementor-button-text">Explore village </span></span></a>
                <a href="#" data-term-strawberry ="https://rangefordvillages.co.uk/contact-us?village=Strawberry+Fields&type=request-brochure" data-term-url="https://online.flipbuilder.com/Rangeford_Villages/ruwq/" target="_blank" data-term-id="647" class="download-bro single-brochure-view-button elementor-button elementor-button-link elementor-size-sm strawberryfields"><span class="elementor-button-content-wrapper"><span class="elementor-button-text">Download the brochure</span></span></a>
            </div>
        ';

    });
});

/**
 * WADSWICK GREEN
 */
add_shortcode('mm_wadswick_green', function() {
    return mm_village_panel('wadswick_green_bg', function() {

        // PUT YOUR HTML FOR WADSWICK GREEN HERE:
        return '
            <div class="location">
                <img src="/wp-content/uploads/2024/09/blue-R-small.svg" alt="">
                Corsham, Wiltshire
            </div>
            <h3>Wadswick <br>green</h3>
            <div class="button-row">
                <a class="elementor-button elementor-button-link elementor-size-sm" href="/villages/wadswick-green/"><span class="elementor-button-content-wrapper"><span class="elementor-button-text">Explore village</span></span></a>
                <a href="#" data-term-id="649" data-term-redirectgreen="https://rangefordvillages.co.uk/contact-us?village=Wadswick+Green&type=request-brochure" data-term-url="https://online.flipbuilder.com/Rangeford_Villages/dhfy/" target="_blank" class="download-bro single-brochure-view-button elementor-button elementor-button-link elementor-size-sm wadswickgreen"><span class="elementor-button-content-wrapper"><span class="elementor-button-text">Download the brochure</span></span></a>
            </div>
        ';

    });
});

/**
 * FUTURE VILLAGES
 */
add_shortcode('mm_future_villages', function() {
    return mm_village_panel('future_villages_bg', function() {

        // PUT YOUR HTML FOR FUTURE VILLAGES HERE:
        return '
            <h3>Future<br>Villages</h3>
            <div class="button-row">
                <a class="elementor-button elementor-button-link elementor-size-sm" href="https://rangefordvillages.co.uk/villages/elstree-hertfordshire/"><span class="elementor-button-content-wrapper"><span class="elementor-button-text">Explore village </span></span></a>
            </div>
        ';

    });
});


/*** RANGEFORD LIFE MENUS ***/

/**
 * LIFESTYLE
 */
add_shortcode('mm_lifestyle', function() {
    return mm_village_panel('lifestyle_bg', function() {

        // PUT YOUR HTML FOR LIFESTYLE HERE:
        return '
            <h3>Lifestyle</h3>

            <div class="button-row">
                <a class="elementor-button elementor-button-link elementor-size-sm"
                href="/rangeford-life/lifestyle/">
                <span class="elementor-button-content-wrapper">
                    <span class="elementor-button-text">
                        Learn more </span>
                    </span>
                </a>
            </div>
        ';

    });
});

/**
 * CARE AND SUPPORT
 */
add_shortcode('mm_care_and_support', function() {
    return mm_village_panel('care_and_support_bg', function() {

        // PUT YOUR HTML FOR CARE AND SUPPORT HERE:
        return '
            <h3>Care and<br>Support</h3>

            <div class="button-row">
                <a class="elementor-button elementor-button-link elementor-size-sm"
                href="/rangeford-life/care/">
                <span class="elementor-button-content-wrapper">
                    <span class="elementor-button-text">
                        Learn more </span>
                    </span>
                </a>
            </div>
        ';

    });
});

/**
 * WELLBEING
 */
add_shortcode('mm_wellbeing', function() {
    return mm_village_panel('wellbeing_bg', function() {

        // PUT YOUR HTML FOR WELLBEING HERE:
        return '
            <h3>Wellbeing</h3>

            <div class="button-row">
                <a class="elementor-button elementor-button-link elementor-size-sm"
                href="/rangeford-life/wellbeing/">
                <span class="elementor-button-content-wrapper">
                    <span class="elementor-button-text">
                        Learn more</span>
                    </span>
                </a>
            </div>
        ';

    });
});

/**
 * THE ORANGERY AT SIDDINGTON PARK
 */
add_shortcode('mm_the_orangery_at_siddington_park', function() {
    return mm_village_panel('the_orangery_at_siddington_park_bg', function() {

        // PUT YOUR HTML FOR THE ORANGERY AT SIDDINGTON PARK HERE:
        return '
            <h3>The Orangery at Siddington Park</h3>

            <div class="button-row">
                <a target="_blank" class="elementor-button elementor-button-link elementor-size-sm"
                href="https://www.theorangeryatsiddingtonpark.co.uk/">
                <span class="elementor-button-content-wrapper">
                    <span class="elementor-button-text">
                        Learn more</span>
                    </span>
                </a>
            </div>
        ';

    });
});

/**
 * THE GREENHOUSE AT WADSWICK GREEN
 */
add_shortcode('mm_the_greenhouse_at_wadswick_green', function() {
    return mm_village_panel('the_greenhouse_at_wadswick_green_bg', function() {

        // PUT YOUR HTML FOR THE GREENHOUSE AT WADSWICK GREEN HERE:
        return '
            <h3>The Greenhouse at Wadswick Green</h3>

            <div class="button-row">
                <a target="_blank" class="elementor-button elementor-button-link elementor-size-sm"
                href="https://www.thegreenhouseatwadswickgreen.co.uk/">
                <span class="elementor-button-content-wrapper">
                    <span class="elementor-button-text">
                        Learn more</span>
                    </span>
                </a>
            </div>
        ';

    });
});

/**
 * OAK AND HONEY AT HOMESWOOD GROVE
 */
add_shortcode('mm_oak_and_honey_at_homeswood_grove', function() {
    return mm_village_panel('oak_and_honey_at_homeswood_grove_bg', function() {

        // PUT YOUR HTML FOR OAK AND HONEY AT HOMESWOOD GROVE HERE:
        return '
            <h3>Oak and Honey at Homewood Grove</h3>

            <div class="button-row">
                <a target="_blank" class="elementor-button elementor-button-link elementor-size-sm"
                href="https://www.oakandhoneyathomewoodgrove.co.uk/">
                <span class="elementor-button-content-wrapper">
                    <span class="elementor-button-text">
                        Learn more</span>
                    </span>
                </a>
            </div>
        ';

    });
});

/**
 * WILD THYME AT STRAWBERRY FIELDS
 */
add_shortcode('mm_wild_thyme_at_strawberry_fields', function() {
    return mm_village_panel('wild_thyme_at_strawberry_fields_bg', function() {

        // PUT YOUR HTML FOR WILD THYME AT STRAWBERRY FIELDS HERE:
        return '
            <h3>Wild Thyme at Strawberry Fields</h3>

            <div class="button-row">
                <a target="_blank" class="elementor-button elementor-button-link elementor-size-sm"
                href="https://www.wildthymeatstrawberryfields.co.uk/">
                <span class="elementor-button-content-wrapper">
                    <span class="elementor-button-text">
                        Learn more</span>
                    </span>
                </a>
            </div>
        ';

    });
});

/**
 * REVIVE SPA AT HOMEWOOD GROVE
 */
add_shortcode('mm_revive_spa_at_homewood_grove', function() {
    return mm_village_panel('revive_spa_at_homewood_grove_bg', function() {

        // PUT YOUR HTML FOR REVIVE SPA AT HOMEWOOD GROVE HERE:
        return '
            <h3>Revive Spa at Homewood Grove</h3>

            <div class="button-row">
                <a target="_blank" class="elementor-button elementor-button-link elementor-size-sm"
                href="https://www.reviveathomewoodgrove.co.uk/">
                <span class="elementor-button-content-wrapper">
                    <span class="elementor-button-text">
                        Learn more</span>
                    </span>
                </a>
            </div>
        ';

    });
});

/**
 * REVIVE SPA AT STRAWBERRY FIELDS
 */
add_shortcode('mm_revive_spa_at_strawberry_fields', function() {
    return mm_village_panel('revive_spa_at_strawberry_fields_bg', function() {

        // PUT YOUR HTML FOR REVIVE SPA AT STRAWBERRY FIELDS HERE:
        return '
            <h3>Revive Spa at Strawberry Fields</h3>

            <div class="button-row">
                <a target="_blank" class="elementor-button elementor-button-link elementor-size-sm"
                href="https://www.reviveatstrawberryfields.co.uk/">
                <span class="elementor-button-content-wrapper">
                    <span class="elementor-button-text">
                        Learn more</span>
                    </span>
                </a>
            </div>
        ';

    });
});


//EOF