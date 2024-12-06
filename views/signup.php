<?php

$pdo = require_once '../database/database.php';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        $errors[] = "The passwords do not match, please try again";
    }

    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    try {
        $sql = "INSERT INTO users(username, email, password_hash) VALUES(?,?,?)";
        $stmt = $pdo->prepare($sql);
        $stmt -> execute([$username, $email, $password_hash]);

        header("Location: ./login.php");

    } catch (PDOException) {
        echo "An error occured, please try again later";
        exit;
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link href = "../public/css/signup.css" rel = "stylesheet"/>
    <link rel="stylesheet" href="../public/css/navbar.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GADGET GARDEN </title>
    <?php require_once "../partials/header.php" ?>
</head>

<body>  
<nav>
            <div class="nav-left">
                <a href="../index.php"><p id="logo-text">GADGET GARDEN</p></a>
            </div>
            <div class="nav-right">
                <a href="../views/products.php"><button class="green-button" >Products</button></a>
                <a href="./aboutpage.php"><button class="white-button">About Us</button></a>
                <?php if (!isset($_SESSION['user_id'])){?>
                <?php echo '<a href="./login.php"><button class="green-button">Login</button></a>' ?>
                 <?php echo '<a href="./signup.php"><button class="white-button">Sign Up</button></a> '?>
                <?php }?>
                <?php if (isset($_SESSION['user_id'])){?>
                <?php echo '<a href="./views/contact.php"><button class="green-button">Contact us</button></a>' ?>
                <?php echo '<a href="./basket.php"><button class="green-button">Basket</button></a>' ?>
                <?php echo '<a href="./logout.php"><button class="white-button">Logout</button></a>' ?>

                <?php }?>

            </div>
</nav>

    <div class = "webpage">

    <div class = "intro">
        <h2>Grow Your Tech Sustainably - Buy, Sell, and Renew at Gadget Garden!</h2>

        <div class="image1"> 


            <img src = "../public/assets/ggLaptopSignIn.png" class = "images" alt = "Laptop Promo"/>

        </div>

        </div>  

        <div class = "signup">
            <h3>Create Account</h3>

            <form method='POST' action='./signup.php' id ="myForm" >

                <div class = "creating">    
                    <label>Username</label>
                    <input required type="text" id="name" name="username" placeholder="Username">

                    <label for="email">E-mail</label>

  <input required type="text" id="email1" name="email" placeholder="Email">

  <label for="phonenumber">Phone Number</label>

  <input required ="number" id="phonenumber" name="phone" placeholder="Phone">


  <label for="password">Password</label>

  <input required type = "password" id="password" name="password" placeholder="Password">

  <label for="cpassword">Confirm Password</label>

  <input required ="text" id="cpassword" name="confirm_password" placeholder="Confirm Password">

  <button type="createaccount">Create Account</button>

<h6>Already have an account? <a href = "./login.php">Log in</a></h6>

                </div>
</div>


            </form>
            </div>
            <?php require_once '../partials/footer.php'?>
</body>
</html>