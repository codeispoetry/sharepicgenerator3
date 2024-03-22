<?php
namespace Sharepicgenerator\Controllers;

/**
 * Proxy controller.
 * Shows files only to authenticated users.
 */
class Proxy {

	/**
	 * Serves a file to an authenticated user.
	 */
	public static function serve() {
		$user = new User();
		$user->login();

		$path = $_GET['p'] ?? null;

		if ( ! $path || str_contains( $path, '..' ) ) {
			header( 'HTTP/1.1 400 Bad Request' );
			exit( 1 );
		}

		if ( ! $user->is_logged_in() ) {
			$logger = new Logger( 'Helper' );
			$logger->alarm( Helper::sanitize_log( $path ) . ' was requested without authentication.' );
			header( 'HTTP/1.1 401 Unauthorized' );
			exit( 1 );
		}

		$real_path = realpath( '../users/' . $user->get_username() . '/' . $path );
		$user_dir  = realpath( dirname( __FILE__, 4 ) . '/users/' . $user->get_username() . '/' );

		// Do only load from user directory.
		if ( ! $real_path || ! str_starts_with( $real_path, $user_dir ) ) {
			$logger = new Logger( 'Helper' );
			$logger->alarm( Helper::sanitize_log( $path ) . ' was requested, but is not in userdir' );
			header( 'HTTP/1.1 404 Not Found' );
			exit( 1 );
		}

		$finfo = finfo_open( FILEINFO_MIME_TYPE );
		$mime  = finfo_file( $finfo, $real_path );
		finfo_close( $finfo );

		header( 'Content-Type: ' . $mime );
		header( 'Content-Length: ' . filesize( $real_path ) );
		header( 'Cache-Control: no-store, no-cache, must-revalidate, max-age=0' );
		header( 'Cache-Control: post-check=0, pre-check=0', false );
		header( 'Pragma: no-cache' );

		readfile( $real_path );
	}
}
