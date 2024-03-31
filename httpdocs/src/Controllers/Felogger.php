<?php
namespace Sharepicgenerator\Controllers;

/**
 * Frontend Logger controller.
 */
class Felogger {

	/**
	 * The username.
	 *
	 * @var string
	 */
	private $user;

	/**
	 * The logger object.
	 *
	 * @var Logger
	 */
	private $logger;

	/**
	 * The config object.
	 *
	 * @var Config
	 */
	private $config;

	/**
	 * The constructor.
	 *
	 * @param string $user The user object.
	 * @param string $config The config object.
	 * @param string $logger The logger object.
	 */
	public function __construct( $user, $config, $logger ) {
		$this->user   = $user;
		$this->config = $config;
		$this->logger = $logger;
	}

	/**
	 * Logs normal behaviour.
	 */
	public function normal() {
		$file = '../logfiles/usage.log';
		$data = json_decode( file_get_contents( 'php://input' ), true );

		if ( empty( $data ) ) {
			return;
		}

		$data = Helper::sanitize_log( join( ' ', $data ) );

		$infos = array(
			'time' => gmdate( 'Y-m-d H:i:s' ),
			'user' => $this->user->get_username(),
			'data' => $data,
		);

		if ( ! file_put_contents( $file, join( "\t", $infos ) . "\n", FILE_APPEND | LOCK_EX ) ) {
			$this->logger->error( 'FrontendLogger error: ' . $file . ' could not be written.' );
			$this->http_error( 'Could not log normal behaviour.' );
		}
	}


	/**
	 * Logs bugs
	 */
	public function bug() {
		$file = '../logfiles/bugs.log';
		$data = json_decode( file_get_contents( 'php://input' ), true );

		if ( empty( $data ) ) {
			return;
		}

		$data = Helper::sanitize_log( join( ' ', $data ) );

		$infos = array(
			'time' => gmdate( 'Y-m-d H:i:s' ),
			'user' => $this->user->get_username(),
			'data' => $data,
		);

		if ( ! file_put_contents( $file, join( "\t", $infos ) . "\n", FILE_APPEND | LOCK_EX ) ) {
			$this->logger->error( 'FrontendLogger error: ' . $file . ' could not be written.' );
			$this->http_error( 'Could not log normal behaviour.' );
		}
	}


	/**
	 * Fail gracefully.
	 *
	 * @param string $message The error message.
	 */
	private function http_error( $message = 'Something went wrong' ) {
		header( 'HTTP/1.0 500 ' . $message );
		die();
	}

	/**
	 * Fail gracefully
	 *
	 * @param mixed $name Method.
	 * @param mixed $arguments Arguments.
	 * @return void
	 */
	public function __call( $name, $arguments ) {
		header( 'HTTP/1.0 404 No route.' );
		die();
	}

}
