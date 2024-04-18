<?php
namespace Sharepicgenerator\Controllers;

/**
 * Unsplash controller.
 * Proxies the Unsplash API.
 */
class Unsplash {

	/**
	 * Gets the Unsplash Key. If not found, returns 500.
	 */
	private static function get_api_key() {
		$config = new Config();
		$key    = $config->get( 'Unsplash', 'apikey' );
		if ( empty( $key ) ) {
			header( 'HTTP/1.0 500 No API key found' );
			exit;
		}

		return $key;
	}
	/**
	 * Proxy function
	 *
	 * @param string $url URL to proxy.
	 * @return string
	 */
	public static function proxy( $url ) {
		$curl = curl_init();

		curl_setopt_array(
			$curl,
			array(
				CURLOPT_URL            => $url,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING       => '',
				CURLOPT_MAXREDIRS      => 10,
				CURLOPT_TIMEOUT        => 30,
				CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST  => 'GET',
				CURLOPT_HTTPHEADER     => array(
					'Accept: */*',
					'Authorization: Client-ID ' . self::get_api_key(),
					'User-Agent: Sharepicgenerator',
				),
			)
		);

		$response = curl_exec( $curl );
		$err      = curl_error( $curl );

		curl_close( $curl );

		if ( $err ) {
			header( 'HTTP/1.0 500 Could not connect to unsplash' );
			exit;
		}

		echo $response;
	}

}
