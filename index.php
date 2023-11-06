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

$path = $_SERVER['REQUEST_URI'];

$segments   = explode( '/', $path );
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
