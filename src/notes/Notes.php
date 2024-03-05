<?php
/**
 * Notes Polyfills for Easy Digital Downloads (EDD)
 *
 * @package       ArrayPress/edd-polyfills
 * @copyright     Copyright 2024, ArrayPress Limited
 * @license       GPL-2.0-or-later
 * @version       1.0.0
 * @author        David Sherlock
 */

declare( strict_types=1 );

if ( ! function_exists( 'edd_get_note_field' ) ) {
	/**
	 * Get a field from a note object.
	 *
	 * @param int    $note_id Note ID. Default `0`.
	 * @param string $field   Field to retrieve from object. Default empty.
	 *
	 * @return mixed Null if note does not exist. Value of note field if exists.
	 */
	function edd_get_note_field( int $note_id = 0, string $field = '' ) {
		$note = edd_get_note( $note_id );

		// Check that field exists.
		return $note->{$field} ?? null;
	}
}

if ( ! function_exists( 'edd_note_exists' ) ) {
	/**
	 * Check if the note exists in the database.
	 *
	 * @param int $note_id The ID of the note to check.
	 *
	 * @return bool True if the note exists, false otherwise.
	 */
	function edd_note_exists( int $note_id ): bool {
		global $wpdb;

		// Bail if a customer ID was not passed.
		if ( empty( $note_id ) ) {
			return false;
		}

		$found = $wpdb->get_var(
			$wpdb->prepare(
				"SELECT COUNT(*) FROM {$wpdb->edd_notes} WHERE id = %d LIMIT 1;",
				$note_id
			)
		);

		return (bool) $found;
	}
}
