<?php
namespace Sharepicgenerator\Controllers;

/**
 * Frontend Logger controller.
 */
class Felogger {

	/**
	 * Env variable like user, config, mailer, etc..
	 *
	 * @var object
	 */
	private $env;


	/**
	 * The constructor.
	 *
	 * @param Env $env Environment with user, config, logger, mailer, etc.
	 */
	public function __construct( $env ) {
		$this->env = $env;
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

		$short = $data['short'];
		unset( $data['short'] );

		$data = Helper::sanitize_log( join( ' ', $data ) );

		$infos = array(
			'time' => gmdate( 'Y-m-d H:i:s' ),
			'user' => $this->env->user->get_username(),
			'data' => $data,
		);

		if ( ! file_put_contents( $file, join( "\t", $infos ) . "\n", FILE_APPEND | LOCK_EX ) ) {
			$this->env->logger->error( 'FrontendLogger error: ' . $file . ' could not be written.' );
			$this->http_error( 'Could not log normal behaviour.' );
		}

		if ( empty( $short ) ) {
			return;
		}
		try {
			$db = new \SQLite3( '../logfiles/usage.db' );
			$db->exec( 'CREATE TABLE IF NOT EXISTS logs (time DATETIME, user TEXT, short TEXT, data TEXT)' );
			$stmt = $db->prepare( 'INSERT INTO logs (time, user, short, data) VALUES (:time, :user, :short, :data)' );
			$stmt->bindValue( ':time', $infos['time'], SQLITE3_TEXT );
			$stmt->bindValue( ':user', $infos['user'], SQLITE3_TEXT );
			$stmt->bindValue( ':short', $short, SQLITE3_TEXT );
			$stmt->bindValue( ':data', $infos['data'], SQLITE3_TEXT );
			$stmt->execute();
			$db->close();
		} catch ( \Exception $e ) {
			$this->env->logger->error( 'SQLite3 logging error: ' . $e->getMessage() );
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
			'user' => $this->env->user->get_username(),
			'data' => $data,
		);

		if ( ! file_put_contents( $file, join( "\t", $infos ) . "\n", FILE_APPEND | LOCK_EX ) ) {
			$this->env->logger->error( 'FrontendLogger error: ' . $file . ' could not be written.' );
			$this->http_error( 'Could not log normal behaviour.' );
		}

		if ( $this->env->config->get( 'Main', 'env' ) !== 'local' ) {
			$this->env->mailer->send( 'mail@tom-rose.de', 'Bug report', $data );
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
