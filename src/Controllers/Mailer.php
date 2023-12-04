<?php
namespace Sharepicgenerator\Controllers;

use PHPMailer\PHPMailer\PHPMailer;

/**
 * Mailer controller.
 */
class Mailer {

	/**
	 * The phpmailer object.
	 *
	 * @var PHPMailer
	 */
	private $phpmailer;

	/**
	 * The constructor. Sets up the mailer.
	 *
	 * @param string $username The users email.
	 */
	public function __construct( $username ) {
		$this->phpmailer = new PHPMailer( true );
		$config          = new Config();

		//phpcs:disable
        $this->phpmailer->isSMTP();
        $this->phpmailer->Host       = $config->get( 'Mail', 'host' );
        $this->phpmailer->SMTPAuth   = true;
        $this->phpmailer->Username   = $config->get( 'Mail', 'username' );
        $this->phpmailer->Password   = $config->get( 'Mail', 'password' );
        $this->phpmailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $this->phpmailer->Port       = $config->get( 'Mail', 'port' );
		$this->phpmailer->CharSet    = 'UTF-8';
        $this->phpmailer->setFrom( 'mail@tom-rose.de', 'Sharepicgenerator' );
        $this->phpmailer->addAddress( $username );
		$this->phpmailer->isHTML( true );
		$this->phpmailer->Timeout = 30;
		//phpcs:enable
	}

	/**
	 * Send a message to the logged in user
	 *
	 * @param string $subject The message.
	 * @param string $message The message.
	 */
	public function send( $subject, $message ) {

		try {
			$this->phpmailer->Subject = $subject;
			$this->phpmailer->Body    = $message;
			$this->phpmailer->send();
		} catch ( \Exception $e ) {
			\Sharepicgenerator\log( "Message could not be sent. phpMailer Error: {$this->phpmailer->ErrorInfo}" );
			return false;
		}
	}

}
