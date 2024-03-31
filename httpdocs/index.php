<?php
/**
 * Main entry point for everything.
 *
 * @package Sharepicgenerator
 */

namespace Sharepicgenerator;

require './vendor/autoload.php';

use Sharepicgenerator\Controllers\Usergreens as User;
use Sharepicgenerator\Controllers\Frontendgreens as Frontend;
use Sharepicgenerator\Controllers\Sharepic;
use Sharepicgenerator\Controllers\Logger;
use Sharepicgenerator\Controllers\Felogger;
use Sharepicgenerator\Controllers\Openai;
use Sharepicgenerator\Controllers\Config;
use Sharepicgenerator\Controllers\Proxy;
use Sharepicgenerator\Controllers\Helper;


Helper::load_textdomain();
$config = new Config();
$user   = new User();
$logger = new Logger( $user );

$controller = ( ! empty( $_GET['c'] ) ) ? $_GET['c'] : 'frontend';
$method     = ( ! empty( $_GET['m'] ) ) ? $_GET['m'] : 'index';

if ( 'frontend' === $controller ) {
	$frontend = new Frontend( $user, $config, $logger );
	$frontend->{$method}();
}

if ( 'sharepic' === $controller ) {
	$sharepic = new Sharepic( $user, $config, $logger );
	$sharepic->{$method}();
}

if ( 'felogger' === $controller ) {
	$felogger = new Felogger( $user, $config, $logger );
	$felogger->{$method}();
}

if ( 'openai' === $controller ) {
	$openai = new Openai();
	$openai->{$method}();
}

if ( 'proxy' === $controller ) {
	Proxy::serve( $user, $config, $logger );
}
