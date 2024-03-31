<?php
namespace Sharepicgenerator\Controllers;

/**
 * Frontend controller.
 */
class Frontendgreens {

	/**
	 * The user object.
	 *
	 * @var User
	 */
	private $user;

	/**
	 * The config object.
	 *
	 * @var Config
	 */
	private $config;

	/**
	 * The logger object.
	 *
	 * @var Logger
	 */
	private $logger;

	/**
	 * Enables the user-object.
	 *
	 * @param User   $user   The user object.
	 * @param Config $config The config object.
	 * @param Logger $logger The logger object.
	 */
	public function __construct( $user, $config, $logger ) {
		$this->user   = $user;
		$this->config = $config;
		$this->logger = $logger;

		if ( 'auto' === $this->config->get( 'Main', 'authenticator' ) ) {
			$this->user->autologin();
		}
	}

	/**
	 * The generator page.
	 */
	public function create() {
		$body_classes  = strToLower( $this->config->get( 'Main', 'menu' ) );
		$starttemplate = $this->config->get( 'Main', 'starttemplate' );
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
