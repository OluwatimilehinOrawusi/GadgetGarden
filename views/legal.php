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
        <link rel="stylesheet" href="../public/css/legal.css">
    </head>
    <body>
<!-- Admin Navbar -->
<nav>
    <div class="nav-left">
        <a href="../index.php"><p id="logo-text">GADGET GARDEN</p></a>
    </div>
    <div class="nav-right">
        <a href="./dashboard.php"><button class="white-button">Dashboard</button></a>
        <?php if($user&&$user['role']==='admin'){?>
        <a href="manage_users.php"><button class="white-button">Users</button></a>
        <?php } ?>
        <a href="manage_orders.php"><button class="white-button">Orders</button></a>
        <a href="admin.php"><button class="white-button">Inventory</button></a>
        <a href="logout.php"><button class="green-button">Logout</button></a>
    </div>
</nav>


             
    <div class="main-container">
    <div class="container">
        <h1>Legal Disclaimer</h1>
        
        <div class="section">
            <h2>Terms of Use</h2>
            <p>By accessing and using this website, you agree to be bound by the following terms and conditions. All information provided on this site is for entertainment purposes only and should not be considered legal advice.</p>
        </div>

        <div class="section">
            <h2>Liability Disclaimer</h2>
            <p>This website and its authors are not responsible for any damages, direct or indirect, resulting from the use of information provided. All content is provided "as is" without any warranties, express or implied.</p>
        </div>

        <div class="section">
            <h2>Privacy Policy</h2>
            <p>We do not collect or store any personal data. However, third-party services used on this site may collect user information in accordance with their policies.</p>
        </div>

        <div class="section">
            <h2>Governing Law</h2>
            <p>This website is governed by the imaginary laws of the Internet and is subject to fictional jurisdiction. Any disputes shall be resolved in accordance with the Code of Digital Ethics 404.</p>
        </div>

        <div class="section">
            <h2>Changes to This Disclaimer</h2>
            <p>We reserve the right to update this disclaimer at any time without notice. Continued use of the website constitutes acceptance of any modifications.</p>
        </div>
    </div>
                </div>

    </body>
</html>