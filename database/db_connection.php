<?php

    //conncection parameters for the database
    $db_host = 'localhost';
    $db_name = 'gadet garden'; //The name of the database that we will be using for the gadget garden
    $username = 'root'; //The username for the database. This was given to the team.
    $password = ''; //The password to access the database


    try {
        $db = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8mb4", $username, $password);
    } catch (PDOException $ex) {
        // If connection fails, display error message and exit script
        echo("Failed to connect to the database.<br>");
        echo($ex->getMessage());
        exit;
    }
?>