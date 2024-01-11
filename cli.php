<?php
namespace Sharepicgenerator\Cli;

require './vendor/autoload.php';

use Sharepicgenerator\Controllers\User;

if ( $argc <= 1 ) {
	die( "Usage: php cli.php <command>\n" );
}

$cli = new CLI();

$command = $argv[1];

if ( 'delete' === $command ) {
	$user = @$argv[2];

	if ( empty( $user ) ) {
		die( "Usage: php cli.php delete <user>\n" );
	}

	$cli->delete_user( $user );
}

if ( 'create' === $command ) {
	$user     = @$argv[2];
	$password = @$argv[3];

	if ( empty( $user ) || empty( $password ) ) {
		die( "Usage: php cli.php create <user> <password>\n" );
	}

	$cli->create_user( $user, $password );
}



/**
 * The CLI class.
 */
class CLI {
	/**
	 * The database connection.
	 *
	 * @var \PDO
	 */
	private $db;

	/**
	 * The constructor.
	 */
	public function __construct() {
		try {
			$this->db = new \PDO( 'sqlite:data/users.db' );

			$this->db->setAttribute(
				\PDO::ATTR_ERRMODE,
				\PDO::ERRMODE_EXCEPTION
			);
		} catch ( \PDOException $e ) {
			echo $e->getMessage();
		}
	}

	/**
	 * Delete a user.
	 *
	 * @param string $username The user.
	 */
	public function delete_user( $username ) {

		echo "User $username ";

		// Delete in db.
		$sql  = 'DELETE FROM users WHERE username = :username';
		$stmt = $this->db->prepare( $sql );
		$stmt->bindParam( ':username', $username );
		$stmt->execute();
		if ( $stmt->rowCount() === 0 ) {
			echo 'was not found in db ';
		} else {
			echo 'was deleted in db ';
		}

		// Delete in filesystem.
		$cmd = 'rm -rf users/' . $username;
		exec( $cmd, $output, $return_var );
		if ( 0 === $return_var && empty( $output ) ) {
			echo "and deleted in filesystem.\n";
		} else {
			echo "and not deleted in filesystem.\n";
		}
	}

	/**
	 * Create a user.
	 *
	 * @param string $username The user.
	 * @param string $password The password.
	 */
	public function create_user( $username, $password ) {
		$user   = new User( $username );
		$result = $user->register( $username );

		if ( empty( $result ) ) {
			echo "User $username could not be created.\n";
			exit( 1 );
		}

		$token = $user->get_token_for_user( $username );
		if ( empty( $token ) ) {
			echo "Token for user $username could not be retrieved.\n";
			exit( 1 );
		}

		$result = $user->set_password( $token, $password );
		if ( empty( $result ) ) {
				echo "The password for $username could not be set.\n";
				exit( 1 );
		}
	}
}
