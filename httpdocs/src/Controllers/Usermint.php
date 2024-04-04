<?php
namespace Sharepicgenerator\Controllers;

/**
 * User controller.
 */
class Usermint {

	/**
	 * The username.
	 *
	 * @var string
	 */
	private $username = false;

	/**
	 * The constructor.
	 */
	public function __construct() {
		if ( 'localhost:9500' === $_SERVER['HTTP_HOST'] ) {
			$_SERVER['OIDC_CLAIM_preferred_username'] = 'localhorst';
		}

		$this->username = $_SERVER['OIDC_CLAIM_preferred_username'];

		if ( empty( $this->username ) ) {
			exit( 1 );
		}

		$this->create_user_space();
	}

	/**
	 * Creates user space, if it does not exist.
	 *
	 * @return void
	 */
	private function create_user_space() {
		$user_dir = '../users/' . $this->username . '/workspace';
		if ( ! file_exists( $user_dir ) ) {
			$oldmask = umask( 0 );
			mkdir( $user_dir, 0775, true );
			umask( $oldmask );
		}
	}

	/**
	 * Get the username.
	 *
	 * @return string
	 */
	public function get_username() {
		return $this->username;
	}

	/**
	 * Get the users dir.
	 *
	 * @return string
	 */
	public function get_dir() {
		return '../users/' . $this->username . '/';
	}

	/**
	 * Get the savings.
	 *
	 * @return array
	 */
	public function get_savings() {
		$save_dir = '../users/' . $this->username . '/save/';

		$savings_dir = glob( $save_dir . '/*', GLOB_ONLYDIR );
		usort(
			$savings_dir,
			function( $a, $b ) {
				return filemtime( $a ) - filemtime( $b );
			}
		);

		$savings = array();
		foreach ( $savings_dir as $dir ) {
			if ( ! file_exists( $dir . '/info.json' ) ) {
				continue;
			}

			$info = file_get_contents( $dir . '/info.json' );
			$data = json_decode( $info, true );

			if ( isset( $data['name'] ) ) {
				$dir             = basename( $dir );
				$savings[ $dir ] = $data['name'];
			}
		}

		return $savings;
	}

}
