<?php
/**
 * Main entry point for everything.
 *
 * @package Sharepicgenerator
 */

namespace Sharepicgenerator;

require './vendor/autoload.php';

use Sharepicgenerator\Controllers\Frontend;
use Sharepicgenerator\Controllers\Sharepic;
use Sharepicgenerator\Controllers\Logger;
use Sharepicgenerator\Controllers\Felogger;
use Sharepicgenerator\Controllers\Openai;
use Sharepicgenerator\Controllers\Config;
use Sharepicgenerator\Controllers\Proxy;
use Sharepicgenerator\Controllers\Mailer;
use Sharepicgenerator\Controllers\Helper;
use Sharepicgenerator\Controllers\User;
use stdClass;

Helper::load_textdomain();
Helper::clean_up_dir( './qrcodes/*', 5 );

$env         = new stdClass();
$env->config = new Config();
$env->user   = new User( $env->config );
$env->logger = new Logger( $env->user );
$env->mailer = new Mailer( $env->config, $env->logger );

$controller = ( ! empty( $_GET['c'] ) ) ? $_GET['c'] : 'frontend';
$method     = ( ! empty( $_GET['m'] ) ) ? $_GET['m'] : 'index';

if ( 'frontend' === $controller ) {
	$frontend = new Frontend( $env );
	$frontend->{$method}();
}

if ( 'sharepic' === $controller ) {
	$sharepic = new Sharepic( $env );
	$sharepic->{$method}();
}

if ( 'felogger' === $controller ) {
	$felogger = new Felogger( $env );
	$felogger->{$method}();
}

if ( 'openai' === $controller ) {
	$openai = new Openai();
	$openai->{$method}();
}

if ( 'proxy' === $controller ) {
	Proxy::serve( $env );
}
