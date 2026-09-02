<?php
/**
 * Shared helpers
 *
 * @package hello-elementor-child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// --- mm_village_panel() ---
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

// --- rv_get_village_by_title: replaces deprecated get_page_by_title() ---

/**
 * Look up a village post by its exact title.
 *
 * Replaces get_page_by_title(), deprecated in WordPress 6.2. Both former call
 * sites passed a user-supplied or imported string and one of them dereferenced
 * the result without a null check, so this returns null explicitly and callers
 * are expected to guard.
 *
 * @param string $title Exact post title to match.
 * @return WP_Post|null The village post, or null if there is no exact match.
 */
function rv_get_village_by_title( $title ) {
	$title = trim( (string) $title );

	if ( '' === $title ) {
		return null;
	}

	$posts = get_posts(
		array(
			'post_type'        => 'villages',
			'title'            => $title,
			'post_status'      => 'any',
			'numberposts'      => 1,
			'orderby'          => 'ID',
			'order'            => 'ASC',
			'suppress_filters' => false,
		)
	);

	return $posts ? $posts[0] : null;
}

/**
 * Whether the queried singular page renders the budget calculator.
 *
 * Gates ~400 KB of calculator-only JS -- the theme's chart.js, the
 * budget-calculator plugin's html2canvas, nouislider and
 * custom-calculator.js -- to the pages that actually render it.
 *
 * The shortcodes ([budget_calculator] from the theme, and the plugin's
 * [budget_calculator_custom]) live in plain post_content on some pages and
 * inside serialised _elementor_data on others, so both stores are checked;
 * the theme also ships a dedicated Budget Calculator page template.
 *
 * @return bool
 */
function rv_is_budget_calculator_page() {
	if ( ! is_singular() ) {
		return false;
	}

	if ( is_page_template( 'budget-calculator.php' ) ) {
		return true;
	}

	$post = get_post();

	if ( ! $post ) {
		return false;
	}

	if ( false !== strpos( $post->post_content, 'budget_calculator' ) ) {
		return true;
	}

	$elementor_data = get_post_meta( $post->ID, '_elementor_data', true );

	return is_string( $elementor_data ) && false !== strpos( $elementor_data, 'budget_calculator' );
}
