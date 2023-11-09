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
	 * The HTML for the sharepic.
	 *
	 * @var string
	 */
	private $html;

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
			$this->html = $data['data'];
			//$this->download_images( );
			$this->replace_paths( );

			$html = $this->resize_output( 2 );

			file_put_contents( $this->file, $this->html );
		}
	}

	/**
	 * Resizes the output.
	 *
	 * @param float $factor The HTML to be rewritten.
	 */
	private function resize_output( $factor ) {
		$this->html = '<style>#sharepic{ zoom: ' . $factor . '; }</style>' . $this->html;
		$this->size['width']  = $this->size['width'] * $factor;
		$this->size['height'] = $this->size['height'] * $factor;
	}

	/**
	 * Replace the paths in the html.
	 *
	 * @param string $inhtml The html to be rewritten.
	 */
	private function replace_paths(  ) {
		$this->html = preg_replace( '#/users/' . $this->user . '/workspace/#', '', $this->html );
		$this->html = preg_replace( '#/tenants/#', '../../../tenants/', $this->html );
	}

	/**
	 * Downloads the images and rewrites html.
	 */
	private function download_images( ) {
		$inhtml = $this->html;
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
		$this->delete_old_files();
		file_put_contents( $filepath, file_get_contents( $url ) );

		$html = preg_replace( "#.$url.#", sprintf( "'/%s?r=%s'", $filepath, rand() ), $html );

	}

	/**
	 * Creates a sharepic.
	 */
	public function create() {
		$path = 'users/' . $this->user . '/output.png';

		$cmd = sprintf(
			'sudo google-chrome --no-sandbox --headless --disable-gpu --screenshot=%s --hide-scrollbars --window-size=%d,%d %s',
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

		$this->delete_old_files();

		$upload_file = 'users/' . $this->user . '/workspace/background.' . $extension;

		if ( ! move_uploaded_file( $file['tmp_name'], $upload_file ) ) {
			$this->no_access();
		}

		echo json_encode( array( 'path' => $upload_file ) );
	}

	/**
	 * Deletes old files.
	 */
	private function delete_old_files() {
		$files = glob( 'users/' . $this->user . '/workspace/background.*' );

		foreach ( $files as $file ) {
			if ( is_file( $file ) ) {
				unlink( $file );
			}
		}
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
