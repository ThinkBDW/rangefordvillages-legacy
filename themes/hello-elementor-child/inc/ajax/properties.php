<?php
/**
 * AJAX: properties
 *
 * @package hello-elementor-child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// --- count_filtered_properties + get_total_filtered_posts ---
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

// --- load_more_properties ---
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
                        <span style="display:none;" class="fill-whish-list wishlist-text">Remove Property</span>
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
