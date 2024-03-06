<?php
/**
 * Order Polyfills for Easy Digital Downloads (EDD)
 *
 * @package       ArrayPress/edd-polyfills
 * @copyright     Copyright 2024, ArrayPress Limited
 * @license       GPL-2.0-or-later
 * @version       1.0.0
 * @author        David Sherlock
 */

declare( strict_types=1 );

if ( ! function_exists( 'edd_get_order_field' ) ) {
	/**
	 * Get a field from an order object.
	 *
	 * @param int    $order_id Order ID. Default `0`.
	 * @param string $field    Field to retrieve from object. Default empty.
	 *
	 * @return mixed Null if order does not exist. Value of Order if exists.
	 */
	function edd_get_order_field( int $order_id = 0, string $field = '' ) {
		$order = edd_get_order( $order_id );

		// Check that field exists.
		return $order->{$field} ?? null;
	}
}

if ( ! function_exists( 'edd_order_exists' ) ) {
	/**
	 * Check if the order exists in the database.
	 *
	 * @param int $order_id The ID of the order to check.
	 *
	 * @return bool True if the order exists, false otherwise.
	 */
	function edd_order_exists( int $order_id ): bool {
		global $wpdb;

		// Bail if a customer ID was not passed.
		if ( empty( $order_id ) ) {
			return false;
		}

		// Execute the query to check if the record exists.
		$found = $wpdb->get_var(
			$wpdb->prepare(
				"SELECT COUNT(*) FROM {$wpdb->edd_orders} WHERE id = %d LIMIT 1;",
				$order_id
			)
		);

		// Return true if a record exists (count is greater than 0), false otherwise.
		return (bool) $found;
	}
}

if ( ! function_exists( 'edd_order_exists_by_key' ) ) {
	/**
	 * Check if an order with the given payment key exists.
	 *
	 * @param string $payment_key The payment key of the order to check.
	 * @param bool   $use_cache   Whether to use cache for the lookup. Default true.
	 *
	 * @return bool True if the order exists, false otherwise.
	 */
	function edd_order_exists_by_key( string $payment_key = '', bool $use_cache = true ): bool {

		// Bail if no payment key
		if ( empty( $payment_key ) ) {
			return false;
		}

		// Generate a cache key for the query result.
		$cache_key = 'edd_order_exists_by_key_' . $payment_key;
		$found     = false;

		// Attempt to retrieve the query result from the cache if caching is enabled.
		if ( $use_cache ) {
			$found = get_transient( $cache_key );
		}

		// If the query result is not found in the cache or caching is disabled, execute the query.
		if ( $found === false ) {
			global $wpdb;

			// Execute the query to check if the order exists.
			$found = $wpdb->get_var(
				$wpdb->prepare(
					"SELECT id FROM {$wpdb->edd_orders} WHERE payment_key = %s LIMIT 1;",
					$payment_key
				)
			);

			// Store the query result in the cache with an expiration time of one hour if caching is enabled.
			if ( $use_cache && $found ) {
				set_transient( $cache_key, $found, HOUR_IN_SECONDS );
			}
		}

		// Return true if the order exists (found is not null or false), false otherwise.
		return ! empty( $found );
	}
}