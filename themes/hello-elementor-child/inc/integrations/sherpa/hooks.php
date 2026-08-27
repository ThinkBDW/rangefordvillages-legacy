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
