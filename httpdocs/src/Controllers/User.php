<?php
namespace Sharepicgenerator\Controllers;

/**
 * User controller.
 */
class User {

	/**
	 * The username.
	 *
	 * @var string
	 */
	private $username = false;

	/**
	 * The config.
	 *
	 * @var object
	 */
	private $config;

	/**
	 * The constructor.
	 *
	 * @param object $config The config.
	 */
	public function __construct( $config ) {
		if ( 'localhost:9500' === $_SERVER['HTTP_HOST'] ) {
			$_SERVER['OIDC_CLAIM_preferred_username'] = 'localhorst';
		}

		$this->username = $_SERVER['OIDC_CLAIM_preferred_username'];

		if ( empty( $this->username ) ) {
			exit( 1 );
		}

		$this->create_user_space();
		$this->config = $config;
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
	 * Check if user is admin.
	 *
	 * @return bool
	 */
	public function is_admin() {
		$admins = explode( ',', $this->config->get( 'Main', 'admins' ) );

		return in_array( $this->username, $admins );
	}

	/**
	 * Logs the user out.
	 */
	public function logout() {
		$url = $this->config->get( 'OIDC', 'logouturl' );
		if ( empty( $url ) ) {
			die( 'No logout url configured' );
		}
		setcookie( 'mod_auth_openidc_session', '', time() - 3600, '/', '', true, true );

		header( 'Location: ' . $url );
		exit( 0 );
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
