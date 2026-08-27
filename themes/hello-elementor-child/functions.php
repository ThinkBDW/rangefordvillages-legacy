<?php
/**
 * hello-elementor-child theme bootstrap.
 *
 * This file was 4,487 lines until the 2026-08 takeover refactor. It is now a
 * loader only -- see inc/ for the code, which was moved verbatim.
 *
 * The include order below deliberately mirrors the order things appeared in the
 * original functions.php. That matters in two places:
 *
 *   - inc/wishlist.php calls session_start() at include time, so it must stay
 *     after the enqueue and post-type registrations, as it was before.
 *   - the two library/ requires bracketed the file (lines 7 and 4057) and may
 *     define helpers used by the code between them.
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
	'shortcodes/budget-calculator',
	'integrations/sherpa/legacy-source-fields',
	'integrations/sherpa/legacy-routing',
	// The new code-based integration. Runs ALONGSIDE the legacy routing and
	// the contact-form-to-any-api plugin during the parallel run: it captures
	// and stores every lead and records the request it would send, but posts
	// nothing while RV_SHERPA_DRY_RUN is true. legacy-routing.php and the
	// plugin are removed together at cutover.
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
