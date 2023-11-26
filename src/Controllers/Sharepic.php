<?php
namespace Sharepicgenerator\Controllers;

use Sharepicgenerator\Controllers\User;
use Sharepicgenerator\Controllers\Logger;

/**
 * Sharepic controller.
 */
class Sharepic {

	/**
	 * The file to write to.
	 *
	 * @var string
	 */
	private $file;

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
	private $template;

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
	 * The constructor. Reads the inputs, stores information.
	 */
	public function __construct() {
		$user       = new User();
		$this->user = $user->get_user_by_token();
		$this->file = 'users/' . $this->user . '/workspace/sharepic.html';

		$this->logger = new Logger( $user );
		$this->config = new Config();

		if ( empty( $this->user ) ) {
			$this->http_error( 'no user' );
			die();
		}

		$data = json_decode( file_get_contents( 'php://input' ), true );

		if ( empty( $data ) ) {
			return;
		}

		$this->size['width']  = $data['size']['width'] ?? 100;
		$this->size['height'] = $data['size']['height'] ?? 100;
		$this->size['zoom']   = $data['size']['zoom'] ?? 1;
		$this->template       = $data['template'] ?? $this->file;

		if ( ! empty( $data['data'] ) ) {
			$this->html = $data['data'];

			$this->download_images();

			$this->set_zoom( 1 / $this->size['zoom'] );

			file_put_contents( $this->file, $this->html );
		}
	}

	/**
	 * Downloads images and rewrites html
	 */
	private function download_images() {
		$this->html = str_replace( '&quot;', "'", $this->html );

		preg_match_all( '/url\(\'([^)]+)\'\)/', $this->html, $matches );
		$urls = $matches[1];

		foreach ( $urls as $url ) {

			$masked_url = preg_quote( $url, '#' );
			if ( ! str_starts_with( $url, 'http' ) ) {
				$this->html = preg_replace( "#$masked_url#", '../../..' . $url, $this->html );
				continue;
			}

			$extension  = strtolower( pathinfo( $url, PATHINFO_EXTENSION ) );
			$local_file = 'users/' . $this->user . '/workspace/background.' . $extension;
			copy( $url, $local_file );

			$this->html = preg_replace( "#$masked_url#", '../../../' . $local_file, $this->html );
		}
	}

	/**
	 * Resizes the output.
	 *
	 * @param float $zoom The HTML to be rewritten.
	 */
	private function set_zoom( $zoom ) {
		$this->html = '<style class="server-only">body{ margin: 0; padding: 0;} #sharepic{ zoom: ' . $zoom . '; }</style>' . $this->html;
	}

	/**
	 * Creates a sharepic.
	 */
	public function create() {
		$path = 'users/' . $this->user . '/output.png';

		$cmd_preprend = ( 'local' === $this->config->get( 'Main', 'env' ) ) ? 'sudo' : '';

		$cmd = sprintf(
			'%s google-chrome --no-sandbox --headless --disable-gpu --screenshot=%s --hide-scrollbars --window-size=%d,%d %s 2>&1',
			$cmd_preprend,
			$path,
			(int) $this->size['width'],
			(int) $this->size['height'],
			escapeshellarg( $this->file )
		);

		exec( $cmd, $output, $return_code );

		if ( 0 !== $return_code ) {
			$this->logger->error( implode( "\n", $output ) );
			$this->http_error( 'Could not create file' );
		}

		echo json_encode( array( 'path' => $path ) );
	}

	/**
	 * Loads the sharepic.
	 *
	 * @throws \Exception If the file does not exist.
	 */
	public function load() {
		$file = '';
		try {
			if ( ! file_exists( $this->template ) ) {
				throw new \Exception( 'File does not exist' );
			}
			$file = file_get_contents( $this->template );
		} catch ( \Exception $e ) {
			$this->logger->error( $this->template . ' ' . $e->getMessage() );
			$this->http_error( 'Could not load file ' . $this->template );
		}

		echo $file;
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
			$this->http_error( 'Invalid file type' );
		}

		$this->delete_old_files();

		$upload_file = 'users/' . $this->user . '/workspace/background.' . $extension;

		if ( ! move_uploaded_file( $file['tmp_name'], $upload_file ) ) {
			$this->http_error( 'Could not upload file' );
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
