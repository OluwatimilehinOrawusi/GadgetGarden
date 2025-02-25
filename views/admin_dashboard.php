<?php
session_start();
require_once "../database/database.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$stmt = $pdo->prepare("SELECT admin FROM users WHERE user_id = :user_id");
$stmt->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user || !$user['admin']) {
    header("Location: ../index.php");
    exit();
}

$totalUsers = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$totalOrders = $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn();
$totalRevenue = $pdo->query("SELECT SUM(total_price) FROM orders")->fetchColumn();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Gadget Garden</title>
    <link rel="stylesheet" href="../public/css/navbar.css">
    <link rel="stylesheet" href="../public/css/styles.css">
    <link rel="stylesheet" href="../public/css/admin.css">
</head>
<body>

<!-- Admin Navigation -->
<nav>
    <div class="nav-left">
        <a href="../index.php"><p id="logo-text">GADGET GARDEN</p></a>
    </div>
    <div class="nav-right">
        <a href="dashboard.php"><button class="white-button">Dashboard</button></a>
        <a href="manage_users.php"><button class="white-button">Users</button></a>
        <a href="admin.php"><button class="white-button">Products</button></a>
        <a href="manage_orders.php"><button class="white-button">Orders</button></a>
        <a href="logout.php"><button class="green-button">Logout</button></a>
    </div>
</nav>

<!-- Admin Dashboard -->
<section class="admin-dashboard">
    <h1 class="subtitle">Admin Dashboard</h1>
    <p class="dashboard-description">Manage users, orders, and products efficiently.</p>

    <div class="dashboard-grid">
        <div class="dashboard-card">
            <h3>Total Users</h3>
            <p><?php echo number_format($totalUsers); ?></p>
        </div>
        <div class="dashboard-card">
            <h3>Total Orders</h3>
            <p><?php echo number_format($totalOrders); ?></p>
        </div>
        <div class="dashboard-card">
            <h3>Total Revenue</h3>
            <p>£<?php echo number_format($totalRevenue, 2); ?></p>
        </div>
    </div>
</section>

</body>
</html>
