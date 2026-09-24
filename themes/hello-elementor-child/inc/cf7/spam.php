<?php
/**
 * Contact Form 7 spam protection.
 *
 * reCAPTCHA v3 out, Akismet in.
 *
 * WHY. CF7's built-in reCAPTCHA v3 integration was configured, with the site
 * keys living only in the `wpcf7` option. reCAPTCHA v3 has no
 * widget, so the way it works is to load Google's api.js on *every* page of the
 * site -- not just the pages that can submit -- and run a scoring routine on
 * each one. That is a third-party connection, a 200KB-ish script and main-thread
 * work on every page view, in exchange for protecting seventeen forms.
 *
 * Akismet does the same job entirely server-side. Nothing is loaded on the front
 * end at all; the check happens inside submission handling, where the visitor is
 * already waiting.
 *
 * Two further consequences, both good:
 *
 *   - The reCAPTCHA keys were a migration prerequisite that existed nowhere but
 *     the production `wpcf7` option -- not wp-config.php, not version control.
 *     With reCAPTCHA gone they no longer need carrying to SiteGround, which
 *     removes a way for every form on the new site to silently reject
 *     submissions as spam.
 *   - contact-form-7-honeypot goes from redundant to load-bearing: it is
 *     now the only check that costs nothing and involves no third party,
 *     so it stays on all seventeen forms.
 *
 * Akismet's verdict can be switched off per environment with
 * RV_CF7_AKISMET_DISABLED -- see rv_cf7_disable_akismet(). The plugin stays
 * active and keyed when it is; only the wpcf7_spam hook goes.
 *
 * @package hello-elementor-child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Make CF7's reCAPTCHA module inert, whatever the database says.
 *
 * Deleting the keys from the `wpcf7` option is the documented way to switch this
 * off, and that is done as part of the cutover -- but it is a database change,
 * so it can be undone by an editor in wp-admin and it reappears on the next pull
 * from production. Removing the hooks in code is the part that cannot drift.
 *
 * Nothing here affects form rendering: no form on the site contains a
 * [recaptcha] tag (checked across all seventeen), so the module contributes only
 * the sitewide script, one hidden field and the server-side token check.
 *
 * init at priority 1 -- CF7 loads its modules on plugins_loaded, so these hooks
 * exist by now, and both wp_enqueue_scripts and wpcf7_spam fire later.
 */
function rv_cf7_disable_recaptcha() {
	// The sitewide google.com/recaptcha/api.js + wpcf7-recaptcha enqueue. This
	// is the whole reason for the change.
	remove_action( 'wp_enqueue_scripts', 'wpcf7_recaptcha_enqueue_scripts', 20 );

	// The hidden field that carried the token.
	remove_filter( 'wpcf7_form_hidden_fields', 'wpcf7_recaptcha_add_hidden_fields', 100 );

	// The server-side check. This is also what made scripted form testing
	// impossible -- it flagged every request without a valid Google token as
	// spam, so the end-to-end lead path could only ever be verified by driving
	// WPCF7_Submission directly.
	remove_filter( 'wpcf7_spam', 'wpcf7_recaptcha_verify_response', 9 );
}
add_action( 'init', 'rv_cf7_disable_recaptcha', 1 );

/**
 * Whether Akismet's verdict is switched off for this site.
 *
 * WHY A CONSTANT RATHER THAN AN OPTION. This is an environment decision, not an
 * editorial one: a staging copy should not be spending the key's reputation on
 * test traffic, while production may want the check on. It also must not be
 * flippable from wp-admin -- turning it on leaves the honeypot as the only
 * layer, which is exactly the kind of quiet change this file exists to prevent.
 * Define it in wp-config.php, next to RV_SHERPA_DRY_RUN.
 *
 * The filter is for tests and for a temporary override from a mu-plugin.
 *
 * @return bool
 */
function rv_cf7_akismet_is_disabled() {
	$disabled = defined( 'RV_CF7_AKISMET_DISABLED' ) && RV_CF7_AKISMET_DISABLED;

	return (bool) apply_filters( 'rv_cf7_akismet_disabled', $disabled );
}

