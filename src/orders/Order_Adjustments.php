<?php
/**
 * Order Adjustment Polyfills for Easy Digital Downloads (EDD)
 *
 * @package       ArrayPress/edd-polyfills
 * @copyright     Copyright 2024, ArrayPress Limited
 * @license       GPL-2.0-or-later
 * @version       1.0.0
 * @author        David Sherlock
 */

declare( strict_types=1 );

if ( ! function_exists( 'edd_get_order_adjustment_field' ) ) {
	/**
	 * Get a field from an order adjustment object.
	 *
	 * @param int    $order_adjustment_id Order Adjustment ID. Default `0`.
	 * @param string $field               Field to retrieve from object. Default empty.
	 *
	 * @return mixed Null if order adjustment does not exist. Value of Order Adjustment if exists.
	 */
	function edd_get_order_adjustment_field( int $order_adjustment_id = 0, string $field = '' ) {
		$order_adjustment = edd_get_order_adjustment( $order_adjustment_id );

		// Check that field exists.
		return $order_adjustment->{$field} ?? null;
	}
}

if ( ! function_exists( 'edd_order_adjustment_exists' ) ) {
	/**
	 * Check if the order adjustment exists in the database.
	 *
	 * @param int $order_adjustment_id The ID of the order adjustment to check.
	 *
	 * @return bool True if the order adjustment exists, false otherwise.
	 */
	function edd_order_adjustment_exists( int $order_adjustment_id ): bool {
		global $wpdb;

		// Bail if a customer ID was not passed.
		if ( empty( $order_adjustment_id ) ) {
			return false;
		}

		// Execute the query to check if the record exists.
		$found = $wpdb->get_var(
			$wpdb->prepare(
				"SELECT COUNT(*) FROM {$wpdb->edd_order_adjustments} WHERE id = %d LIMIT 1;",
				$order_adjustment_id
			)
		);

		// Return true if a record exists (count is greater than 0), false otherwise.
		return (bool) $found;
	}
}