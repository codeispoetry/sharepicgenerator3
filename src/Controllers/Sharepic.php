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
	 * Infos about the sharepic, like name.
	 *
	 * @var string
	 */
	private $info;

	/**
	 * The constructor. Reads the inputs, stores information.
	 */
	public function __construct() {
		$user       = new User();
		$this->user = $user->get_user_by_token();
		$dir        = 'users/' . $this->user . '/workspace';
		if ( ! file_exists( $dir ) ) {
			mkdir( $dir );
		}
		$this->file = $dir . '/sharepic.html';

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
		$this->info           = $data['name'] ?? 'no-name';

		if ( ! empty( $data['data'] ) ) {
			$this->html = $data['data'];

			$this->set_zoom( 1 / $this->size['zoom'] );

			$scaffold = '<!DOCTYPE html><html lang="de"><head><meta charset="UTF-8"></head><body>%s</body></html>';
			file_put_contents( $this->file, sprintf( $scaffold, $this->html ) );
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
	 * Saves the sharepic.
	 */
	public function save() {
		$workspace = 'users/' . $this->user . '/workspace/';
		$save_dir  = 'users/' . $this->user . '/save/';
		$id        = rand( 1000000, 9999999 );
		$save      = $save_dir . $id;

		$save_count = count( glob( $save_dir . '/*', GLOB_ONLYDIR ) );
		if ( $save_count > 10 ) {
			$this->http_error( 'Too many files' );
		}

		if ( ! file_exists( $save_dir ) ) {
			mkdir( $save_dir );
		}

		$cmd = "cp -R $workspace $save 2>&1";
		exec( $cmd, $output, $return_code );
		if ( 0 !== $return_code ) {
			$this->logger->error( implode( "\n", $output ) );
			$this->http_error( 'Could not save sharepic' );
		}

		// Rewrite paths.
		$cmd = "sed -i 's/workspace/save\/$id/g' $save/sharepic.html 2>&1";
		exec( $cmd, $output, $return_code );
		if ( 0 !== $return_code ) {
			$this->logger->error( implode( "\n", $output ) );
			$this->http_error( 'Could not rewrite path' );
		}

		$name = preg_replace( '/[^a-zA-Z0-9\säöüßÄÖÜ]/', '-', $this->info );
		file_put_contents( $save . '/info.json', json_encode( array( 'name' => $name ) ) );

		echo json_encode(
			array(
				'full_path'  => $save . '/sharepic.html',
				'id'         => $id,
				'save_count' => $save_count,
			)
		);
		return true;
	}

	/**
	 * Deletes a sharepic.
	 */
	public function delete() {
		$data = json_decode( file_get_contents( 'php://input' ), true );

		$sharepic = $data['saving'] ?? false;

		if ( ! $sharepic ) {
			$this->http_error( 'Could not delete sharepic' );
		}

		$save_dir = './users/' . $this->user . '/save/' . (int) $sharepic;

		if ( ! file_exists( $save_dir ) ) {
			$this->http_error( 'Could not find sharepic' );
		}

		$cmd = "rm -rf $save_dir 2>&1";
		exec( $cmd, $output, $return_code );
		if ( 0 !== $return_code ) {
			$this->logger->error( implode( "\n", $output ) );
			$this->http_error( 'Could not delete sharepic' );
		}

		echo json_encode( array( 'success' => true ) );
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

		$this->logger->access( 'Command executed ' . $cmd );

		if ( 0 !== $return_code ) {
			$this->logger->error( implode( "\n", $output ) );
			$this->http_error( 'Could not create file' );
		}

		$this->create_thumbnail( $path );

		echo json_encode( array( 'path' => $path ) );
	}

	/**
	 * Loads an image from a URL.
	 */
	public function load_from_url() {
		$data = json_decode( file_get_contents( 'php://input' ), true );

		$url = $data['url'] ?? false;

		if ( ! $url ) {
			$this->http_error( 'Could not load image' );
		}

		$extension = strtolower( pathinfo( $url, PATHINFO_EXTENSION ) );
		if ( ! in_array( $extension, array( 'jpg', 'jpeg', 'png' ) ) ) {
			$this->http_error( 'Invalid file type' );
		}

		$this->delete_old_files();

		$upload_file = 'users/' . $this->user . '/workspace/background.' . $extension;

		copy( $url, $upload_file );

		echo json_encode( array( 'path' => $upload_file ) );
	}


	/**
	 * Creates a thumbnail and saves it to the tmp folder.
	 *
	 * @param string $path The path to the image.
	 */
	private function create_thumbnail( $path ) {
		$thumbnail = bin2hex( random_bytes( 16 ) ) . '.png';
		$cmd       = sprintf(
			'convert %s -resize 400x400 ./tmp/%s 2>&1',
			$path,
			$thumbnail
		);

		exec( $cmd, $output, $return_code );

		if ( 0 !== $return_code ) {
			$this->logger->error( implode( "\n", $output ) );
			$this->http_error( 'Could not create thumbnail' );
		}

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
