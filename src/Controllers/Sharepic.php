<?php
namespace Sharepicgenerator\Controllers;

use Sharepicgenerator\Controllers\User;
/**
 * Sharepic controller.
 */
class Sharepic {

	/**
	 * The file to write to.
	 *
	 * @var string
	 */
	private $file = 'users/tom/workspace/sharepic.html';

	/**
	 * The template to be loaded
	 *
	 * @var string
	 */
	private $template = 'users/tom/workspace/sharepic.html';

	/**
	 * The size of the sharepic.
	 *
	 * @var array
	 */
	private $size = array(
		'width'  => 100,
		'height' => 100,
	);

	/**
	 * The user object.
	 *
	 * @var User
	 */
	private $user;

	/**
	 * The constructor. Reads the inputs, stores information.
	 */
	public function __construct() {
		$user       = new User();
		$this->user = $user->get_user_by_token();

		if ( empty( $this->user ) ) {
			$this->no_access();
			die();
		}

		$data = json_decode( file_get_contents( 'php://input' ), true );

		if ( empty( $data ) ) {
			return;
		}

		$this->size['width']  = $data['size']['width'] ?? 100;
		$this->size['height'] = $data['size']['height'] ?? 100;
		$this->template       = $data['template'] ?? $this->file;

		if ( ! empty( $data['data'] ) ) {
			file_put_contents( $this->file, $data['data'] );
		}
	}

	/**
	 * Creates a sharepic.
	 */
	public function create() {
		$path = 'users/' . $this->user . '/output.png';

		$cmd = sprintf(
			'sudo google-chrome --no-sandbox --headless --disable-gpu --screenshot=%s --hide-scrollbars --window-size=%d,%d http://localhost/%s',
			$path,
			$this->size['width'],
			$this->size['height'],
			$this->file
		);

		$output = shell_exec( $cmd );

		echo json_encode( array( 'path' => $path ) );
	}

	/**
	 * Loads the sharepic.
	 */
	public function load() {
		echo file_get_contents( $this->template );
	}

	/**
	 * Fail gracefully.
	 */
	private function no_access() {
		header( 'HTTP/1.0 403 Forbidden' );
		die();
	}
}
