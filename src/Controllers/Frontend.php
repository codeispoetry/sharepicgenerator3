<?php
namespace Sharepicgenerator\Controllers;

use PHPMailer\PHPMailer\PHPMailer;

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
		if ( ! $this->user->login() ) {
			header( 'Location: /' );
			die();
		}
		include_once './src/Views/Creator.php';
	}

	/**
	 * The registration page.
	 */
	public function register() {
		include_once './src/Views/Header.php';
		if ( ! isset( $_POST['register_mail'] ) ) {
			include_once './src/Views/User/Register.php';
			include_once './src/Views/Footer.php';
			return;
		}

		if ( ! $this->user->register( $_POST['register_mail'] ) ) {
			include_once './src/Views/User/NotRegistered.php';
			include_once './src/Views/Footer.php';
			return;
		}

		include_once './src/Views/User/Registered.php';
		include_once './src/Views/Footer.php';
	}

	/**
	 * Sends the email with the reset token.
	 */
	public function send_email_reset_password() {
		// Show form to enter the passwords.
		if ( empty( $_POST['username'] ) || ! filter_var( $_POST['username'], FILTER_VALIDATE_EMAIL ) ) {
			$this->no_access();
		}
		$username = $_POST['username'];
		$token    = $this->user->get_token_for_user( $username );

		if ( empty( $token ) ) {
			$this->no_access();
		}

		$mail = new PHPMailer( true );
		//phpcs:disable
		try {
			$mail->isSMTP();
			$mail->Host       = $this->config->get( 'Mail', 'host' );
			$mail->SMTPAuth   = true;
			$mail->Username   = $this->config->get( 'Mail', 'username' );
			$mail->Password   = $this->config->get( 'Mail', 'password' );
			$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
			$mail->Port       = $this->config->get( 'Mail', 'port' );

			// Recipients.
			$mail->setFrom( 'mail@tom-rose.de', 'Sharepicgenerator' );
			$mail->addAddress( $username );

			// Content.
			$mail->isHTML( true );
			$mail->Subject = 'Password Reset';

			$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
			$serverAddress = $_SERVER['HTTP_HOST'];
			$link = $protocol . $serverAddress . '/index.php/frontend/reset_password?token=' . $token;
			$mail->Body    = _('Click on the following link to reset your password: ') . $link;

			$mail->send();
		} catch ( \Exception $e ) {
					echo $mail->ErrorInfo;
					die();
					error_log( "Message could not be sent. Mailer Error: {$mail->ErrorInfo}" );
		}
		//phpcs:enable

		include_once './src/Views/Header.php';
		echo "Schau in Dein Postfach.";
		include_once './src/Views/Footer.php';
	}

	/**
	 * Reset password page.
	 */
	public function reset_password() {
		// Perform the password reset.
		if ( isset( $_POST['token'] ) && isset( $_POST['password'] ) && isset( $_POST['password_repeat'] ) && $_POST['password'] === $_POST['password_repeat'] ) {
			$token    = $_POST['token'];
			$password = $_POST['password'];
			if ( ! $this->user->set_password( $token, $password ) ) {
				$this->no_access();
			}

			include_once './src/Views/Header.php';
			include_once './src/Views/User/PasswordResetted.php';
			include_once './src/Views/Footer.php';
			return;
		}

		$token = $_GET['token'] ?? '';
		include_once './src/Views/Header.php';
		include_once './src/Views/User/ResetPassword.php';
		include_once './src/Views/Footer.php';

	}

	/**
	 * Request password reset.
	 */
	public function request_password_reset() {
		include_once './src/Views/Header.php';
		include_once './src/Views/User/RequestPasswordReset.php';
		include_once './src/Views/Footer.php';
	}


	/**
	 * The home page.
	 */
	public function index() {
		include_once './src/Views/Header.php';
		include_once './src/Views/Home.php';
		include_once './src/Views/Footer.php';
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
