<?php
session_start();
require_once "../database/database.php";

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

try {
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $user_id = $_SESSION['user_id'];

    // Fetch user role to check admin access
    $stmt = $pdo->prepare("SELECT role FROM users WHERE user_id = :user_id");
    $stmt->execute(["user_id" => $user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user || $user['role'] !== 'admin') {
        header("Location: ../index.php");
        exit();
    }

    // Fetch all orders
    $stmt = $pdo->prepare("
        SELECT o.order_id, o.user_id, o.total_price, o.order_date, u.username 
        FROM orders o 
        JOIN users u ON o.user_id = u.user_id 
        ORDER BY o.order_date DESC
    ");
    $stmt->execute();
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Database error: " . htmlspecialchars($e->getMessage());
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders Management - Gadget Garden</title>
    <link rel="stylesheet" href="../public/css/navbar.css">
    <link rel="stylesheet" href="../public/css/styles.css">
    <link rel="stylesheet" href="../public/css/orders.css">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 90%;
            max-width: 1000px;
            margin: 40px auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #333;
            font-size: 28px;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: white;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: center;
        }

        th {
            background-color: #1E5631;
            color: white;
            font-size: 16px;
        }

        td {
            font-size: 14px;
            color: #333;
        }

        .view-btn {
            background: #145A32;
            color: white;
            border: none;
            padding: 8px 12px;
            font-size: 14px;
            border-radius: 5px;
            cursor: pointer;
            transition: 0.3s;
        }

        .view-btn:hover {
            background: #117A3D;
        }
    </style>
</head>
<body>

<?php require_once "../partials/navbar.php"; ?>

<div class="container">
    <h1>Order Management</h1>
    <p style="text-align:center;">Only admins can manage orders.</p>

    <table>
        <thead>
            <tr>
                <th>Order ID</th>
                <th>User</th>
                <th>Total Price</th>
                <th>Order Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($orders)): ?>
                <tr><td colspan="5" style="text-align:center; color:red;">No orders found.</td></tr>
            <?php else: ?>
                <?php foreach ($orders as $order) : ?>
                    <tr>
                        <td><?php echo htmlspecialchars($order['order_id']); ?></td>
                        <td><?php echo htmlspecialchars($order['username']); ?> (ID: <?php echo $order['user_id']; ?>)</td>
                        <td>£<?php echo number_format($order['total_price'], 2); ?></td>
                        <td><?php echo htmlspecialchars($order['order_date']); ?></td>
                        <td>
                            <a href="view_order.php?order_id=<?php echo $order['order_id']; ?>">
                                <button class="view-btn">View Details</button>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require_once "../partials/footer.php"; ?>

</body>
</html>
