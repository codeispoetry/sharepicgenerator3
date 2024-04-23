<?php
namespace Sharepicgenerator\Controllers;

use PHPMailer\PHPMailer\PHPMailer;
use Sharepicgenerator\Controllers\Logger;

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
	 * The logger object.
	 *
	 * @var Logger
	 */
	private $logger;

	/**
	 * The constructor. Sets up the mailer.
	 *
	 * @param Config $config The config object.
	 * @param Logger $logger The logger object.
	 */
	public function __construct( $config, $logger ) {
		$this->logger    = $logger;
		$this->phpmailer = new PHPMailer( true );

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
		$this->phpmailer->isHTML( true );
		$this->phpmailer->Timeout = 30;
		//phpcs:enable
	}

	/**
	 * Send a message to the logged in user
	 *
	 * @param string $to The receiver.
	 * @param string $subject The message.
	 * @param string $message The message.
	 */
	public function send( $to, $subject, $message ) {
		try {
			$this->phpmailer->addAddress( $to );
			$this->phpmailer->Subject = $subject;
			$this->phpmailer->Body    = $message;
			$this->phpmailer->send();
		} catch ( \Exception $e ) {
			$this->logger->error( "E-mail could not be sent. phpMailer Error: {$this->phpmailer->ErrorInfo}" );
			return false;
		}

		return true;
	}
}
