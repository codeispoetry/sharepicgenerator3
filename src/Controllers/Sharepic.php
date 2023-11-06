<?php
namespace Sharepicgenerator\Controllers;

/**
 * Sharepic controller.
 */
class Sharepic {

	/**
	 * The file to write to.
	 *
	 * @var string
	 */
	private $file = 'user/tom/workspace/sharepic.html';

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
	 * The constructor. Reads the inputs, stores information.
	 */
	public function __construct() {
		$data = json_decode( file_get_contents( 'php://input' ), true );

		$this->size['width']  = $data['size']['width'] ?? 100;
		$this->size['height'] = $data['size']['height'] ?? 100;
		file_put_contents( $this->file, $data['data'] );
	}


	/**
	 * Creates a sharepic.
	 */
	public function create() {
		$cmd = sprintf(
			'sudo google-chrome --no-sandbox --headless --disable-gpu --screenshot=output.png --hide-scrollbars --window-size=%d,%d http://localhost/%s',
			$this->size['width'],
			$this->size['height'],
			$this->file
		);

		$output = shell_exec( $cmd );

		echo json_encode( array( 'a' => 'done' ) );
	}
}
