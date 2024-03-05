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

if ( ! function_exists( 'edd_get_download_file_field' ) ) {
	/**
	 * Retrieves the specified field value for a download file in Easy Digital Downloads.
	 *
	 * This function allows you to get specific metadata about a download file, such as its name or file URL,
	 * based on the provided download ID and file ID. The value of the requested field is returned if found.
	 *
	 * @param int    $download_id The unique identifier for the downloadable product.
	 * @param int    $file_id     The specific file ID within the download to query.
	 * @param string $field       The field whose value is being requested (e.g., 'name', 'file').
	 *
	 * @return mixed The value of the requested field if it exists, null otherwise.
	 */
	function edd_get_download_file_field( int $download_id, int $file_id, string $field ) {

		// Bail if no download ID was passed.
		if ( empty( $download_id ) ) {
			return false;
		}

		// Fetch downloadable prices.
		$download_files = edd_get_download_files( $download_id );

		// Set default prices.
		$retval = null;

		if ( $download_files && is_array( $download_files ) ) {
			if ( isset( $download_files[ $file_id ][ $field ] ) ) {
				$retval = $download_files[ $file_id ][ $field ];
			}
		}

		// Filter & return.
		return apply_filters( 'edd_get_download_file_field', $retval, $download_id, $file_id, $field );
	}
}

if ( ! function_exists( 'edd_get_download_file' ) ) {

	/**
	 * Retrieves metadata for a specific file associated with a download product.
	 *
	 * This function fetches metadata for a given file of a download product, identified by the download ID
	 * and an optional file key. It provides detailed information about the file if available.
	 *
	 * @param int $download_id       The unique identifier for the downloadable product.
	 * @param int $file_id           The specific file ID within the download to query.
	 *                               If null, metadata for all files is returned.
	 *
	 * @return array|string|false The file's metadata if found, an empty string if the file key does not exist,
	 *                             and false if no download ID was passed.
	 */
	function edd_get_download_file( int $download_id, int $file_id ) {

		// Bail if no download ID was passed.
		if ( empty( $download_id ) ) {
			return false;
		}

		$files = edd_get_download_files( $download_id );

		$retval = false;

		if ( ! empty( $files ) && isset( $files[ $file_id ] ) ) {
			$retval = $files[ $file_id ];
		}

		// Filter & return
		return apply_filters( 'edd_get_download_file', $retval, $download_id, $file_id );
	}
}

if ( ! function_exists( 'edd_get_download_file_name' ) ) {
	/**
	 * Retrieves the name of a file associated with a download in Easy Digital Downloads.
	 *
	 * @param int $download_id The ID of the downloadable product.
	 * @param int $file_id     The specific file ID within the download to query.
	 *
	 * @return string|false The name of the file if available, or false if the download ID is invalid.
	 */
	function edd_get_download_file_name( int $download_id, int $file_id ) {

		// Bail if no download ID was passed.
		if ( empty( $download_id ) ) {
			return false;
		}

		$files = edd_get_download_files( $download_id );

		$retval = '';

		if ( ! empty( $files ) && isset( $files[ $file_id ] ) ) {
			$retval = ! empty( $files[ $file_id ]['name'] )
				? $files[ $file_id ]['name']
				: edd_get_file_name( $files[ $file_id ] );
		}

		// Filter & return
		return apply_filters( 'edd_get_download_file_name', $retval, $download_id, $file_id );
	}
}