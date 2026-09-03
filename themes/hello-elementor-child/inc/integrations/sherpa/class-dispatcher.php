<?php
/**
 * Queue, send and retry leads.
 *
 * The ordering here is the whole point: capture() commits the lead to the
 * database and only then schedules delivery. If Action Scheduler is broken, if
 * Sherpa is down, if this file fatals -- the lead is already safe and can be
 * retried from the admin screen.
 *
 * Retry exists because the inherited integration had none. wp_cf7anyapi_logs
 * shows 69 leads in 2026 receiving HTTP 500 and being discarded, spread evenly
 * across every month. A bounded backoff would have recovered most of them.
 *
 * The mirror image of that ordering is when the data goes away again: a lead
 * that reaches Sherpa is anonymised in the same request, before this function
 * returns. Only a lead that did NOT reach Sherpa keeps its personal data, and
 * only because it is then the sole remaining copy. See class-store.php.
 *
 * @package hello-elementor-child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class RV_Sherpa_Dispatcher {

	const HOOK_SEND  = 'rv_sherpa_send_lead';
	const HOOK_ALERT = 'rv_sherpa_daily_alert';
	const GROUP      = 'rv-sherpa';

	/**
	 * Backoff schedule, in seconds. One entry per retry after the first attempt.
	 *
	 * Spread over ~15 hours, which covers a long CRM outage without hammering
	 * it, and leaves a same-day window for a human to notice.
	 *
	 * @var int[]
	 */
	const BACKOFF = array( 60, 300, 1800, 7200, 43200 );

	/**
	 * Map, route and describe a submission, without deciding what to do with it.
	 *
	 * Split out of capture() so that a submission judged spam is prepared exactly
	 * the same way as an accepted one. That equivalence is the point: a held spam
	 * row has to be a fully-formed, sendable lead, because the whole reason it is
	 * kept is that someone may decide it was a real enquiry and release it. If
	 * the mapping were deferred until release, a false positive would sit in the
	 * queue for days before anyone discovered it could not be mapped at all.
	 *
	 * @param int    $form_id     CF7 form ID.
	 * @param array  $posted_data CF7 posted data.
	 * @param string $form_title  Form title, for the admin list.
	 * @return array|false row/endpoint/payload/route, or false when the form is
	 *                     not a configured lead form.
	 */
	private static function prepare( $form_id, array $posted_data, $form_title = '' ) {
		$form_id = (int) $form_id;
		$config  = rv_sherpa_config();

		// Not every form is a lead -- form 11060 is a newsletter signup.
		if ( ! isset( $config[ $form_id ] ) ) {
			return false;
		}

		$route = RV_Sherpa_Router::resolve( $form_id, $posted_data );
		$built = RV_Sherpa_Mapper::build( $form_id, $posted_data );

		$contact  = RV_Sherpa_Mapper::contact_summary( $built['payload'] );
		$warnings = $built['warnings'];

		if ( null === $route['community'] ) {
			$warnings[] = 'unroutable: ' . $route['reason'];
			$endpoint   = '';
		} else {
			$endpoint = RV_Sherpa_Router::endpoint( $route['community'] );
		}

		return array(
			'endpoint' => $endpoint,
			'payload'  => $built['payload'],
			'route'    => $route,
			'row'      => array(
				'form_id'       => $form_id,
				'form_title'    => $form_title,
				'community_id'  => (int) $route['community'],
				'contact_name'  => $contact['name'],
				'contact_email' => $contact['email'],
				'contact_phone' => $contact['phone'],
				'endpoint'      => $endpoint,
				'payload'       => wp_json_encode( $built['payload'] ),
				'last_error'    => $warnings ? implode( ' | ', $warnings ) : null,
			),
		);
	}

	/**
	 * Capture a lead and schedule delivery.
	 *
	 * @param int   $form_id     CF7 form ID.
	 * @param array $posted_data CF7 posted data.
	 * @param string $form_title Form title, for the admin list.
	 * @return int|false Lead ID, or false when the form is not configured.
	 */
	public static function capture( $form_id, array $posted_data, $form_title = '' ) {
		$form_id  = (int) $form_id;
		$prepared = self::prepare( $form_id, $posted_data, $form_title );

		if ( ! $prepared ) {
			return false;
		}

		$endpoint = $prepared['endpoint'];
		$dry_run  = (bool) apply_filters( 'rv_sherpa_dry_run', RV_SHERPA_DRY_RUN, $form_id );

		$lead_id = RV_Sherpa_Store::insert(
			$prepared['row'] + array(
				'status' => $dry_run ? RV_Sherpa_Store::STATUS_DRY_RUN : RV_Sherpa_Store::STATUS_PENDING,
			)
		);

		if ( ! $lead_id ) {
			// Last resort: if even the insert failed, get the lead into the log
			// so it is recoverable from the filesystem.
			//
			// This is the one place that writes lead data somewhere the
			// retention policy cannot reach it, and it is a deliberate
			// exception: the insert failing means this log line is the only
			// copy of the enquiry that exists anywhere. A lost lead is worse
			// than a line in debug.log. If it ever fires, the log entry should
			// be actioned and removed by hand.
			error_log(
				sprintf(
					'[rv-sherpa] FAILED TO STORE LEAD form=%d routing=%s payload=%s',
					$form_id,
					$prepared['route']['reason'],
					wp_json_encode( $prepared['payload'] )
				)
			);
			return false;
		}

		if ( $dry_run ) {
			return $lead_id;
		}

		if ( '' === $endpoint ) {
			RV_Sherpa_Store::update(
				$lead_id,
				array( 'status' => RV_Sherpa_Store::STATUS_FAILED )
			);
			return $lead_id;
		}

		self::schedule( $lead_id, 0 );

		return $lead_id;
	}

	/**
	 * Hold a submission that was judged spam, instead of losing it.
	 *
	 * WHY THIS EXISTS. Spam protection on this site is Akismet plus the honeypot,
	 * and Akismet fails CLOSED: a spam verdict aborts the submission, so no lead
	 * is captured, no notification is sent, and the visitor is told only that
	 * "there was an error -- please try again later". Nothing is written down
	 * anywhere. Flamingo is not installed, so before this existed a false
	 * positive was an enquiry that silently never happened.
	 *
	 * That is not a theoretical risk. Akismet was observed on 2026-09-03
	 * rejecting every submission from this site for an extended period --
	 * identical wording that it had accepted minutes earlier, regardless of the
	 * blog URL or the visitor IP sent with the check. Its verdict on a given
	 * submission is not reproducible, and it is now the only substantive layer.
	 *
	 * So a held row is a complete, sendable lead that simply has not been sent.
	 * A human releases it or discards it from the CRM Leads screen.
	 *
	 * WHAT IS NOT HELD. A verdict that any agent other than Akismet contributed
	 * to -- in practice the honeypot -- is dropped as before. The honeypot is
	 * deterministic: it is tripped by filling a field that is invisible to
	 * people, or by submitting faster than a person can type. It does not have
	 * false positives worth a review queue, and holding what it catches would
	 * bury the Akismet cases in bot traffic while accumulating personal data
	 * from actual spammers. Same reasoning as the test allowlist in
	 * audit/scripts/rv-cf7-test-allowlist.php.
	 *
	 * @param int    $form_id     CF7 form ID.
	 * @param array  $posted_data CF7 posted data.
	 * @param string $form_title  Form title, for the admin list.
	 * @param array  $context     spam log entries and the Akismet parameters.
	 * @return int|false Lead ID, or false when nothing was held.
	 */
	public static function capture_spam( $form_id, array $posted_data, $form_title = '', array $context = array() ) {
		$prepared = self::prepare( (int) $form_id, $posted_data, $form_title );

		if ( ! $prepared ) {
			return false;
		}

		$row = $prepared['row'];

		// last_error keeps the mapper's routing warnings and nothing else. The
		// spam verdict goes in spam_context, which is where the admin screen
		// reads it from: folding the two together produced rows whose Detail
		// column read "sourceName was posted as 'Internet'; sent as 'Company
		// Website' | akismet: Akismet returns a spam response." -- one routine
		// note that is on every lead by design, welded to the only sentence
		// that actually explains why the row is here.
		$row['status']       = RV_Sherpa_Store::STATUS_SPAM;
		$row['spam_context'] = wp_json_encode( $context );

		$lead_id = RV_Sherpa_Store::insert( $row );

		if ( ! $lead_id ) {
			// Same last-resort as capture(): a lead nobody can see is worse than a
			// line in the log.
			error_log(
				sprintf(
					'[rv-sherpa] FAILED TO STORE SPAM-HELD LEAD form=%d payload=%s',
					(int) $form_id,
					wp_json_encode( $prepared['payload'] )
				)
			);
			return false;
		}

		// Nothing is scheduled. A held row is terminal until a human acts on it.
		return $lead_id;
	}

	/**
	 * Release a held lead into the CRM.
	 *
	 * @param int $lead_id Lead ID.
	 * @return true|string true, or a reason it could not be released.
	 */
	public static function approve_spam( $lead_id ) {
		$lead = RV_Sherpa_Store::get( $lead_id );

		if ( ! $lead || RV_Sherpa_Store::STATUS_SPAM !== $lead->status ) {
			return 'that lead is not being held';
		}

		if ( ! empty( $lead->redacted_at ) ) {
			return 'the details were erased by the retention sweep, so there is nothing left to send';
		}

		// Tell Akismet it got this one wrong before doing anything else. This is
		// the only moment the correction is known, and reporting it is what stops
		// the same enquirer being rejected again next time.
		self::report_to_akismet( $lead, 'submit-ham' );

		if ( '' === $lead->endpoint ) {
			// Routable leads are the normal case; an unroutable one becomes an
			// ordinary undelivered lead so it shows up in the failure count
			// rather than disappearing from the held list into nothing.
			RV_Sherpa_Store::update( $lead_id, array( 'status' => RV_Sherpa_Store::STATUS_FAILED ) );
			return 'the lead has no community to send to; it is now listed as undelivered';
		}

		$dry_run = (bool) apply_filters( 'rv_sherpa_dry_run', RV_SHERPA_DRY_RUN, (int) $lead->form_id );

		RV_Sherpa_Store::update(
			$lead_id,
			array(
				'attempts' => 0,
				'status'   => $dry_run ? RV_Sherpa_Store::STATUS_DRY_RUN : RV_Sherpa_Store::STATUS_PENDING,
			)
		);

		if ( $dry_run ) {
			return true;
		}

		self::schedule( $lead_id, 0 );

		return true;
	}

	/**
	 * Discard a held lead.
	 *
	 * @param int $lead_id Lead ID.
	 * @return bool
	 */
	public static function discard_spam( $lead_id ) {
		$lead = RV_Sherpa_Store::get( $lead_id );

		if ( ! $lead || RV_Sherpa_Store::STATUS_SPAM !== $lead->status ) {
			return false;
		}

		// Confirming a correct verdict is worth as much as correcting a wrong one:
		// both are what keep the key's judgement on this site accurate.
		self::report_to_akismet( $lead, 'submit-spam' );

		return RV_Sherpa_Store::delete( $lead_id );
	}

	/**
	 * Report a human's decision back to Akismet.
	 *
	 * Uses the low-level endpoint rather than Akismet::submit_ham(), which takes
	 * a wp_comments row ID -- these submissions are not comments and never touch
	 * that table. The parameters are the ones the comment-check was actually made
	 * with, stashed at the time by rv_sherpa_remember_akismet_parameters().
	 *
	 * Deliberately best-effort. A CRM release must not fail because Akismet is
	 * unreachable, so everything here is swallowed and logged.
	 *
	 * @param object $lead     Lead row.
	 * @param string $endpoint 'submit-ham' or 'submit-spam'.
	 */
	private static function report_to_akismet( $lead, $endpoint ) {
		if ( ! class_exists( 'Akismet' ) || ! method_exists( 'Akismet', 'http_post' ) ) {
			return;
		}

		$context = json_decode( (string) $lead->spam_context, true );
		$params  = is_array( $context ) ? ( $context['akismet'] ?? array() ) : array();

		if ( ! is_array( $params ) || ! $params ) {
			return;
		}

		try {
			Akismet::http_post( Akismet::build_query( $params ), $endpoint );
		} catch ( Throwable $e ) {
			error_log( '[rv-sherpa] ' . $endpoint . ' failed for lead ' . (int) $lead->id . ': ' . $e->getMessage() );
		}
	}

	/**
	 * Schedule a delivery attempt.
	 *
	 * @param int $lead_id Lead ID.
	 * @param int $delay   Seconds from now.
	 */
	public static function schedule( $lead_id, $delay = 0 ) {
		$args = array( 'lead_id' => (int) $lead_id );

		// Action Scheduler ships with The Events Calendar, which is a KEEP.
		// Fall back to WP-Cron if it ever goes away.
		if ( function_exists( 'as_schedule_single_action' ) ) {
			as_schedule_single_action( time() + (int) $delay, self::HOOK_SEND, $args, self::GROUP );
			return;
		}

		wp_schedule_single_event( time() + (int) $delay, self::HOOK_SEND, array( $args ) );
	}

	/**
	 * Deliver one lead. Bound to HOOK_SEND.
	 *
	 * @param array|int $args Action arguments.
	 */
	public static function handle_send( $args ) {
		$lead_id = is_array( $args ) ? (int) ( $args['lead_id'] ?? 0 ) : (int) $args;
		$lead    = $lead_id ? RV_Sherpa_Store::get( $lead_id ) : null;

		if ( ! $lead ) {
			return;
		}

		// Already delivered -- a duplicate action firing must not re-POST.
		if ( in_array(
			$lead->status,
			array( RV_Sherpa_Store::STATUS_SENT, RV_Sherpa_Store::STATUS_DUPLICATE ),
			true
		) ) {
			return;
		}

		$payload  = json_decode( (string) $lead->payload, true );
		$attempts = (int) $lead->attempts + 1;

		if ( ! is_array( $payload ) ) {
			RV_Sherpa_Store::update(
				$lead_id,
				array(
					'status'     => RV_Sherpa_Store::STATUS_FAILED,
					'attempts'   => $attempts,
					'last_error' => 'stored payload is not valid JSON',
				)
			);
			return;
		}

		$result = RV_Sherpa_Client::send( $lead->endpoint, $payload );

		$update = array(
			'attempts'    => $attempts,
			'http_status' => $result['http_status'],
			'response'    => substr( (string) $result['body'], 0, 65535 ),
			'last_error'  => $result['error'] ? $result['error'] : null,
		);

		if ( $result['ok'] ) {
			$update['status']          = $result['duplicate']
				? RV_Sherpa_Store::STATUS_DUPLICATE
				: RV_Sherpa_Store::STATUS_SENT;
			$update['next_attempt_at'] = null;

			// Keep the pointer to where the data now lives before erasing our
			// copy of it. A Sherpa lead ID is not personal data on its own, and
			// without it an anonymised row cannot be tied back to the CRM record
			// it produced -- which is the one thing reconciliation needs.
			$update['crm_reference'] = self::extract_reference( $result['body'] );

			RV_Sherpa_Store::update( $lead_id, $update );

			// The lead is in the CRM. The CRM is the system of record, so this
			// row has no further reason to hold anybody's contact details.
			if ( rv_sherpa_anonymise_on_delivery( (int) $lead->form_id ) ) {
				RV_Sherpa_Store::anonymise( $lead_id );
			}

			return;
		}

		$can_retry = $result['retryable'] && $attempts <= count( self::BACKOFF );

		if ( $can_retry ) {
			$delay = self::BACKOFF[ $attempts - 1 ];

			$update['status']          = RV_Sherpa_Store::STATUS_RETRYING;
			$update['next_attempt_at'] = gmdate( 'Y-m-d H:i:s', time() + $delay );

			RV_Sherpa_Store::update( $lead_id, $update );
			self::schedule( $lead_id, $delay );
			return;
		}

		$update['status']          = RV_Sherpa_Store::STATUS_FAILED;
		$update['next_attempt_at'] = null;

		RV_Sherpa_Store::update( $lead_id, $update );

		self::alert_failure( RV_Sherpa_Store::get( $lead_id ) );
	}

	/**
	 * Pull Sherpa's own identifier for the created lead out of the response.
	 *
	 * Sherpa is not consistent about where it puts this -- a create returns the
	 * new record, a 409 duplicate returns the existing one -- so try the shapes
	 * that have been observed and give up quietly rather than guessing.
	 *
	 * @param string $body Response body.
	 * @return string Empty string when there is nothing to take.
	 */
	private static function extract_reference( $body ) {
		$decoded = json_decode( (string) $body, true );

		if ( ! is_array( $decoded ) ) {
			return '';
		}

		$candidates = array(
			$decoded['id'] ?? null,
			$decoded['leadId'] ?? null,
			$decoded['leadID'] ?? null,
			$decoded['data']['id'] ?? null,
			$decoded['data']['leadId'] ?? null,
			$decoded['lead']['id'] ?? null,
			$decoded['error']['lead']['id'] ?? null,
		);

		foreach ( $candidates as $candidate ) {
			if ( is_scalar( $candidate ) && '' !== (string) $candidate ) {
				return substr( (string) $candidate, 0, 64 );
			}
		}

		return '';
	}

	/**
	 * Email on a permanent failure.
	 *
	 * Immediate rather than batched: a lost enquiry for a retirement village is
	 * worth an interruption, and the whole reason the old integration's 500s
	 * went unnoticed for months is that nothing ever raised its hand.
	 *
	 * Deliberately carries no contact details. The point of the retention policy
	 * is to stop lead data spreading, and email is the worst place for it to go:
	 * it lands in mailboxes nobody controls, and on this site the email-log
	 * plugin has been storing every outgoing message in the database -- 7,209 of
	 * them so far. The alert says which lead, and links to it.
	 *
	 * @param object|null $lead Lead row.
	 */
	public static function alert_failure( $lead ) {
		if ( ! $lead ) {
			return;
		}

		/**
		 * Filter the failure alert recipient.
		 *
		 * @param string $email Recipient.
		 */
		$to = apply_filters( 'rv_sherpa_alert_email', get_option( 'admin_email' ) );

		if ( ! $to ) {
			return;
		}

		$subject = sprintf(
			'[%s] Sherpa CRM: lead #%d could not be delivered',
			wp_specialchars_decode( get_bloginfo( 'name' ), ENT_QUOTES ),
			$lead->id
		);

		$body = implode(
			"\n",
			array(
				'A form submission could not be delivered to Sherpa CRM after ' . (int) $lead->attempts . ' attempts.',
				'',
				'The lead is stored and can be retried from wp-admin, so nothing has been lost:',
				admin_url( 'admin.php?page=rv-sherpa-leads' ),
				'',
				'Lead ID:    ' . $lead->id,
				'Form:       ' . $lead->form_title . ' (#' . $lead->form_id . ')',
				'Community:  ' . RV_Sherpa_Router::community_name( $lead->community_id ),
				'',
				'HTTP status: ' . ( $lead->http_status ?: 'no response' ),
				'Error:       ' . $lead->last_error,
			)
		);

		wp_mail( $to, $subject, $body );
	}

	/**
	 * Daily digest, as a backstop for missed individual alerts.
	 *
	 * Also runs the retention sweep. The two belong together: the sweep is what
	 * eventually erases an undelivered lead, and the digest is the warning that
	 * one is sitting there waiting to be erased. Sweeping first would be wrong
	 * -- report on the day's failures, then age out the old ones.
	 */
	public static function handle_daily_alert() {
		$failures = RV_Sherpa_Store::recent_failures( 24 );

		self::run_retention_sweep();

		if ( ! $failures ) {
			return;
		}

		$to = apply_filters( 'rv_sherpa_alert_email', get_option( 'admin_email' ) );

		if ( ! $to ) {
			return;
		}

		$lines = array(
			sprintf( '%d lead(s) failed to reach Sherpa CRM in the last 24 hours.', count( $failures ) ),
			'',
			'All of them are stored and retryable:',
			admin_url( 'admin.php?page=rv-sherpa-leads&status=failed' ),
			'',
		);

		// No contact details here either -- see alert_failure().
		foreach ( $failures as $lead ) {
			$lines[] = sprintf(
				'#%d  %s  %s  HTTP %s',
				$lead->id,
				$lead->form_title,
				RV_Sherpa_Router::community_name( $lead->community_id ),
				$lead->http_status ?: '-'
			);
		}

		wp_mail(
			$to,
			sprintf(
				'[%s] Sherpa CRM: %d undelivered lead(s)',
				wp_specialchars_decode( get_bloginfo( 'name' ), ENT_QUOTES ),
				count( $failures )
			),
			implode( "\n", $lines )
		);
	}

	/**
	 * Age out personal data that is past its retention window.
	 *
	 * @return int Rows anonymised.
	 */
	public static function run_retention_sweep() {
		$done = RV_Sherpa_Store::anonymise_stale( rv_sherpa_retention_days() );

		if ( $done ) {
			// Worth a line in the log: this is the only record that data was
			// erased, and "why has that lead gone blank" is a question someone
			// will eventually ask.
			error_log( sprintf( '[rv-sherpa] retention sweep anonymised %d lead(s)', $done ) );
		}

		return $done;
	}

	/**
	 * Requeue a lead from the admin screen.
	 *
	 * @param int $lead_id Lead ID.
	 * @return bool
	 */
	public static function retry( $lead_id ) {
		$lead = RV_Sherpa_Store::get( $lead_id );

		if ( ! $lead ) {
			return false;
		}

		// An anonymised lead has no payload left to send. Retrying it would
		// POST nothing and then mark itself failed a second time.
		if ( ! empty( $lead->redacted_at ) ) {
			return false;
		}

		RV_Sherpa_Store::update(
			$lead_id,
			array(
				// Reset the counter so a manual retry gets a full backoff run.
				'attempts' => 0,
				'status'   => RV_Sherpa_Store::STATUS_PENDING,
			)
		);

		self::schedule( $lead_id, 0 );

		return true;
	}
}
