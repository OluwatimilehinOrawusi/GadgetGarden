<?php
session_start();
require_once "../database/database.php";

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?error=Please+log+in");
    exit();
}

// Check if the user is an admin or manager
$stmt = $pdo->prepare("SELECT role FROM users WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user || ($user['role'] !== 'admin' && $user['role'] !== 'manager')) {
    die("Error: You are not authorized to access this page.");
}

// Process Order Status Update
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['order_id']) && isset($_POST['order_status'])) {
    $orderId = intval($_POST['order_id']);
    $newStatus = $_POST['order_status'];

    $updateQuery = $pdo->prepare("UPDATE orders SET order_status = :order_status WHERE order_id = :order_id");
    $updateQuery->execute([':order_status' => $newStatus, ':order_id' => $orderId]);

    header("Location: manage_orders.php");
    exit();
}

// Fetch orders
$orderQuery = $pdo->prepare("
    SELECT o.order_id, o.order_date, o.total_price, o.order_status, 
           u.username, u.email 
    FROM orders o
    JOIN users u ON o.user_id = u.user_id
    ORDER BY o.order_date DESC
");
$orderQuery->execute();
$orders = $orderQuery->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Orders - Gadget Garden</title>
    <link rel="stylesheet" href="../public/css/navbar.css">
    <link rel="stylesheet" href="../public/css/styles.css">
    <link rel="stylesheet" href="../public/css/manage_orders.css">
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
        <a href="logout.php"><button class="green-button">Logout</button></a>
    </div>
</nav>

<div class="container">
    <h1>Manage Orders</h1>

    <?php if (empty($orders)) : ?>
        <p class="no-orders">No orders available.</p>
    <?php else : ?>
        <table>
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer</th>
                    <th>Email</th>
                    <th>Total Price</th>
                    <th>Order Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($order['order_id']); ?></td>
                    <td><?php echo htmlspecialchars($order['username']); ?></td>
                    <td><?php echo htmlspecialchars($order['email']); ?></td>
                    <td>£<?php echo number_format($order['total_price'], 2); ?></td>
                    <td><?php echo htmlspecialchars($order['order_date']); ?></td>
                    <td>
                        <form method="POST">
                            <input type="hidden" name="order_id" value="<?php echo $order['order_id']; ?>">
                            <select name="order_status">
                                <option value="Paid" <?php if ($order['order_status'] === 'Paid') echo 'selected'; ?>>Paid</option>
                                <option value="Dispatched" <?php if ($order['order_status'] === 'Dispatched') echo 'selected'; ?>>Dispatched</option>
                                <option value="Delivered" <?php if ($order['order_status'] === 'Delivered') echo 'selected'; ?>>Delivered</option>
                            </select>
                            <button type="submit" class="update-button">Update</button>
                        </form>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

</body>
</html>
