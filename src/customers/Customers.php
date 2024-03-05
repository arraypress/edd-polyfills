<?php
/**
 * Customer Polyfills for Easy Digital Downloads (EDD)
 *
 * @package       ArrayPress/edd-polyfills
 * @copyright     Copyright 2024, ArrayPress Limited
 * @license       GPL-2.0-or-later
 * @version       1.0.0
 * @author        David Sherlock
 */

declare( strict_types=1 );

if ( ! function_exists( 'edd_customer_exists' ) ) {
	/**
	 * Check if the order exists in the database.
	 *
	 * @param int $customer_id The ID of the customer to check.
	 *
	 * @return bool True if the order exists, false otherwise.
	 */
	function edd_customer_exists( int $customer_id ): bool {
		global $wpdb;

		// Bail if a customer ID was not passed.
		if ( empty( $customer_id ) ) {
			return false;
		}

		// Execute the query to check if the record exists.
		$found = $wpdb->get_var(
			$wpdb->prepare(
				"SELECT COUNT(*) FROM {$wpdb->edd_customers} WHERE id = %d LIMIT 1;",
				$customer_id
			)
		);

		// Return true if a record exists (count is greater than 0), false otherwise.
		return (bool) $found;
	}
}

if ( ! function_exists( 'edd_get_customer_emails' ) ) {
	/**
	 * Retrieve all email addresses associated with a customer.
	 *
	 * @param int $customer_id The ID of the customer.
	 *
	 * @return array An array of email addresses associated with the customer.
	 */
	function edd_get_customer_emails( int $customer_id = 0 ): array {

		// Bail if no customer ID was passed.
		if ( empty( $customer_id ) ) {
			return array();
		}

		$customer = edd_get_customer( $customer_id );

		return $customer ? $customer->get_emails() : array();
	}
}
