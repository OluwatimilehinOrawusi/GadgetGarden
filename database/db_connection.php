<?php
$db_host = 'localhost';
$db_name = 'gadgetgarden';
$username = 'root';
$password = '';

try {
    $db = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8mb4", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $db; 
} catch (PDOException $ex) {
    die("Failed to connect to the database. Error: " . $ex->getMessage()); 
}
?>
