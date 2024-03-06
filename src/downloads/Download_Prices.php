<?php
/**
 * Download (Variable Prices) Polyfills for Easy Digital Downloads (EDD)
 *
 * @package       ArrayPress/edd-polyfills
 * @copyright     Copyright 2024, ArrayPress Limited
 * @license       GPL-2.0-or-later
 * @version       1.0.0
 * @author        David Sherlock
 */

declare( strict_types=1 );

if ( ! function_exists( 'edd_get_download_variable_price_field' ) ) {
	/**
	 * Retrieves the specified field value for a download file in Easy Digital Downloads.
	 *
	 * This function allows you to get specific metadata about a download file, such as its name or file URL,
	 * based on the provided download ID and file ID. The value of the requested field is returned if found.
	 *
	 * @param int    $download_id The unique identifier for the downloadable product.
	 * @param int    $price_id    The specific file ID within the download to query.
	 * @param string $field       The field whose value is being requested (e.g., 'name', 'file').
	 *
	 * @return mixed The value of the requested field if it exists, null otherwise.
	 */
	function edd_get_download_variable_price_field( int $download_id, int $price_id, string $field ) {

		// Bail if no download ID was passed.
		if ( empty( $download_id ) ) {
			return false;
		}

		$download = edd_get_download( $download_id );

		// Bail if the download cannot be retrieved.
		if ( ! $download instanceof EDD_Download ) {
			return false;
		}

		// Fetch downloadable prices.
		$prices = $download->get_prices();

		// Set default prices.
		$retval = null;

		if ( is_array( $prices ) && ! empty( $price_id ) ) {
			if ( isset( $prices[ $price_id ][ $field ] ) ) {
				$retval = $prices[ $price_id ][ $field ];
			}
		}

		// Filter & return.
		return apply_filters( 'edd_get_download_variable_price_field', $retval, $download_id, $price_id, $field );
	}
}

if ( ! function_exists( 'edd_get_highest_price_id' ) ) {
	/**
	 * Retrieves the ID for the most expensive price option of a variable priced download.
	 *
	 * @note
	 *
	 * @param int $download_id Download ID.
	 *
	 * @return int|false ID of the highest price, false if download does not exist.
	 */
	function edd_get_highest_price_id( int $download_id = 0 ) {

		// Attempt to get the ID of the current item in the WordPress loop.
		if ( empty( $download_id ) ) {
			$download_id = get_the_ID();
		}

		// Bail if download ID is still empty.
		if ( empty( $download_id ) ) {
			return false;
		}

		// Return download price if variable prices do not exist for download.
		if ( ! edd_has_variable_prices( $download_id ) ) {
			return edd_get_download_price( $download_id );
		}

		$list_handler = new EDD\Utils\ListHandler( edd_get_variable_prices( $download_id ) );
		$max_key      = $list_handler->search( 'amount', 'max' );

		return false !== $max_key ? absint( $max_key ) : false;
	}
}