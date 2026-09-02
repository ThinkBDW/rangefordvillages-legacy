<?php
/**
 * Shortcodes: miscellaneous
 *
 * @package hello-elementor-child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// --- [current_year] ---
// / Current Year
function current_year_shortcode()
{
    $current_datetime = date('Y');
    return $current_datetime;
}
add_shortcode('current_year', 'current_year_shortcode');


// --- [last_modified_date] ---

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


// --- [category_button] ---
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
