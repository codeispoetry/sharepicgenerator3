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
	 * The database connection.
	 *
	 * @var \PDO
	 */
	private $db;

	/**
	 * The constructor.
	 */
	public function __construct() {
		try {
			$this->db = new \PDO( 'sqlite:users.db' );

			$this->db->setAttribute(
				\PDO::ATTR_ERRMODE,
				\PDO::ERRMODE_EXCEPTION
			);

		} catch ( \PDOException $e ) {
			echo $e->getMessage();
		}
	}

	/**
	 * Log the user in.
	 *
	 * @return bool True if the user is logged in.
	 */
	public function login() {
		if ( empty( $_POST['username'] ) ) {
			return false;
		}

		$this->username = preg_replace( '/[^a-zA-Z0-9]/', '', $_POST['username'] );
		$bearer_token   = uniqid( 'sg', true );

		$stmt = $this->db->prepare( 'UPDATE users SET token = :token WHERE username = :username' );
		$stmt->bindParam( ':token', $bearer_token );
		$stmt->bindParam( ':username', $this->username );
		$stmt->execute();

		$user_dir = 'users/' . $this->username . '/workspace';
		if ( ! file_exists( $user_dir ) ) {
			mkdir( $user_dir, 0777, true );
		}

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
		$cookie_token = $_COOKIE['bearer_token'] ?? false;

		if ( empty( $cookie_token ) ) {
			return false;
		}

		$stmt = $this->db->prepare( 'SELECT * FROM users WHERE token = :token' );
		$stmt->bindParam( ':token', $cookie_token );
		$stmt->execute();

		$result = $stmt->fetch( \PDO::FETCH_ASSOC );

		if ( false === $result ) {
			return false;
		}

		return $result['username'];
	}
}
