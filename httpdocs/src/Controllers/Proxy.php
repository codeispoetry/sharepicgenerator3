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

		if ( ! $user->is_logged_in() ) {
			header( 'HTTP/1.1 401 Unauthorized' );
			exit( 1 );
		}

		self::get_file();
	}


	/**
	 * Gets the file and serves it.
	 */
	private static function get_file() {

		$path = $_GET['p'] ?? null;

		if ( ! $path || strpos( $path, '..' ) !== false ) {
			header( 'HTTP/1.1 400 Bad Request' );
			exit( 1 );
		}

		$path = '../' . $path;

		if ( ! file_exists( $path ) ) {
			header( 'HTTP/1.1 404 Not Found' );
			exit( 1 );
		}

		$finfo = finfo_open( FILEINFO_MIME_TYPE );
		$mime  = finfo_file( $finfo, $path );
		finfo_close( $finfo );

		header( 'Content-Type: ' . $mime );
		header( 'Content-Length: ' . filesize( $path ) );
		header( 'Cache-Control: no-store, no-cache, must-revalidate, max-age=0' );
		header( 'Cache-Control: post-check=0, pre-check=0', false );
		header( 'Pragma: no-cache' );

		readfile( $path );
	}
}
