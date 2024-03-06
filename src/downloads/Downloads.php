<?php
/**
 * Download Polyfills for Easy Digital Downloads (EDD)
 *
 * @package       ArrayPress/edd-polyfills
 * @copyright     Copyright 2024, ArrayPress Limited
 * @license       GPL-2.0-or-later
 * @version       1.0.0
 * @author        David Sherlock
 */

declare( strict_types=1 );

if ( ! function_exists( 'edd_download_exists' ) ) {
	/**
	 * Check if the download/post exists in the database.
	 *
	 * @param int $download_id The ID of the download to check.
	 *
	 * @return bool True if the download exists, false otherwise.
	 */
	function edd_download_exists( int $download_id ): bool {
		global $wpdb;

		// Bail if a customer ID was not passed.
		if ( empty( $download_id ) ) {
			return false;
		}

		// Execute the query to check if the record exists.
		$found = $wpdb->get_var(
			$wpdb->prepare(
				"SELECT COUNT(*) FROM {$wpdb->posts} WHERE ID = %d LIMIT 1;",
				$download_id
			)
		);

		// Return true if a record exists (count is greater than 0), false otherwise.
		return (bool) $found;
	}
}