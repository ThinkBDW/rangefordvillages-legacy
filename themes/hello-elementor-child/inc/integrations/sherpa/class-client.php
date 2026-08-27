<?php
/**
 * HTTP transport for the Sherpa Create Lead endpoint.
 *
 * @package hello-elementor-child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class RV_Sherpa_Client {

	/** Seconds to wait for Sherpa before giving up on an attempt. */
	const TIMEOUT = 20;

	/**
	 * POST a lead.
	 *
	 * @param string $endpoint Full URL.
	 * @param array  $payload  JSON body.
	 * @return array {
	 *     @type bool     $ok          True when the lead is in Sherpa.
	 *     @type bool     $duplicate   True when Sherpa reported an existing lead.
	 *     @type bool     $retryable   True when another attempt could succeed.
	 *     @type int|null $http_status HTTP status, null on transport failure.
	 *     @type string   $body        Response body.
	 *     @type string   $error       Human-readable error, empty on success.
	 * }
	 */
	public static function send( $endpoint, array $payload ) {
		$token = rv_sherpa_token();

		if ( '' === $token ) {
			return array(
				'ok'          => false,
				'duplicate'   => false,
				// Not retryable: no amount of waiting produces a token.
				'retryable'   => false,
				'http_status' => null,
				'body'        => '',
				'error'       => 'SHERPA_API_TOKEN is not defined. Set it in wp-config.php.',
			);
		}

		$response = wp_remote_post(
			$endpoint,
			array(
				'timeout' => self::TIMEOUT,
				'headers' => array(
					'Authorization' => 'Bearer ' . $token,
					'Content-Type'  => 'application/json',
					'Accept'        => 'application/json',
				),
				'body'    => wp_json_encode( $payload ),
			)
		);

		if ( is_wp_error( $response ) ) {
			// Transport failure -- DNS, TLS, timeout. Always worth retrying.
			return array(
				'ok'          => false,
				'duplicate'   => false,
				'retryable'   => true,
				'http_status' => null,
				'body'        => '',
				'error'       => 'transport: ' . $response->get_error_message(),
			);
		}

		$status = (int) wp_remote_retrieve_response_code( $response );
		$body   = (string) wp_remote_retrieve_body( $response );

		if ( $status >= 200 && $status < 300 ) {
			return array(
				'ok'          => true,
				'duplicate'   => false,
				'retryable'   => false,
				'http_status' => $status,
				'body'        => $body,
				'error'       => '',
			);
		}

		// 409 means "Duplicate Lead: Lead already exists in this company" --
		// Sherpa deduplicates on email or phone. The lead IS in the CRM, so
		// this is an outcome to record, not a failure to retry. 291 of the
		// inherited 2026 submissions were 409s.
		if ( 409 === $status ) {
			return array(
				'ok'          => true,
				'duplicate'   => true,
				'retryable'   => false,
				'http_status' => $status,
				'body'        => $body,
				'error'       => '',
			);
		}

		// 5xx and 429 are transient. 69 leads in 2026 hit a bare
		// {"error":{"code":500,"message":"Internal Server Error"}} and were
		// dropped without retry -- roughly ten lost enquiries a month.
		$retryable = ( $status >= 500 || 429 === $status );

		return array(
			'ok'          => false,
			'duplicate'   => false,
			'retryable'   => $retryable,
			'http_status' => $status,
			'body'        => $body,
			'error'       => sprintf( 'HTTP %d: %s', $status, self::extract_message( $body ) ),
		);
	}

	/**
	 * Pull a readable message out of a Sherpa error response.
	 *
	 * @param string $body Response body.
	 * @return string
	 */
	private static function extract_message( $body ) {
		$decoded = json_decode( $body, true );

		if ( is_array( $decoded ) ) {
			if ( isset( $decoded['error']['message'] ) ) {
				return (string) $decoded['error']['message'];
			}
			if ( isset( $decoded['errors'][0]['message'] ) ) {
				return (string) $decoded['errors'][0]['message'];
			}
			if ( isset( $decoded['message'] ) ) {
				return (string) $decoded['message'];
			}
		}

		return trim( substr( $body, 0, 300 ) );
	}
}
