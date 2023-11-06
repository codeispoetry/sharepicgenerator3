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
	 * Log the user in.
	 *
	 * @return bool True if the user is logged in.
	 */
	public function login() {
		if ( empty( $_POST['username'] ) ) {
			return false;
		}

		$this->username = 'tom'; //$_POST['username'];
		$bearer_token   = '7'; // uniqid( 'sg', true );

		setcookie(
			'bearer_token',
			$bearer_token,
			array(
				'expires'  => time() + ( 1 * 24 * 60 * 60 ),
				'secure'   => false,
				'httponly' => true,
				'path'     => '/',
				'samesite' => 'Strict',
			)
		);

		return true;
	}

	/**
	 * If the User is logged in or not.
	 *
	 * @return boolean
	 */
	public function is_logged_in() {
		return ! empty( $this->username );
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
	 * Get the user by token.
	 *
	 * @return string The username.
	 */
	public function get_user_by_token() {
		$token = $_COOKIE['bearer_token'] ?? false;

		if ( empty( $token ) ) {
			return false;
		}

		if ( '7' !== $token ) {
			return false;
		}

		return 'tom';
	}
}
