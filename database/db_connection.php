<?php

    //conncection parameters for the database
    $db_host = 'localhost';
    $db_name = 'cs2team33_db'; //The name of the database that we will be using for the gadget garden
    $username = 'cs2team33'; //The username for the database. This was given to the team.
    $password = 'ILINC7qEIJdMtba'; //The password to access the database


    try {
        $db = new PDO("mysql:dbname=$db_name;host=$db_host", $username, $password);
    } catch (PDOException $ex) {
        // If connection fails, display error message and exit script
        echo("Failed to connect to the database.<br>");
        echo($ex->getMessage());
        exit;
    }
?>