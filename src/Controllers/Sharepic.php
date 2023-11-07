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
	 * @param string $html The html to be rewritten.
	 * @return string
	 */
	private function download_images( $html ) {
		$html = html_entity_decode( $html );
		preg_match_all( '/url\((.*?)\);/', $html, $matches );

		if ( empty( $matches[0] ) || empty( $matches[1][0] ) ) {
			return $html;
		}

		$url = substr( $matches[1][0], 1, -1 );

		if ( ! str_starts_with( $url, 'http' ) ) {
			return $html;
		}

		$extension = strtolower( pathinfo( $url, PATHINFO_EXTENSION ) );
		if ( ! in_array( $extension, array( 'jpg', 'jpeg', 'png' ) ) ) {
			return $html;
		}

		$filename = 'background.' . $extension;
		$filepath = 'users/' . $this->user . '/workspace/' . $filename;
		file_put_contents( $filepath, file_get_contents( $url ) );

		$html = preg_replace( "#.$url.#", "'" . $filename . "'", $html );

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
	 * Fail gracefully.
	 */
	private function no_access() {
		header( 'HTTP/1.0 403 Forbidden' );
		die();
	}
}
