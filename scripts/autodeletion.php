<?php
namespace Sharepicgenerator;

require './httpdocs/vendor/autoload.php';

use Sharepicgenerator\Controllers\Config;
use Sharepicgenerator\Controllers\User;

$greenapi = new GreenAPI();

$users = $greenapi->get_users_to_be_deleted( 1 );
foreach ( $users as $user ) {
	$status = User::delete( $user->username );
	printf( "User %s %s\n", $user->username, $status );
	$greenapi->add_user_to_notify( $user->id, $status );
}
$greenapi->notify();



/**
 * Green API
 */
class GreenAPI {
	/**
	 * The API URL
	 *
	 * @var string
	 */
	private $api_url;

	/**
	 * Username:password for the Auth Header.
	 *
	 * @var string
	 */
	private $userpwd;

	/**
	 * The users to notify
	 *
	 * @var array
	 */
	private $users_to_notify = array();

	/**
	 * Read config
	 */
	public function __construct() {
		$config        = new Config( 'config.ini' );
		$this->api_url = $config->get( 'Greens', 'offboardingApi' );
		$this->userpwd = $config->get( 'Greens', 'userpwd' );
	}

	/**
	 * Gets all the users to be deleted from the API.
	 *
	 * @param int $limit The number of users to get.
	 * @throws \Exception On Curl error.
	 */
	public function get_users_to_be_deleted( $limit = 1 ) {
		$url     = $this->api_url . 'self?limit=' . $limit;
		$headers = array(
			'accept: application/json',
		);

		$curl = curl_init( $url );
		curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $curl, CURLOPT_HTTPHEADER, $headers );
		curl_setopt( $curl, CURLOPT_USERPWD, $this->userpwd );

		$response = curl_exec( $curl );

		if ( false === $response ) {
			$err = curl_error( $curl );
			curl_close( $curl );
			throw new \Exception( "Curl error: $err" );
		}

		$httpcode = curl_getinfo( $curl, CURLINFO_HTTP_CODE );
		if ( 200 !== $httpcode ) {
			throw new \Exception( "HTTP code: $httpcode\nMessage: $response" );
		}

		curl_close( $curl );

		$json = json_decode( $response, false );

		return $json->data;
	}

	/**
	 * Adds a user to the list of users to notify the API.
	 *
	 * @param string $id The user's id.
	 * @param string $status The status: not_found or deleted.
	 * @return void
	 */
	public function add_user_to_notify( $id, $status ) {
		$this->users_to_notify[] = array(
			'id'     => $id,
			'status' => $status,
		);
	}

	/**
	 * Notifies the API about the deletion, send POST request.
	 *
	 * @return void
	 * @throws \Exception On Curl error.
	 */
	public function notify() {
		$url     = $this->api_url . 'self/batch';
		$headers = array(
			'accept: */*',
			'Content-Type: application/json',
		);
		$data    = array(
			'upsert' => $this->users_to_notify,
		);

		$curl = curl_init( $url );
		curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $curl, CURLOPT_HTTPHEADER, $headers );
		curl_setopt( $curl, CURLOPT_POST, true );
		curl_setopt( $curl, CURLOPT_USERPWD, $this->userpwd );
		curl_setopt( $curl, CURLOPT_POSTFIELDS, json_encode( $data ) );

		$response = curl_exec( $curl );

		if ( false === $response ) {
			$err = curl_error( $curl );
			curl_close( $curl );
			throw new \Exception( "Curl error: $err" );
		}

		$httpcode = curl_getinfo( $curl, CURLINFO_HTTP_CODE );
		if ( $httpcode < 200 || $httpcode >= 300 ) {
			throw new \Exception( "HTTP code: $httpcode\nMessage: $response" );
		}

		curl_close( $curl );
	}
}
