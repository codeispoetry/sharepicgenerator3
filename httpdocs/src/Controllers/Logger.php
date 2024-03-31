<?php
namespace Sharepicgenerator\Controllers;

/**
 * Logger controller.
 */
class Logger {

	/**
	 * The logfile.
	 *
	 * @var string
	 */
	private $file;

	/**
	 * The elements of the line to be written later.
	 *
	 * @var array
	 */
	private $line;

	/**
	 * The constructor.
	 *
	 * @param User $user The user object.
	 * @throws \Exception If the log file is not writable.
	 */
	public function __construct( $user ) {
		$this->line = array(
			'timestamp' => gmdate( 'Y-m-d H:i:s' ),
			'user'      => $user->get_username(),
			'caller'    => '',
			'message'   => '',
		);
	}

	/**
	 * Prepares access.log
	 *
	 * @param string $message The info to log.
	 * @throws \Exception If the log file is not writable.
	 */
	public function access( $message ) {
		$this->line['message'] = $message;
		$this->file            = '../logfiles/access.log';
		$this->write();
	}

	/**
	 * Prepares error.log
	 *
	 * @param string $message The info to log.
	 */
	public function error( $message ) {
		$this->line['message'] = $message;
		$this->file            = '../logfiles/error.log';
		$this->write();
	}

	/**
	 * Prepares alarm.log
	 *
	 * @param string $message The info to log.
	 */
	public function alarm( $message ) {
		$this->line['message'] = $message;
		$this->file            = '../logfiles/alarm.log';
		$this->write();
	}

	/**
	 * Write the log info.
	 *
	 * @throws \Exception If the log file is not writable.
	 */
	private function write() {
		$backtrace            = debug_backtrace(); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_debug_backtrace
		$this->line['caller'] = $backtrace[2]['class'] . '::' . $backtrace[2]['function'];

		// Should data be more sanitized?
		$this->line['message'] = escapeshellarg( $this->line['message'] );

		$line = implode( ' | ', $this->line );

		try {
			file_put_contents( $this->file, $line . PHP_EOL, FILE_APPEND );
		} catch ( \Exception $e ) {
			throw new \Exception( $this->file . ' is not writable.' );
		}

	}

}
