<?php
session_start();
require_once "../database/database.php";

// Redirect if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch user role from the database to verify admin status
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT admin FROM users WHERE user_id = :user_id");
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Redirect non-admin users
if (!$user || !$user['admin']) {
    header("Location: ../index.php");
    exit();
}

// Fetch total users
$totalUsers = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn() ?? 0;

// Fetch total orders
$totalOrders = $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn() ?? 0;

// Fetch total revenue
$totalRevenue = $pdo->query("SELECT SUM(total_price) FROM orders")->fetchColumn();
$totalRevenue = $totalRevenue ? number_format($totalRevenue, 2) : "0.00"; // Format revenue

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../public/css/admin.css">
</head>
<body>

<!-- Admin Navigation -->
<nav>
    <h2>Admin Panel</h2>
    <ul>
        <li><a href="admin_dashboard.php">Dashboard</a></li>
        <li><a href="manage_users.php">Users</a></li>
        <li><a href="admin.php">Products</a></li>
        <li><a href="manage_orders.php">Orders</a></li>
        <li><a href="logout.php">Logout</a></li>
    </ul>
</nav>

<!-- Dashboard Stats -->
<section class="admin-dashboard">
    <h1>Welcome, Admin</h1>
    <div class="dashboard-stats">
        <div class="stat-box">
            <h3>Total Users</h3>
            <p><?php echo $totalUsers; ?></p>
        </div>
        <div class="stat-box">
            <h3>Total Orders</h3>
            <p><?php echo $totalOrders; ?></p>
        </div>
        <div class="stat-box">
            <h3>Total Revenue</h3>
            <p>£<?php echo $totalRevenue; ?></p>
        </div>
    </div>
</section>

</body>
</html>
