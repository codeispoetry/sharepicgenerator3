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
	 * The frontend controller.
	 */
	public function create() {
		if ( ! $this->user->login() ) {
			header( 'Location: /' );
		}

		include_once './src/Views/Header.php';
		include_once './src/Views/Creator.php';
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
}
