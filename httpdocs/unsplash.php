<?php
/**
 * Proxy for unsplash API.
 * Gets an api url and add authorization header, which has to be kept secret.
 *
 * @package Sharepicgenerator
 */

namespace Sharepicgenerator;

require './vendor/autoload.php';

use Sharepicgenerator\Controllers\Unsplash;

$url = $_GET['u'];

if ( empty( $url ) || parse_url( $url, PHP_URL_HOST ) !== 'api.unsplash.com' ) {
	header( 'HTTP/1.0 400 No unsplash api url given' );
	exit;
}

Unsplash::proxy( $url );
