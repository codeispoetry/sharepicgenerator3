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
	 *
	 * @param string $config_file The path to the config file.
	 */
	public function __construct( $config_file = '../config.ini' ) {
		if ( ! file_exists( $config_file ) ) {
			die( 'Please create a config.ini file.' );
		}
		$this->config = parse_ini_file( $config_file, true );
	}


	/**
	 * Getter for the config array.
	 *
	 * @param string $section The section of the config.
	 * @param string $key The key of the config.
	 * @return string
	 */
	public function get( $section, $key = null ) {
		if ( null === $key ) {
			return $this->config[ $section ];
		}

		if ( ! isset( $this->config[ $section ][ $key ] ) ) {
			return false;
		}
		return $this->config[ $section ][ $key ];
	}

	/**
	 * Setter for the config array.
	 *
	 * @param string $section The section of the config.
	 * @param string $key The key of the config.
	 * @param string $value The value of the config.
	 */
	public function set( $section, $key, $value ) {
		$this->config[ $section ][ $key ] = $value;
	}
}
