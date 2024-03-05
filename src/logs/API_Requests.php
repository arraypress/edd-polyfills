<?php
/**
 * API Request Log Polyfills for Easy Digital Downloads (EDD)
 *
 * @package       ArrayPress/edd-polyfills
 * @copyright     Copyright 2024, ArrayPress Limited
 * @license       GPL-2.0-or-later
 * @version       1.0.0
 * @author        David Sherlock
 */

declare( strict_types=1 );

if ( ! function_exists( 'edd_get_api_request_log_field' ) ) {
	/**
	 * Get a field from an API request log object.
	 *
	 * @param int    $api_request_log_id API request log ID. Default `0`.
	 * @param string $field              Field to retrieve from object. Default empty.
	 *
	 * @return mixed Null if API request log does not exist. Value of log field if exists.
	 */
	function edd_get_api_request_log_field( int $api_request_log_id = 0, string $field = '' ) {
		$api_request_log = edd_get_api_request_log( $api_request_log_id );

		// Check that field exists.
		return $api_request_log->{$field} ?? null;
	}
}

if ( ! function_exists( 'edd_api_request_log_exists' ) ) {
	/**
	 * Check if the API request log exists in the database.
	 *
	 * @param int $api_request_log_id The ID of the API request log to check.
	 *
	 * @return bool True if the API request log exists, false otherwise.
	 */
	function edd_api_request_log_exists( int $api_request_log_id ): bool {
		global $wpdb;

		// Bail if API request Log ID was not passed.
		if ( empty( $api_request_log_id ) ) {
			return false;
		}

		$found = $wpdb->get_var(
			$wpdb->prepare(
				"SELECT COUNT(*) FROM {$wpdb->edd_logs_api_requests} WHERE id = %d LIMIT 1;",
				$api_request_log_id
			)
		);

		return (bool) $found;
	}
}
