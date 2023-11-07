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
			$html = $this->download_images( $data['data'] );
			file_put_contents( $this->file, $html );
		}
	}

	/**
	 * Downloads the images and rewrites html.
	 *
	 * @param string $inhtml The html to be rewritten.
	 * @return string
	 */
	private function download_images( $inhtml ) {
		$html = html_entity_decode( $inhtml );
		preg_match_all( '/url\((.*?)\);/', $html, $matches );

		if ( empty( $matches[0] ) || empty( $matches[1][0] ) ) {
			return $inhtml;
		}

		$url = substr( $matches[1][0], 1, -1 );

		if ( ! str_starts_with( $url, 'http' ) ) {
			return $inhtml;
		}

		$extension = strtolower( pathinfo( $url, PATHINFO_EXTENSION ) );
		if ( ! in_array( $extension, array( 'jpg', 'jpeg', 'png' ) ) ) {
			return $inhtml;
		}

		$filename = 'background.' . $extension;
		$filepath = 'users/' . $this->user . '/workspace/' . $filename;
		$files    = glob( 'users/' . $this->user . '/workspace/background.*' );

		foreach ( $files as $file ) {
			if ( is_file( $file ) ) {
				unlink( $file );
			}
		}
		file_put_contents( $filepath, file_get_contents( $url ) );

		$html = preg_replace( "#.$url.#", sprintf( "'/%s?r=%s'", $filepath, rand() ), $html );

		return $html;
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
	 * Uploads an images
	 */
	public function upload() {
		if ( ! isset( $_FILES['file'] ) ) {
			return;
		}

		$file = $_FILES['file'];

		$extension = strtolower( pathinfo( $file['name'], PATHINFO_EXTENSION ) );
		if ( ! in_array( $extension, array( 'jpg', 'jpeg', 'png' ) ) ) {
			$this->no_access();
		}

		$files = glob( 'users/' . $this->user . '/workspace/background.*' );

		foreach ( $files as $sfile ) {
			if ( is_file( $sfile ) ) {
				unlink( $sfile );
			}
		}

		$upload_file = 'users/' . $this->user . '/workspace/background.' . $extension;

		if ( ! move_uploaded_file( $file['tmp_name'], $upload_file ) ) {
			$this->no_access();
		}

		echo json_encode( array( 'path' => $upload_file ) );
	}

	/**
	 * Fail gracefully.
	 */
	private function no_access() {
		header( 'HTTP/1.0 403 Forbidden' );
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
		$this->no_access();
	}
}
