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
        token TEXT NOT NULL UNIQUE)'
	);
} catch ( PDOException $e ) {
	echo $e->getMessage();
}

