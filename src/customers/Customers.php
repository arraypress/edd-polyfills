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

if ( ! function_exists( 'edd_get_customer_id_by_user_id' ) ) {
	/**
	 * Get the customer ID associated with a user ID.
	 *
	 * @param int  $user_id   The user ID.
	 * @param bool $use_cache Whether to use cache for the lookup. Default true.
	 *
	 * @return int|false The customer ID or false if the customer ID is not found.
	 */
	function edd_get_customer_id_by_user_id( int $user_id = 0, bool $use_cache = true ) {

		// If the user ID is not specified, use the current user ID.
		if ( empty( $user_id ) && is_user_logged_in() ) {
			$user_id = get_current_user_id();
		}

		// If the user ID is still empty, return false.
		if ( empty( $user_id ) ) {
			return false;
		}

		// Generate a cache key for the query result.
		$cache_key   = 'edd_get_customer_id_by_user_id_' . $user_id;
		$customer_id = false;

		// Attempt to retrieve the query result from the cache if caching is enabled.
		if ( $use_cache ) {
			$customer_id = get_transient( $cache_key );
		}

		// If the query result is not found in the cache or caching is disabled, execute the query.
		if ( $customer_id === false ) {
			global $wpdb;

			// Execute the query to retrieve the customer ID.
			$customer_id = $wpdb->get_var(
				$wpdb->prepare(
					"SELECT id FROM {$wpdb->edd_customers} WHERE user_id = %d LIMIT 1;",
					$user_id
				)
			);

			// If the customer ID is found and caching is enabled, store the query result in the cache with an expiration time of one hour.
			if ( $use_cache && $customer_id ) {
				set_transient( $cache_key, $customer_id, HOUR_IN_SECONDS );
			}
		}

		// Return the customer ID or false if the customer ID is not found.
		return $customer_id ? intval( $customer_id ) : false;
	}
}