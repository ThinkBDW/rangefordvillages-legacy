<?php
/**
 * Theme Builder guard: keep the site header and footer assigned site-wide.
 *
 * Production has repeatedly lost the footer: on 2026-09-24 at 11:17 it served
 * Hello Elementor's fallback `<footer id="site-footer">` instead of Elementor
 * template 37. The mechanism is in Elementor Pro (4.2.3):
 *
 * - Every Update on a header/footer template also runs the
 *   `theme_builder_save_conditions` request, sending whatever copy of the
 *   display conditions the editor loaded when it opened.
 * - Conditions_Manager::ajax_save_theme_template_conditions() treats a
 *   missing or empty list as "no conditions" and deletes
 *   `_elementor_conditions` outright.
 *
 * The Site Editor app (Templates -> Theme Builder) saves the same way over
 * REST, PUT /elementor/v1/site-editor/templates-conditions/{id}, and there an
 * empty list also deletes the conditions. That endpoint cannot report a failure
 * either: its save_conditions() returns a WP_Error, which update_item() tests
 * with `! $is_saved`, and an object is always truthy, so it answers `true`
 * regardless (seen 2026-09-24: three blocked saves, each shown as a success).
 *
 * So an editor that opened half-loaded (editors were also seeing "Error:
 * Cookie check failed", a failed REST nonce check) can unassign the footer
 * from the whole site with an ordinary Update, and nothing records who or when.
 *
 * This file does two things:
 *
 * 1. Refuses any write that would leave a protected template without its
 *    required condition. In the page editor the refusal makes Elementor's save
 *    return false, so it reports "Error while saving conditions". Over REST,
 *    where Elementor would say it succeeded anyway, the response is replaced
 *    with an explicit error (rv_theme_conditions_rest_error()). It also refuses to trash or delete those templates,
 *    which unassigns them just as completely.
 * 2. Logs every change to any template's conditions, blocked or not, with the
 *    user and the request that made it, to Tools -> Theme Template Log. There
 *    is no SSH to production, so the log has to be readable from wp-admin.
 *
 * For a deliberate change to the header/footer assignment, define
 * RV_ALLOW_THEME_CONDITION_CHANGES as true in wp-config.php, make the change,
 * then remove it again.
 *
 * @package hello-elementor-child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** Meta key Elementor Pro stores display conditions under. */
const RV_THEME_CONDITIONS_META = '_elementor_conditions';

/** Option holding the change log. Not autoloaded. */
const RV_THEME_CONDITIONS_LOG = 'rv_theme_conditions_log';

/** How many log entries are kept. */
const RV_THEME_CONDITIONS_LOG_MAX = 100;

/**
 * Templates that must keep a condition, keyed by post ID.
 *
 * The IDs are the same here and on production (checked against the live
 * markup, `data-elementor-id` 78 and 37, on 2026-09-24).
 *
 * @return array<int,string> Post ID => condition it must always include.
 */
function rv_protected_theme_templates() {
	return apply_filters(
		'rv_protected_theme_templates',
		array(
			78 => 'include/general', // Site header, "Entire Site".
			37 => 'include/general', // Site footer, "Entire Site".
		)
	);
}

/**
 * Whether a deliberate change has been allowed for this environment.
 *
 * @return bool
 */
function rv_theme_conditions_unlocked() {
	return defined( 'RV_ALLOW_THEME_CONDITION_CHANGES' ) && RV_ALLOW_THEME_CONDITION_CHANGES;
}

/**
 * Describe the request making the change, for the log.
 *
 * Elementor batches editor calls into one admin-ajax `elementor_ajax` POST,
 * with the individual actions JSON-encoded in `actions`. Only the action
 * names are kept; the payload holds the whole document.
 *
 * @return string
 */
