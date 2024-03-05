<?php
/**
 * Log Polyfills for Easy Digital Downloads (EDD)
 *
 * @package       ArrayPress/edd-polyfills
 * @copyright     Copyright 2024, ArrayPress Limited
 * @license       GPL-2.0-or-later
 * @version       1.0.0
 * @author        David Sherlock
 */

declare( strict_types=1 );

if ( ! function_exists( 'edd_get_log_field' ) ) {
	/**
	 * Get a field from a log object.
	 *
	 * @param int    $log_id Log ID. Default `0`.
	 * @param string $field  Field to retrieve from object. Default empty.
	 *
	 * @return mixed Null if log does not exist. Value of log field if exists.
	 */
	function edd_get_log_field( int $log_id = 0, string $field = '' ) {
		$log = edd_get_log( $log_id );

		// Check that field exists.
		return $log->{$field} ?? null;
	}
}

if ( ! function_exists( 'edd_log_exists' ) ) {
	/**
	 * Check if the log exists in the database.
	 *
	 * @param int $log_id The ID of the log to check.
	 *
	 * @return bool True if the log exists, false otherwise.
	 */
	function edd_log_exists( int $log_id ): bool {
		global $wpdb;

		// Bail if a customer ID was not passed.
		if ( empty( $file_download_log_id ) ) {
			return false;
		}

		$found = $wpdb->get_var(
			$wpdb->prepare(
				"SELECT COUNT(*) FROM {$wpdb->edd_logs} WHERE id = %d LIMIT 1;",
				$file_download_log_id
			)
		);

		return (bool) $found;
	}
}

