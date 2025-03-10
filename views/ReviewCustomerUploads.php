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

//Retrieve username
function getUsernameById($pdo, $user_id) {
    $stmt = $pdo->prepare("SELECT username FROM users WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    return $user ? $user['username'] : null; 
}



//retrieve upload products information
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
    <link rel="stylesheet" href="../public/css/navbar.css">
    <link rel="stylesheet" href="../public/css/styles.css">
    <link rel="stylesheet" href="../public/css/reviewcustomeruploads.css">
</head>
<body>

<nav>
        <div class="nav-left">
            <a href="../index.php"><p id="logo-text">GADGET GARDEN</p></a>
        </div>
        <div class="nav-right">
            <a href="../views/aboutpage.php"><button class="white-button">About Us</button></a>
            <?php if (!isset($_SESSION['user_id'])) { ?>
                <a href="./login.php"><button class="green-button">Login</button></a>
                <a href="./signup.php"><button class="white-button">Sign Up</button></a>
            <?php } else { ?>
                <a href="./basket.php"><button class="white-button">Basket</button></a>
                <a href="./contact.php"><button class="white-button">Contact Us</button></a>
                <a href="./profile.php"><button class="white-button">Profile</button></a>
                <a href="./logout.php"><button class="green-button">Logout</button></a>
            <?php } ?>
        </div>
    </nav>
    <h1>Review User Uploads</h1>
    <?php if (empty($uploads)) : ?>
        <p>No products have been uploaded</p>
    <?php else : ?>
        <table>
            <thead>
                    <th>User ID</th>
                    <th>Username</th>
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
                    <td><?php echo htmlspecialchars($product['user_id']); ?></td>
                    <td><?php echo htmlspecialchars(getUsernameById($pdo, $product['user_id'])); ?></td>
                    <td><?php echo htmlspecialchars($product['product_id']); ?></td>
                    <td><?php echo htmlspecialchars($product['name']); ?></td>
                    <td><?php echo htmlspecialchars($product['price']); ?></td>
                    <td>£<?php echo number_format($product['quantity']); ?></td>
                    <td><?php echo htmlspecialchars($product['condition']); ?></td>
                    <td><?php echo htmlspecialchars($product['description']); ?></td>
                    <td><img src="<?= htmlspecialchars($product['image_path']) ?>" width="40" height="40" alt = "image of user product upload"></td>
                    <td> <form action = "approveproduct.php" method = "POST">
                        <input type = "hidden" name = "product_id" value = "<?= $product['product_id'] ?>">
                        <button type = "submit" name ="action" value = "approve">Approve</button>
                        <button type = "submit" name = "action" value = "reject">Reject</button>
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