function rv_theme_conditions_request_context() {
	if ( defined( 'WP_CLI' ) && WP_CLI ) {
		return 'wp-cli';
	}

	// phpcs:disable WordPress.Security.NonceVerification -- read-only, for logging.
	$parts = array();

	if ( isset( $_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI'] ) ) {
		$uri     = wp_parse_url( sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ), PHP_URL_PATH );
		$parts[] = sanitize_text_field( wp_unslash( $_SERVER['REQUEST_METHOD'] ) ) . ' ' . $uri;
	}

	if ( isset( $_REQUEST['action'] ) ) {
		$parts[] = 'action=' . sanitize_key( wp_unslash( $_REQUEST['action'] ) );
	}

	if ( isset( $_POST['actions'] ) ) {
		$actions = json_decode( wp_unslash( $_POST['actions'] ), true ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- only the keys' action names are kept.
		if ( is_array( $actions ) ) {
			$names = array();
			foreach ( $actions as $action ) {
				if ( isset( $action['action'] ) ) {
					$names[] = sanitize_key( $action['action'] );
				}
			}
			$parts[] = 'elementor=' . implode( ',', $names );
		}
	}

	if ( isset( $_SERVER['HTTP_REFERER'] ) ) {
		$referer = wp_parse_url( sanitize_text_field( wp_unslash( $_SERVER['HTTP_REFERER'] ) ) );
		$parts[] = 'from ' . ( isset( $referer['path'] ) ? $referer['path'] : '' ) . ( isset( $referer['query'] ) ? '?' . $referer['query'] : '' );
	}

	if ( isset( $_SERVER['HTTP_USER_AGENT'] ) ) {
		$parts[] = substr( sanitize_text_field( wp_unslash( $_SERVER['HTTP_USER_AGENT'] ) ), 0, 160 );
	}
	// phpcs:enable

	return implode( ' | ', $parts );
}

/**
 * Whether a write was blocked during this request.
 *
 * @param bool $set Pass true to record a block.
 * @return bool
 */
function rv_theme_conditions_blocked( $set = false ) {
	static $blocked = false;

	if ( $set ) {
		$blocked = true;
	}

	return $blocked;
}

/**
 * Append one entry to the log.
 *
 * @param string $event   blocked | changed | deleted.
 * @param int    $post_id Template ID.
 * @param mixed  $before  Conditions before the change.
 * @param mixed  $after   Conditions requested.
 * @return void
 */
function rv_theme_conditions_log( $event, $post_id, $before, $after ) {
	if ( 'blocked' === $event ) {
		rv_theme_conditions_blocked( true );
	}

	$user = wp_get_current_user();

	$entry = array(
		'time'    => time(),
		'event'   => $event,
		'post_id' => (int) $post_id,
		'title'   => $post_id ? get_the_title( $post_id ) : '(all templates)',
		'user'    => $user->exists() ? $user->user_login : '(none)',
		'before'  => array_values( (array) $before ),
		'after'   => array_values( (array) $after ),
		'request' => rv_theme_conditions_request_context(),
	);

	$log = get_option( RV_THEME_CONDITIONS_LOG, array() );
	$log = is_array( $log ) ? $log : array();
	array_unshift( $log, $entry );

	update_option( RV_THEME_CONDITIONS_LOG, array_slice( $log, 0, RV_THEME_CONDITIONS_LOG_MAX ), false );

	// Also to the PHP error log, in case the option itself is ever lost.
	error_log( 'rv theme conditions: ' . wp_json_encode( $entry ) ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
}

/**
 * Current conditions stored on a template.
 *
 * Read straight from the database: this runs inside the meta filters, so
 * get_post_meta() would re-enter them.
 *
 * @param int $post_id Template ID.
 * @return string[]
 */
function rv_theme_conditions_current( $post_id ) {
	global $wpdb;

	$value = $wpdb->get_var( // phpcs:ignore WordPress.DB.DirectDatabaseQuery
		$wpdb->prepare(
			"SELECT meta_value FROM {$wpdb->postmeta} WHERE post_id = %d AND meta_key = %s LIMIT 1",
			$post_id,
			RV_THEME_CONDITIONS_META
		)
	);

	return null === $value ? array() : (array) maybe_unserialize( $value );
}

/**
 * Block an add/update that drops a protected template's required condition.
 *
 * Hooked to both add_ and update_: once the meta row is gone, Elementor's
 * next save arrives as an add, not an update.
 *
 * @param null|bool $check     Short-circuit value; null lets the write proceed.
 * @param int       $object_id Post ID.
 * @param string    $meta_key  Meta key.
 * @param mixed     $value     New value.
 * @return null|bool
 */
function rv_theme_conditions_guard_write( $check, $object_id, $meta_key, $value ) {
	if ( RV_THEME_CONDITIONS_META !== $meta_key || rv_theme_conditions_unlocked() ) {
		return $check;
	}

	$protected = rv_protected_theme_templates();

	if ( isset( $protected[ $object_id ] ) && ! in_array( $protected[ $object_id ], (array) $value, true ) ) {
		rv_theme_conditions_log( 'blocked', $object_id, rv_theme_conditions_current( $object_id ), $value );
		return false;
	}

	return $check;
}
add_filter( 'update_post_metadata', 'rv_theme_conditions_guard_write', 10, 4 );
add_filter( 'add_post_metadata', 'rv_theme_conditions_guard_write', 10, 4 );

/**
 * Block deleting a protected template's conditions.
 *
 * This is the path the empty-conditions save takes. A delete across all posts
 * (`$delete_all`, as delete_post_meta_by_key() does) is refused too, since it
 * would unassign every template at once.
 *
 * @param null|bool $check      Short-circuit value.
 * @param int       $object_id  Post ID.
 * @param string    $meta_key   Meta key.
 * @param mixed     $meta_value Value to match.
 * @param bool      $delete_all Whether deleting across all posts.
 * @return null|bool
 */
function rv_theme_conditions_guard_delete( $check, $object_id, $meta_key, $meta_value, $delete_all ) {
	if ( RV_THEME_CONDITIONS_META !== $meta_key || rv_theme_conditions_unlocked() ) {
		return $check;
	}

	if ( $delete_all ) {
		rv_theme_conditions_log( 'blocked', 0, array( '(all)' ), array() );
		return false;
	}

	if ( array_key_exists( (int) $object_id, rv_protected_theme_templates() ) ) {
		rv_theme_conditions_log( 'blocked', $object_id, rv_theme_conditions_current( $object_id ), array() );
		return false;
	}

	return $check;
}
add_filter( 'delete_post_metadata', 'rv_theme_conditions_guard_delete', 10, 5 );

/**
 * Turn a REST response into an error when this request's write was blocked.
 *
 * Without this the Site Editor reports a blocked save as saved (see the file
 * header), and the person saving has no idea the conditions did not change.
 *
 * @param mixed           $response Callback result.
 * @param array           $handler  Route handler.
 * @param WP_REST_Request $request  Request.
 * @return mixed
 */
function rv_theme_conditions_rest_error( $response, $handler, $request ) {
	if ( ! rv_theme_conditions_blocked() ) {
		return $response;
	}

	return new WP_Error(
		'rv_theme_conditions_blocked',
		'Not saved: the site header and footer must keep the "Entire Site" condition. Reload to see the conditions actually in place. Details: Tools -> Theme Template Log.',
		array( 'status' => 409 )
	);
}
add_filter( 'rest_request_after_callbacks', 'rv_theme_conditions_rest_error', 10, 3 );

/**
 * Log conditions changes that were allowed through, on any template.
 *
 * Another template taking "Entire Site" for the same location is the other
 * way the footer can change, so these are worth seeing as well.
 *
 * @param int|int[] $meta_id    Meta ID(s).
 * @param int       $object_id  Post ID.
 * @param string    $meta_key   Meta key.
 * @param mixed     $meta_value New value.
 * @return void
 */
function rv_theme_conditions_log_change( $meta_id, $object_id, $meta_key, $meta_value ) {
	if ( RV_THEME_CONDITIONS_META !== $meta_key ) {
		return;
	}

	$deleted = 'deleted_post_meta' === current_filter();

	rv_theme_conditions_log( $deleted ? 'deleted' : 'changed', $object_id, array(), $deleted ? array() : $meta_value );
}
add_action( 'added_post_meta', 'rv_theme_conditions_log_change', 10, 4 );
add_action( 'updated_post_meta', 'rv_theme_conditions_log_change', 10, 4 );
add_action( 'deleted_post_meta', 'rv_theme_conditions_log_change', 10, 4 );

/**
 * Refuse to trash or delete a protected template.
 *
 * @param mixed   $check Short-circuit value; null lets it proceed.
 * @param WP_Post $post  Post being trashed or deleted.
 * @return mixed
 */
function rv_theme_conditions_guard_trash( $check, $post ) {
	if ( $post && array_key_exists( (int) $post->ID, rv_protected_theme_templates() ) && ! rv_theme_conditions_unlocked() ) {
		rv_theme_conditions_log( 'blocked', $post->ID, array( 'trash/delete' ), array() );
		return false;
	}

	return $check;
}
add_filter( 'pre_trash_post', 'rv_theme_conditions_guard_trash', 10, 2 );
add_filter( 'pre_delete_post', 'rv_theme_conditions_guard_trash', 10, 2 );

/**
 * Tools -> Theme Template Log.
 *
 * @return void
 */
function rv_theme_conditions_admin_menu() {
	add_management_page(
		'Theme Template Log',
		'Theme Template Log',
		'manage_options',
		'rv-theme-template-log',
		'rv_theme_conditions_render_log'
	);
}
add_action( 'admin_menu', 'rv_theme_conditions_admin_menu' );

/**
 * Render the log page.
 *
 * @return void
 */
function rv_theme_conditions_render_log() {
	$log = get_option( RV_THEME_CONDITIONS_LOG, array() );

	echo '<div class="wrap"><h1>Theme Template Log</h1>';
	echo '<p>Every change to an Elementor template&#8217;s display conditions. <strong>Blocked</strong> rows are saves that would have left the site header or footer without &#8220;Entire Site&#8221;. See <code>inc/theme-builder-guard.php</code>.</p>';

	if ( empty( $log ) ) {
		echo '<p>No changes recorded yet.</p></div>';
		return;
	}

	echo '<table class="widefat striped"><thead><tr><th>When</th><th>Event</th><th>Template</th><th>User</th><th>Before</th><th>Requested</th><th>Request</th></tr></thead><tbody>';

	foreach ( $log as $entry ) {
		printf(
			'<tr><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td><small>%s</small></td></tr>',
			esc_html( wp_date( 'Y-m-d H:i:s', $entry['time'] ) ),
			'blocked' === $entry['event'] ? '<strong style="color:#b32d2e">blocked</strong>' : esc_html( $entry['event'] ),
			esc_html( $entry['title'] . ' (#' . $entry['post_id'] . ')' ),
			esc_html( $entry['user'] ),
			esc_html( implode( ', ', $entry['before'] ) ),
			esc_html( $entry['after'] ? implode( ', ', $entry['after'] ) : '(none)' ),
			esc_html( $entry['request'] )
		);
	}

	echo '</tbody></table></div>';
}

/**
 * Point admins at the log when a save was blocked in the last 14 days.
 *
 * @return void
 */
function rv_theme_conditions_notice() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;
	if ( ! $screen || ! in_array( $screen->id, array( 'dashboard', 'edit-elementor_library' ), true ) ) {
		return;
	}

	$recent = array_filter(
		(array) get_option( RV_THEME_CONDITIONS_LOG, array() ),
		function ( $entry ) {
			return 'blocked' === $entry['event'] && $entry['time'] > time() - 14 * DAY_IN_SECONDS;
		}
	);

	if ( ! $recent ) {
		return;
	}

	printf(
		'<div class="notice notice-warning"><p>%d save(s) in the last 14 days tried to remove the site header or footer&#8217;s &#8220;Entire Site&#8221; condition and were blocked. <a href="%s">See the Theme Template Log</a>.</p></div>',
		count( $recent ),
		esc_url( admin_url( 'tools.php?page=rv-theme-template-log' ) )
	);
}
add_action( 'admin_notices', 'rv_theme_conditions_notice' );
