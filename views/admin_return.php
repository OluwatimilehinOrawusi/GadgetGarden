<?php
session_start();

if (!isset($_SESSION['handled_returns'])) {
    $_SESSION['handled_returns'] =[];
}

// Require database connection
$pdo = require_once "../database/database.php";

// Ensure only admins can access
$user_id = $_SESSION['user_id'] ?? null;
$stmt = $pdo->prepare("SELECT role FROM users WHERE user_id = :user_id");
$stmt->execute([':user_id' => $user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user || $user['role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

// Handle return status updates
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_return_status'])) {
    $order_id = $_POST['order_id'];
    $new_status = $_POST['return_status']; // 'Approved' or 'Rejected'

    // Ensure valid status
    if (in_array($new_status, ['Approved', 'Rejected'])) {
        $stmt1 = $pdo->prepare("UPDATE orders SET return_status = :new_status WHERE order_id = :order_id");
        $stmt1->execute([':new_status' => $new_status, ':order_id' => $order_id]);

        $stmt2 = $pdo->prepare("UPDATE returns SET status = :new_status WHERE order_id = :order_id");
        $stmt2->execute([':new_status' => $new_status, ':order_id' => $order_id]);

        $_SESSION['handled_returns'][] = $order_id;
        $_SESSION['success_message'] = "Return status updated successfully.";
    } else {
        $_SESSION['error_message'] = "Invalid return status.";
    }
    header("Location: admin_return.php");
    exit();
}

// Fetch only orders with return requests
$stmt = $pdo->prepare("SELECT o.order_id, o.user_id, o.total_price, o.order_date, o.order_status, o.return_status, r.reason, r.details FROM orders o INNER JOIN returns r ON o.order_id = r.order_id WHERE o.return_status != 'No Return' ORDER BY o.order_date DESC");
$stmt->execute();
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Returns - Admin</title>
 
    <link rel="stylesheet" href="../public/css/navbar.css">
    <link rel="stylesheet" href="../public/css/styles.css">
    <link rel="stylesheet" href="../public/css/admin_return.css">
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

<div id="orders-container">
    <h1 class="subtitle">Manage Return Requests</h1>

    <?php if (isset($_SESSION['success_message'])): ?>
        <p class="success"><?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?></p>
    <?php endif; ?>

    <?php if (isset($_SESSION['error_message'])): ?>
        <p class="error"><?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?></p>
    <?php endif; ?>

    <table>
        <thead>
            <tr>
                <th>Order ID</th>
                <th>User ID</th>
                <th>Total Price (£)</th>
                <th>Order Date</th>
                <th>Order Status</th>
                <th>Return Status</th>
                <th>Reason</th>
                <th>Details</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
    <?php foreach ($orders as $order): ?>
        <?php if (!in_array($order['order_id'], $_SESSION['handled_returns'])): ?>
        <tr>
            <td><?php echo htmlspecialchars($order['order_id']); ?></td>
            <td><?php echo htmlspecialchars($order['user_id']); ?></td>
            <td>£<?php echo number_format($order['total_price'], 2); ?></td>
            <td><?php echo htmlspecialchars($order['order_date']); ?></td>
            <td><?php echo htmlspecialchars($order['order_status']); ?></td>
            <td><?php echo htmlspecialchars($order['return_status']); ?></td>
            <td><?php echo htmlspecialchars($order['reason']); ?></td>
            <td><?php echo htmlspecialchars($order['details']); ?></td>
            <td>
                <form action="admin_return.php" method="POST">
                    <input type="hidden" name="order_id" value="<?php echo $order['order_id']; ?>">
                    <select name="return_status">
                        <option value="Approved" <?php echo ($order['return_status'] == 'Approved') ? 'selected' : ''; ?>>Approve</option>
                        <option value="Rejected" <?php echo ($order['return_status'] == 'Rejected') ? 'selected' : ''; ?>>Reject</option>
                    </select>
                    <button type="submit" class = "submitbutton" name="update_return_status">Update</button>
                </form>
            </td>
        </tr>
        <?php endif; ?>
    <?php endforeach; ?>
            <?php if (empty($orders)): ?>
                <tr>
                    <td colspan="7" style="text-align: center;">No return requests found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>