/**
 * Take Akismet out of the submission path, leaving the plugin itself alone.
 *
 * CF7's module registers exactly one verdict hook -- see
 * modules/akismet/akismet.php -- so removing it stops the check before the API
 * call is built: no POST to Akismet, no wait for the answer on submit, and no
 * enquirer data leaving the site.
 *
 * WHY NOT JUST DEACTIVATE THE PLUGIN. Two things still need it. Akismet::http_post()
 * is how RV_Sherpa_Dispatcher::report_to_akismet() clears the held-submission
 * backlog -- those rows predate the switch and their Send to CRM / Discard
 * decisions should still be reported. And wpcf7_akismet_is_available() staying
 * true keeps Settings -> Integration honest about the key being present, so
 * turning the check back on is one line rather than a reinstall.
 *
 * Removing the annotations instead (rv_cf7_akismet_annotate_tag) would also
 * work, because wpcf7_akismet_submitted_params() bails when no tag carries an
 * akismet: option -- but by side effect. Someone reading this file later would
 * see Akismet fully wired and no obvious reason it never fires.
 *
 * init at priority 1, matching rv_cf7_disable_recaptcha: CF7 loads its modules
 * on plugins_loaded, so the hook exists by now, and wpcf7_spam fires later.
 */
function rv_cf7_disable_akismet() {
	if ( ! rv_cf7_akismet_is_disabled() ) {
		return;
	}

	remove_filter( 'wpcf7_spam', 'wpcf7_akismet', 10 );
}
add_action( 'init', 'rv_cf7_disable_akismet', 1 );

/**
 * CF7 field names that hold the enquirer's own name.
 *
 * Akismet runs on a form only when at least one of its form-tags carries an
 * `akismet:` option -- see wpcf7_akismet_submitted_params(), which returns false
 * and skips the check entirely when it finds none. So the seventeen forms have
 * to be annotated, and doing it here rather than in the stored form definitions
 * is deliberate: those definitions are content, so they are overwritten by the
 * final freeze-window pull. A code filter survives that.
 *
 * Field names are shared across forms -- text-178 is the first-name field on
 * nine of them -- so this is one list, not a per-form map. Address, town,
 * postcode and the message textarea are deliberately absent: they belong in
 * Akismet's `content`, not its `author`.
 *
 * @return string[]
 */
function rv_cf7_akismet_author_fields() {
	return apply_filters(
		'rv_cf7_akismet_author_fields',
		array(
			// text-178 / text-316 / text-370: first and last name across the
			// village, contact and event forms.
			'text-178',
			'text-316',
			'text-370',
			// "Save your calculation" (12311).
			'text-523',
			'text-470',
			// The brochure forms (7807, 10765, 32660).
			'FirstName',
			'LastName',
			// The retired "Sherpa Form" (13096), named after the API fields.
			'primaryContactFirstName',
			'primaryContactLastName',
		)
	);
}

/**
 * Tell Akismet which fields are the author and the author's email.
 *
 * Email is matched by tag type rather than by name, so a form added later is
 * covered without editing this file. Names have to be matched by name, because
 * the other text fields on these forms are address lines.
 *
 * @param array $tag     Scanned form-tag, still an array at this point.
 * @param bool  $replace Whether the scan is rendering output.
 * @return array
 */
function rv_cf7_akismet_annotate_tag( $tag, $replace = false ) {
	// The filter passes ( $scanned_tag, $replace ) and the tag is a plain array
	// until WPCF7_FormTag wraps it just after. Guard anyway -- the inherited
	// theme had this signature backwards, which is what fired 9,467 warnings a
	// page load.
	if ( ! is_array( $tag ) || empty( $tag['name'] ) ) {
		return $tag;
	}

	if ( 'email' === ( $tag['basetype'] ?? '' ) ) {
		$option = 'akismet:author_email';
	} elseif ( in_array( $tag['name'], rv_cf7_akismet_author_fields(), true ) ) {
		$option = 'akismet:author';
	} else {
		return $tag;
	}

	$tag['options'] = isset( $tag['options'] ) ? (array) $tag['options'] : array();

	// If the form definition already says something about Akismet, it wins.
	foreach ( $tag['options'] as $existing ) {
		if ( is_string( $existing ) && 0 === stripos( $existing, 'akismet:' ) ) {
			return $tag;
		}
	}

	$tag['options'][] = $option;

	return $tag;
}
add_filter( 'wpcf7_form_tag', 'rv_cf7_akismet_annotate_tag', 10, 2 );

