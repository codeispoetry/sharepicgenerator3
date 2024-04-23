<?php
namespace Sharepicgenerator\Controllers;

/**
 * Get all the Fonts.
 */
class Fonts {

	/**
	 * The path to the css file.
	 *
	 * @var string
	 */
	const CSS_FILE = './src/Views/rte/rte.css';

	/**
	 * Get the font list for the dropdown of tinyMCE.
	 *
	 * @return string
	 */
	public static function get_font_family_formats() {
		$woffs = self::get_fonts();
		$fonts = array();
		foreach ( $woffs as $woff ) {
			$font    = pathinfo( $woff, PATHINFO_FILENAME );
			$fonts[] = $font . '=' . $font;
		}

		return join( ';', $fonts );
	}

	/**
	 * Write the css file for the fonts.
	 * This method if called from cli in the terminal.
	 */
	public static function write_css_file() {
		$woffs   = self::get_fonts();
		$pattern = "@font-face {
	font-family: '%s';
	src: url('../../.%s') format('woff2');
	font-weight: 300;
}";
		$css     = '';
		foreach ( $woffs as $woff ) {
			$css .= sprintf(
				$pattern,
				pathinfo( $woff, PATHINFO_FILENAME ),
				$woff
			) . "\n";
		}

		file_put_contents( self::CSS_FILE, $css ) || die( 'Could not write file' );
	}

	/**
	 * Get all the fonts.
	 *
	 * @return array
	 */
	private static function get_fonts() {
		$woffs = glob( './assets/fonts/*.woff2' );
		return $woffs;
	}

	/**
	 * Gets the path to the css file with the fonts.
	 *
	 * @return string Path to css-file.
	 */
	public static function get_css_file() {
		return self::CSS_FILE;
	}
}
