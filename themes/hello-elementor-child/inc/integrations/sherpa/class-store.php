<?php
/**
 * Durable lead store.
 *
 * Every lead is written here BEFORE any HTTP call is attempted, which is what
 * makes the integration lossless: a CRM outage, a broken queue, a fatal in the
 * dispatcher or a rollback of the site cannot lose an enquiry, because the
 * enquiry is already committed to the database.
 *
 * This also replaces Flamingo as the in-WordPress record of leads. Flamingo
 * held 3,132 submissions and was the only place a lead could be recovered from;
 * removing it without this table would have left no record at all.
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
			PRIMARY KEY (id),
			KEY status (status),
			KEY created_at (created_at),
			KEY form_id (form_id),
			KEY contact_email (contact_email)
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
