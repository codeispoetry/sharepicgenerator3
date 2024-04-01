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
	 * Env variable like user, config, mailer, etc..
	 *
	 * @var object
	 */
	private $env;


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
	 * Raw data.
	 *
	 * @var string
	 */
	private $raw_data;

	/**
	 * The body class.
	 *
	 * @var string
	 */
	private $body_class;

	/**
	 * Should the output image be a jpg?
	 *
	 * @var bool
	 */
	private $jpg;


	/**
	 * The constructor. Reads the inputs, stores information.
	 *
	 * @param Env $env Environment with user, config, logger, mailer, etc.
	 */
	public function __construct( $env ) {
		$this->env = $env;

		$this->dir = $this->env->user->get_dir() . 'workspace';
		if ( ! file_exists( $this->dir ) ) {
			mkdir( $this->dir );
		}
		$this->file = $this->dir . '/sharepic.html';

		$data = json_decode( file_get_contents( 'php://input' ), true );

		if ( empty( $data ) ) {
			return;
		}

		$this->size['width']  = (int) ( $data['size']['width'] ?? 100 );
		$this->size['height'] = (int) ( $data['size']['height'] ?? 100 );
		$this->size['zoom']   = (float) ( $data['size']['zoom'] ?? 1 );
		$this->jpg            = (bool) ( $data['jpg'] ?? 1 );
		$this->template       = ( isset( $data['template'] ) ) ? $data['template'] : $this->file;
		$this->info           = ( isset( $data['name'] ) ) ? Helper::sanitze_az09( $data['name'] ) : 'no-name';
		$this->mode           = ( isset( $data['mode'] ) && in_array( $data['mode'], array( 'save', 'publish' ) ) ) ? $data['mode'] : 'save';
		$this->raw_data       = $data['data'] ?? '';
		$this->body_class     = ( isset( $data['body_class'] ) ) ? Helper::sanitze_az09( $data['body_class'] ) : '';

		if ( str_starts_with( $this->template, 'save' ) ) {
			$this->template = $this->env->user->get_dir() . $this->template;
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
		$workspace = $this->env->user->get_dir() . 'workspace/';
		$save_dir  = $this->env->user->get_dir() . 'save/';

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

		$cmd = "cp -R $workspace $save 2>&1";
		exec( $cmd, $output, $return_code );
		if ( 0 !== $return_code ) {
			$this->env->logger->error( implode( "\n", $output ) );
			$this->http_error( 'Could not save sharepic' );
		}

		// Write HTML-File.
		file_put_contents( $save . '/sharepic.html', $this->raw_data );

		// Write info file.
		$name = preg_replace( '/[^a-zA-Z0-9\säöüßÄÖÜ]/', '-', $this->info );
		file_put_contents( $save . '/info.json', json_encode( array( 'name' => $name ) ) );

		echo json_encode(
			array(
				'full_path'  => 'save/' . $id . '/sharepic.html',
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

		// The casting to int is necessary to prevent directory traversal.
		$save_dir = $this->env->user->get_dir() . '/save/' . (int) $sharepic;

		if ( ! file_exists( $save_dir ) ) {
			$this->http_error( 'Could not find sharepic' );
		}

		$cmd = sprintf( 'rm -rf %s 2>&1', escapeshellarg( $save_dir ) );
		exec( $cmd, $output, $return_code );
		if ( 0 !== $return_code ) {
			$this->env->logger->error( implode( "\n", $output ) );
			$this->http_error( 'Could not delete sharepic' );
		}

		echo json_encode( array( 'success' => true ) );
	}

	/**
	 * Creates a sharepic by taking the screenshot of the HTML.
	 */
	public function create() {
		$output_file = 'output.png';
		$path        = $this->env->user->get_dir() . $output_file;
		$config      = new Config();

		$doc = new \DOMDocument();
		libxml_use_internal_errors( true );
		// mb_convert_encoding is said to be deprecated, but not in the docs.
		$doc->loadHTML( @mb_convert_encoding( $this->raw_data, 'HTML-ENTITIES', 'UTF-8' ) );
		libxml_clear_errors();
		$this->html = filter_var( $doc->saveHTML(), FILTER_DEFAULT, FILTER_FLAG_STRIP_LOW );

		$this->set_zoom( 1 / $this->size['zoom'] );

		$scaffold = '<!DOCTYPE html><html lang="de"><head><meta charset="UTF-8"></head><body class="%s">%s</body></html>';
		file_put_contents( $this->file, sprintf( $scaffold, $this->body_class, $this->html ) );

		$cmd_preprend = ( 'local' === $this->env->config->get( 'Main', 'env' ) ) ? 'sudo' : '';

		$this->delete_unused_files();

		$cmd = sprintf(
			'%s google-chrome --no-sandbox --headless --disable-gpu --screenshot=%s --hide-scrollbars --window-size=%d,%d %s 2>/dev/null',
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

		$this->env->logger->access( 'Command executed: ' . $cmd . ' ' . implode( "\n", $output ) );

		if ( 0 !== $return_code ) {
			$this->env->logger->error( implode( "\n", $output ) );
			$this->http_error( 'Could not create file' );
		}

		if ( $this->jpg ) {
			$this->convert_to_jpg( $path );
			$output_file = substr( $output_file, 0, -3 ) . 'jpg';
		}

		$this->create_thumbnail( $path );

		echo json_encode( array( 'path' => 'index.php?c=proxy&r=' . rand( 1, 999999 ) . '&p=' . $output_file ) );
	}

	/**
	 * Loads an image from a URL.
	 */
	public function load_from_url() {
		$this->env->logger->access( 'Loading image from URL' );
		$data = json_decode( file_get_contents( 'php://input' ), true );

		$url = filter_var( $data['url'], FILTER_VALIDATE_URL );

		if ( ! $url ) {
			$this->http_error( 'Could not load image' );
			return;
		}

		if ( ! Helper::is_image_file_remote( $url ) ) {
			$this->http_error( 'Could not load image' );
			return;
		}

		// $this->delete_my_old_files();

		$extension = strtolower( pathinfo( $url, PATHINFO_EXTENSION ) );
		if ( ! in_array( $extension, array( 'jpg', 'jpeg', 'png', 'gif' ) ) ) {
			$this->http_error( 'Could not load image' );
			return;
		}
		$upload_file = $this->env->user->get_dir() . 'workspace/background.' . $extension;

		copy( $url, $upload_file );

		if ( ! Helper::is_image_file_local( $upload_file ) ) {
			unlink( $upload_file );
			$this->http_error( 'Could copy load image' );
			return;
		}

		$this->reduce_filesize( $upload_file );

		$this->env->logger->access( 'Image loaded from URL' );
		echo json_encode( array( 'path' => 'index.php?c=proxy&r=' . rand( 1, 999999 ) . '&p=workspace/background.' . $extension ) );

	}

	/**
	 * Converts an image to jpg.
	 *
	 * @param string $path The path to the image.
	 */
	private function convert_to_jpg( $path ) {
		$extension = strtolower( pathinfo( $path, PATHINFO_EXTENSION ) );

		if ( 'jpg' === $extension ) {
			return;
		}

		$cmd = sprintf(
			'convert %s %s 2>&1',
			$path,
			substr( $path, 0, -3 ) . 'jpg'
		);

		exec( $cmd, $output, $return_code );
		$this->env->logger->access( 'Command executed: ' . $cmd . ' ' . implode( "\n", $output ) );

		if ( 0 !== $return_code ) {
			$this->env->logger->error( $cmd . ' OUTPUT=' . implode( "\n", $output ) );
			$this->http_error( 'Could not convert image' );
		}
	}

	/**
	 * Creates a thumbnail and saves it to the tmp folder.
	 *
	 * @param string $path The path to the image.
	 */
	private function create_thumbnail( $path ) {
		$thumbnail = bin2hex( random_bytes( 16 ) ) . '.png';
		$cmd       = sprintf(
			'convert %s -resize 400x400 ../tmp/%s 2>&1',
			$path,
			$thumbnail
		);

		exec( $cmd, $output, $return_code );

		if ( 0 !== $return_code ) {
			$this->env->logger->error( $cmd . ' OUTPUT=' . implode( "\n", $output ) );
			$this->http_error( 'Could not create thumbnail' );
		}

	}

	/**
	 * Loads the sharepic.
	 *
	 * @throws \Exception If the file does not exist.
	 */
	public function load() {
		try {
			$real_path    = realpath( $this->template );
			$template_dir = realpath( dirname( __FILE__, 3 ) ) . '/templates/';
			$user_dir     = realpath( $this->env->user->get_dir() );

			if ( ! $real_path ) {
				throw new \Exception( 'File does not exist' );
			}

			// Do only load from template or user directory.
			if ( ! str_starts_with( $real_path, $template_dir ) && ! str_starts_with( $real_path, $user_dir ) ) {
				throw new \Exception( 'File may not be serverd' );
			}

			// If the file is in the user directory (it is saved), copy all files to workspace.
			if ( str_starts_with( $real_path, $user_dir ) ) {
				$workspace = $user_dir . '/workspace';

				$cmd = sprintf(
					'cp -R %s/* %s 2>&1',
					dirname( $real_path ),
					$workspace
				);

				exec( $cmd, $output, $return_code );
				$this->env->logger->access( 'Command executed: ' . $cmd . ' ' . implode( "\n", $output ) );

				if ( 0 !== $return_code ) {
					$this->env->logger->alarm( $cmd . ' OUTPUT=' . implode( "\n", $output ) );
					$this->http_error( 'Could not copy files' );
				}
			}

			echo file_get_contents( $this->template );
		} catch ( \Exception $e ) {
			$this->env->logger->alarm( $this->template . ' ' . $e->getMessage() );
			$this->http_error( 'Could not load file ' );
		}
	}

	/**
	 * Uploads an images
	 */
	public function upload() {
		if ( ! isset( $_FILES['file'] ) ) {
			return;
		}

		if ( is_array( $_FILES['file']['name'] ) ) {
			$this->env->logger->alarm( 'Multiple files uploaded' );
			$this->http_error( 'Could not upload file' );
		}

		$file = $_FILES['file'];

		// $this->delete_my_old_files();

		$extension   = strtolower( pathinfo( $file['name'], PATHINFO_EXTENSION ) );
		$upload_file = $this->env->user->get_dir() . 'workspace/background.' . $extension;

		if ( ! move_uploaded_file( $file['tmp_name'], $upload_file ) ) {
			$this->http_error( 'Could not upload file. Code 1.' );
		}

		if ( ! Helper::is_image_file_local( $upload_file ) ) {
			unlink( $upload_file );
			$this->http_error( 'Could not upload image. Code 2.' );
			return;
		}

		$this->reduce_filesize( $upload_file );

		echo json_encode( array( 'path' => 'index.php?c=proxy&r=' . rand( 1, 999999 ) . '&p=workspace/background.' . $extension ) );
	}

	/**
	 * Uploads an addpic
	 */
	public function upload_addpic() {
		if ( ! isset( $_FILES['file'] ) ) {
			return;
		}

		if ( is_array( $_FILES['file']['name'] ) ) {
			$this->env->logger->alarm( 'Multiple files uploaded' );
			$this->http_error( 'Could not upload file' );
		}

		$file = $_FILES['file'];

		$extension     = strtolower( pathinfo( $file['name'], PATHINFO_EXTENSION ) );
		$raw_file_path = 'workspace/addpic-' . rand() . '.' . $extension;
		$upload_file   = $this->env->user->get_dir() . $raw_file_path;

		if ( ! move_uploaded_file( $file['tmp_name'], $upload_file ) ) {
			$this->http_error( 'Could not upload file' );
		}

		if ( ! Helper::is_image_file_local( $upload_file ) ) {
			unlink( $upload_file );
			$this->http_error( 'Could not upload image. Code 2.' );
			return;
		}

		$this->reduce_filesize( $upload_file, 2000, 1000 );

		$return = array(
			'path' => 'index.php?c=proxy&r=' . rand( 1, 999999 ) . '&p=' . $raw_file_path,
		);

		$this->env->logger->access( json_encode( $return ) );

		echo json_encode( $return );
	}

	/**
	 * Deletes old files.
	 *
	 * @deprecated This method will soon be deleted.
	 */
	private function delete_my_old_files() {
		$files = glob( $this->env->user->get_dir() . 'workspace/background.*' );

		foreach ( $files as $file ) {
			if ( is_file( $file ) ) {
				unlink( $file );
			}
		}
	}

	/**
	 * Deletes unused files from workspace.
	 *
	 * @deprecated This method will soon be deleted.
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

		$file = escapeshellarg( $file );
		$cmd  = sprintf(
			'mogrify -resize %1$dx%1$d  -define jpeg:extent=%2$dkb %3$s 2>&1',
			(int) $max_pixels,
			(int) $max_filesize,
			$file
		);
		exec( $cmd, $output, $return_code );
		if ( 0 !== $return_code ) {
			$this->env->logger->error( implode( "\n", $output ) );
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
