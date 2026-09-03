<?php
/**
 * wp-admin screen for the lead store.
 *
 * This is what makes removing Flamingo safe. Flamingo was the only in-WordPress
 * record of a lead; without a replacement, a lead that failed to reach Sherpa
 * would be invisible and unrecoverable.
 *
 * It is NOT a lead archive, and the screen says so. Delivered leads show with
 * their contact columns erased, because that is what the retention policy in
 * class-store.php does to them the moment Sherpa accepts them. The rows that
 * still have details are the rows that still need a human.
 *
 * @package hello-elementor-child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class RV_Sherpa_Admin {

	const PAGE       = 'rv-sherpa-leads';
	const CAPABILITY = 'edit_pages';

	/**
	 * Register hooks.
	 */
	public static function init() {
		add_action( 'admin_menu', array( __CLASS__, 'menu' ) );
		add_action( 'admin_post_rv_sherpa_retry', array( __CLASS__, 'handle_retry' ) );
		add_action( 'admin_post_rv_sherpa_approve', array( __CLASS__, 'handle_approve' ) );
		add_action( 'admin_post_rv_sherpa_discard', array( __CLASS__, 'handle_discard' ) );
		add_action( 'admin_post_rv_sherpa_export', array( __CLASS__, 'handle_export' ) );
		add_action( 'admin_notices', array( __CLASS__, 'failure_notice' ) );
		add_action( 'admin_notices', array( __CLASS__, 'held_notice' ) );
	}

	/**
	 * Add the menu entry, badged with the outstanding failure count.
	 */
	public static function menu() {
		$counts = RV_Sherpa_Store::counts();
		$title  = 'CRM Leads';

		// Both states are waiting on a person: an undelivered lead needs
		// recovering, a held one needs judging. One badge, so neither is missed.
		$waiting = (int) ( $counts[ RV_Sherpa_Store::STATUS_FAILED ] ?? 0 )
			+ (int) ( $counts[ RV_Sherpa_Store::STATUS_SPAM ] ?? 0 );

		if ( $waiting ) {
			$title .= sprintf(
				' <span class="awaiting-mod"><span class="pending-count">%d</span></span>',
				$waiting
			);
		}

		add_menu_page(
			'Sherpa CRM Leads',
			$title,
			self::CAPABILITY,
			self::PAGE,
			array( __CLASS__, 'render' ),
			'dashicons-share-alt',
			26
		);
	}

	/**
	 * Persistent warning while any lead is undelivered.
	 */
	public static function failure_notice() {
		if ( ! current_user_can( self::CAPABILITY ) ) {
			return;
		}

		$counts = RV_Sherpa_Store::counts();
		$failed = (int) ( $counts[ RV_Sherpa_Store::STATUS_FAILED ] ?? 0 );

		if ( ! $failed ) {
			return;
		}

		printf(
			'<div class="notice notice-error"><p><strong>Sherpa CRM:</strong> %d lead(s) have not reached the CRM. They are stored and can be retried. <a href="%s">Review them</a>.</p></div>',
			$failed,
			esc_url( admin_url( 'admin.php?page=' . self::PAGE . '&status=failed' ) )
		);
	}

	/**
	 * Standing reminder that submissions are being held for judgement.
	 *
	 * Separate from the failure notice, and a warning rather than an error,
	 * because the two ask for different things. An undelivered lead is a fault
	 * to be fixed; a held one is a decision to be made, and most of them will
	 * rightly be discarded.
	 */
	public static function held_notice() {
		if ( ! current_user_can( self::CAPABILITY ) ) {
			return;
		}

		$counts = RV_Sherpa_Store::counts();
		$held   = (int) ( $counts[ RV_Sherpa_Store::STATUS_SPAM ] ?? 0 );

		if ( ! $held ) {
			return;
		}

		printf(
			'<div class="notice notice-warning"><p><strong>Sherpa CRM:</strong> %d submission(s) were rejected as spam and are being held for review. '
				. 'Spam filtering is not perfect, so one of them may be a real enquiry. <a href="%s">Check them</a>.</p></div>',
			$held,
			esc_url( admin_url( 'admin.php?page=' . self::PAGE . '&status=' . RV_Sherpa_Store::STATUS_SPAM ) )
		);
	}

	/**
	 * Retry a single lead.
	 */
	public static function handle_retry() {
		if ( ! current_user_can( self::CAPABILITY ) ) {
			wp_die( 'Insufficient permissions.' );
		}

		$lead_id = isset( $_GET['lead'] ) ? (int) $_GET['lead'] : 0;

		check_admin_referer( 'rv_sherpa_retry_' . $lead_id );

		if ( $lead_id ) {
			RV_Sherpa_Dispatcher::retry( $lead_id );
		}

		wp_safe_redirect( add_query_arg( 'retried', $lead_id, wp_get_referer() ?: admin_url( 'admin.php?page=' . self::PAGE ) ) );
		exit;
	}

	/**
	 * The enquiry text of a held submission, for the reviewer to read.
	 *
	 * referralNote only -- it is the single free-text field in the whole Sherpa
	 * payload, and the only thing here that is the enquirer's own words.
	 *
	 * An earlier version fell back to Akismet's comment_content when that was
	 * empty, on the theory that something was better than nothing. It is not:
	 * CF7 builds that field by concatenating every value it was not told is an
	 * author field, so on a form submitted with no message it produced
	 * "3434343535353 / Company Website / Internet / self / 1 / 20260903 /
	 * MTc4ODQ1MTgzNA==...", i.e. the phone number, three hidden routing
	 * constants, the consent tickbox, today's date and the honeypot's time
	 * token. That tells a reviewer nothing and puts internals on screen.
	 *
	 * Most forms have no message field at all -- the brochure requests, for one
	 * -- so returning nothing is the normal case, and the Contact column is
	 * what the decision rests on there.
	 *
	 * @param object $row Lead row.
	 * @return string Empty string when there is nothing to show.
	 */
	private static function held_message( $row ) {
		$payload = json_decode( (string) $row->payload, true );

		if ( is_array( $payload ) && ! empty( $payload['referralNote'] ) ) {
			return trim( (string) $payload['referralNote'] );
		}

		return '';
	}

	/**
	 * Whether a form even offers the enquirer somewhere to write.
	 *
	 * Read from the Sherpa config rather than the submission: a form has a
	 * message field exactly when its map sends something to referralNote, and
	 * two forms (23421, 25885) deliberately drop that mapping.
	 *
	 * @param int $form_id CF7 form ID.
	 * @return bool
	 */
	private static function form_has_message_field( $form_id ) {
		$config = function_exists( 'rv_sherpa_config' ) ? rv_sherpa_config() : array();
		$map    = (array) ( $config[ $form_id ]['map'] ?? array() );

		return in_array( 'referralNote', $map, true );
	}

	/**
	 * Why a submission is being held, in a sentence.
	 *
	 * Read from spam_context rather than last_error, which carries the mapper's
	 * routing warnings -- notably "sourceName was posted as 'Internet'; sent as
	 * 'Company Website'", which is on every single lead by design (see
	 * rv_sherpa_source_fields) and has nothing to do with the spam verdict.
	 *
	 * @param object $row Lead row.
	 * @return string
	 */
	private static function held_reason( $row ) {
		$context = json_decode( (string) $row->spam_context, true );
		$log     = is_array( $context ) ? (array) ( $context['log'] ?? array() ) : array();

		$agents = array();

		foreach ( $log as $entry ) {
			$agent = trim( (string) ( $entry['agent'] ?? '' ) );

			if ( '' !== $agent ) {
				$agents[] = 'akismet' === strtolower( $agent ) ? 'Akismet' : $agent;
			}
		}

		$agents = array_unique( $agents );

		if ( ! $agents ) {
			return 'Judged spam by the form\'s spam checks.';
		}

		return implode( ' and ', $agents ) . ' judged this submission spam.';
	}

	/**
	 * Release one held submission into the CRM.
	 */
	public static function handle_approve() {
		if ( ! current_user_can( self::CAPABILITY ) ) {
			wp_die( 'Insufficient permissions.' );
		}

		$lead_id = isset( $_GET['lead'] ) ? (int) $_GET['lead'] : 0;

		check_admin_referer( 'rv_sherpa_approve_' . $lead_id );

		$result = $lead_id ? RV_Sherpa_Dispatcher::approve_spam( $lead_id ) : 'no lead specified';

		self::redirect_with_notice(
			true === $result ? 'success' : 'error',
			true === $result
				? sprintf( 'Lead #%d has been sent to Sherpa CRM, and Akismet has been told it was not spam.', $lead_id )
				: sprintf( 'Lead #%d was not sent: %s.', $lead_id, $result )
		);
	}

	/**
	 * Discard one held submission.
	 */
	public static function handle_discard() {
		if ( ! current_user_can( self::CAPABILITY ) ) {
			wp_die( 'Insufficient permissions.' );
		}

		$lead_id = isset( $_GET['lead'] ) ? (int) $_GET['lead'] : 0;

		check_admin_referer( 'rv_sherpa_discard_' . $lead_id );

		$ok = $lead_id ? RV_Sherpa_Dispatcher::discard_spam( $lead_id ) : false;

		self::redirect_with_notice(
			$ok ? 'success' : 'error',
			$ok
				? sprintf( 'Held submission #%d has been discarded, and Akismet has been told the verdict was right.', $lead_id )
				: sprintf( 'Held submission #%d could not be discarded -- it may already have been actioned.', $lead_id )
		);
	}

	/**
	 * Send the user back to the list with a one-off message.
	 *
	 * The message travels in the URL rather than a transient because it is
	 * per-click feedback, not state: two people acting on the queue at once
	 * should each see their own result.
	 *
	 * @param string $type    success|error.
	 * @param string $message Message text.
	 */
	private static function redirect_with_notice( $type, $message ) {
		$back = wp_get_referer() ?: admin_url( 'admin.php?page=' . self::PAGE );

		wp_safe_redirect(
			add_query_arg(
				array(
					'rv_notice'      => $type,
					'rv_notice_text' => rawurlencode( $message ),
				),
				remove_query_arg( array( 'rv_notice', 'rv_notice_text' ), $back )
			)
		);
		exit;
	}

	/**
	 * Export the store as CSV.
	 */
	public static function handle_export() {
		if ( ! current_user_can( self::CAPABILITY ) ) {
			wp_die( 'Insufficient permissions.' );
		}

		check_admin_referer( 'rv_sherpa_export' );

		$status = isset( $_GET['status'] ) ? sanitize_key( $_GET['status'] ) : '';
		$rows   = RV_Sherpa_Store::query(
			array(
				'status' => $status,
				'limit'  => 100000,
			)
		);

		nocache_headers();
		header( 'Content-Type: text/csv; charset=utf-8' );
		header( 'Content-Disposition: attachment; filename=sherpa-leads-' . gmdate( 'Ymd-His' ) . '.csv' );

		$out = fopen( 'php://output', 'w' );

		fputcsv(
			$out,
			array(
				'id', 'created_at_utc', 'form_id', 'form', 'community', 'name',
				'email', 'phone', 'status', 'attempts', 'http_status', 'error',
				'crm_reference', 'redacted_at_utc',
			)
		);

		foreach ( $rows as $row ) {
			fputcsv(
				$out,
				array(
					$row->id,
					$row->created_at,
					$row->form_id,
					$row->form_title,
					RV_Sherpa_Router::community_name( $row->community_id ),
					$row->contact_name,
					$row->contact_email,
					$row->contact_phone,
					$row->status,
					$row->attempts,
					$row->http_status,
					$row->last_error,
					$row->crm_reference,
					$row->redacted_at,
				)
			);
		}

		fclose( $out );
		exit;
	}

	/**
	 * Render the list screen.
	 */
	public static function render() {
		if ( ! current_user_can( self::CAPABILITY ) ) {
			return;
		}

		$status = isset( $_GET['status'] ) ? sanitize_key( $_GET['status'] ) : '';
		$search = isset( $_GET['s'] ) ? sanitize_text_field( wp_unslash( $_GET['s'] ) ) : '';
		$paged  = max( 1, isset( $_GET['paged'] ) ? (int) $_GET['paged'] : 1 );
		$per    = 50;

		$counts = RV_Sherpa_Store::counts();
		$rows   = RV_Sherpa_Store::query(
			array(
				'status' => $status,
				'search' => $search,
				'limit'  => $per,
				'offset' => ( $paged - 1 ) * $per,
			)
		);

		$labels = array(
			RV_Sherpa_Store::STATUS_SENT      => 'Delivered',
			RV_Sherpa_Store::STATUS_DUPLICATE => 'Already in CRM',
			RV_Sherpa_Store::STATUS_PENDING   => 'Queued',
			RV_Sherpa_Store::STATUS_RETRYING  => 'Retrying',
			RV_Sherpa_Store::STATUS_FAILED    => 'Undelivered',
			RV_Sherpa_Store::STATUS_SPAM      => 'Held as spam',
			RV_Sherpa_Store::STATUS_DRY_RUN   => 'Dry run',
		);

		echo '<div class="wrap">';
		echo '<h1 class="wp-heading-inline">Sherpa CRM Leads</h1>';

		if ( ! empty( $_GET['rv_notice_text'] ) ) {
			printf(
				'<div class="notice notice-%s is-dismissible"><p>%s</p></div>',
				'success' === ( $_GET['rv_notice'] ?? '' ) ? 'success' : 'error',
				esc_html( rawurldecode( wp_unslash( $_GET['rv_notice_text'] ) ) )
			);
		}

		if ( RV_SHERPA_DRY_RUN ) {
			echo '<div class="notice notice-info inline"><p><strong>Dry run is active.</strong> '
				. 'Leads are captured and stored, and the request that would have been sent is recorded, '
				. 'but nothing is POSTed to Sherpa. Set <code>RV_SHERPA_DRY_RUN</code> to false in '
				. 'wp-config.php on production.</p></div>';
		}

		// Explain the blank columns before anyone reports them as a bug.
		$windows   = rv_sherpa_retention_days();
		$holding   = RV_Sherpa_Store::unredacted_count();
		$fail_days = (int) ( $windows[ RV_Sherpa_Store::STATUS_FAILED ] ?? 0 );

		printf(
			'<div class="notice notice-info inline"><p><strong>This is a delivery record, not a lead archive.</strong> '
				. 'A lead\'s contact details are erased as soon as Sherpa accepts it -- Sherpa is the system of '
				. 'record from that point. Details are kept only for leads that did <em>not</em> arrive, because '
				. 'those need recovering by hand, and are erased after %d days. '
				. '<strong>%d</strong> row(s) currently hold personal data.</p></div>',
			$fail_days,
			$holding
		);

		if ( RV_Sherpa_Store::STATUS_SPAM === $status || ! empty( $counts[ RV_Sherpa_Store::STATUS_SPAM ] ) ) {
			printf(
				'<div class="notice notice-warning inline"><p><strong>Held submissions.</strong> '
					. 'These were rejected as spam by Akismet, so they never reached the CRM and no notification was sent. '
					. 'They are kept because that judgement is not always right -- read the enquiry and decide. '
					. '<em>Send to CRM</em> delivers it and tells Akismet it was wrong; <em>Discard</em> deletes it and '
					. 'confirms the verdict. Either way Akismet learns, so the queue should get quieter. '
					. 'Anything still held after %d days is erased by the retention sweep.</p></div>',
				(int) ( $windows[ RV_Sherpa_Store::STATUS_SPAM ] ?? 30 )
			);
		}

		if ( '' === rv_sherpa_token() && ! RV_SHERPA_DRY_RUN ) {
			echo '<div class="notice notice-error inline"><p><strong>No API token.</strong> '
				. 'Define <code>SHERPA_API_TOKEN</code> in wp-config.php. Leads are still being stored, '
				. 'so nothing is being lost -- but nothing is reaching the CRM.</p></div>';
		}

		// Status filter links.
		echo '<ul class="subsubsub">';
		$total = array_sum( $counts );
		$links = array( '' => 'All (' . $total . ')' );

		foreach ( $labels as $key => $label ) {
			if ( ! empty( $counts[ $key ] ) ) {
				$links[ $key ] = $label . ' (' . (int) $counts[ $key ] . ')';
			}
		}

		$i = 0;
		foreach ( $links as $key => $label ) {
			$url = admin_url( 'admin.php?page=' . self::PAGE . ( $key ? '&status=' . $key : '' ) );
			printf(
				'<li>%s<a href="%s"%s>%s</a></li>',
				$i++ ? ' | ' : '',
				esc_url( $url ),
				$status === $key ? ' class="current"' : '',
				esc_html( $label )
			);
		}
		echo '</ul>';

		// Search + export.
		echo '<form method="get" style="margin:12px 0;">';
		printf( '<input type="hidden" name="page" value="%s">', esc_attr( self::PAGE ) );
		printf( '<input type="hidden" name="status" value="%s">', esc_attr( $status ) );
		printf(
			'<input type="search" name="s" value="%s" placeholder="Search name, email or phone">',
			esc_attr( $search )
		);
		echo '<input type="submit" class="button" value="Search"> ';
		printf(
			'<a class="button" href="%s">Export CSV</a>',
			esc_url(
				wp_nonce_url(
					admin_url( 'admin-post.php?action=rv_sherpa_export&status=' . $status ),
					'rv_sherpa_export'
				)
			)
		);
		echo '</form>';

		echo '<table class="wp-list-table widefat fixed striped">';
		echo '<thead><tr>'
			. '<th style="width:60px">ID</th>'
			. '<th style="width:140px">Received (UTC)</th>'
			. '<th>Contact</th>'
			. '<th style="width:150px">Form</th>'
			. '<th style="width:130px">Community</th>'
			. '<th style="width:120px">Status</th>'
			. '<th>Detail</th>'
			. '</tr></thead><tbody>';

		if ( ! $rows ) {
			echo '<tr><td colspan="7">No leads recorded yet.</td></tr>';
		}

		foreach ( $rows as $row ) {
			$is_bad  = in_array(
				$row->status,
				array( RV_Sherpa_Store::STATUS_FAILED, RV_Sherpa_Store::STATUS_RETRYING ),
				true
			);
			$is_held = RV_Sherpa_Store::STATUS_SPAM === $row->status;

			echo '<tr>';
			printf( '<td>%d</td>', (int) $row->id );
			printf( '<td>%s</td>', esc_html( $row->created_at ) );

			if ( ! empty( $row->redacted_at ) ) {
				printf(
					'<td><span class="description" title="Erased %s UTC">&mdash; erased &mdash;</span></td>',
					esc_attr( $row->redacted_at )
				);
			} else {
				printf(
					'<td><strong>%s</strong><br><span class="description">%s%s</span></td>',
					esc_html( $row->contact_name ?: '(no name)' ),
					esc_html( $row->contact_email ?: '' ),
					$row->contact_phone ? '<br>' . esc_html( $row->contact_phone ) : ''
				);
			}

			printf(
				'<td>%s<br><span class="description">#%d</span></td>',
				esc_html( $row->form_title ),
				(int) $row->form_id
			);

			// community_id 0 means routing failed, and "Community 0" reads like a
			// real place. Say what it is.
			printf(
				'<td>%s</td>',
				$row->community_id
					? esc_html( RV_Sherpa_Router::community_name( $row->community_id ) )
					: '<span class="description">&mdash; not resolved &mdash;</span>'
			);

			printf(
				'<td><span style="%s">%s</span>%s</td>',
				$is_bad ? 'color:#b32d2e;font-weight:600' : ( $is_held ? 'color:#996800;font-weight:600' : '' ),
				esc_html( $labels[ $row->status ] ?? $row->status ),
				$row->attempts > 1 ? '<br><span class="description">' . (int) $row->attempts . ' attempts</span>' : ''
			);

			echo '<td>';
			if ( $row->http_status ) {
				printf( '<code>HTTP %d</code> ', (int) $row->http_status );
			}
			if ( $row->last_error && ! $is_held ) {
				printf( '<span class="description">%s</span>', esc_html( wp_trim_words( $row->last_error, 20 ) ) );
			}
			if ( $row->crm_reference ) {
				printf( '<span class="description">Sherpa ref %s</span>', esc_html( $row->crm_reference ) );
			}
			if ( $is_bad && ! empty( $row->redacted_at ) ) {
				// Nothing left to send -- see RV_Sherpa_Dispatcher::retry().
				echo '<br><span class="description">Past retention; no longer recoverable here.</span>';
			}
			if ( $is_bad && empty( $row->redacted_at ) ) {
				printf(
					'<br><a class="button button-small" href="%s">Retry now</a>',
					esc_url(
						wp_nonce_url(
							admin_url( 'admin-post.php?action=rv_sherpa_retry&lead=' . (int) $row->id ),
							'rv_sherpa_retry_' . (int) $row->id
						)
					)
				);
			}
			if ( $is_held && empty( $row->redacted_at ) ) {
				printf( '<span class="description">%s</span>', esc_html( self::held_reason( $row ) ) );

				// The one thing that stops "Send to CRM" working, so say it
				// here rather than letting the click fail.
				if ( '' === $row->endpoint ) {
					printf(
						'<br><span style="color:#b32d2e">%s</span>',
						esc_html( 'No village resolved, so this cannot be sent to the CRM as it stands: ' . $row->last_error )
					);
				}

				// What the person actually wrote, where there is a message
				// field at all. Nothing else distinguishes a real enquiry from
				// a bot as quickly, so it is shown rather than hidden behind a
				// link.
				$message = self::held_message( $row );

				if ( '' !== $message ) {
					printf(
						'<blockquote style="margin:6px 0;padding:6px 10px;border-left:3px solid #dcdcde;color:#50575e">%s</blockquote>',
						esc_html( wp_trim_words( $message, 45 ) )
					);
				} else {
					// "Blank" and "this form never had one" look identical on the
					// row but mean different things to a reviewer: an enquiry
					// form submitted with nothing written in it is itself a
					// signal, whereas a brochure request has no box to write in.
					echo '<br><span class="description"><em>'
						. ( self::form_has_message_field( (int) $row->form_id )
							? 'Message left blank.'
							: 'This form has no message field.' )
						. '</em></span>';
				}

				echo '<br>';

				printf(
					'<a class="button button-small button-primary" href="%s">Send to CRM</a> ',
					esc_url(
						wp_nonce_url(
							admin_url( 'admin-post.php?action=rv_sherpa_approve&lead=' . (int) $row->id ),
							'rv_sherpa_approve_' . (int) $row->id
						)
					)
				);
				printf(
					'<a class="button button-small" href="%s" onclick="return confirm(\'Discard this submission? It will be deleted.\');">Discard</a>',
					esc_url(
						wp_nonce_url(
							admin_url( 'admin-post.php?action=rv_sherpa_discard&lead=' . (int) $row->id ),
							'rv_sherpa_discard_' . (int) $row->id
						)
					)
				);
			}
			echo '</td>';
			echo '</tr>';
		}

		echo '</tbody></table>';
		echo '</div>';
	}
}

RV_Sherpa_Admin::init();
