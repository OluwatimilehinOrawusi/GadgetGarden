<?php
session_start();
require_once "../database/database.php";

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?error=Please+log+in");
    exit();
}

// grab user role
if (!isset($_SESSION['user_role'])) {
    $stmt = $pdo->prepare("SELECT admin FROM users WHERE user_id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    $_SESSION['user_role'] = $user['admin'] ? 'admin' : 'user';
}

// Ensure user is an admin
if ($_SESSION['user_role'] !== 'admin') {
    die("Error: Permission denied. You are not an admin");
}

$stmt = $pdo->prepare("SELECT * FROM upload_products WHERE Admin_approve = 0");
$stmt->execute();
$uploads = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Review Uploads</title>
</head>
<body>
    <h1>Review User Uploads</h1>
    <?php if (empty($uploads)) : ?>
        <p style="text-align: center; color: #888;">No products have been uploaded</p>
    <?php else : ?>
        <table>
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>Product ID</th>
                    <th>Product Name</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Condition</th>
                    <th>Description</th>
                    <th>Images</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($uploads as $product) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($product['product_id']); ?></td>
                    <td><?php echo htmlspecialchars($product['product_name']); ?></td>
                    <td><?php echo htmlspecialchars($product['price']); ?></td>
                    <td>£<?php echo number_format($product['quantity']); ?></td>
                    <td><?php echo htmlspecialchars($product['condition']); ?></td>
                    <td><?php echo htmlspecialchars($product['description']); ?></td>
                    <td><img src="<?= htmlspecialchars($product['image_path']) ?>" width="40" height="40" alt = "image of user product upload"></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
    

</body>
</html>