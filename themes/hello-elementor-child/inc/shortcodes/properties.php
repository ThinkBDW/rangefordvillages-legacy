<?php
/**
 * Shortcodes: property listings
 *
 * @package hello-elementor-child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// --- [home_properties], [village_properties] ---
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

    // Execute the query
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




// --- [home_properties_three_row] ---
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



