<?php
/**
 * Shortcodes: Google maps
 *
 * Moved verbatim from functions.php during the takeover refactor
 * (was lines 3549-3764). No behaviour change in the move commit.
 *
 * @package hello-elementor-child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// --- functions.php lines 3549-3764: [custom_google_map] (+ dead plural dupe), [property_main_location_url] ---
// Removed: custom_google_map_repeater_shortcodes() -- a ~70-line copy of the
// function below, differing only by the trailing 's' in its name. It was never
// called and never registered as a shortcode.
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


