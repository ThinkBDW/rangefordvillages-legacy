<?php
/**
 * wp-admin screen for the lead store.
 *
 * This is what makes removing Flamingo safe. Flamingo was the only in-WordPress
 * record of a lead; without a replacement, a lead that failed to reach Sherpa
 * would be invisible and unrecoverable.
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
		add_action( 'admin_post_rv_sherpa_export', array( __CLASS__, 'handle_export' ) );
		add_action( 'admin_notices', array( __CLASS__, 'failure_notice' ) );
	}

	/**
	 * Add the menu entry, badged with the outstanding failure count.
	 */
	public static function menu() {
		$counts  = RV_Sherpa_Store::counts();
		$failed  = (int) ( $counts[ RV_Sherpa_Store::STATUS_FAILED ] ?? 0 );
		$title   = 'CRM Leads';

		if ( $failed ) {
			$title .= sprintf(
				' <span class="awaiting-mod"><span class="pending-count">%d</span></span>',
				$failed
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
			RV_Sherpa_Store::STATUS_DRY_RUN   => 'Dry run',
		);

		echo '<div class="wrap">';
		echo '<h1 class="wp-heading-inline">Sherpa CRM Leads</h1>';

		if ( RV_SHERPA_DRY_RUN ) {
			echo '<div class="notice notice-info inline"><p><strong>Dry run is active.</strong> '
				. 'Leads are captured and stored, and the request that would have been sent is recorded, '
				. 'but nothing is POSTed to Sherpa. Set <code>RV_SHERPA_DRY_RUN</code> to false in '
				. 'wp-config.php on production.</p></div>';
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
			$is_bad = in_array(
				$row->status,
				array( RV_Sherpa_Store::STATUS_FAILED, RV_Sherpa_Store::STATUS_RETRYING ),
				true
			);

			echo '<tr>';
			printf( '<td>%d</td>', (int) $row->id );
			printf( '<td>%s</td>', esc_html( $row->created_at ) );

			printf(
				'<td><strong>%s</strong><br><span class="description">%s%s</span></td>',
				esc_html( $row->contact_name ?: '(no name)' ),
				esc_html( $row->contact_email ?: '' ),
				$row->contact_phone ? '<br>' . esc_html( $row->contact_phone ) : ''
			);

			printf(
				'<td>%s<br><span class="description">#%d</span></td>',
				esc_html( $row->form_title ),
				(int) $row->form_id
			);

			printf( '<td>%s</td>', esc_html( RV_Sherpa_Router::community_name( $row->community_id ) ) );

			printf(
				'<td><span style="%s">%s</span>%s</td>',
				$is_bad ? 'color:#b32d2e;font-weight:600' : '',
				esc_html( $labels[ $row->status ] ?? $row->status ),
				$row->attempts > 1 ? '<br><span class="description">' . (int) $row->attempts . ' attempts</span>' : ''
			);

			echo '<td>';
			if ( $row->http_status ) {
				printf( '<code>HTTP %d</code> ', (int) $row->http_status );
			}
			if ( $row->last_error ) {
				printf( '<span class="description">%s</span>', esc_html( wp_trim_words( $row->last_error, 20 ) ) );
			}
			if ( $is_bad ) {
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
			echo '</td>';
			echo '</tr>';
		}

		echo '</tbody></table>';
		echo '</div>';
	}
}

RV_Sherpa_Admin::init();
