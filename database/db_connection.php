<?php

    //conncection parameters for the database
    $db_host = 'localhost';
    $db_name = 'cs2team33_db';
    $username = 'cs2team33';
    $password = 'ILINC7qEIJdMtba';

    
    try {
        $db = new PDO("mysql:dbname=$db_name;host=$db_host", $username, $password);
    } catch (PDOException $ex) {
        // If connection fails, display error message and exit script
        echo("Failed to connect to the database.<br>");
        echo($ex->getMessage());
        exit;
    }
?>