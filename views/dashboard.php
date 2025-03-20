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
        <a href="./dashboard.php"><button class="white-button">Dashboard</button></a>
        <a href="manage_users.php"><button class="white-button">Users</button></a>
        <a href="manage_orders.php"><button class="white-button">Orders</button></a>
        <a href="admin.php"><button class="white-button">Products</button></a>
        <a href="./logout.php"><button class="green-button">Logout</button></a>
    </div>
</nav>

    <div id="main-container">
    <h1> Welcome <?php echo htmlspecialchars($user['username']); ?> !</h1>
    <p>Administration Dashboard</p>
        <div id="grid-container">
                    
        <a href="./manage_orders.php" id="order-card" class="dashboard-cards clickable-div">
        </a>

        <a href="./reviewPage.php" id="product_reviews" class="dashboard-cards clickable-div">
        </a>

        <a href="./ReviewCustomerUploads.php" id="reviewUploads" class="dashboard-cards clickable-div">
        </a>

        <a href="./legal.php" id="legal-card"class="dashboard-cards clickable-div">
        </a>

        <a href="./admin.php" id="dashboard-links" class="dashboard-cards clickable-div">
            <img src="../public/assets/Home.png">
            <p>Inventory Management</p>
        </a>

        <a href="./admin_dashboard.php" id="analytic-card" class="dashboard-cards clickable-div">
        </a>

        <a href="./alerts.php" class="dashboard-cards clickable-div">
            <img src="../public/assets/VolumeUp.png">
            <p>Alerts</p>
        </a>

        <a href="./admin_contact.php" class="dashboard-cards clickable-div">
            <img src="../public/assets/Account.png">
            <p>Customer Queries</p>
        </a>

        <a href="./manage_users.php" class="dashboard-cards clickable-div">
            <img src="../public/assets/Partner.png">
            <p>Customer Details</p>
        </a>

        <a href="./forum.php" class="dashboard-cards clickable-div">
            <img src="../public/assets/Megaphone.png">
            <p>Customer Forum</p>
        </a>
        
        </div>
    </div>
</body>
</html>
