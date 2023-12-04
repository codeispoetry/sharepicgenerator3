<?php
unlink( 'data/users.db' );
try {
	$db = new PDO( 'sqlite:data/users.db' );

	$db->setAttribute(
		PDO::ATTR_ERRMODE,
		PDO::ERRMODE_EXCEPTION
	);

	$db->exec(
		'CREATE TABLE IF NOT EXISTS users (
			id INTEGER PRIMARY KEY AUTOINCREMENT,
			username TEXT NOT NULL UNIQUE,
			password TEXT NOT NULL,
			token TEXT NOT NULL UNIQUE,
			role TEXT 
		)'
	);

	$db->exec(
		'CREATE TABLE IF NOT EXISTS tenants (
			id INTEGER PRIMARY KEY AUTOINCREMENT,
			tenant TEXT NOT NULL UNIQUE
		)'
	);

	$db->exec(
		'CREATE TABLE IF NOT EXISTS userstenants (
			userID INTEGER NOT NULL,
			tenantID INTEGER NOT NULL
		)'
	);

	$db->exec( "INSERT INTO tenants ('tenant') VALUES ('de')" );
	$db->exec( "INSERT INTO tenants ('tenant') VALUES ('einigungshilfe')" );

	$db->exec( "INSERT INTO userstenants ('userID','tenantID') VALUES (1,1);" );


	exec( 'chmod 777 data/users.db' );
} catch ( PDOException $e ) {
	echo $e->getMessage();
}

