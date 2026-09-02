<?php
/**
 * Durable lead store, with retention.
 *
 * Two requirements that pull against each other, and how they are reconciled.
 *
 * LOSSLESS. Every lead is written here BEFORE any HTTP call is attempted. A CRM
 * outage, a broken queue, a fatal in the dispatcher or a rollback of the site
 * cannot lose an enquiry, because the enquiry is already committed. That is the
 * whole reason the table exists: the integration this replaces discarded
 * leads on a bare HTTP 500, with no retry and no alert.
 *
 * MINIMAL. WordPress is not to be a second, permanent copy
 * of the enquiry book. Once a lead is in Sherpa, Sherpa is the system of record,
 * and there is no business reason to keep the enquirer's name, email, phone and
 * address sitting in a database that is dumped to a developer laptop on every
 * pull.
 *
 * The two are reconciled by writing the personal data, using it, and erasing it
 * as soon as it is no longer needed:
 *
 *   delivered (sent / duplicate)  anonymised immediately, in the same request
 *   undelivered (failed)          RETAINED -- it is the only copy left and a
 *                                 human still has to recover it; anonymised
 *                                 once the retention window expires
 *   dry-run / skipped             anonymised after a short window
 *
 * What survives anonymisation is the record, not the person: when it arrived,
 * which form, which community, how many attempts, what Sherpa answered, and the
 * CRM reference to look it up by. Enough to audit delivery and count enquiries,
 * and it identifies nobody.
 *
 * CONSEQUENCE, worth stating plainly: this table is no longer a lead archive.
 * It replaces Flamingo as the place an *undelivered* lead is recovered from --
 * not as somewhere to browse past enquiries. Anyone looking up an enquirer goes
 * to Sherpa.
 *
 * @package hello-elementor-child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class RV_Sherpa_Store {

	const STATUS_PENDING   = 'pending';
	const STATUS_SENT      = 'sent';
	const STATUS_DUPLICATE = 'duplicate';
	const STATUS_RETRYING  = 'retrying';
	const STATUS_FAILED    = 'failed';
	const STATUS_DRY_RUN   = 'dry-run';
	const STATUS_SKIPPED   = 'skipped';

	/**
	 * Table name.
	 *
	 * @return string
	 */
	public static function table() {
		global $wpdb;
		return $wpdb->prefix . 'rangeford_leads';
	}

	/**
	 * Create or upgrade the table.
	 */
	public static function install() {
		global $wpdb;

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';

		$table   = self::table();
		$collate = $wpdb->get_charset_collate();

		// payload/response are longtext: Sherpa's 409 duplicate response embeds
		// the entire existing lead record and can run to several KB.
		$sql = "CREATE TABLE {$table} (
			id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
			created_at DATETIME NOT NULL,
			updated_at DATETIME NOT NULL,
			form_id BIGINT UNSIGNED NOT NULL DEFAULT 0,
			form_title VARCHAR(191) NOT NULL DEFAULT '',
			community_id SMALLINT UNSIGNED NOT NULL DEFAULT 0,
			contact_name VARCHAR(191) NOT NULL DEFAULT '',
			contact_email VARCHAR(191) NOT NULL DEFAULT '',
			contact_phone VARCHAR(64) NOT NULL DEFAULT '',
			endpoint VARCHAR(255) NOT NULL DEFAULT '',
			payload LONGTEXT NULL,
			status VARCHAR(20) NOT NULL DEFAULT 'pending',
			attempts SMALLINT UNSIGNED NOT NULL DEFAULT 0,
			next_attempt_at DATETIME NULL,
			http_status SMALLINT UNSIGNED NULL,
			last_error TEXT NULL,
			response LONGTEXT NULL,
			crm_reference VARCHAR(64) NOT NULL DEFAULT '',
			redacted_at DATETIME NULL,
			PRIMARY KEY (id),
			KEY status (status),
			KEY created_at (created_at),
			KEY form_id (form_id),
			KEY contact_email (contact_email),
			KEY redacted_at (redacted_at)
		) {$collate};";

		dbDelta( $sql );
	}

	/**
	 * Record a new lead.
	 *
	 * @param array $row Column values.
	 * @return int|false Insert ID, or false on failure.
	 */
	public static function insert( array $row ) {
		global $wpdb;

		$now = current_time( 'mysql', true );

		$data = wp_parse_args(
			$row,
			array(
				'created_at'      => $now,
				'updated_at'      => $now,
				'form_id'         => 0,
				'form_title'      => '',
				'community_id'    => 0,
				'contact_name'    => '',
				'contact_email'   => '',
				'contact_phone'   => '',
				'endpoint'        => '',
				'payload'         => '',
				'status'          => self::STATUS_PENDING,
				'attempts'        => 0,
				'next_attempt_at' => null,
				'http_status'     => null,
				'last_error'      => null,
				'response'        => null,
				'crm_reference'   => '',
				'redacted_at'     => null,
			)
		);

		$ok = $wpdb->insert( self::table(), $data );

		return $ok ? (int) $wpdb->insert_id : false;
	}

	/**
	 * Update a lead.
	 *
	 * @param int   $id  Lead ID.
	 * @param array $row Column values.
	 * @return bool
	 */
	public static function update( $id, array $row ) {
		global $wpdb;

		$row['updated_at'] = current_time( 'mysql', true );

		return false !== $wpdb->update( self::table(), $row, array( 'id' => (int) $id ) );
	}

	/**
	 * Fetch one lead.
	 *
	 * @param int $id Lead ID.
	 * @return object|null
	 */
	public static function get( $id ) {
		global $wpdb;

		return $wpdb->get_row(
			$wpdb->prepare( 'SELECT * FROM ' . self::table() . ' WHERE id = %d', (int) $id )
		);
	}

	/**
	 * Count leads by status.
	 *
	 * @return array status => count
	 */
	public static function counts() {
		global $wpdb;

		$rows = $wpdb->get_results( 'SELECT status, COUNT(*) AS n FROM ' . self::table() . ' GROUP BY status' );

		$out = array();
		foreach ( (array) $rows as $row ) {
			$out[ $row->status ] = (int) $row->n;
		}

		return $out;
	}

	/**
	 * Query leads for the admin screen.
	 *
	 * @param array $args status, search, limit, offset.
	 * @return array
	 */
	public static function query( array $args = array() ) {
		global $wpdb;

		$args = wp_parse_args(
			$args,
			array(
				'status' => '',
				'search' => '',
				'limit'  => 50,
				'offset' => 0,
			)
		);

		$where  = array( '1=1' );
		$params = array();

		if ( $args['status'] ) {
			$where[]  = 'status = %s';
			$params[] = $args['status'];
		}

		if ( $args['search'] ) {
			$like     = '%' . $wpdb->esc_like( $args['search'] ) . '%';
			$where[]  = '( contact_name LIKE %s OR contact_email LIKE %s OR contact_phone LIKE %s )';
			$params[] = $like;
			$params[] = $like;
			$params[] = $like;
		}

		$sql = 'SELECT * FROM ' . self::table()
			. ' WHERE ' . implode( ' AND ', $where )
			. ' ORDER BY id DESC LIMIT %d OFFSET %d';

		$params[] = (int) $args['limit'];
		$params[] = (int) $args['offset'];

		return $wpdb->get_results( $wpdb->prepare( $sql, $params ) );
	}

	/**
	 * Erase the personal data from a lead, keeping the record.
	 *
	 * Called the moment a lead reaches Sherpa, and by the retention sweep for
	 * everything else. Sherpa is the system of record from that point; holding a
	 * second copy here serves nobody and travels with every database export.
	 *
	 * The row is emptied rather than deleted, deliberately. The count of leads
	 * per form per community, and the proof that each one was delivered, are the
	 * things this table was built to provide -- and they are exactly what the
	 * inherited integration could not tell anyone. Deleting rows would throw
	 * that away along with the personal data.
	 *
	 * Written as raw SQL rather than through update(): $wpdb->update() casts a
	 * null value to an empty string rather than writing SQL NULL, and this is
	 * the one method whose entire job is to actually erase the columns.
	 *
	 * @param int $id Lead ID.
	 * @return bool
	 */
	public static function anonymise( $id ) {
		global $wpdb;

		$id = (int) $id;

		if ( ! $id ) {
			return false;
		}

		$now = current_time( 'mysql', true );

		$result = $wpdb->query(
			$wpdb->prepare(
				'UPDATE ' . self::table() . "
				    SET contact_name  = '',
				        contact_email = '',
				        contact_phone = '',
				        payload       = NULL,
				        response      = NULL,
				        redacted_at   = %s,
				        updated_at    = %s
				  WHERE id = %d
				    AND redacted_at IS NULL",
				$now,
				$now,
				$id
			)
		);

		return false !== $result;
	}

	/**
	 * Anonymise rows that have sat in a terminal state past their window.
	 *
	 * Only terminal statuses are swept. pending and retrying are transient by
	 * construction -- the backoff runs out after about fifteen hours and the row
	 * becomes failed -- and anonymising one mid-flight would destroy the payload
	 * the next attempt needs.
	 *
	 * @param array $windows status => days.
	 * @return int Rows anonymised.
	 */
	public static function anonymise_stale( array $windows ) {
		global $wpdb;

		$done = 0;

		foreach ( $windows as $status => $days ) {
			$days = (int) $days;

			if ( $days < 0 ) {
				continue; // Negative means "keep indefinitely".
			}

			$ids = $wpdb->get_col(
				$wpdb->prepare(
					'SELECT id FROM ' . self::table() . '
					  WHERE status = %s
					    AND redacted_at IS NULL
					    AND updated_at < DATE_SUB( UTC_TIMESTAMP(), INTERVAL %d DAY )
					  LIMIT 500',
					$status,
					$days
				)
			);

			foreach ( (array) $ids as $id ) {
				if ( self::anonymise( $id ) ) {
					$done++;
				}
			}
		}

		return $done;
	}

	/**
	 * How many rows still hold personal data.
	 *
	 * Surfaced in wp-admin so the retention policy is observable rather than
	 * assumed. A number that only ever grows means the sweep is not running.
	 *
	 * @return int
	 */
	public static function unredacted_count() {
		global $wpdb;

		return (int) $wpdb->get_var(
			'SELECT COUNT(*) FROM ' . self::table() . ' WHERE redacted_at IS NULL'
		);
	}

	/**
	 * Leads that failed permanently within a window.
	 *
	 * @param int $hours Lookback window.
	 * @return array
	 */
	public static function recent_failures( $hours = 24 ) {
		global $wpdb;

		return $wpdb->get_results(
			$wpdb->prepare(
				'SELECT * FROM ' . self::table() . '
				  WHERE status = %s
				    AND updated_at >= DATE_SUB( UTC_TIMESTAMP(), INTERVAL %d HOUR )
				  ORDER BY id DESC',
				self::STATUS_FAILED,
				(int) $hours
			)
		);
	}
}
