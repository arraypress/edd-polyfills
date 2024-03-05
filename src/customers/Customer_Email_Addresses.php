<?php
/**
 * Customer Email Address Polyfills for Easy Digital Downloads (EDD)
 *
 * @package       ArrayPress/edd-polyfills
 * @copyright     Copyright 2024, ArrayPress Limited
 * @license       GPL-2.0-or-later
 * @version       1.0.0
 * @author        David Sherlock
 */

declare( strict_types=1 );

if ( ! function_exists( 'edd_get_customer_email_address_field' ) ) {
	/**
	 * Get a field from an customer email address object.
	 *
	 * @param int    $customer_email_address_id Customer Email Address ID. Default `0`.
	 * @param string $field                     Field to retrieve from object. Default empty.
	 *
	 * @return mixed Null if customer email address does not exist. Value of Customer Email Address if exists.
	 */
	function edd_get_customer_email_address_field( int $customer_email_address_id = 0, string $field = '' ) {
		$customer_email_address = edd_get_customer_email_address( $customer_email_address_id );

		// Check that field exists.
		return $customer_email_address->{$field} ?? null;
	}
}

if ( ! function_exists( 'edd_customer_email_address_exists' ) ) {
	/**
	 * Check if the customer email address exists in the database.
	 *
	 * @param int $customer_email_address_id The ID of the customer email address to check.
	 *
	 * @return bool True if the customer email address exists, false otherwise.
	 */
	function edd_customer_email_address_exists( int $customer_email_address_id ): bool {
		global $wpdb;

		// Bail if a customer ID was not passed.
		if ( empty( $customer_email_address_id ) ) {
			return false;
		}

		// Execute the query to check if the record exists.
		$found = $wpdb->get_var(
			$wpdb->prepare(
				"SELECT COUNT(*) FROM {$wpdb->edd_customer_email_addresses} WHERE id = %d LIMIT 1;",
				$customer_email_address_id
			)
		);

		// Return true if a record exists (count is greater than 0), false otherwise.
		return (bool) $found;
	}
}