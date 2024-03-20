<?php
namespace Sharepicgenerator\Controllers;

/**
 * Helper controller.
 */
class Helper {

	/**
	 * Sanitze user input from malicious code.
	 *
	 * @param string $input The input, that should be sanitized.
	 */
	public static function sanitize( $input ) {
		$input = trim( $input );
		$input = stripslashes( $input );
		$input = htmlspecialchars( $input );
		$input = escapeshellarg( $input );
		return $input;
	}

	/**
	 * Allow only a-z, A-Z and 0-9.
	 *
	 * @param string $input The input, that should be sanitized.
	 */
	public static function sanitze_az09( $input ) {
		$input = preg_replace( '/[^a-zA-Z0-9]/', '', $input );
		return $input;
	}

	/**
	 * Sanitze token.
	 *
	 * @param string $input The input, that should be sanitized.
	 */
	public static function sanitze_token( $input ) {
		$input = preg_replace( '/[^a-zA-Z0-9\.]/', '', $input );
		return $input;
	}

	/**
	 * Sanitze for log.
	 *
	 * @param string $input The input, that should be sanitized.
	 */
	public static function sanitize_log( $input ) {
		$input = trim( $input );
		$input = stripslashes( $input );
		$input = htmlspecialchars( $input );
		$input = escapeshellarg( $input );
		return $input;
	}

	/**
	 * Sanitze for usage in an URL.
	 *
	 * @param string $input The input, that should be sanitized.
	 */
	public static function sanitize_url( $input ) {
		$input = trim( $input );
		$input = urlencode( $input );
		return $input;
	}

}
