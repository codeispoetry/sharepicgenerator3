<?php
namespace Sharepicgenerator\Controllers;

/**
 * Frontend controller.
 */
class Frontend {

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
	 * Enables the user-object.
	 */
	public function __construct() {
		$this->user   = new User();
		$this->config = new Config();
	}

	/**
	 * The generator page.
	 */
	public function create() {
		$auto          = ( ! empty( $_GET['auto'] ) ) ? Helper::sanitze_az09( $_GET['auto'] ) : '';
		$allowed_autos = array( 'einigungshilfe' );
		$body_classes  = strToLower( $this->config->get( 'Main', 'menu' ) );
		$templates     = $this->config->get( 'Templates' );
		$published     = $this->get_published();

		if ( ! empty( $auto ) && in_array( $auto, $allowed_autos, true ) ) {
			$this->user->autologin( $auto );
			$starttemplate = $auto;
			include_once './src/Views/Creator.php';
			return;
		}

		if ( ! $this->user->login() ) {
			header( 'Location: index.php' );
			die();
		}

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
	 * The registration page.
	 */
	public function register() {
		$register_mail = Helper::sanitize( $_POST['register_mail'] );
		if ( empty( $register_mail ) || ! filter_var( $register_mail, FILTER_VALIDATE_EMAIL ) ) {
			$this->no_access();
			return false;
		}

		if ( ! $this->user->register( $register_mail ) ) {
			$title   = _( 'Register' );
			$message = _( 'Registration failed. Are you already registered?' );
			include_once './src/Views/Hero.php';
			return;
		}

		$title   = _( 'Register' );
		$message = _( 'You have successfully registered. Please check your mails to confirm your registration.' );
		include_once './src/Views/Hero.php';
	}

	/**
	 * Show form for request password reset and send email.
	 */
	public function request_password_reset() {
		$title   = _( 'Reset password' );
		$message = _( 'Please check your email for the reset link.' );

		if ( ! $this->user->send_password_link() ) {
			$title   = _( 'Error' );
			$message = _( 'An error occurred. Please try again later.' );
		}
		include_once './src/Views/Hero.php';
	}

	/**
	 * Reset password page.
	 */
	public function reset_password() {
		if ( empty( $_POST['password'] ) ) {
			$token = ( ! empty( $_GET['token'] ) ) ? Helper::sanitze_token( $_GET['token'] ) : '';
			if ( isset( $_GET['newpassword'] ) ) {
				$title        = _( 'Create your password' );
				$submit_value = _( 'Set your new password' );
			} else {
				$title        = _( 'Reset password' );
				$submit_value = _( 'Reset password' );
			}

			include_once './src/Views/User/ResetPassword.php';
			return;
		}

		if (
			! isset( $_POST['token'] ) ||
			! isset( $_POST['password'] ) ||
			! isset( $_POST['password_repeat'] ) ||
			$_POST['password'] !== $_POST['password_repeat']
		) {

			$title   = _( 'An error occured' );
			$message = '<a href="/">' . _( 'Your password could not be set. Did you repeat it correctly?' ) . '</a>';
			include_once './src/Views/Hero.php';
			return;
		}

		// Perform the password reset.
		$token    = Helper::sanitze_token( $_POST['token'] );
		$password = Helper::sanitize( $_POST['password'] );
		if ( ! $this->user->set_password( $token, $password ) ) {
			$this->no_access();
		}

		$title   = _( 'Password reset' );
		$message = '<a href="index.php">' . _( 'Your password has been reset. Please login.' ) . '</a>';
		include_once './src/Views/Hero.php';
	}

	/**
	 * Get the published templates.
	 *
	 * @return array
	 */
	public function get_published() {
		$save_dir = 'templates/mint/community/';

		$savings_dir = glob( $save_dir . '/*', GLOB_ONLYDIR );
		usort(
			$savings_dir,
			function( $a, $b ) {
				return filemtime( $a ) - filemtime( $b );
			}
		);

		$savings = array();
		foreach ( $savings_dir as $dir ) {
			if ( ! file_exists( $dir . '/info.json' ) ) {
				continue;
			}

			$info = file_get_contents( $dir . '/info.json' );
			$data = json_decode( $info, true );

			if ( isset( $data['name'] ) ) {
				$dir             = $dir . '/sharepic.html';
				$savings[ $dir ] = $data['name'];
			}
		}

		return $savings;
	}

	/**
	 * The Log view.
	 */
	public function log() {
		$this->user->get_user_by_token();
		if ( ! $this->user->is_admin() ) {
			$this->no_access();
		}
		include_once './src/Views/Logs/Log.php';
	}

	/**
	 * The Log view.
	 */
	public function sharepics() {
		$this->user->get_user_by_token();
		if ( ! $this->user->is_admin() ) {
			$this->no_access();
		}

		$files = glob( '../tmp/*.{jpg,png,gif}', GLOB_BRACE );

		usort(
			$files,
			function( $a, $b ) {
				return ( filemtime( $a ) < filemtime( $b ) ) ? 1 : -1;
			}
		);

		$files = array_map(
			function( $file ) {
				return strncmp( $file, '../', 3 ) === 0 ? substr( $file, 3 ) : $file;
			},
			$files
		);

		include_once './src/Views/Logs/Sharepics.php';
	}

	/**
	 * Log the user out
	 */
	public function logout() {
		$this->user->logout();
		setcookie( 'authenticator', '', time() - 3600, '/' );

		$title   = _( 'Logout' );
		$message = _( 'You have been logged out.' );
		include_once './src/Views/Hero.php';
	}

	/**
	 * The home page.
	 */
	public function index() {
		global $config;
		$template = ( 'greens' === $config->get( 'Main', 'authenticator' ) ) ? 'Home-Greens' : 'Home';
		$body     = ( 'greens' === $config->get( 'Main', 'authenticator' ) ) ? 'greens' : 'mint';
		include_once './src/Views/' . $template . '.php';
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
