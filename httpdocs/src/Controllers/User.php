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

		if ( 'open.sharepicgenerator.de' === $_SERVER['HTTP_HOST'] ) {
			$_SERVER['OIDC_CLAIM_preferred_username'] = 'open';
		}

		if ( 'develop.sharepicgenerator.de' === $_SERVER['HTTP_HOST'] ) {
			$_SERVER['OIDC_CLAIM_preferred_username'] = 'robert';
		}

		if ( 'mint.sharepicgenerator.de' === $_SERVER['HTTP_HOST'] ) {
			$_SERVER['OIDC_CLAIM_preferred_username'] = 'heike';
		}

		$this->username = $_SERVER['OIDC_CLAIM_preferred_username'];

		if ( empty( $this->username ) ) {
			exit( 1 );
		}

		$this->create_user_space();
		$this->config = $config;
	}

	/**
	 * Deletes a user.
	 *
	 * @param string $username The username.
	 * @return string The status deleted or not_found.
	 * @throws \Exception On missing $username.
	 */
	public static function delete( $username ) {
		if ( empty( $username ) ) {
			throw new \Exception( 'No username given' );
		}

		$status = 'not_found';

		$user_dir = './users/' . $username . '/';
		if ( file_exists( $user_dir ) ) {
			system( 'rm -rf ' . $user_dir );
			$status = 'deleted';
		}

		$logfiles = glob( 'logfiles/*.log' );
		foreach ( $logfiles as $logfile ) {
			$lines = file( $logfile );

			$filtered_lines = array_filter(
				$lines,
				function ( $line ) use ( $username ) {
					return strpos( $line, $username ) === false;
				}
			);

			if ( $filtered_lines !== $lines ) {
				$status = 'deleted';
				file_put_contents( $logfile, implode( '', $filtered_lines ) );
			}
		}

		return $status;
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
	 * Returns if user may use openai.
	 *
	 * @return bool True if user may use openai.
	 */
	public function may_openai() {
		$allowed_users = $this->config->get( 'OpenAI', 'users' );
		if ( empty( $allowed_users ) ) {
			return false;
		}

		return in_array( $this->username, explode( ',', $allowed_users ) );
	}

	/**
	 * Get the users dir. WIth trailing slash.
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
			function ( $a, $b ) {
				return filemtime( $b ) - filemtime( $a );
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

	/**
	 * Get the public savings.
	 *
	 * @return array
	 */
	public function get_public_savings() {
		$public_savings = glob( 'public_savings/*' );
		foreach ( $public_savings as $key => $dir ) {
			if ( ! file_exists( $dir . '/info.json' ) ) {
				unset( $public_savings[ $key ] );
				continue;
			}

			$info = file_get_contents( $dir . '/info.json' );
			$data = json_decode( $info, true );

			if ( ! isset( $data['owner'] ) || $data['owner'] !== $this->get_username() ) {
				unset( $public_savings[ $key ] );
				continue;
			}
		}
		return $public_savings;
	}

	/**
	 * Return the users saved palette.
	 * The saving of this palette is handled in Sharepic-class
	 * due to security concerns.
	 *
	 * @return string The palette.
	 * @throws \Exception On missing config file.
	 */
	public function get_palette() {
		try {
			$config_file = $this->get_dir() . 'config.json';
			if ( ! file_exists( $config_file ) ) {
				throw new \Exception( 'Config file does not exist.' );
			}
			$data = json_decode( file_get_contents( $config_file ) );
			if ( null === $data ) {
				throw new \Exception( 'Failed to decode JSON.' );
			}
		} catch ( \Exception $e ) {
			return '[]';
		}
		return '["' . join( '","', $data->palette ) . '"]';
	}

	/**
	 * Get a setting from the config file.
	 *
	 * @param string $key The key to get.
	 * @return mixed|null The value or null if not found.
	 */
	public function get_settings( $key ) {
		$config_file = $this->get_dir() . 'config.json';

		$data = @file_get_contents( $config_file );
		if ( empty( $data ) ) {
			return null;
		}
		$config = json_decode( $data, true );
		if ( null === $config ) {
			return null;
		}

		return $config[ $key ] ?? null;
	}

	/**
	 * Check if the user is a staff member.
	 *
	 * @return bool True if the user is a staff member.
	 */
	public function is_staff() {
		return str_ends_with( $this->username, '@mint-vernetzt.de' ) || str_ends_with( $this->username, '@matrix-ggmbh.de' ) || str_ends_with( $this->username, '@matrix-gruppe.de' ) || str_ends_with( $this->username, '@matrix-gmbh.de' ) || str_ends_with( $this->username, '@tom-rose.de' ) || in_array( $this->username, [ 'robert', 'heike', 'localhorst', 'open', 'develop' ], true) ;
	}
}
