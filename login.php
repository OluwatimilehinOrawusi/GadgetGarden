<?php
if (isset($_POST['submitted'])) {
    require_once("app/database/db_connection.php");
}





?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign in</title>
    <?php require_once "app/partials/header.php" ?>
    <link rel="stylesheet" href="public/css/navbar.css">
    <link rel="stylesheet" href="public/css/styles.css">
    <link rel = "stylesheet" href = "public/css/login.css">
</head>
<body>
<?php require_once "app/partials/navbar.php"?>
<div id = "login-page">
    <div id = "leftside-container">
        <h1 id = "catchphrase"> Grow Your Tech Sustainably - Buy, Sell, and Renew at Gadget Garden!</h1>
        <img src = "public/assets/ggLaptopSignIn.png" alt = "An image of a laptop with a garden within the screen">
    </div>
    <div id = "login-container">
        <h1>Sign in</h1>
        <br> <br>
        <form method = "post" action = "login.php" id = "loginform">
        <div id = "textboxesandlabels">
            <label for="username">Username</label>
            <input type = "text" id = "username" name = "username" required>
            <br> <br>
            <label for = "password">Pasword</label>
            <input type = "text" id = "password" name = "password" required>
            <br> <br>
            <p class="forgot-password">Forgot your password? Click <a href="-">here</a> to reset</p>
            <br> <br> 
        </div>

        <input type = "submit" value = "Sign in" id = "subbutton"/>
        <input type = "hidden" name = "submitted" value = "true"/>
        <br> <br>
        <p class = "register-new">Don't have an account? <a href ="-">Sign up</a></p>
        </form> 
    </div>
</div>
<?php require_once 'app/partials/footer.php'?>
</body>
</html>