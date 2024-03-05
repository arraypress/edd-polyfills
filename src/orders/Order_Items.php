<?php
/**
 * Order Item Polyfills for Easy Digital Downloads (EDD)
 *
 * @package       ArrayPress/edd-polyfills
 * @copyright     Copyright 2024, ArrayPress Limited
 * @license       GPL-2.0-or-later
 * @version       1.0.0
 * @author        David Sherlock
 */

declare( strict_types=1 );

if ( ! function_exists( 'edd_get_order_item_field' ) ) {
	/**
	 * Get a field from an order item object.
	 *
	 * @param int    $order_item_id Order item ID. Default `0`.
	 * @param string $field         Field to retrieve from object. Default empty.
	 *
	 * @return mixed Null if order item does not exist. Value of Order Item if exists.
	 */
	function edd_get_order_item_field( int $order_item_id = 0, string $field = '' ) {
		$order_item = edd_get_order_item( $order_item_id );

		// Check that field exists.
		return $order_item->{$field} ?? null;
	}
}

if ( ! function_exists( 'edd_order_item_exists' ) ) {
	/**
	 * Check if the order item exists in the database.
	 *
	 * @param int $order_item_id The ID of the order item to check.
	 *
	 * @return bool True if the customer exists, false otherwise.
	 */
	function edd_order_item_exists( int $order_item_id ): bool {
		global $wpdb;

		// Bail if a customer ID was not passed.
		if ( empty( $order_item_id ) ) {
			return false;
		}

		// Execute the query to check if the record exists.
		$found = $wpdb->get_var(
			$wpdb->prepare(
				"SELECT COUNT(*) FROM {$wpdb->edd_order_items} WHERE id = %d LIMIT 1;",
				$order_item_id
			)
		);

		// Return true if a record exists (count is greater than 0), false otherwise.
		return (bool) $found;
	}
}

if ( ! function_exists( 'edd_get_order_item_by_cart_index' ) ) {
	/**
	 * Retrieves an order item from an Easy Digital Downloads (EDD) order by its cart index.
	 *
	 * @param int $order_id   The unique identifier of the EDD order.
	 * @param int $cart_index The cart index of the order item to retrieve.
	 *
	 * @return mixed|null     The order item object if found, or false if not found.
	 */
	function edd_get_order_item_by_cart_index( int $order_id, int $cart_index ) {
		$order_items = edd_get_order_items( array(
			'order_id'   => $order_id,
			'cart_index' => $cart_index,
			'number'     => 1
		) );

		return $order_items ? reset( $order_items ) : false;
	}
}