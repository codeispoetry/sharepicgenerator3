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
	 * The constructor.
	 */
	public function __construct() {
		$user       = new User();
		$this->user = $user->get_user_by_token();

		$this->logger = new Logger( $user );
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
			'user' => $this->user,
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
