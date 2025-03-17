<?php
session_start();
require_once ("../database/database.php");

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

// Check if user is an admin
$stmt = $pdo->prepare("SELECT role FROM users WHERE user_id = :user_id");
$stmt->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Ensure only admins can access
if (!$user || ($user['role'] !== 'admin' && $user['role'] !== 'manager')) {
    die("Access denied.");
}

// Fetch all orders
$orderQuery = $pdo->prepare("SELECT order_id, user_id, total_price, order_status FROM orders ORDER BY order_id DESC");
$orderQuery->execute();
$orders = $orderQuery->fetchAll(PDO::FETCH_ASSOC);

// Update Order Status
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['order_id']) && isset($_POST['order_status'])) {
    $orderId = intval($_POST['order_id']);
    $newStatus = $_POST['order_status'];

    $updateQuery = $pdo->prepare("UPDATE orders SET order_status = :order_status WHERE order_id = :order_id");
    $updateQuery->execute([':order_status' => $newStatus, ':order_id' => $orderId]);

    header("Location: admin_orders.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin - Manage Orders</title>
    <?php require '../partials/header.php'; ?>
    <link rel="stylesheet" href="../public/css/admin_orders.css">
</head>
<body>
    <?php require '../partials/navbar.php'; ?>

    <div class="admin-container">
        <h2>Manage Orders</h2>
        
        <table>
            <tr>
                <th>Order ID</th>
                <th>User ID</th>
                <th>Total Price</th>
                <th>Status</th>
            </tr>
            <?php foreach ($orders as $order) : ?>
                <tr>
                    <td><?php echo htmlspecialchars($order['order_id']); ?></td>
                    <td><?php echo htmlspecialchars($order['user_id']); ?></td>
                    <td>£<?php echo htmlspecialchars($order['total_price']); ?></td>
                    <td>
                        <form method="POST">
                            <input type="hidden" name="order_id" value="<?php echo $order['order_id']; ?>">
                            <select name="order_status" class="status-dropdown">
                                <option value="Paid" <?php if ($order['order_status'] === 'Paid') echo 'selected'; ?>>Paid</option>
                                <option value="Dispatched" <?php if ($order['order_status'] === 'Dispatched') echo 'selected'; ?>>Dispatched</option>
                                <option value="Delivered" <?php if ($order['order_status'] === 'Delivered') echo 'selected'; ?>>Delivered</option>
                            </select>
                            <button type="submit">Update</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>

    <?php require '../partials/footer.php'; ?>
</body>
</html>