/**
 * Keep what goes to Akismet small, and free of binary data.
 *
 * CF7 builds `comment_content` by concatenating every posted field it has not
 * been told is an author field. On form 12311 ("Save your calculation") that
 * includes chart_one_image, chart_two_image, hidden_chart_image and two more --
 * each a base64 PNG produced by html2canvas, hundreds of KB per submission,
 * which would otherwise be POSTed to Akismet verbatim on every enquiry.
 *
 * It also keeps the third-party transfer proportionate, which matters now that
 * the rest of the integration deliberately stops holding lead data
 * (class-store.php). Akismet needs a name, an email and enough prose to judge --
 * not the enquirer's full postal address and a rendered chart.
 *
 * @param array $comment Parameters bound for the Akismet API.
 * @return array
 */
function rv_cf7_akismet_trim_parameters( $comment ) {
	if ( empty( $comment['comment_content'] ) ) {
		return $comment;
	}

	$content = (string) $comment['comment_content'];

	// data: URIs, and any other long unbroken base64 run.
	$content = preg_replace(
		'#data:[a-z0-9.+-]+/[a-z0-9.+-]+;base64,[A-Za-z0-9+/=\s]+#i',
		'[binary removed]',
		$content
	);
	$content = preg_replace( '#[A-Za-z0-9+/=]{512,}#', '[binary removed]', $content );

	$max = (int) apply_filters( 'rv_cf7_akismet_max_content', 4096 );

	if ( strlen( $content ) > $max ) {
		$content = substr( $content, 0, $max );
	}

	$comment['comment_content'] = $content;

	return $comment;
}
add_filter( 'wpcf7_akismet_parameters', 'rv_cf7_akismet_trim_parameters', 10, 1 );

/**
 * Whether the honeypot form-tag is actually registered.
 *
 * Tested by asking CF7 what tag types exist, rather than by looking for a
 * plugin constant. The plugin has been rebranded to "CF7Apps" and now defines
 * CF7APPS_VERSION, with the honeypot itself living in a legacy-honeypot module
 * -- so a constant check would silently drift with the next rename. What matters
 * is the tag type: without it, every `[honeypot-424]` on the seventeen forms
 * renders as literal text and the protection is simply gone.
 *
 * @return bool
 */
function rv_cf7_honeypot_is_active() {
	if ( ! class_exists( 'WPCF7_FormTagsManager' ) ) {
		return false;
	}

	$types = WPCF7_FormTagsManager::get_instance()->collect_tag_types();

	return in_array( 'honeypot', (array) $types, true );
}

/**
 * Say so, loudly, when the spam layers are not actually in place.
 *
 * Akismet does nothing at all without an active plugin and a valid API key:
 * wpcf7_akismet_is_available() returns false and the wpcf7_spam filter returns
 * early without contacting anything. With reCAPTCHA now removed in code, that
 * state leaves only the honeypot and CF7's disallowed-list check.
 *
 * The lesson from the inherited install is that a form path which fails quietly
 * stays broken for months. Same principle: if the swap is only
 * half done, that has to be visible in wp-admin rather than inferred later from
 * a spike in junk enquiries.
 */
