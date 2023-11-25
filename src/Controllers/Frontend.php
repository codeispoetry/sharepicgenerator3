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
		}
		include_once './src/Views/Creator.php';
	}

	/**
	 * The registration page.
	 */
	public function register() {
		include_once './src/Views/Header.php';
		if ( isset( $_POST['register_mail'] ) ) {
			if ( $this->user->register( $_POST['register_mail'] ) ) {
				include_once './src/Views/Register/Registered.php';
			} else {
				include_once './src/Views/Register/NotRegistered.php';
			}
		} else {
			include_once './src/Views/Register/Register.php';
		}
		include_once './src/Views/Footer.php';
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
		header( 'HTTP / 1.0 404 Not Found . ' );
			die();
	}
}
