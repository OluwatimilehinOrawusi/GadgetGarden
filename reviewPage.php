<?php
//starts the session
session_start();

//connects the user to the database
require_once('db_connection.php');

?>







<!----Start of the html code--->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Review Item - Gadget Garden</title>
    <style>
        /* Navigation Bar */
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #fff;
            padding: 10px 20px;
            border-bottom: 1px solid #ddd;
        }

        .navbar .logo {
            font-weight: bold;
            color: #1e5631;
            font-size: 1.5em;
        }

        .navbar .menu {
            display: flex;
            gap: 20px;
        }

        .navbar .menu a {
            text-decoration: none;
            color: #333;
            font-size: 1em;
        }

        .navbar .auth-buttons {
            display: flex;
            gap: 10px;
        }

        .navbar .auth-buttons a {
            text-decoration: none;
            color: #333;
            padding: 5px 15px;
            border: 1px solid #333;
            border-radius: 3px;
        }

        .navbar .auth-buttons a.register {
            background-color: #333;
            color: #fff;
        }
     
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <header class="navbar">
        <div class="logo">GADGET GARDEN</div>
        <nav class="menu">
            <a href="#">Products</a>
            <a href="#">Solutions</a>
            <a href="#">Community</a>
            <a href="#">Resources</a>
            <a href="#">Pricing</a>
            <a href="#">Contact</a>
        </nav>
        <div class="auth-buttons">
            <a href="#" class="sign-in">Sign in</a>
            <a href="#" class="register">Register</a>
        </div>
    </header>    

     <!-----Review writing field---->   
    <div class="review-content">
      <h2>Review Order</h2>
      <textarea placeholder="Write a review"></textarea>
      <button class="submit-btn">Submit</button>
    </div>
</body>
</html>