function rv_cf7_spam_protection_notice() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	// Only where it is actionable: the dashboard, the CF7 screens and the CRM
	// lead list. Not on every page of wp-admin.
	$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;
	$id     = $screen ? $screen->id : '';

	$relevant = ( 'dashboard' === $id )
		|| false !== strpos( $id, 'wpcf7' )
		|| false !== strpos( $id, 'rv-sherpa-leads' );

	if ( ! $relevant ) {
		return;
	}

	// Switched off deliberately is still worth saying out loud. It leaves the
	// honeypot as the only layer, and it silently empties the held-submission
	// queue that CRM Leads offers to review -- neither of which is visible from
	// anywhere else in wp-admin.
	if ( rv_cf7_akismet_is_disabled() ) {
		printf(
			'<div class="notice notice-warning"><p><strong>Akismet is switched off for form submissions.</strong> '
				. 'Its verdict is removed in code, because <code>RV_CF7_AKISMET_DISABLED</code> is set in '
				. '<code>wp-config.php</code>. %s No new submissions can be held for review, because there are no '
				. 'Akismet verdicts left to hold. See <code>inc/cf7/spam.php</code>.</p></div>',
			rv_cf7_honeypot_is_active()
				? 'The honeypot is now the only spam filter on the forms.'
				: '<strong>The honeypot is not registered either, so the forms have no spam filter at all.</strong>'
		);

		return;
	}

	$missing = array();

	if ( ! function_exists( 'wpcf7_akismet_is_available' ) ) {
		$missing[] = 'Contact Form 7 is not active';
	} elseif ( ! wpcf7_akismet_is_available() ) {
		$missing[] = class_exists( 'Akismet' )
			? 'Akismet is installed but has no valid API key'
			: 'the Akismet plugin is not installed or not active';
	}

	if ( ! rv_cf7_honeypot_is_active() ) {
		$missing[] = 'the CF7 honeypot form-tag is not registered';
	}

	if ( ! $missing ) {
		return;
	}

	printf(
		'<div class="notice notice-warning"><p><strong>Form spam protection is incomplete.</strong> '
			. 'reCAPTCHA has been removed from this site in favour of Akismet, but %s. '
			. 'Forms still work; they are just less well protected than intended. '
			. 'See <code>inc/cf7/spam.php</code>.</p></div>',
		esc_html( implode( ', and ', $missing ) )
	);
}
add_action( 'admin_notices', 'rv_cf7_spam_protection_notice' );

/**
 * Keep CF7's REST responses out of SiteGround's page cache.
 *
 * The honeypot plugin (CF7 Apps 3.7.x) names its trap field with a random
 * string it keeps in a daily transient, and the server only accepts a post
 * that carries today's name. Because cached pages hold a stale name, its script
 * calls CF7's GET /contact-forms/{id}/refill on every page load and renames the
 * field from the reply. SiteGround's dynamic cache caches that GET like any
 * other anonymous request, so the "fresh" name was a cached one too.
 * On 2026-09-24 the A91 page, rendered at 11:17 with field `jjlwpyialq68`, was
 * renamed to `zb0g3nizlnoc` by a refill cached at 05:06. The server no
 * longer expected that name, so every brochure download was judged spam, and
 * the visitor only saw "There was an error trying to send your message".
 * Akismet being off made no difference: the honeypot is what failed.
 *
 * WordPress only sends no-cache headers on REST responses to logged-in users,
 * so they are added here for the whole CF7 namespace. The plugin's own
 * submissions are POSTs and were never cached; this is about the refill.
 *
 * @param WP_HTTP_Response|mixed $response Result about to be served.
 * @param WP_REST_Server         $server   Server instance.
 * @param WP_REST_Request        $request  Request being served.
 * @return WP_HTTP_Response|mixed
 */
function rv_cf7_rest_nocache( $response, $server, $request ) {
	if ( $response instanceof WP_HTTP_Response
		&& 0 === strpos( $request->get_route(), '/contact-form-7/' ) ) {
		foreach ( wp_get_nocache_headers() as $name => $value ) {
			if ( $value ) {
				$response->header( $name, $value );
			}
		}
	}

	return $response;
}
add_filter( 'rest_post_dispatch', 'rv_cf7_rest_nocache', 10, 3 );
