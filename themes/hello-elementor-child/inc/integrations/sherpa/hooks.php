<?php
/**
 * Wire the Sherpa integration into Contact Form 7 and the scheduler.
 *
 * @package hello-elementor-child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Capture a lead once CF7 has validated the submission and sent the mail.
 *
 * wpcf7_mail_sent, not wpcf7_before_send_mail: it fires only after validation
 * and mail delivery succeed, so spam caught by the honeypot and submissions
 * that failed validation never reach the CRM. It also cannot abort the mail,
 * which wpcf7_before_send_mail can -- the old integration hooked there and
 * additionally called `fopen(...) or die()`, so a read-only theme directory
 * would have killed the submission mid-request.
 *
 * @param WPCF7_ContactForm $form Submitted form.
 */
function rv_sherpa_on_mail_sent( $form ) {
	if ( ! class_exists( 'WPCF7_Submission' ) ) {
		return;
	}

	$submission = WPCF7_Submission::get_instance();

	if ( ! $submission ) {
		return;
	}

	// Never let a CRM problem surface to the visitor as a failed submission.
	// The lead is stored before any network call, so swallowing the exception
	// here loses nothing.
	try {
		RV_Sherpa_Dispatcher::capture(
			$form->id(),
			(array) $submission->get_posted_data(),
			$form->title()
		);
	} catch ( Throwable $e ) {
		error_log( '[rv-sherpa] capture failed: ' . $e->getMessage() );
	}
}
add_action( 'wpcf7_mail_sent', 'rv_sherpa_on_mail_sent', 10, 1 );

/**
 * Remember the parameters the Akismet check was actually made with.
 *
 * Needed so that "Send to CRM" on a held lead can report the false positive
 * back with submit-ham, which is what stops the same person being rejected
 * again. Akismet's submit-* endpoints expect the same parameters the
 * comment-check was given, and there is no other point in the request where
 * they can be read.
 *
 * Only the documented comment fields are kept. CF7 also hands Akismet the
 * entire $_SERVER array -- filesystem paths, PHP-FPM internals, every request
 * header -- and none of that belongs in a database row that is exported with
 * the site.
 *
 * @param array $comment Parameters bound for the Akismet API.
 * @return array Unmodified.
 */
function rv_sherpa_remember_akismet_parameters( $comment ) {
	$keep = array(
		'comment_type',
		'comment_author',
		'comment_author_email',
		'comment_author_url',
		'comment_content',
		'comment_date_gmt',
		'blog',
		'blog_lang',
		'blog_charset',
		'user_ip',
		'user_agent',
		'referrer',
		'permalink',
	);

	$GLOBALS['rv_sherpa_akismet_parameters'] = array_intersect_key(
		(array) $comment,
		array_flip( $keep )
	);

	return $comment;
}
add_filter( 'wpcf7_akismet_parameters', 'rv_sherpa_remember_akismet_parameters', PHP_INT_MAX, 1 );

/**
 * Hold a submission that CF7 rejected as spam.
 *
 * wpcf7_submit rather than wpcf7_spam: the filter runs while the verdict is
 * still being formed and other agents may yet have their say, whereas this
 * fires once, with the final status, after every check has run. That matters
 * because whether the row is held at all depends on which agents contributed
 * -- see RV_Sherpa_Dispatcher::capture_spam().
 *
 * @param WPCF7_ContactForm $form   Submitted form.
 * @param array             $result Submission result.
 */
function rv_sherpa_on_spam( $form, $result ) {
	if ( ! isset( $result['status'] ) || 'spam' !== $result['status'] ) {
		return;
	}

	if ( ! class_exists( 'WPCF7_Submission' ) ) {
		return;
	}

	$submission = WPCF7_Submission::get_instance();

	if ( ! $submission || ! method_exists( $submission, 'get_spam_log' ) ) {
		return;
	}

	$log = (array) $submission->get_spam_log();

	// Only Akismet's verdicts are reviewable. An empty log means the submission
	// was flagged by something that did not say who it was, which is not a
	// basis for storing someone's contact details.
	$agents = array_unique( array_map( fn( $e ) => strtolower( (string) ( $e['agent'] ?? '' ) ), $log ) );
	$hold   = $agents && array( 'akismet' ) === array_values( $agents );

	/**
	 * Filter whether a spam-judged submission is held for review.
	 *
	 * @param bool              $hold       Whether to hold it.
	 * @param array             $log        Spam log entries.
	 * @param WPCF7_ContactForm $form       Submitted form.
	 */
	if ( ! apply_filters( 'rv_sherpa_hold_spam', $hold, $log, $form ) ) {
		return;
	}

	// Same contract as rv_sherpa_on_mail_sent(): a problem here must never
	// change what the visitor sees, and there is nothing useful to do about it
	// in the request anyway.
	try {
		RV_Sherpa_Dispatcher::capture_spam(
			$form->id(),
			(array) $submission->get_posted_data(),
			$form->title(),
			array(
				'log'     => $log,
				'akismet' => $GLOBALS['rv_sherpa_akismet_parameters'] ?? array(),
			)
		);
	} catch ( Throwable $e ) {
		error_log( '[rv-sherpa] spam capture failed: ' . $e->getMessage() );
	}
}
add_action( 'wpcf7_submit', 'rv_sherpa_on_spam', 10, 2 );

// Delivery worker.
add_action( RV_Sherpa_Dispatcher::HOOK_SEND, array( 'RV_Sherpa_Dispatcher', 'handle_send' ), 10, 1 );

// Daily digest backstop.
add_action( RV_Sherpa_Dispatcher::HOOK_ALERT, array( 'RV_Sherpa_Dispatcher', 'handle_daily_alert' ) );

/**
 * Make sure the daily digest is scheduled.
 */
function rv_sherpa_schedule_daily_alert() {
	if ( ! function_exists( 'as_has_scheduled_action' ) || ! function_exists( 'as_schedule_recurring_action' ) ) {
		return;
	}

	if ( as_has_scheduled_action( RV_Sherpa_Dispatcher::HOOK_ALERT ) ) {
		return;
	}

	as_schedule_recurring_action(
		time() + HOUR_IN_SECONDS,
		DAY_IN_SECONDS,
		RV_Sherpa_Dispatcher::HOOK_ALERT,
		array(),
		RV_Sherpa_Dispatcher::GROUP
	);
}
add_action( 'init', 'rv_sherpa_schedule_daily_alert', 20 );
