<?php
/**
 * Order Address Polyfills for Easy Digital Downloads (EDD)
 *
 * @package       ArrayPress/edd-polyfills
 * @copyright     Copyright 2024, ArrayPress Limited
 * @license       GPL-2.0-or-later
 * @version       1.0.0
 * @author        David Sherlock
 */

declare( strict_types=1 );

if ( ! function_exists( 'edd_get_order_address_field' ) ) {
	/**
	 * Get a field from an order address object.
	 *
	 * @param int    $order_address_id Order Address ID. Default `0`.
	 * @param string $field            Field to retrieve from object. Default empty.
	 *
	 * @return mixed Null if order address does not exist. Value of Order Address if exists.
	 */
	function edd_get_order_address_field( int $order_address_id = 0, string $field = '' ) {
		$order_address = edd_get_order_address( $order_address_id );

		// Check that field exists.
		return $order_address->{$field} ?? null;
	}
}

if ( ! function_exists( 'edd_order_address_exists' ) ) {
	/**
	 * Check if the order address exists in the database.
	 *
	 * @param int $order_address_id The ID of the order address to check.
	 *
	 * @return bool True if the order address exists, false otherwise.
	 */
	function edd_order_address_exists( int $order_address_id ): bool {
		global $wpdb;

		// Bail if a customer ID was not passed.
		if ( empty( $order_address_id ) ) {
			return false;
		}

		// Execute the query to check if the record exists.
		$found = $wpdb->get_var(
			$wpdb->prepare(
				"SELECT COUNT(*) FROM {$wpdb->edd_order_addresses} WHERE id = %d LIMIT 1;",
				$order_address_id
			)
		);

		// Return true if a record exists (count is greater than 0), false otherwise.
		return (bool) $found;
	}
}