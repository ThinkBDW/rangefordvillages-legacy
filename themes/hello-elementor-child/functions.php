<?php
/**
 * hello-elementor-child theme bootstrap.
 *
 * A loader only -- the code lives in inc/.
 *
 * The include order matters in one place: the two library/ requires may define
 * helpers used by the code between them, so they keep their positions.
 *
 * inc/wishlist.php used to call session_start() at include time, which pinned
 * it after the enqueue and post-type registrations. The session is gone
 * (2026-09-02 -- see rv_wishlist_ids_from_request()), so that constraint no
 * longer applies, but the position is left alone rather than churned.
 *
 * Do not reorder without checking.
 *
 * @package hello-elementor-child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$rv_includes = array(
	// Loaded first: library/retirement-properties.php below calls
	// rv_get_village_by_title() from here. Safe to hoist -- this file only
	// defines functions and has no load-time side effects.
	'helpers',
	'media',
	// GTM. Also defines rv_legacy_snippets_active(), which inc/enqueue.php
	// gates the recovered map/events JS on.
	'analytics',
	'enqueue',
	'post-types',
	'ajax/properties',
	'wishlist',
	'shortcodes/hero-slider',
	'shortcodes/properties',
	'elementor-queries',
	'shortcodes/misc',
	'elementor-widgets',
	'admin',
	'shortcodes/gallery',
	'shortcodes/brochures',
	'shortcodes/villages',
	'cf7/fields',
	// Makes the forms' hand-written <label>s real labels: associated, visible
	// and marked for required fields. Pairs with new-style.css.
	'cf7/labels',
	'rewrites',
	'cf7/redirects',
	'ajax/brochures',
	'cf7/mail',
	// Must load before init:1, where it removes CF7's reCAPTCHA hooks.
	'cf7/spam',
	'shortcodes/budget-calculator',
	'brochure-modal',
	// Removed 2026-09-02: 'integrations/sherpa/legacy-source-fields'. It was a
	// wp_footer script that overwrote vendorName / sourceCategory / sourceName
	// from ACF options and then let the "how did you hear about us" dropdown
	// overwrite sourceCategory again, so the source attribution Sherpa received
	// depended on whether JavaScript ran -- and could be any of eleven
	// categories, most of which are not configured inquiry sources in the CRM.
	// Every lead came in with a source-mismatch alert. The three values are now
	// set server-side in rv_sherpa_source_fields().
	//
	// The only lead delivery path. RV_SHERPA_DRY_RUN gates real POSTs.
	'integrations/sherpa/bootstrap',
	'shortcodes/maps',
	'events',
);

foreach ( $rv_includes as $rv_include ) {
	require_once get_stylesheet_directory() . '/inc/' . $rv_include . '.php';
}

// Functionality for filtering retirement properties (was functions.php:7).
// Now loaded after inc/helpers.php, whose rv_get_village_by_title() it calls.
require_once get_stylesheet_directory() . '/library/retirement-properties.php';

// Village map shortcode (was functions.php:4057, i.e. after events.php).
require_once get_stylesheet_directory() . '/library/shortcode-village-map.php';

foreach ( array( 'ajax/villages', 'shortcodes/megamenu-panels' ) as $rv_include ) {
	require_once get_stylesheet_directory() . '/inc/' . $rv_include . '.php';
}

unset( $rv_includes, $rv_include );
