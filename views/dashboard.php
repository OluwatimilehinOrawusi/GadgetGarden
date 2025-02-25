<?php
session_start();

$pdo = require_once "../database/database.php";

$user_id = $_SESSION['user_id'] ?? null;

// Fetch the admin status of the user
$stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = :user_id");
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Dashboard</title>
        <?php require_once "../partials/header.php" ?>
        <link rel="stylesheet" href="../public/css/dashboard.css">
    </head>
    <body>
        <nav>
        <div class="nav-left">
        <a href="../index.php"><p id="logo-text">GADGET GARDEN</p></a>
        </div>
        <div class="nav-right">
            <a href="#categories"><button class="green-button">Categories</button></a>
            <a href="./views/aboutpage.php"><button class="white-button">About Us</button></a>

            <?php if (!isset($_SESSION['user_id'])) { ?>
                <a href="./login.php"><button class="green-button">Login</button></a>
                <a href="./signup.php"><button class="white-button">Sign Up</button></a>
            <?php } ?>

            <?php if (isset($_SESSION['user_id'])) { ?>
                <a href="./basket.php"><button class="green-button">Basket</button></a>
                <a href="./contact.php"><button class="green-button">Contact Us</button></a>
                <a href="./profile.php"><button class="white-button">Profile</button></a>

                <?php if ($user && $user['admin']) { ?>
                    <a href="./views/dashboard.php"><button class="white-button">Admin Dashboard</button></a>
                <?php } ?>

                <a href="./views/logout.php"><button class="green-button">Logout</button></a>
            <?php } ?>
        </div>
    </nav>
    <div id="main-container">
    <h1> Welcome <?php echo $user['username'] ?> !</h1>
    <p>Administration Dashboard</p>
        <div id="grid-container">
                    
        <div class="dashboard-cards"><a href="./orders.php" class="clickable-div"><img src=../public\assets\Quickdelivery.png ><p>Orders</p></a></div>
                    <div class="dashboard-cards"><img src=../public\assets\Checkbox.png ><a href="./reviewPage.php"><p>Product Reviews</p></a></div>
                    <div class="dashboard-cards"><img src=../public\assets\Download.png ><a href="./reviewPage.php"><p>Review Customer Uploads</p></a></div>
                    <div class="dashboard-cards"><img src=../public\assets\Invoice.png ><a href="./legal.php"><p>Legal</p></a></div>
                    <div id="dashboard-links" class="dashboard-cards"><img src=../public\assets\Home.png ><a href="./reviewPage.php"><p>Inventory management</p></a></div>
                    <div id="analytic-card"class="dashboard-cards"><img src=../public\assets\Diagram.png ><a href="./admin_dashboard.php"><p>Analytics</p></a></div>
                    <div class="dashboard-cards"><img src=../public\assets\VolumeUp.png ><a href="./alerts.php"><p>Alerts</p></a></div>
                    
                    <div class="dashboard-cards"><img src=../public\assets\Account.png ><a href="./IAM.php"><p>IAM</p></a></div>
                    <div class="dashboard-cards"><img src=../public\assets\Partner.png ><a href="./manage-customers.php"><p>Customer details</p></a></div>
                    <div class="dashboard-cards"><img src=../public\assets\Megaphone.png ><a href="./forum.php"><p>Customer Forum</p></a></div>
                    
                 
        </div>
        </div>
    </body>
</html>