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
use Sharepicgenerator\Controllers\Config;
use Sharepicgenerator\Controllers\User;
use Sharepicgenerator\Controllers\Logger;
use stdClass;

$env         = new stdClass();
$env->config = new Config();
$env->user   = new User( $env->config );
$env->logger = new Logger( $env->user );

$url = $_GET['u'];

if ( empty( $url ) || parse_url( $url, PHP_URL_HOST ) !== 'api.unsplash.com' ) {
	header( 'HTTP/1.0 400 No unsplash api url given' );
	exit;
}
Unsplash::proxy( $url, $env->logger );
