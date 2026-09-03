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
	// Memoised per request: the property listings call this once per tile,
	// and every tile in a village resolves the same handful of titles.
	static $cache = array();

	$title = trim( (string) $title );

	if ( '' === $title ) {
		return null;
	}

	if ( array_key_exists( $title, $cache ) ) {
		return $cache[ $title ];
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

	$cache[ $title ] = $posts ? $posts[0] : null;

	return $cache[ $title ];
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

// --- rv_google_maps_api_key() ---

/**
 * The Google Maps browser key, from one place.
 *
 * The key used to be hard-coded in inc/enqueue.php AND stored separately in
 * the elementor_google_maps_api_key option, so the theme map and Elementor's
 * own map widget could drift apart. Worse, replacing it meant a code deploy.
 *
 * Anotherway's key (AIzaSyAFKJ4-...) sits on a Google Cloud project with
 * billing switched off, which is why the map renders as a blank grey box:
 * the JS API loads, refuses to serve tiles and falls back to a static image.
 * The Geocoding API on the same key answers REQUEST_DENIED, "You must enable
 * Billing on the Google Cloud Project". No amount of code fixes that -- the
 * site needs a key on a billed project. Reading the option means swapping it
 * is a wp option update, not a release.
 *
 * Order: RV_GOOGLE_MAPS_API_KEY constant (for wp-config overrides), then the
 * option, then the rv_google_maps_api_key filter.
 *
 * @return string Key, or '' if none is configured.
 */
function rv_google_maps_api_key() {
	if ( defined( 'RV_GOOGLE_MAPS_API_KEY' ) && RV_GOOGLE_MAPS_API_KEY ) {
		$key = RV_GOOGLE_MAPS_API_KEY;
	} else {
		$key = (string) get_option( 'elementor_google_maps_api_key', '' );
	}

	return trim( (string) apply_filters( 'rv_google_maps_api_key', $key ) );
}

// --- rv_google_maps_key_is_usable() ---

/**
 * Is the configured key one Google will actually serve a map for?
 *
 * Needed because the interesting failure is silent. With billing switched off
 * on the owning Cloud project -- the state Anotherway's key is in -- the Maps
 * JavaScript API loads, reports no error, does not call gm_authFailure, and
 * simply paints nothing. The visitor gets a 500px grey rectangle on the page
 * that is supposed to show them where the home is.
 *
 * So ask Google directly. The Static Maps endpoint answers 403 with a plain
 * text body that names the cause, and both causes it names are key- or
 * project-level, so they break the JS API too:
 *
 *   "You must enable Billing on the Google Cloud Project ..."
 *   "The provided API key is invalid."
 *
 * Only those two count. Anything else -- a timeout, a quota trip, Static Maps
 * being disabled for a key that is fine for Maps JS -- is treated as usable,
 * so a false negative cannot hide a working map. Cached for 12 hours, keyed on
 * the key itself, so pasting in a good key clears the verdict immediately
 * rather than waiting out the transient.
 *
 * @param string $key The key to check.
 * @return bool
 */
function rv_google_maps_key_is_usable( $key ) {
	if ( ! $key ) {
		return false;
	}

	$cache_key = 'rv_gmaps_key_ok_' . md5( $key );
	$cached    = get_transient( $cache_key );

	if ( false !== $cached ) {
		return 'ok' === $cached;
	}

	$response = wp_remote_get(
		add_query_arg(
			array(
				'center' => '51.5,-0.1',
				'zoom'   => 12,
				'size'   => '1x1',
				'key'    => $key,
			),
			'https://maps.googleapis.com/maps/api/staticmap'
		),
		array( 'timeout' => 5 )
	);

	// Fail open: if we could not ask, assume the key is fine.
	if ( is_wp_error( $response ) ) {
		set_transient( $cache_key, 'ok', 5 * MINUTE_IN_SECONDS );
		return true;
	}

	$body   = (string) wp_remote_retrieve_body( $response );
	$broken = false !== stripos( $body, 'enable Billing' )
		|| false !== stripos( $body, 'API key is invalid' );

	set_transient( $cache_key, $broken ? 'broken' : 'ok', 12 * HOUR_IN_SECONDS );

	return ! $broken;
}
