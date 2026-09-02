<?php
/**
 * Brochure modals -- replaces the popup-maker plugin.
 *
 * popup-maker was carrying exactly two live popups (10766 "Single Page
 * Brochure" and 32688 "Single Page Lifestyle Brochure"), each containing
 * nothing but a Contact Form 7 shortcode. Two further popups -- 7895, the
 * plugin's own demo, and 7896 -- never rendered on any page. That is a whole
 * plugin, its eight theme posts and its database tables for two CF7 forms in
 * a box.
 *
 * This reproduces the exact DOM contract the rest of the site already depends
 * on, rather than inventing a cleaner one:
 *
 *   - #popmake-{id}                 styled by style.css / new-style.css
 *   - .pum-container .popmake       "
 *   - .pum-content.popmake-content  styled by new-style.css
 *   - .pum-close.popmake-close      adjacent sibling of .pum-content, because
 *                                   new-style.css targets
 *                                   `.pum-theme-7888 .pum-content+.pum-close`
 *   - .pum-theme-7888               "
 *
 * Keeping those names means no CSS changed and, more importantly,
 * inc/cf7/redirects.php keeps working untouched: it finds the submitted popup
 * with `closest('[id^="popmake-"]')` and closes it by firing a jQuery click on
 * `.popmake-close`. Bound with jQuery here for that reason -- a native
 * listener would not always see jQuery's synthetic trigger.
 *
 * Triggers, matching what popup-maker bound:
 *   - .single-brochure-view-button      -> 10766 (popup 10766's extra_selectors;
 *                                         used by the mega menu and
 *                                         single-properties.php)
 *   - [data-popmake] / .popmake-{id}    -> that popup, popup-maker's own
 *                                         click binding
 *
 * @package hello-elementor-child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The popups that were actually reachable, and the form each held.
 *
 * Verified from the rendered HTML before removal: only these two emitted a
 * container on any page.
 */
function rv_brochure_modals() {
	return array(
		10766 => '[contact-form-7 id="5c5a8dc" title="Single Page Brochure"]',
		32688 => '[contact-form-7 id="2b77045" title="Single Page Brochure_copy"]',
	);
}

/**
 * Print the modals in the footer, as popup-maker did -- sitewide and
 * unconditionally. The triggers are spread across Elementor content, the mega
 * menu and single-properties.php, so there is no reliable template condition
 * to gate on.
 */
function rv_render_brochure_modals() {
	if ( is_admin() ) {
		return;
	}

	foreach ( rv_brochure_modals() as $id => $shortcode ) {
		?>
		<div class="pum pum-overlay pum-theme-7888 pum-theme-lightbox popmake-overlay rv-modal-overlay"
			data-rv-modal="<?php echo esc_attr( $id ); ?>"
			role="dialog"
			aria-modal="true"
			aria-hidden="true">
			<div id="popmake-<?php echo esc_attr( $id ); ?>" class="pum-container popmake theme-7888 rv-modal">
				<div class="pum-content popmake-content">
					<?php echo do_shortcode( $shortcode ); ?>
				</div>
				<button type="button" class="pum-close popmake-close" aria-label="<?php esc_attr_e( 'Close', 'hello-elementor-child' ); ?>">&times;</button>
			</div>
		</div>
		<?php
	}
}
add_action( 'wp_footer', 'rv_render_brochure_modals' );
