<?php
/**
 * The Events Calendar integration
 *
 * @package hello-elementor-child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// --- TEC events-bar selectors, [events_by_village_category], [event_categories] ---
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

// --- tribe_events_after_template ---
add_action('tribe_events_after_template', function() {
    echo '<div class="custom-events-footer" style="padding: 20px; background: #eee; text-align: center;display:none;">
            <h3>Join Us for More Events!</h3>
            <p>Follow us on social media for updates.</p>
          </div>';
});

