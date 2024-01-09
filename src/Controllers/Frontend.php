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
			header( 'Location: index.php' );
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
		$title   = _( 'Reset password' );
		$message = _( 'Please check your email for the reset link.' );

		if ( ! $this->user->send_password_link() ) {
			$title   = _( 'Error' );
			$message = _( 'An error occurred. Please try again later.' );
		}
		include_once './src/Views/Hero.php';
	}

	/**
	 * Reset password page.
	 */
	public function reset_password() {
		if ( empty( $_POST['password'] ) ) {
			$token = $_GET['token'] ?? '';
			if ( isset( $_GET['newpassword'] ) ) {
				$title        = _( 'Create your password' );
				$submit_value = _( 'Set your new password' );
			} else {
				$title        = _( 'Reset password' );
				$submit_value = _( 'Reset password' );
			}

			include_once './src/Views/User/ResetPassword.php';
			return;
		}

		if (
			! isset( $_POST['token'] ) ||
			! isset( $_POST['password'] ) ||
			! isset( $_POST['password_repeat'] ) ||
			$_POST['password'] !== $_POST['password_repeat']
		) {

			$title   = _( 'An error occured' );
			$message = '<a href="/">' . _( 'Your password could not be set. Did you repeat it correctly?' ) . '</a>';
			include_once './src/Views/Hero.php';
			return;
		}

		// Perform the password reset.
		$token    = $_POST['token'];
		$password = $_POST['password'];
		if ( ! $this->user->set_password( $token, $password ) ) {
			$this->no_access();
		}

			$title   = _( 'Password reset' );
			$message = '<a href="index.php">' . _( 'Your password has been reset. Please login.' ) . '</a>';
			include_once './src/Views/Hero.php';
			return;

	}

	/**
	 * The Log view.
	 */
	public function log() {
		$this->user->get_user_by_token();
		if ( ! $this->user->is_admin() ) {
			$this->no_access();
		}
		include_once './src/Views/Logs/Log.php';
	}

	/**
	 * The Log view.
	 */
	public function sharepics() {
		$this->user->get_user_by_token();
		if ( ! $this->user->is_admin() ) {
			$this->no_access();
		}

		$cmd = 'find /tmp -type f \( -name "*.jpg" -o -name "*.png" -o -name "*.gif" \) -mtime +7 -exec rm -f {} \;';
		exec( $cmd, $output, $return_var );

		include_once './src/Views/Logs/Sharepics.php';
	}

	/**
	 * Log the user out
	 */
	public function logout() {
		$this->user->logout();
		$title   = _( 'Logout' );
		$message = _( 'You have been logged out.' );
		include_once './src/Views/Hero.php';
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
