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
use Sharepicgenerator\Controllers\Felogger;
use Sharepicgenerator\Controllers\Openai;
use Sharepicgenerator\Controllers\Config;
use Sharepicgenerator\Controllers\Proxy;

bindtextdomain( 'sg', './languages' );
textdomain( 'sg' );
if ( 'de' === get_lang() ) {
	setlocale( LC_ALL, 'de_DE.utf8' );
}

$config = new Config();


$controller = ( ! empty( $_GET['c'] ) ) ? $_GET['c'] : 'frontend';
$method     = ( ! empty( $_GET['m'] ) ) ? $_GET['m'] : 'index';

if ( 'frontend' === $controller ) {
	$frontend = new Frontend();
	$frontend->{$method}();
}

if ( 'sharepic' === $controller ) {
	$sharepic = new Sharepic();
	$sharepic->{$method}();
}

if ( 'felogger' === $controller ) {
	$felogger = new Felogger();
	$felogger->{$method}();
}

if ( 'openai' === $controller ) {
	$openai = new Openai();
	$openai->{$method}();
}

if ( 'proxy' === $controller ) {
	Proxy::serve();
}

/**
 * Get the language.
 *
 * @return string
 */
function get_lang() {
	if ( isset( $_COOKIE['lang'] ) ) {
		return $_COOKIE['lang'];
	}

	if ( isset( $_SERVER['HTTP_ACCEPT_LANGUAGE'] ) ) {
		return substr( $_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2 );
	}

	return false;
}

