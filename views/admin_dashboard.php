<?php
session_start();

// Ensure database connection
require_once "../database/database.php";

if (!isset($pdo) || !$pdo instanceof PDO) {
    die("Database connection failed.");
}

// Check if user is an admin
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch admin status
$stmt = $pdo->prepare("SELECT admin FROM users WHERE user_id = :user_id");
$stmt->bindParam(':user_id', $_SESSION['user_id']);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Redirect if not admin
if (!$user || !$user['admin']) {
    header("Location: ../index.php");
    exit();
}

// Fetch total users
$totalUsers = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();

// Fetch total orders
$totalOrders = $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn();

// Fetch total revenue
$totalRevenue = $pdo->query("SELECT SUM(total_price) FROM orders")->fetchColumn();

// Fetch all orders with user details
$ordersStmt = $pdo->prepare("
    SELECT o.order_id, o.total_price, o.order_date, u.username, u.email
    FROM orders o
    JOIN users u ON o.user_id = u.user_id
    ORDER BY o.order_date DESC
");
$ordersStmt->execute();
$orders = $ordersStmt->fetchAll(PDO::FETCH_ASSOC);
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
            <p>£<?php echo number_format($totalRevenue, 2); ?></p>
        </div>
    </div>
</section>

<!-- Orders Table -->
<section class="admin-orders">
    <h2>All Orders</h2>
    <table>
        <tr>
            <th>Order ID</th>
            <th>Customer</th>
            <th>Email</th>
            <th>Total Price</th>
            <th>Order Date</th>
        </tr>
        <?php foreach ($orders as $order) { ?>
            <tr>
                <td><?php echo htmlspecialchars($order['order_id']); ?></td>
                <td><?php echo htmlspecialchars($order['username']); ?></td>
                <td><?php echo htmlspecialchars($order['email']); ?></td>
                <td>£<?php echo number_format($order['total_price'], 2); ?></td>
                <td><?php echo htmlspecialchars($order['order_date']); ?></td>
            </tr>
        <?php } ?>
    </table>
</section>

</body>
</html>
