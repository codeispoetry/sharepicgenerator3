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
	 * Path to the users workspace.
	 *
	 * @var string
	 */
	private $dir;

	/**
	 * Saving or publishing.
	 *
	 * @var string
	 */
	private $mode;

	/**
	 * The constructor. Reads the inputs, stores information.
	 */
	public function __construct() {
		$user       = new User();
		$this->user = $user->get_user_by_token();
		$this->dir  = 'users/' . $this->user . '/workspace';
		if ( ! file_exists( $this->dir ) ) {
			mkdir( $this->dir );
		}
		$this->file = $this->dir . '/sharepic.html';

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
		$this->mode           = $data['mode'] ?? 'save';
		$body_class           = $data['body_class'] ?? '';

		if ( ! empty( $data['data'] ) ) {
			$this->html = $data['data'];

			$this->set_zoom( 1 / $this->size['zoom'] );

			$scaffold = '<!DOCTYPE html><html lang="de"><head><meta charset="UTF-8"></head><body class="%s">%s</body></html>';
			file_put_contents( $this->file, sprintf( $scaffold, $body_class, $this->html ) );
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

		if ( 'publish' === $this->mode ) {
			$save_dir = 'templates/mint/community/';
		}

		$id   = rand( 1000000, 9999999 );
		$save = $save_dir . $id;

		$save_count = count( glob( $save_dir . '/*', GLOB_ONLYDIR ) );
		if ( $save_count > 10 ) {
			$this->http_error( 'Too many files' );
		}

		if ( ! file_exists( $save_dir ) ) {
			mkdir( $save_dir );
		}

		$this->delete_unused_files();

		$cmd = "cp -R $workspace $save 2>&1 && chmod -R 775 $save 2>&1";
		exec( $cmd, $output, $return_code );
		if ( 0 !== $return_code ) {
			$this->logger->error( implode( "\n", $output ) );
			$this->http_error( 'Could not save sharepic' );
		}

		// Rewrite paths.
		$cmd = "sed -i 's#$workspace#$save/#g' $save/sharepic.html 2>&1";
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
	 * Creates a sharepic by taking the screenshot of the HTML.
	 */
	public function create() {
		$path   = 'users/' . $this->user . '/output.png';
		$config = new Config();

		$cmd_preprend = ( 'local' === $this->config->get( 'Main', 'env' ) ) ? 'sudo' : '';

		$this->delete_unused_files();

		$cmd = sprintf(
			'%s google-chrome --no-sandbox --headless --disable-gpu --screenshot=%s --hide-scrollbars --window-size=%d,%d %s 2>&1',
			$cmd_preprend,
			$path,
			(int) $this->size['width'],
			(int) $this->size['height'],
			escapeshellarg( $this->file )
		);

		if ( $config->get( 'Main', 'engine' ) === 'puppeteer' ) {
			$cmd = sprintf(
				'%s xvfb-run --auto-servernum --server-num=1 node puppeteer.js %s %d %d file:///var/www/html/%s 2>&1',
				$cmd_preprend,
				$path,
				(int) $this->size['width'],
				(int) $this->size['height'],
				escapeshellarg( $this->file )
			);
		}

		exec( $cmd, $output, $return_code );

		$this->logger->access( 'Command executed: ' . $cmd . ' ' . implode( "\n", $output ) );

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
		$this->logger->access( 'Loading image from URL' );
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
		$this->reduce_filesize( $upload_file );

		$this->logger->access( 'Image loaded from URL' );
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

		$this->reduce_filesize( $upload_file );

		echo json_encode( array( 'path' => $upload_file ) );
	}

	/**
	 * Uploads an addpic
	 */
	public function upload_addpic() {
		if ( ! isset( $_FILES['file'] ) ) {
			return;
		}

		$file = $_FILES['file'];

		$extension = strtolower( pathinfo( $file['name'], PATHINFO_EXTENSION ) );
		if ( ! in_array( $extension, array( 'jpg', 'jpeg', 'png' ) ) ) {
			$this->http_error( 'Invalid file type' );
		}

		$upload_file = 'users/' . $this->user . '/workspace/addpic-' . rand() . '.' . $extension;

		if ( ! move_uploaded_file( $file['tmp_name'], $upload_file ) ) {
			$this->http_error( 'Could not upload file' );
		}

		$this->reduce_filesize( $upload_file, 2000, 1000 );

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
	 * Deletes unused files from workspace.
	 */
	private function delete_unused_files() {
		$html = file_get_contents( $this->file );

		$dom = new \DOMDocument();
		libxml_use_internal_errors( true ); // Suppress warnings.
		$dom->loadHTML( $html );
		libxml_clear_errors();

		// IMG tags.
		$images     = $dom->getElementsByTagName( 'img' );
		$used_files = array_map(
			function( $image ) {
				return $image->getAttribute( 'src' );
			},
			iterator_to_array( $images )
		);

		// Background images.
		$elements = $dom->getElementsByTagName( '*' );
		foreach ( $elements as $element ) {
			$style = $element->getAttribute( 'style' );

			preg_match_all( '/url\(([^)]+)\)/', $style, $matches );

			foreach ( $matches[1] as $url ) {
				$used_files[] = substr( $url, 1, -1 );
			}
		}

		$used_files = array_map(
			function( $file ) {
				return explode( '?', $file )[0];
			},
			$used_files
		);

		// Delete unused files.
		$available_files = glob( $this->dir . '/*.{jpg,jpeg,png,gif}', GLOB_BRACE );

		foreach ( $available_files as $file ) {
			if ( ! in_array( $file, $used_files ) ) {
				unlink( $file );
			}
		}
	}

	/**
	 * Reduces the filesize of an image.
	 *
	 * @param string $file The file to reduce.
	 * @param int    $max_pixels The maximum number of pixels.
	 * @param int    $max_filesize The maximum filesize in kb.
	 */
	private function reduce_filesize( $file, $max_pixels = 4500, $max_filesize = 3000 ) {
		if ( filesize( $file ) < $max_filesize * 1024 ) {
			return;
		}

		$cmd = sprintf(
			'mogrify -resize %1$dx%1$d  -define jpeg:extent=%2$dkb %3$s 2>&1',
			$max_pixels,
			$max_filesize,
			$file
		);
		exec( $cmd, $output, $return_code );
		if ( 0 !== $return_code ) {
			$this->logger->error( implode( "\n", $output ) );
			$this->http_error( 'Could convert image' );
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
