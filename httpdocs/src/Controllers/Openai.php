<?php
namespace Sharepicgenerator\Controllers;

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
	 * Env variable like user, config, mailer, etc..
	 *
	 * @var object
	 */
	private $env;

	/**
	 * The constructor.
	 *
	 * @param Env $env Environment with user, config, logger, mailer, etc.
	 */
	public function __construct( $env ) {
		$this->env    = $env;
		$this->apikey = $this->env->config->get( 'OpenAI', 'apikey' );
	}

	/**
	 * Function, that is called from the frontend to get the response from the OpenAI API.
	 *
	 * @return void
	 */
	public function dalle() {
		$data = json_decode( file_get_contents( 'php://input' ), true );

		if ( empty( $data ) || empty( $data['prompt'] ) ) {
			header( 'HTTP/1.0 400 No prompt given.' );
			die();
		}

		$prompt = Helper::sanitize_url( $data['prompt'] );

		$response = $this->curl( $prompt );

		$json = json_decode( $response );

		if ( empty( $json ) || empty( $json->data ) ) {
			header( 'HTTP/1.0 500 ' . $json->error->message );
			die();
		}
		$remote_url = $json->data[0]->url;
		$local_file = '../users/' . $this->env->user->get_username() . '/workspace/background.png';
		copy( $remote_url, $local_file );

		if ( Helper::is_image_file_local( $local_file ) === false ) {
			unlink( $local_file );
			$this->http_error( 'Could not use image.' );
			return;
		}
		$json->local_file = 'index.php?c=proxy&r=' . rand( 1, 999999 ) . '&p=workspace/background.png';

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

		if ( ! $response || curl_errno( $ch ) ) {
			die( 'Error: "' . curl_error( $ch ) . '" - Code: ' . curl_errno( $ch ) );
		}

		$info        = curl_getinfo( $ch );
		$header_size = $info['header_size'];
		$header      = substr( $response, 0, $header_size );
		$body        = substr( $response, $header_size );
		file_put_contents( 'ratelimit.txt', $header );
		if ( preg_match( '/openai-ratelimit-remaining: (\d+)/i', $header, $matches ) ) {
			file_put_contents( 'ratelimit.txt', $matches[1] );
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
