<?php
/**
 * Google Tag Manager (GTM-TTQQDVM)
 *
 * Recovered verbatim from Elementor Pro Custom Code posts #3468 (location
 * elementor_head, priority 1) and #3469 (elementor_body_start) during the
 * 2026 takeover, task A3. The audit originally attributed these snippets to
 * the ele-custom-skin plugin; they are in fact elementor_snippet posts, i.e.
 * CONTENT -- which is why they came back with every database pull and why
 * removing plugins at cutover would never have stopped them loading. Both
 * ran site-wide (condition include/general).
 *
 * Guarded on those posts no longer being PUBLISHED: while a snippet post is
 * live, Elementor Pro still prints the original, and printing a second
 * container would double-fire every tag and corrupt analytics. Setting the
 * snippet posts to draft flips this copy live in the same request -- no gap,
 * no overlap -- and republishing them is the instant rollback. GTM Preview
 * sign-off before cutover is still required: RUNBOOK.md section 8.
 *
 * @package hello-elementor-child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Whether any of the recovered Elementor Pro Custom Code posts is still
 * published (GTM head/body here, plus the map and events JS gated in
 * inc/enqueue.php). One all-or-nothing switch so the parallel state cannot
 * half-overlap: republishing ANY of the four suppresses ALL the theme
 * copies.
 *
 * @return bool
 */
function rv_legacy_snippets_active() {
	// #3468 GTM head, #3469 GTM noscript, #19421 map JS, #19743 events JS.
	foreach ( array( 3468, 3469, 19421, 19743 ) as $snippet_id ) {
		if ( 'publish' === get_post_status( $snippet_id ) ) {
			return true;
		}
	}

	return false;
}

/**
 * GTM container - Custom Code post #3468, verbatim.
 */
function rv_gtm_head() {
	if ( rv_legacy_snippets_active() ) {
		return;
	}
	?>
<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-TTQQDVM');</script>
<!-- End Google Tag Manager -->
	<?php
}
add_action( 'wp_head', 'rv_gtm_head', 1 );

/**
 * GTM noscript iframe - Custom Code post #3469, verbatim. The theme's
 * header.php calls wp_body_open(), so this lands directly after <body>,
 * matching the original's elementor_body_start placement.
 */
function rv_gtm_body_open() {
	if ( rv_legacy_snippets_active() ) {
		return;
	}
	?>
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-TTQQDVM"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
	<?php
}
add_action( 'wp_body_open', 'rv_gtm_body_open' );
