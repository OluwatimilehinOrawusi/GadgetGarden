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


//Category association array
$categories = [
    1 => "Laptops",
    2 => "Audio",
    3 => "Phones",
    4 => "Gaming",
    5 => "Wearables",
    6 => "Tablets",
    7 => "Accessories",
    8 => "Computers"
];

?>


<!-- HTML -->
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

    <div class="container">
    <h1>Review User Uploads</h1>
    <?php if (empty($uploads)) : ?>
        <p>No products have been uploaded</p>
    <?php else : ?>
        <!------Table Headers------>
        <table>
            <thead>
                    <th>User ID</th>
                    <th>Username</th>
                    <th>Product ID</th>
                    <th>Product Name</th>
                    <th>Product Category</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Condition</th>
                    <th>Description</th>
                    <th>Images</th>
                    <th>Decision</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($uploads as $product) { ?>
                    
                        <!------Table Information------>    
                <tr>
                    <td><?php echo htmlspecialchars($product['user_id']); ?></td>
                    <td><?php echo htmlspecialchars(getUsernameById($pdo, $product['user_id'])); ?></td>
                    <td><?php echo htmlspecialchars($product['product_id']); ?></td>
                    <td><?php echo htmlspecialchars($product['name']); ?></td>
                    <td><?php echo htmlspecialchars($categories[$product['category_id']] ?? "Unknown"); ?></td>
                    <td>£<?php echo htmlspecialchars($product['price']); ?></td>
                    <td><?php echo number_format($product['quantity']); ?></td>
                    <td><?php echo htmlspecialchars($product['condition']); ?></td>
                    <td><?php echo htmlspecialchars($product['description']); ?></td>
                    <td><img src="<?= htmlspecialchars($product['image_path']) ?>" width="200" height="200" alt = "image of user product upload"></td>
                    <td> <form action = "approveproduct.php" method = "POST">
                        <input type = "hidden" name = "product_id" value = "<?= $product['product_id'] ?>">
                        <button type = "submit" name ="action" value = "approve" id = "approve">Approve</button>
                        <br> <br>
                        <button type = "submit" name = "action" value = "reject" id = "reject">Reject</button>
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