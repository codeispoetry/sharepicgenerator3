<?php
namespace Sharepicgenerator\Controllers;

/**
 * Frontend controller.
 */
class Frontend {

	/**
	 * Env variable like user, config, mailer, etc..
	 *
	 * @var object
	 */
	private $env;


	/**
	 * Enables the user-object.
	 *
	 * @param Env $env Environment with user, config, logger, mailer, etc.
	 */
	public function __construct( $env ) {
		$this->env = $env;
	}

	/**
	 * The generator page.
	 */
	public function create() {
		$body_classes  = strToLower( $this->env->config->get( 'Main', 'tenant' ) );
		$starttemplate = $this->env->config->get( 'Main', 'starttemplate' );
		include_once './src/Views/Creator.php';
	}

	/**
	 * Shows an arbitrary view
	 */
	public function view() {
		$view = ( ! empty( $_GET['view'] ) ) ? Helper::sanitze_az09( $_GET['view'] ) : '';

		$pages = glob( './src/Views/Pages/*.php' );
		$pages = array_map(
			function( $filename ) {
				return pathinfo( $filename, PATHINFO_FILENAME );
			},
			$pages
		);

		if ( empty( $view ) || ! in_array( $view, $pages, true ) ) {
			$this->no_access();
		}
		include_once './src/Views/Pages/' . $view . '.php';
	}


	/**
	 * TThis is just a redirect to the create method.
	 * In other cases than the Greens, there is a dedicated
	 * welcome page here at index.
	 */
	public function index() {
		$this->create();
	}

	/**
	 * Fail gracefully
	 *
	 * @param mixed $name Method.
	 * @param mixed $arguments Arguments.
	 * @return void
	 */
	public function __call( $name, $arguments ) {
		$this->no_access();
	}

	/**
	 * Fail gracefully.
	 */
	private function no_access() {
		header( 'HTTP/1.0 404 Not Found.' );
		die();
	}
}
