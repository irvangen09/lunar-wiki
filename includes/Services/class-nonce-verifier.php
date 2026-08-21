<?php
/**
 * Shared nonce verification helper for term/post meta save handlers.
 *
 * @package Lunar\Services
 */

namespace Lunar\Services;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Nonce_Verifier {

	public static function is_valid( string $nonce_field, string $nonce_action ): bool {
		return isset( $_POST[ $nonce_field ] ) // phpcs:ignore WordPress.Security.NonceVerification.Missing
			&& wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST[ $nonce_field ] ) ), $nonce_action );
	}
}