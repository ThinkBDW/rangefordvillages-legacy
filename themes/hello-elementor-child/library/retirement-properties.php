<?php

function rangeford_acf_image_url($value)
{
    if (empty($value)) {
        return '';
    }

    if (is_array($value)) {
        return $value['url'] ?? '';
    }

    if (is_numeric($value)) {
        return wp_get_attachment_url($value) ?: '';
    }

    return (string) $value;
}

/**
 * Fees note for property tiles — label + link from the matching village post.
 */
function rangeford_get_property_fees_note($location = '')
{
    if (!$location) {
        $location = get_field('location');
    }

    if (!$location) {
        return null;
    }

    $label = 'Other fees apply';
    $url = '';
    $target = '';

    $village_post = get_page_by_title($location, OBJECT, 'villages');
    if ($village_post) {
        $village_label = get_field('village_property_fees_label', $village_post->ID);
        if ($village_label) {
            $label = $village_label;
        }

        $link = get_field('village_property_fees_link', $village_post->ID);
        if (is_array($link)) {
            $url = isset($link['url']) ? $link['url'] : '';
            $target = isset($link['target']) ? $link['target'] : '';
        } elseif (is_string($link)) {
            $url = $link;
        }
    }

    return array(
        'label'  => $label,
        'url'    => $url,
        'target' => $target,
    );
}

function rangeford_render_property_fees_note($location = '')
{
    $fees = rangeford_get_property_fees_note($location);
    if (!$fees || !$fees['label']) {
        return;
    }

    echo '<p class="property-fees-note">';
    if (!empty($fees['url'])) {
        echo '<a href="' . esc_url($fees['url']) . '"';
        if (!empty($fees['target'])) {
            echo ' target="' . esc_attr($fees['target']) . '"';
        }
        echo '>' . esc_html($fees['label']) . '</a>';
    } else {
        echo esc_html($fees['label']);
    }
    echo '</p>';
}

