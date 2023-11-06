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
		include_once './src/Views/Header.html';
		include_once './src/Views/Creator.html';
		include_once './src/Views/Footer.html';
	}

	/**
	 * The home page.
	 */
	public static function index() {
		self::create();
	}
}
