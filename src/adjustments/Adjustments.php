<?php
/**
 * Adjustment Polyfills for Easy Digital Downloads (EDD)
 *
 * @package       ArrayPress/edd-polyfills
 * @copyright     Copyright 2024, ArrayPress Limited
 * @license       GPL-2.0-or-later
 * @version       1.0.0
 * @author        David Sherlock
 */

declare( strict_types=1 );

if ( ! function_exists( 'edd_get_adjustment_field' ) ) {
	/**
	 * Get a field from an adjustment object.
	 *
	 * @param int    $adjustment_id Adjustment ID. Default `0`.
	 * @param string $field         Field to retrieve from object. Default empty.
	 *
	 * @return mixed Null if adjustment does not exist. Value of Adjustment if exists.
	 */
	function edd_get_adjustment_field( int $adjustment_id = 0, string $field = '' ) {
		$adjustment = edd_get_adjustment( $adjustment_id );

		// Check that field exists.
		return $adjustment->{$field} ?? null;
	}
}

if ( ! function_exists( 'edd_adjustment_exists' ) ) {
	/**
	 * Check if the order exists in the database.
	 *
	 * @param int $adjustment_id The ID of the order to check.
	 *
	 * @return bool True if the order exists, false otherwise.
	 */
	function edd_adjustment_exists( int $adjustment_id ): bool {
		global $wpdb;

		// Bail if a customer ID was not passed.
		if ( empty( $adjustment_id ) ) {
			return false;
		}

		// Execute the query to check if the record exists.
		$found = $wpdb->get_var(
			$wpdb->prepare(
				"SELECT COUNT(*) FROM {$wpdb->edd_adjustments} WHERE id = %d LIMIT 1;",
				$adjustment_id
			)
		);

		// Return true if a record exists (count is greater than 0), false otherwise.
		return (bool) $found;
	}
}

if ( ! function_exists( 'edd_is_adjustment_type' ) ) {
	/**
	 * Check if a given adjustment is of a specific type.
	 *
	 * @param int    $adjustment_id The ID of the adjustment to check.
	 * @param string $type          The expected type of the adjustment. Default empty.
	 *
	 * @return bool True if the adjustment is of the specified type, false otherwise.
	 */
	function edd_is_adjustment_type( int $adjustment_id, string $type = '' ): bool {
		$adjustment_type = edd_get_adjustment_field( $adjustment_id, 'type' );

		return $adjustment_type && strtolower( $type ) === strtolower( $adjustment_type );
	}
}