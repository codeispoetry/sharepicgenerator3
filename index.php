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

bindtextdomain( 'sg', './languages' );
textdomain( 'sg' );
if ( 'de' === get_lang() ) {
	setlocale( LC_ALL, 'de_DE.utf8' );
}

$path = $_SERVER['REQUEST_URI'];

$parts = parse_url( $path );

$segments   = explode( '/', $parts['path'] );
$controller = $segments[2] ?? 'frontend';
$method     = $segments[3] ?? 'index';

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

/**
 * Logs to local file.
 *
 * @param string $message The Log-Message.
 * @return void
 */
function log( $message ) {
	$line  = gmdate( 'Y-m-d H:i:s' );
	$line .= "\t" . $message;
	$line .= "\n";
	file_put_contents( 'logs/logger.log', $line );
}
