<?php
namespace Sharepicgenerator\Controllers;

/**
 * Frontend controller.
 */
class Frontend {

	/**
	 * The user object.
	 *
	 * @var User
	 */
	private $user;

	/**
	 * The config object.
	 *
	 * @var Config
	 */
	private $config;

	/**
	 * Enables the user-object.
	 */
	public function __construct() {
		$this->user   = new User();
		$this->config = new Config();
	}

	/**
	 * The generator page.
	 */
	public function create() {
		if ( ! $this->user->login() ) {
			header( 'Location: /' );
			die();
		}
		include_once './src/Views/Creator.php';
	}

	/**
	 * The registration page.
	 */
	public function register() {
		include_once './src/Views/Header.php';
		if ( ! isset( $_POST['register_mail'] ) ) {
			include_once './src/Views/User/Register.php';
			include_once './src/Views/Footer.php';
			return;
		}

		if ( ! $this->user->register( $_POST['register_mail'] ) ) {
			include_once './src/Views/User/NotRegistered.php';
			include_once './src/Views/Footer.php';
			return;
		}

		include_once './src/Views/User/Registered.php';
		include_once './src/Views/Footer.php';
	}

	/**
	 * Show form for request password reset and send email.
	 */
	public function request_password_reset() {
		if ( empty( $_POST['username'] ) ) {
			include_once './src/Views/Header.php';
			include_once './src/Views/User/RequestPasswordReset.php';
			include_once './src/Views/Footer.php';
			return;
		}

		$this->user->send_password_link();

		include_once './src/Views/Header.php';
		echo 'Schau in Dein Postfach.';
		include_once './src/Views/Footer.php';
	}

	/**
	 * Reset password page.
	 */
	public function reset_password() {
		if ( empty( $_POST['password'] ) ) {
			$token = $_GET['token'] ?? '';
			include_once './src/Views/Header.php';
			include_once './src/Views/User/ResetPassword.php';
			include_once './src/Views/Footer.php';
			return;
		}

		// Perform the password reset.
		if (
			isset( $_POST['token'] ) &&
			isset( $_POST['password'] ) &&
			isset( $_POST['password_repeat'] ) &&
			$_POST['password'] === $_POST['password_repeat']
		) {
			$token    = $_POST['token'];
			$password = $_POST['password'];
			if ( ! $this->user->set_password( $token, $password ) ) {
				$this->no_access();
			}

			include_once './src/Views/Header.php';
			include_once './src/Views/User/PasswordResetted.php';
			include_once './src/Views/Footer.php';
			return;
		}

	}


	/**
	 * The home page.
	 */
	public function index() {
		include_once './src/Views/Header.php';
		include_once './src/Views/Home.php';
		include_once './src/Views/Footer.php';
	}

	/**
	 * Fail gracefully
	 *
	 * @param mixed $name Method.
	 * @param mixed $arguments Arguments.
	 * @return void
	 */
	public function __call( $name, $arguments ) {
		$this->no_access();
	}

	/**
	 * Fail gracefully.
	 */
	private function no_access() {
		header( 'HTTP/1.0 404 Not Found.' );
		die();
	}
}
