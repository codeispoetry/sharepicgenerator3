<?php
namespace Sharepicgenerator\Controllers;

/**
 * Proxy controller.
 * Shows files only to authenticated users.
 */
class Proxy {

	/**
	 * Serves a file to an authenticated user.
	 *
	 * @param Object $env Environment with user, config, logger, mailer, etc.
	 */
	public static function serve( $env ) {
		$path = $_GET['p'] ?? null;

		if ( ! $path || str_contains( $path, '..' ) ) {
			header( 'HTTP/1.1 400 Bad Request' );
			exit( 1 );
		}

		$clearance = false;

		// Check, if the requested file is in the userdir.
		$provided_path = realpath( $env->user->get_dir() . $path );
		$allowed_dir   = realpath( $env->user->get_dir() );
		if ( $provided_path && str_starts_with( $provided_path, $allowed_dir ) ) {
			$clearance = true;
		}

		// Check, if the requested file is in the tmp dir.
		if ( ! $clearance ) {
			$provided_path = realpath( '../' . $path );
			$allowed_dir   = realpath( dirname( __DIR__, 3 ) . '/tmp/' );
			if ( $provided_path && str_starts_with( $provided_path, $allowed_dir ) ) {
				$clearance = true;
			}
		}

		if ( ! $clearance ) {
			$env->logger->alarm( Helper::sanitize_log( $path ) . ' was requested, but is not in userdir' );
			header( 'HTTP/1.1 404 Not Found' );
			exit( 1 );
		}

		$finfo = finfo_open( FILEINFO_MIME_TYPE );
		$mime  = finfo_file( $finfo, $provided_path );
		finfo_close( $finfo );

		header( 'Content-Type: ' . $mime );
		header( 'Content-Length: ' . filesize( $provided_path ) );
		header( 'Cache-Control: no-store, no-cache, must-revalidate, max-age=0' );
		header( 'Cache-Control: post-check=0, pre-check=0', false );
		header( 'Pragma: no-cache' );

		readfile( $provided_path );
	}
}
