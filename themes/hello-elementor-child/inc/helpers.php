<?php
/**
 * Shared helpers
 *
 * Moved verbatim from functions.php during the takeover refactor
 * (was lines 4121-4142). No behaviour change in the move commit.
 *
 * @package hello-elementor-child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// --- functions.php lines 4121-4142: mm_village_panel() ---
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

// --- added during takeover: replaces deprecated get_page_by_title() ---

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
