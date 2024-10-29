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
			$fonts[] = self::get_font_family_from_filename( $woff );
		}

		// in earlier version, the format was fontname=fontname.

		$fonts = array_unique( $fonts );

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
	font-weight: %s;
	font-style: %s;
}";
		$css     = '';
		foreach ( $woffs as $woff ) {
			$parts = explode( '-', pathinfo( $woff, PATHINFO_FILENAME ) );

			$family  = self::get_font_family_from_filename( $woff );
			$charset = $parts[1];
			$weight  = $parts[2];
			$style   = $parts[3];

			$css .= sprintf(
				$pattern,
				$family,
				$woff,
				$weight,
				$style
			) . "\n";
		}

		$css .= '.mce-content-body { word-wrap: normal !important; }';

		file_put_contents( self::CSS_FILE, $css ) || die( 'Could not write file' );
	}

	/**
	 * Get the font family from the filename.
	 *
	 * @param string $filename The filename.
	 * @return string The font family.
	 */
	private static function get_font_family_from_filename( $filename ) {
		$parts = explode( '-', pathinfo( $filename, PATHINFO_FILENAME ) );
		return ucFirst( $parts[0] );
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
