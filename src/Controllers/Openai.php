<?php
namespace Sharepicgenerator\Controllers;

use Sharepicgenerator\Controllers\User;
use Sharepicgenerator\Controllers\Logger;

/**
 * Open AI controller.
 */
class Openai {
	/**
	 * The api key.
	 *
	 * @var string
	 */
	private $apikey;

	/**
	 * The user object.
	 *
	 * @var User
	 */
	private $user;

	/**
	 * The constructor.
	 */
	public function __construct() {
		global $config;
		$this->apikey = $config->get( 'OpenAI', 'apikey' );

		$user       = new User();
		$this->user = $user->get_user_by_token();
	}

	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function dalle() {
		$data = json_decode( file_get_contents( 'php://input' ), true );

		if ( empty( $data ) || empty( $data['prompt'] ) ) {
			header( 'HTTP/1.0 400 No prompt given.' );
			die();
		}

		if ( true ) { // use real api.
			$response = $this->curl( $data['prompt'] );
			$json     = json_decode( $response );

			$remote_url = $json->data[0]->url;
			$local_file = 'users/' . $this->user . '/workspace/dalle.png';
			copy( $remote_url, $local_file );
			$json->local_file = $local_file;

			file_put_contents( 'tmp/dalle.json', $json );

		} else {
			$response         = file_get_contents( 'tmp/dalle.json' );
			$json             = json_decode( $response );
			$local_file       = 'users/' . $this->user . '/workspace/dalle.png';
			$json->local_file = $local_file;
		}

		echo json_encode( $json );
	}

	/**
	 * Performs the curl request.
	 *
	 * @param string $prompt The prompt.
	 * @return string The response.
	 */
	private function curl( $prompt ) {
		if ( empty( $prompt ) ) {
			return false;
		}

		$url = 'https://api.openai.com/v1/images/generations';

		$data = array(
			'model'  => 'dall-e-3',
			'prompt' => $prompt,
			'n'      => 1,
			'size'   => '1024x1024',
		);

		$options = array(
			CURLOPT_URL            => $url,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_POST           => true,
			CURLOPT_POSTFIELDS     => json_encode( $data ),
			CURLOPT_HTTPHEADER     => array(
				'Content-Type: application/json',
				"Authorization: Bearer $this->apikey",
			),
		);

		$ch = curl_init();
		curl_setopt_array( $ch, $options );
		$response = curl_exec( $ch );

		if ( ! $response ) {
			die( 'Error: "' . curl_error( $ch ) . '" - Code: ' . curl_errno( $ch ) );
		}

		curl_close( $ch );

		return $response;
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
