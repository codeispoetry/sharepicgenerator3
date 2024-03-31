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

	/**
	 * Checks, if a remote file is an image.
	 *
	 * @param string $url The url of the file.
	 */
	public static function is_image_file_remote( $url ) {

		if ( filter_var( $url, FILTER_VALIDATE_URL ) === false ) {
			return false;
		}

		$headers = get_headers( $url, 1 );
		$ct      = $headers['Content-Type'];

		if ( empty( $ct ) || ! str_starts_with( $ct, 'image/' ) ) {
			$logger = new Logger( 'Helper' );
			$logger->error( self::sanitize_log( $url ) . ' has Content-Type: ' . $ct );
			return false;
		}

		return true;
	}


	/**
	 * Checks, if a file is an image.
	 *
	 * @param string $path The path of the file.
	 */
	public static function is_image_file_local( $path ) {

		$extension = strtolower( pathinfo( $path, PATHINFO_EXTENSION ) );
		if ( ! in_array( $extension, array( 'jpg', 'jpeg', 'png' ) ) ) {
			return false;
		}

		$ct = mime_content_type( $path );
		if ( empty( $ct ) || ! str_starts_with( $ct, 'image/' ) ) {
			$logger = new Logger( 'Helper' );
			$logger->error( self::sanitize_log( $path ) . ' has Content-Type: ' . $ct );
			return false;
		}

		return true;
	}


	/**
	 * Load the textdomain.
	 */
	public static function load_textdomain() {
		bindtextdomain( 'sg', './languages' );
		textdomain( 'sg' );
		if ( 'de' === self::get_lang() ) {
			setlocale( LC_ALL, 'de_DE.utf8' );
		}
	}

	/**
	 * Get the language.
	 *
	 * @return string
	 */
	private static function get_lang() {
		if ( isset( $_COOKIE['lang'] ) ) {
			return $_COOKIE['lang'];
		}

		if ( isset( $_SERVER['HTTP_ACCEPT_LANGUAGE'] ) ) {
			return substr( $_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2 );
		}

		return false;
	}
}

