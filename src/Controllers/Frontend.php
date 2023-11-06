<?php
namespace Sharepicgenerator\Controllers;

/**
 * Frontend controller.
 */
class Frontend {

	/**
	 * The frontend controller.
	 */
	public static function create() {
		include_once './src/Views/Header.php';
		include_once './src/Views/Creator.php';
		include_once './src/Views/Footer.php';
	}

	/**
	 * The home page.
	 */
	public static function index() {
		self::create();
	}
}
