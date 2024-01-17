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
use Sharepicgenerator\Controllers\Config;

bindtextdomain( 'sg', './languages' );
textdomain( 'sg' );
if ( 'de' === get_lang() ) {
	setlocale( LC_ALL, 'de_DE.utf8' );
}

$config = new Config();

if ( isset( $_GET['self'] ) ) {
	setcookie( 'authenticator', 'self' );
	$config->set( 'Main', 'authenticator', 'self' );
}

if ( isset( $_GET['auth'] ) && 'auto' === $_GET['auth'] ) {
	setcookie( 'authenticator', '', time() - 3600 );
	header( 'Location: index.php' );
	die();
}

if ( isset( $_COOKIE['authenticator'] ) ) {
	$config->set( 'Main', 'authenticator', 'self' );
}

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
