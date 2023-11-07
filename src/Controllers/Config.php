<?php
namespace Sharepicgenerator\Controllers;

/**
 * Configuration controller.
 */
class Config {

	/**
	 * The config array.
	 *
	 * @var array
	 */
	private $config;
	/**
	 * The constructor. Reads the config.ini file.
	 */
	public function __construct() {
		$config_file = 'config.ini';
		if ( ! file_exists( $config_file ) ) {
			die( 'Please create a config.ini file.' );
		}
		$this->config = parse_ini_file( 'config.ini', true );
	}


	/**
	 * Getter for the config array.
	 *
	 * @param string $section The section of the config.
	 * @param string $key The key of the config.
	 * @return string
	 */
	public function get( $section, $key ) {
		if ( ! isset( $this->config[ $section ][ $key ] ) ) {
			return false;
		}
		return $this->config[ $section ][ $key ];
	}


}
