<?php
try {
	$db = new PDO( 'sqlite:data/users.db' );

	$db->setAttribute(
		PDO::ATTR_ERRMODE,
		PDO::ERRMODE_EXCEPTION
	);

	$db->exec(
		'CREATE TABLE IF NOT EXISTS users (
        username TEXT NOT NULL UNIQUE,
        password TEXT NOT NULL,
        token TEXT NOT NULL UNIQUE)'
	);

	exec( 'chmod 777 data/users.db' );
} catch ( PDOException $e ) {
	echo $e->getMessage();
}