function rangeford_render_property_icon_listing()
{
    $location = get_field('location');
    $bed = get_field('bed');
    $properties_type = get_field('properties_type');
    $bathroom = get_field('bathroom');
    $location_icon = rangeford_acf_image_url(get_field('location_icon'));
    $bed_icon = rangeford_acf_image_url(get_field('bed_icon'));
    $properties_type_icon = rangeford_acf_image_url(get_field('properties_type_icon'));
    $bathroom_icon = rangeford_acf_image_url(get_field('bathroom_icon'));
    $properties_sale_icon = rangeford_acf_image_url(get_field('properties_sale_icon'));
    $properties_sale_text = get_field('properties_sale_text');
    $floor_text = get_field('property_floor') ?: get_field('dormer_bungalow_text');
    $floor_icon = rangeford_acf_image_url(get_field('property_floor_icon'));
    if (!$floor_icon) {
        $floor_icon = rangeford_acf_image_url(get_field('dormer_bungalow_icon'));
    }
    ?>
    <ul class="icon-listing">
        <?php if ($location) { ?>
            <li><img src="<?php echo esc_url($location_icon); ?>" alt="">
                <?php echo esc_html($location); ?>
            </li>
        <?php } ?>
        <?php if ($bed) { ?>
            <li><img src="<?php echo esc_url($bed_icon); ?>" alt="">
                <?php echo esc_html($bed); ?> Bedroom(s)
            </li>
        <?php } ?>
        <?php if ($properties_type) { ?>
            <li><img src="<?php echo esc_url($properties_type_icon); ?>" alt="">
                <?php echo esc_html($properties_type); ?>
            </li>
        <?php } ?>
        <?php if ($bathroom) { ?>
            <li><img src="<?php echo esc_url($bathroom_icon); ?>" alt="">
                <?php echo esc_html($bathroom); ?> Bathroom(s)
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
    <?php
}

add_action('wp_ajax_sort_properties', 'sort_and_paginate_properties');
add_action('wp_ajax_nopriv_sort_properties', 'sort_and_paginate_properties');


function sort_and_paginate_properties()
{
    $page = isset($_POST['page']) ? max(1, intval($_POST['page'])) : 1;
    $posts_per_page = 15; // Adjust the number of posts per page as needed
    $hide_reserved = isset($_POST['hide_reserved']) ? $_POST['hide_reserved'] : 'false'; // Default to false
    $saved_properties = isset($_POST['saved_properties']) ? $_POST['saved_properties'] : 'false';
    // Retrieve the 'saved' parameter value from the AJAX request
    $savedPropertyValue = isset($_POST['savedPropertyValue']) ? sanitize_text_field($_POST['savedPropertyValue']) : '';
    $sort_value = isset($_POST['sort_value']) ? sanitize_text_field($_POST['sort_value']) : '';
	$minrangePrice = isset($_POST['minrangePrice']) ? floatval($_POST['minrangePrice']) : 0;
	$maxrangePrice = isset($_POST['maxrangePrice']) ? floatval($_POST['maxrangePrice']) : 0;

		
    // Sorting
    if ($_POST['sort_value']) {
      
	  if ($minrangePrice || $maxrangePrice || in_array($sort_value, ['low_to_high', 'high_to_low'])) {
		  	$args = array(
            'post_type' => 'properties',
            'posts_per_page' => $posts_per_page, // Adjust the number of posts per page as needed
            'post_status' => 'publish',
            'orderby' => 'meta_value',
            'meta_key' => 'price',
            'order' => ($_POST['sort_value'] == 'low_to_high') ? 'ASC' : 'DESC',
            'paged' => $page,
            'meta_query' => array(
                array(
                    'key' => 'reserved',
                    'value' => 'yes',
                    'compare' => 'NOT LIKE' // Exclude posts where 'reserved' is 'yes'
                )
        ),
        );
	  } else { 
		$args = array(
			'post_type' => 'properties',
			'posts_per_page' => $posts_per_page, // Adjust the number of posts per page as needed
			'post_status' => 'publish',
			'orderby' => array(
				'meta_value_num' => 'DESC', // Order by bed (descending)
				'meta_value' => ($_POST['sort_value'] == 'low_to_high') ? 'ASC' : 'DESC', // Order by price based on the sort value
			),
			'meta_key' => 'price', // Ensure the price meta key is used
			'paged' => $page,
			'meta_query' => array(
				array(
					'key' => 'reserved',
					'value' => 'yes',
					'compare' => 'NOT LIKE' // Exclude posts where 'reserved' is 'yes'
				),
				array(
					'key' => 'beds',
					'compare' => 'EXISTS',
				),
			),
		);
	  }
    } else {
		$args = array(
			'post_type' => 'properties',
			'posts_per_page' => $posts_per_page, // Adjust the number of posts per page as needed
			'post_status' => 'publish',
			'paged' => $page,
			'meta_query' => array(
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
                'price' => 'ASC',
				'bed' => 'DESC',
				'title' => 'ASC',
			),
		);
    }
	

	
    // Add filter condition for hiding reserved posts
    if ($hide_reserved === 'true')  {
        $args['meta_query'][] = array(
            'key' => 'reserved',
            'value' => 'yes',
            'compare' => 'NOT LIKE',
        );
    }
    // Add filter conditions for location and property type
    if ($_POST['location']) {
        $args['meta_query'][] = array(
            'key' => 'location',
            'value' => $_POST['location'],
            'compare' => '=',
        );
		$args['orderby'] = array(
            'price' => ($_POST['sort_value'] == 'low_to_high') ? 'ASC' : 'DESC',
            'bed' => 'DESC'
        
    );
    $args['meta_key'] = 'price';
	
    }
    if ($_POST['property_type']) {
        $args['meta_query'][] = array(
            'key' => 'properties_type',
            'value' => $_POST['property_type'],
            'compare' => '=',
        );
    }
    // Add filter conditions for min and max price
    if ($_POST['minrangePrice'] && $_POST['maxrangePrice']) {
        $args['meta_query'][] = array(
            'key' => 'price',
            'value' => array($_POST['minrangePrice'], $_POST['maxrangePrice']),
            'compare' => 'BETWEEN',
            'type' => 'NUMERIC',
        );
    }
    // } else {
    //     if ($_POST['minrangePrice']) {
    //         $minPrice = number_format($_POST['minrangePrice']);
    //         $args['meta_query'][] = array(
    //             'key' => 'price',
    //             'value' => $minPrice,
    //             'compare' => '>=', // Adjusted comparison for minimum price
    //             'type' => 'NUMERIC',
    //         );
    //     }
    //     if ($_POST['maxrangePrice']) {
    //         $maxPrice = number_format($_POST['maxrangePrice']);
    //         $args['meta_query'][] = array(
    //             'key' => 'price',
    //             'value' => $maxPrice,
    //             'compare' => '<=', // Adjusted comparison for maximum price
    //             'type' => 'NUMERIC',
    //         );
    //     }
    // }
    // Add filter conditions for min and max bed
    if ($_POST['min_bed'] && $_POST['max_bed']) {
        $args['meta_query'][] = array(
            'key' => 'bed',
            'value' => array($_POST['min_bed'], $_POST['max_bed']),
            'compare' => 'BETWEEN',
            'type' => 'NUMERIC',
        );
    } else {
        if ($_POST['min_bed']) {
            $args['meta_query'][] = array(
                'key' => 'bed',
                'value' => $_POST['min_bed'],
                'compare' => '>=',
                'type' => 'NUMERIC',
            );
        }
        if ($_POST['max_bed']) {
            $args['meta_query'][] = array(
                'key' => 'bed',
                'value' => $_POST['max_bed'],
                'compare' => '<=',
                'type' => 'NUMERIC',
            );
        }
    }
    // Add filter condition for 'condition'
    if ($_POST['condition']) {
        $args['meta_query'][] = array(
            'key' => 'condition', // Replace 'condition' with the actual meta key for condition
            'value' => $_POST['condition'],
            'compare' => '=',
        );
    }
    // Add filter conditions for amenities
    if (!empty($_POST['amenities'])) {
        $args['tax_query'][] = array(
            'taxonomy' => 'amenities',
            'field' => 'slug',
            'terms' => $_POST['amenities'],
            'operator' => 'IN',
        );
    }
    // Add filter condition for showing only saved properties
    if ($saved_properties === 'true') {
        //$wishlist = getWishlistFromLocalStorage();
        $wishlist = $_SESSION['wishlist'];
        if (!empty($wishlist)) {
            $args['post__in'] = $wishlist;
            // Set orderby to ensure price sorting works
            $args['orderby'] = array(
                'meta_value' => ($_POST['sort_value'] == 'low_to_high') ? 'ASC' : 'DESC',
                'post__in' => 'ASC',
            );
            $args['meta_key'] = 'price';
        } else {
            // No wishlist items, don't display any posts
            $args['post__in'] = array(0);
        }
    }
    // Add filter condition for showing only saved properties if requested
    if ($saved_properties === 'false' && $savedPropertyValue === 'property') {
        $wishlist = $_SESSION['wishlist'];
        if (!empty($wishlist)) {
            $args['post__in'] = $wishlist;
            // Set orderby to ensure price sorting works
            $args['orderby'] = array(
                'meta_value' => ($_POST['sort_value'] == 'low_to_high') ? 'ASC' : 'DESC',
                'post__in' => 'ASC',
            );
            $args['meta_key'] = 'price';
        } else {
            // No wishlist items, don't display any posts
            $args['post__in'] = array(0);
        }
    }


    // If both location and property type are provided, set 'relation' => 'AND'
    if ($_POST['location'] && $_POST['property_type'] && $_POST['minrangePrice'] && $_POST['maxrangePrice'] && $_POST['max_bed'] && $_POST['min_bed'] && $_POST['condition'] && $_POST['amenities'] && $_POST['hide_reserved'] && $_POST['saved_properties']) {
        $args['meta_query']['relation'] = 'AND';
    }

    $query = new WP_Query($args);
    /*echo '<pre>';
    print_r($args);
    echo '</pre>';*/

?>
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
                        $bed = get_field('bed');
                        $properties_type = get_field('properties_type');
                        $bathroom = get_field('bathroom');
                        $location_icon = rangeford_acf_image_url(get_field('location_icon'));
                        $bed_icon = rangeford_acf_image_url(get_field('bed_icon'));
                        $properties_type_icon = rangeford_acf_image_url(get_field('properties_type_icon'));
                        $bathroom_icon = rangeford_acf_image_url(get_field('bathroom_icon'));
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
                                <li><img src="<?php echo esc_url($location_icon); ?>" alt="">
                                    <?php echo esc_html($location); ?>
                                </li>
                            <?php } ?>
                            <?php if ($bed) { ?>
                                <li><img src="<?php echo esc_url($bed_icon); ?>" alt="">
                                    <?php echo esc_html($bed); ?> Bedroom(s)
                                </li>
                            <?php } ?>
                            <?php if ($properties_type) { ?>
                                <li><img src="<?php echo esc_url($properties_type_icon); ?>" alt="">
                                    <?php echo esc_html($properties_type); ?>
                                </li>
                            <?php } ?>
                            <?php if ($bathroom) { ?>
                                <li><img src="<?php echo esc_url($bathroom_icon); ?>" alt="">
                                    <?php echo esc_html($bathroom); ?> Bathroom(s)
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
                        <a href="<?php the_permalink(); ?>" class="elementor-button elementor-button-link elementor-size-sm">View
                            full details</a>
                        <div class="wishlist-icon-section" data-property-id="<?php echo get_the_ID(); ?>">
                            <img class="without-fill" src="/wp-content/uploads/2024/05/save-property-line-icon-1.svg">
                            <img style="display:none;" class="fill-whish-list" src="/wp-content/uploads/2024/05/save-property-line-icon-fill-1.svg" alt="Wishlist"> 
                            <span class="wishlist-text without-fill">Save Property</span>
                            <span style="display:none;" class="fill-whish-list" class="wishlist-text">Remove Property</span>
                        </div>
                        
                    </div>

                </div>

        <?php endwhile;

        else :
            echo 'No properties found';
        endif; ?>
    </div>
    <?php

    wp_reset_postdata();
    // Your existing code for filtering and pagination

    // Call the function to get the total count of filtered properties
    $total_filtered_posts = count_filtered_properties($args);

    // Output the total count
    echo '<p style="display:none;" class="total-properties">' . $total_filtered_posts . '</p>';

    // Your existing code for displaying properties list
    // Output pagination links
    echo '<div class="pagination">';
    echo paginate_links(
        array(
            'total' => $query->max_num_pages,
            'current' => max(1, $page),
            'prev_text' => __('<'),
            'next_text' => __('>'),
        )
    );
    echo '</div>';
    die(); ?>
    <div id="loader-wrapper" class="loader" style="display: none;">
        <div id="loader"></div>
    </div>
<?php
}