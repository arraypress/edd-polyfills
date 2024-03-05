<?php
/**
 * File Download Log Polyfills for Easy Digital Downloads (EDD)
 *
 * @package       ArrayPress/edd-polyfills
 * @copyright     Copyright 2024, ArrayPress Limited
 * @license       GPL-2.0-or-later
 * @version       1.0.0
 * @author        David Sherlock
 */

declare( strict_types=1 );

if ( ! function_exists( 'edd_get_logs_file_download_field' ) ) {
	/**
	 * Get a field from a file download log object.
	 *
	 * @param int    $file_download_log_id File Download Log ID. Default `0`.
	 * @param string $field                Field to retrieve from object. Default empty.
	 *
	 * @return mixed Null if file download log does not exist. Value of log field if exists.
	 */
	function edd_get_file_download_log_field( int $file_download_log_id = 0, string $field = '' ) {
		$file_download_log = edd_get_file_download_log( $file_download_log_id );

		// Check that field exists.
		return $file_download_log->{$field} ?? null;
	}
}

if ( ! function_exists( 'edd_file_download_log_exists' ) ) {
	/**
	 * Check if the file download log exists in the database.
	 *
	 * @param int $file_download_log_id The ID of the file download log to check.
	 *
	 * @return bool True if the file download log exists, false otherwise.
	 */
	function edd_file_download_log_exists( int $file_download_log_id ): bool {
		global $wpdb;

		// Bail if a customer ID was not passed.
		if ( empty( $file_download_log_id ) ) {
			return false;
		}

		$found = $wpdb->get_var(
			$wpdb->prepare(
				"SELECT COUNT(*) FROM {$wpdb->edd_logs_file_downloads} WHERE id = %d LIMIT 1;",
				$file_download_log_id
			)
		);

		return (bool) $found;
	}
}

