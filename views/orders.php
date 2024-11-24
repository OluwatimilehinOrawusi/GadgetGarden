<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if not logged in
    header("Location: login.php");
    }

try {
    
    $pdo = require_once "../database/database.php" ;

    // Get the logged-in user's ID from the session
    $user_id = $_SESSION['user_id'];

    // Fetch the admin status of the user
    $stmt = $pdo->prepare("SELECT admin FROM users WHERE user_id = :user_id");
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);



    if ($user) {
        // Check if the user is not an admin
        if (!$user['admin']) {
            // Redirect to an error page or homepage
            header("Location: ../index.php");
            exit();
        }
    } else {
        // If no user is found, destroy the session and redirect to login
        session_destroy();
        header("Location: login.php");
        exit();
    }
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
    exit();
}

// If the user is an admin, allow access to the page
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders Page</title>
    <?php require_once "../partials/header.php" ?>
    <link rel="stylesheet" href="../public/css/orders.css">
</head>
<body>
    <div id="orders-container">
        <h1 class="subtitle">Welcome to the Orders Page</h1>
        <p>Only admins can view this page.</p>
        <!-- Add your admin-specific content here -->

        <?php
        try {
        // Database connection

        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Query to fetch all orders
        $sql = "SELECT * FROM orders";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();

        // Fetch all orders as an associative array
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

    
        echo "</div>";
        } catch (PDOException $e) {
        echo "Database error: " . $e->getMessage();
        exit();
        }
        ?>


        <div class="card">
            <?php foreach ($orders as $order) { ?>
                <div class=order>
                <h3>Order ID: </h3>
                <p><strong>User ID: <?php echo $order['user_id']?></strong></p>
                <p><strong>Total Price: <?php echo $order['total_price']?></strong></p>
                <p><strong>Order Date: <?php echo $order['order_date']?></strong></p>
                </div>
                <br>
            <?php }?>
        </div>

    </div>
    <?php require_once "../partials/footer.php" ?>
    </body>
    </html>
