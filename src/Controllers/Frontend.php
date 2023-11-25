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
		if ( ! isset( $_POST['register_mail'] ) ) {
			$this->no_access();
		}

		if ( ! $this->user->register( $_POST['register_mail'] ) ) {
			$title   = _( 'Register' );
			$message = _( 'Registration failed. Are you already registered?' );
			include_once './src/Views/Hero.php';
			return;
		}

		$title   = _( 'Register' );
		$message = _( 'You have successfully registered. Please check your mails to confirm your registration.' );
		include_once './src/Views/Hero.php';
	}

	/**
	 * Show form for request password reset and send email.
	 */
	public function request_password_reset() {
		if ( ! $this->user->send_password_link() ) {
			$this->no_access();
		}

		$title   = _( 'Reset password' );
		$message = _( 'Please check your email for the reset link.' );
		include_once './src/Views/Hero.php';
	}

	/**
	 * Reset password page.
	 */
	public function reset_password() {
		if ( empty( $_POST['password'] ) ) {
			$token = $_GET['token'] ?? '';
			include_once './src/Views/User/ResetPassword.php';
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

			$title   = _( 'Password reset' );
			$message = '<a href="/">' . _( 'Your password has been reset. Please login.' ) . '</a>';
			include_once './src/Views/Hero.php';
			return;
		}

	}


	/**
	 * The home page.
	 */
	public function index() {
		$body = 'home';
		include_once './src/Views/Home.php';
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
