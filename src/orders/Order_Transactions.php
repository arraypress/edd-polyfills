<?php
/**
 * Order Transaction Polyfills for Easy Digital Downloads (EDD)
 *
 * @package       ArrayPress/edd-polyfills
 * @copyright     Copyright 2024, ArrayPress Limited
 * @license       GPL-2.0-or-later
 * @version       1.0.0
 * @author        David Sherlock
 */

declare( strict_types=1 );

if ( ! function_exists( 'edd_get_order_transaction_field' ) ) {
	/**
	 * Get a field from an order transaction object.
	 *
	 * @param int    $order_transaction_id Order Transaction ID. Default `0`.
	 * @param string $field                Field to retrieve from object. Default empty.
	 *
	 * @return mixed Null if order transaction does not exist. Value of Order Transaction if exists.
	 */
	function edd_get_order_transaction_field( int $order_transaction_id = 0, string $field = '' ) {
		$order_transaction = edd_get_order_transaction( $order_transaction_id );

		// Check that field exists.
		return $order_transaction->{$field} ?? null;
	}
}

if ( ! function_exists( 'edd_order_transaction_exists' ) ) {
	/**
	 * Check if the order transaction exists in the database.
	 *
	 * @param int $order_transaction_id The ID of the order transaction to check.
	 *
	 * @return bool True if the order transaction exists, false otherwise.
	 */
	function edd_order_transaction_exists( int $order_transaction_id ): bool {
		global $wpdb;

		// Bail if a customer ID was not passed.
		if ( empty( $order_transaction_id ) ) {
			return false;
		}

		// Execute the query to check if the record exists.
		$found = $wpdb->get_var(
			$wpdb->prepare(
				"SELECT COUNT(*) FROM {$wpdb->edd_order_transactions} WHERE id = %d LIMIT 1;",
				$order_transaction_id
			)
		);

		// Return true if a record exists (count is greater than 0), false otherwise.
		return (bool) $found;
	}
}