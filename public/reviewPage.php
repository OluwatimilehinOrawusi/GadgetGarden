<?php
//starts the session
session_start();

//connects the user to the database
require_once('db_connection.php');

//get the product ID and store it in a variable
$productID = isset($_GET['product_id'])

include 'navbar.php';
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