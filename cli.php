#!/usr/bin/env php
<?php
namespace Sharepicgenerator\Cli;

require './httpdocs/vendor/autoload.php';

use Sharepicgenerator\Controllers\User;

if ( $argc <= 1 ) {
	die( "Usage: php cli.php <command>\n" );
}

$cli = new CLI();

$command = $argv[1];

if ( 'delete' === $command ) {
	$user = $argv[2] ?? '';

	if ( empty( $user ) ) {
		die( "Usage: php cli.php delete <user>\n" );
	}

	$cli->delete_user( $user );
}

if ( 'deletedir' === $command ) {
	$user = $argv[2] ?? '';

	if ( empty( $user ) ) {
		die( "Usage: php cli.php deletedir <user>\n" );
	}

	$cli->delete_user_dir( $user );
}

if ( 'create' === $command ) {
	$user     = $argv[2] ?? '';
	$password = $argv[3] ?? '';

	if ( empty( $user ) || empty( $password ) ) {
		die( "Usage: php cli.php create <user> <password>\n" );
	}

	$cli->create_user( $user, $password );
}

if ( 'set_role' === $command ) {
	$user = $argv[2] ?? '';
	$role = $argv[3] ?? '';

	if ( empty( $user ) || empty( $role ) ) {
		die( "Usage: php cli.php set_role <user> <role>\n" );
	}

	$result = $cli->set_role( $user, $role );

	if ( $result ) {
		echo "The role for $user was set to $role.\n";
	}
}

if ( 'user-count' === $command ) {
	printf( "There are %d users.\n", $cli->get_users() );
}

if ( 'users' === $command ) {
	$cli->list_users();
}

if ( 'flush' === $command ) {
	$what = $argv[2] ?? '';

	if ( empty( $what ) ) {
		die( "Usage: php cli.php flush <logs|sharepics|all>\n" );
	}

	switch ( $what ) {
		case 'logs':
			if ( ! $cli->delete_in_filesystem( 'logs/*.log' ) ) {
				echo "Could not delete logfiles.\n";
			}
			break;
		case 'sharepics':
			if ( ! $cli->delete_in_filesystem( 'tmp/*.png' ) ) {
				echo "Could not delete logfiles.\n";
			}
			break;
		case 'all':
			if ( ! $cli->delete_in_filesystem( 'logs/*.log' ) ) {
				echo "Could not delete logfiles.\n";
			}
			if ( ! $cli->delete_in_filesystem( 'tmp/*.png' ) ) {
				echo "Could not delete logfiles.\n";
			}
			break;
		default:
			echo "Usage: php cli.php flush <what>\n";
			break;
	}
}

if ( 'clean' === $command ) {
	printf( "All savings deleted.\n", $cli->delete_savings() );
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
	 * Delete files in the filesystem.
	 *
	 * @param string $glob The file or directory.
	 * @return bool
	 */
	public function delete_in_filesystem( $glob ) {
		$cmd = 'rm -rf ' . $glob;
		exec( $cmd, $output, $return_var );
		return ( 0 === $return_var && empty( $output ) );
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
		$this->delete_user_dir( $username );
	}

	/**
	 * Delete a user's directory in the filesystem.
	 *
	 * @param string $username The user.
	 */
	public function delete_user_dir( $username ) {
		// Delete in filesystem.
		$cmd = 'rm -rf users/' . $username;
		exec( $cmd, $output, $return_var );
		if ( 0 === $return_var && empty( $output ) ) {
			echo "Deleted in filesystem.\n";
		} else {
			echo "Nothing deleted in filesystem.\n";
		}
	}

	/**
	 * Gets number of all users.
	 */
	public function get_users() {

		$sql  = 'SELECT COUNT(*) AS COUNT FROM users';
		$stmt = $this->db->prepare( $sql );
		$stmt->execute();
		$result = $stmt->fetch();
		return $result['COUNT'];
	}

	/**
	 * List users.
	 */
	public function list_users() {
		$sql  = 'SELECT * FROM users';
		$stmt = $this->db->prepare( $sql );
		$stmt->execute();
		while ( $result = $stmt->fetch() ) {
			echo $result['username'] . ' ' . $result['role'] . "\n";
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

	/**
	 * Set a user's role, e.g. admin.
	 *
	 * @param string $username The user.
	 * @param string $role The role.
	 */
	public function set_role( $username, $role ) {
		if ( empty( $role ) || empty( $username ) ) {
			echo "Please provide username and role.\n";
			return false;
		}

		$stmt = $this->db->prepare( 'UPDATE users SET role = :role WHERE username = :username' );
		$stmt->bindParam( ':role', $role );
		$stmt->bindParam( ':username', $username );
		$result = $stmt->execute();

		if ( empty( $result ) ) {
				echo "The role for $username could not be set.\n";
				exit( 1 );
		}

		return true;
	}

	/**
	 * Delete all savings.
	 */
	public function delete_savings() {
		$cmd = 'rm -rf users/*/save';
		exec( $cmd, $output, $return_var );
		return ( 0 === $return_var && empty( $output ) );
	}
}
