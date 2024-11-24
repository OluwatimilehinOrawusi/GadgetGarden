<?php

$dbhost = 'localhost';
$dbname = 'cs2team33_db';
$username = 'cs2team33';
$password = 'ILINC7qEIJdMtba';

try {
	$db = new PDO("mysql:host=$dbhost;dbname=$dbname", $username,$password);
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $ex) {
	echo("Failed to connect to the database.<br>");
	echo($ex->getMessage());
	exit;
}
?> 