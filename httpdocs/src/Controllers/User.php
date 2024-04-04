<?php
namespace Sharepicgenerator\Controllers;

/**
 * User controller.
 *
 * @deprecated version 1.0
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
	private $tenants = array();

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
			$this->db = new \PDO( 'sqlite:../data/users.db' );

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
	 * Logs a user in automatically with random password
	 */
	public function autologin() {
		// See, if user is already logged in.
		if ( $this->get_user_by_token() ) {
			return true;
		}

		$username = 'auto' . bin2hex( random_bytes( 4 ) );

		$this->username = $username;
		$this->role     = 'auto';

		// Create user in db, if it does not exist.
		$user = $this->create_user( $username );
		$this->logger->access( "auto user {$username} created" );

		$this->create_user_space();

		if ( ! $this->set_token() ) {
			return false;
		}

		return true;
	}

	/**
	 * Log the user in.
	 *
	 * @throws \Exception If the authenticator method does not exist.
	 * @return bool True if the user is logged in.
	 */
	public function login() {
		global $config;

		// See, if user is already logged in.
		if ( $this->get_user_by_token() ) {
			return true;
		}

		$authenticator = 'authenticate_' . $config->get( 'Main', 'authenticator' );
		if ( ! method_exists( $this, $authenticator ) ) {
			throw new \Exception( "Method $authenticator does not exist" );
		}

		$user = $this->$authenticator();

		if ( ! $user ) {
			return false;
		}

		$this->role     = $user['role'];
		$this->username = $user['username'];
		$this->set_tenants( $user['id'] );
		$this->create_user_space();

		if ( ! $this->set_token() ) {
			return false;
		}

		return true;
	}

	/**
	 * Authenticates using own database.
	 *
	 * @return bool|array False if the user is not authenticated, else the user array.
	 */
	private function authenticate_self() {
		if ( empty( $_POST['username'] ) ) {
			return false;
		}

		$username = Helper::sanitize( $_POST['username'] );
		$user     = $this->get_user_array( $username );

		if ( ! $user ) {
			return false;
		}

		if ( empty( $username ) || ! filter_var( $username, FILTER_VALIDATE_EMAIL ) ) {
			return false;
		}

		if ( ! password_verify( Helper::sanitize( $_POST['password'] ), $user['password'] ) ) {
			$this->logger->error( "user {$user['username']} wrong password" );
			return false;
		}

		return $user;
	}

	/**
	 * Authenticates using the green's SSO.
	 *
	 * @return bool|array False if the user is not authenticated, else the user array.
	 */
	private function authenticate_greens() {
		global $config;

		if ( 'localhost:9500' === $_SERVER['HTTP_HOST'] && 'local' === $config->get( 'Main', 'env' ) ) {
			$_SERVER['OIDC_CLAIM_preferred_username'] = 'localhorst';
		}

		$username = $_SERVER['OIDC_CLAIM_preferred_username'];

		if ( empty( $username ) ) {
			$this->logger->error( "{$username} not set by OIDC" );
			die( 'Error: Username not set by OIDC' );
		}

		$user = $this->get_user_array( $username );

		// Create user in db, if it does not exist.
		if ( false === $user ) {
			$user = $this->create_user( $username );
			$this->logger->access( "greens user {$username} created" );
		}

		return $user;
	}

	/**
	 * Creates a user in the database.
	 *
	 * @param string $username The username.
	 * @param string $role The role.
	 * @return array|bool The user array or false.
	 */
	private function create_user( $username, $role = 'auto' ) {
		$password = bin2hex( random_bytes( 16 ) ); // no one will ever see this password.
		$token    = bin2hex( random_bytes( 16 ) );

		$sql = 'INSERT INTO users ( username,password,token,role ) VALUES (:username, :password, :token,:role)';

		$stmt = $this->db->prepare( $sql );
		$stmt->bindParam( ':username', $username );
		$stmt->bindParam( ':password', $password );
		$stmt->bindParam( ':token', $token );
		$stmt->bindParam( ':role', $role );

		$stmt->execute();

		if ( $stmt->rowCount() === 0 ) {
			$this->logger->error( "could not create auto user for {$username}" );
			return false;
		}

		$user = $this->get_user_array( $username );
		return $user;
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
	 * Sets the bearer token for a user in the database and as cookie.
	 *
	 * @return bool True if the token was set.
	 */
	private function set_token() {
		$bearer_token = uniqid( 'sg', true );
		$stmt         = $this->db->prepare( 'UPDATE users SET token = :token WHERE username = :username' );
		$stmt->bindParam( ':token', $bearer_token );
		$stmt->bindParam( ':username', $this->username );
		$stmt->execute();

		if ( $stmt->rowCount() === 0 ) {
			$this->logger->error( "could not set bearer token for {$this->username}" );
			return false;
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
	 * Sets the tenants for a user.
	 *
	 * @param int $user_id The user id.
	 * @return void
	 */
	private function set_tenants( $user_id ) {
		$stmt = $this->db->prepare( 'SELECT * FROM tenants JOIN userstenants WHERE tenantID = id AND userID = :userId' );
		$stmt->bindParam( ':userId', $user_id );
		$stmt->execute();
		$tenants = $stmt->fetchAll( \PDO::FETCH_ASSOC );

		foreach ( $tenants as $tenant ) {
			$this->tenants[] = $tenant['tenant'];
		}
	}

	/**
	 * Gets a user array from the database.
	 *
	 * @param string $username The username.
	 * @return array|bool The user object or false.
	 */
	private function get_user_array( $username ) {
		$stmt = $this->db->prepare( 'SELECT * FROM users WHERE username = :username' );
		$stmt->bindParam( ':username', $username );
		$stmt->execute();
		$result = $stmt->fetch( \PDO::FETCH_ASSOC );

		if ( ! $result ) {
			$this->logger->error( "user $username not found" );
			return false;
		}

		return $result;
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
	 * Get the tenants.
	 *
	 * @return array
	 */
	public function get_tenants() {
		return $this->tenants;
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
		$this->role     = $result['role'];

		$stmt = $this->db->prepare( 'SELECT * FROM tenants JOIN userstenants WHERE tenantID = id AND userID = :userid' );
		$stmt->bindParam( ':userid', $result['id'] );
		$stmt->execute();
		$tenants = $stmt->fetchAll( \PDO::FETCH_ASSOC );

		foreach ( $tenants as $tenant ) {
			$this->tenants[] = $tenant['tenant'];
		}

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
			$this->logger->error( 'Could not create for ' . $mail . ': ' . $e->getMessage() );
			return false;
		}

		$this->logger->access( 'Account created for ' . $mail );

		// Do not send e-mails, while from cli.
		if ( 'cli' === php_sapi_name() ) {
			return true;
		}

		$message = $this->prepare_mail_message( 'src/Views/Mail/Account_Creation.php' );

		$mail = new Mailer( $mail );

		return $mail->send( _( 'Account created' ), $message );
	}

	/**
	 * Send the password reset mail.
	 *
	 * @return bool True if the mail was sent.
	 */
	public function send_password_link() {
		if ( empty( $_POST['username'] ) || ! filter_var( $_POST['username'], FILTER_VALIDATE_EMAIL ) ) {
			$this->logger->error( 'empty username in send_password_link' );
			return false;
		}

		$username = Helper::sanitize( $_POST['username'] );
		$token    = $this->get_token_for_user( $username );

		if ( empty( $token ) ) {
			$this->logger->error( 'empty token in send_password_link' );
			return false;
		}

		$message = $this->prepare_mail_message( 'src/Views/Mail/Password_Link.php' );

		$mail = new Mailer( $username );
		if ( ! $mail->send( _( 'Password Reset' ), $message ) ) {
			$this->logger->error( 'mailer error in send_password_link' );
		}

		return true;
	}

	/**
	 * Prepare the mail message.
	 *
	 * @param string $view The path to the view.
	 * @return string The message.
	 */
	private function prepare_mail_message( $view ) {
		$protocol       = ( ! empty( $_SERVER['HTTPS'] ) && 'off' !== $_SERVER['HTTPS'] || '443' === $_SERVER['SERVER_PORT'] ) ? 'https://' : 'http://';
		$server_address = $_SERVER['HTTP_HOST'];
		$link           = $protocol . $server_address . '/index.php?c=frontend&m=reset_password&token=' . $token;

		ob_start();
		include $view;
		return ob_get_clean();
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
}
