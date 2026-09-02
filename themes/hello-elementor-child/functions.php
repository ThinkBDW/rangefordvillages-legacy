<?php
/**
 * hello-elementor-child theme bootstrap.
 *
 * A loader only -- the code lives in inc/.
 *
 * The include order matters in two places:
 *
 *   - inc/wishlist.php calls session_start() at include time, so it must stay
 *     after the enqueue and post-type registrations, as it was before.
 *   - the two library/ requires may define helpers used by the code
 *     between them, so they keep their positions.
 *
 * Do not reorder without checking both.
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
	'rewrites',
	'cf7/redirects',
	'ajax/brochures',
	'cf7/mail',
	// Must load before init:1, where it removes CF7's reCAPTCHA hooks.
	'cf7/spam',
	'shortcodes/budget-calculator',
	'brochure-modal',
	'integrations/sherpa/legacy-source-fields',
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
