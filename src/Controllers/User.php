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
	 * The tenant.
	 *
	 * @var string
	 */
	private $tenant = false;

	/**
	 * The user's role.
	 *
	 * @var string
	 */
	private $role = false;

	/**
	 * The database connection.
	 *
	 * @var \PDO
	 */
	private $db;

	/**
	 * The logger object.
	 *
	 * @var Logger
	 */
	private $logger;

	/**
	 * The constructor.
	 */
	public function __construct() {
		try {
			$this->db = new \PDO( 'sqlite:data/users.db' );

			$this->db->setAttribute(
				\PDO::ATTR_ERRMODE,
				\PDO::ERRMODE_EXCEPTION
			);

			$this->logger = new Logger( 'Login' );

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
		// See, if user is already logged in.
		if ( $this->get_user_by_token() ) {
			return true;
		}

		if ( empty( $_POST['username'] ) || ! filter_var( $_POST['username'], FILTER_VALIDATE_EMAIL ) ) {
			return false;
		}

		$username = $_POST['username'];

		// Check password.
		$stmt = $this->db->prepare( 'SELECT * FROM users WHERE username = :username' );
		$stmt->bindParam( ':username', $username );
		$stmt->execute();
		$result = $stmt->fetch( \PDO::FETCH_ASSOC );

		if ( ! $result ) {
			$this->logger->error( "user $username not found" );
			return false;
		}

		if ( ! password_verify( $_POST['password'], $result['password'] ) ) {
			$this->logger->error( "user $username wrong password" );
			return false;
		}

		$this->tenant = $result['tenant'];
		$this->role   = $result['role'];

		// Set token.
		$bearer_token = uniqid( 'sg', true );
		$stmt         = $this->db->prepare( 'UPDATE users SET token = :token WHERE username = :username' );
		$stmt->bindParam( ':token', $bearer_token );
		$stmt->bindParam( ':username', $username );
		$stmt->execute();

		if ( $stmt->rowCount() === 0 ) {
			$this->logger->error( "could not set bearer token for $username" );
			return false;
		}

		$this->username = $username;
		$user_dir       = 'users/' . $this->username . '/workspace';
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
	 * Logs the user out.
	 */
	public function logout() {
		$cookie = 'bearer_token';

		if ( isset( $_COOKIE[ $cookie ] ) ) {
			unset( $_COOKIE[ $cookie ] );
			setcookie( $cookie, '', time() - 3600, '/' );
		}
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
	 * Get the tenant.
	 *
	 * @return string
	 */
	public function get_tenant() {
		return $this->tenant;
	}

	/**
	 * Get the role.
	 *
	 * @return string
	 */
	public function get_role() {
		return $this->role;
	}

	/**
	 * Get the savings.
	 *
	 * @return array
	 */
	public function get_savings() {
		$save_dir = 'users/' . $this->username . '/save/';

		$savings_dir = glob( $save_dir . '/*', GLOB_ONLYDIR );

		$savings = array();
		foreach ( $savings_dir as $dir ) {
			if ( ! file_exists( $dir . '/info.json' ) ) {
				continue;
			}

			$info = file_get_contents( $dir . '/info.json' );
			$data = json_decode( $info, true );

			if ( isset( $data['name'] ) ) {
				$savings[ $dir ] = $data['name'];
			}
		}

		return $savings;
	}

	/**
	 * Is the user admin
	 *
	 * @return bool
	 */
	public function is_admin() {
		return ( 'admin' === $this->role );
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

		$this->username = $result['username'];
		$this->tenant   = $result['tenant'];
		$this->role     = $result['role'];

		return $this->username;
	}

	/**
	 * Get the token for a user.
	 *
	 * @param string $username The username.
	 * @return string The token.
	 */
	public function get_token_for_user( $username ) {
		$stmt = $this->db->prepare( 'SELECT token FROM users WHERE username = :username' );
		$stmt->bindParam( ':username', $username );
		$stmt->execute();

		$result = $stmt->fetch( \PDO::FETCH_ASSOC );

		if ( false === $result ) {
			$this->logger->error( 'No token for user ' . $username );
			return false;
		}

		return $result['token'];
	}


	/**
	 * Register a user.
	 *
	 * @param string $mail The mail address.
	 * @return bool True if the user is registered.
	 */
	public function register( $mail ) {
		if ( empty( $mail ) || ! filter_var( $mail, FILTER_VALIDATE_EMAIL ) ) {
			return false;
		}

		$password = bin2hex( random_bytes( 16 ) ); // no one will ever see this password.
		$token    = bin2hex( random_bytes( 16 ) );

		$stmt = $this->db->prepare( 'INSERT INTO users (username, password, token) VALUES (:username, :password, :token)' );
		$stmt->bindParam( ':username', $mail );
		$stmt->bindParam( ':password', $password );
		$stmt->bindParam( ':token', $token );
		try {
			$stmt->execute();
		} catch ( \PDOException $e ) {
			return false;
		}

		$this->logger->access( 'Account created for ' . $mail );

		$protocol       = ( ! empty( $_SERVER['HTTPS'] ) && 'off' !== $_SERVER['HTTPS'] || '443' === $_SERVER['SERVER_PORT'] ) ? 'https://' : 'http://';
		$server_address = $_SERVER['HTTP_HOST'];
		$link           = $protocol . $server_address . '/index.php/frontend/reset_password?token=' . $token;
		$message        = _( 'You have successfully registered. Please click on the following link to confirm your registration: ' ) . $link;

		$mail = new Mailer( $mail );
		$mail->send( _( 'Account created' ), $message );
		return true;
	}

	/**
	 * Set the password for a user.
	 *
	 * @param string $token The token.
	 * @param string $password The password.
	 */
	public function set_password( $token, $password ) {
		$stmt = $this->db->prepare( 'SELECT username FROM users WHERE token = :token' );
		$stmt->bindParam( ':token', $token );
		$stmt->execute();
		$result = $stmt->fetch( \PDO::FETCH_ASSOC );

		if ( ! $result ) {
			return false;
		}

		$this->logger->access( 'Password resetted for ' . $result['username'] );

		$stmt     = $this->db->prepare( 'UPDATE users SET password = :password WHERE token = :token' );
		$password = password_hash( $password, PASSWORD_BCRYPT );
		$stmt->bindParam( ':password', $password );
		$stmt->bindParam( ':token', $token );
		$result = $stmt->execute();

		return $result;
	}

	/**
	 * Send the password reset mail.
	 *
	 * @return bool True if the mail was sent.
	 */
	public function send_password_link() {
		if ( empty( $_POST['username'] ) || ! filter_var( $_POST['username'], FILTER_VALIDATE_EMAIL ) ) {
			return false;
		}

		$username = $_POST['username'];
		$token    = $this->get_token_for_user( $username );

		if ( empty( $token ) ) {
			return false;
		}

		$protocol       = ( ! empty( $_SERVER['HTTPS'] ) && 'off' !== $_SERVER['HTTPS'] || '443' === $_SERVER['SERVER_PORT'] ) ? 'https://' : 'http://';
		$server_address = $_SERVER['HTTP_HOST'];
		$link           = $protocol . $server_address . '/index.php/frontend/reset_password?token=' . $token;
		$message        = _( 'Click on the following link to reset your password: ' ) . $link;

		$mail = new Mailer( $username );
		$mail->send( _( 'Password Reset' ), $message );

		return true;
	}
}
