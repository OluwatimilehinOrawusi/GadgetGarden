<?php
//Start Sessions
session_start();

//Connects to database
$pdo = require_once "../database/database.php";


$user = null;
if (isset($_SESSION['user_id'])) {
    $stmt = $pdo->prepare("SELECT role FROM users WHERE user_id = :user_id");
    $stmt->execute(['user_id' => $_SESSION['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
}

if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];
    $stmt = $pdo->prepare("SELECT * FROM products WHERE product_id = ?");
    $stmt->execute([$product_id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_id = $_POST['product_id'];
    $new_name = $_POST['name'];
    $new_description = $_POST['description'];
    $new_stock = $_POST['stock'];
    $new_price = filter_var($_POST['price'], FILTER_VALIDATE_FLOAT); // Validate the price
    $new_condition = $_POST['condition'];

    // Check if price is valid
    if ($new_price === false || $new_price < 0) {
        $_SESSION['error'] = "Invalid price value.";
        header("Location: update.php?product_id=" . $product_id);
        exit;
    }

    if (!empty($_FILES['image']['name'])) {
        $original_name = basename($_FILES['image']['name']);
        $image = time() . '_' . preg_replace("/[^a-zA-Z0-9._-]/", "_", $original_name); // Clean file name
        $target_dir = "../uploads/";
        $target = $target_dir . $image;

        // Ensure upload directory exists
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0755, true);
        }

        if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
            $sql = "UPDATE products SET name = ?, description = ?, stock = ?, price = ?, state = ?, image = ? WHERE product_id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$new_name, $new_description, $new_stock, $new_price, $new_condition, $target, $product_id]);
        } else {
            $_SESSION['error'] = "Image upload failed.";
            header("Location: update.php?product_id=" . $product_id);
            exit;
        }
    } else {
        $sql = "UPDATE products SET name = ?, description = ?, stock = ?, price = ?, state = ? WHERE product_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$new_name, $new_description, $new_stock, $new_price, $new_condition, $product_id]);
    }

    $_SESSION['success'] = "Product updated successfully!";
    header("Location: admin.php");
    exit;
}
?>



<!-- HTML -->
<!DOCTYPE html>
<html>
<head>
    <title>Update Product</title>
    <link rel="stylesheet" href="../public/css/navbar.css">
    <link rel="stylesheet" href="../public/css/styles.css">
    <link rel="stylesheet" href="../public/css/update_product.css"> <!--  New CSS File -->
</head>
<body>

<!-- Admin Navbar -->
<nav>
    <div class="nav-left">
        <a href="../index.php"><p id="logo-text">GADGET GARDEN</p></a>
    </div>
    <div class="nav-right">
        <a href="./dashboard.php"><button class="white-button">Dashboard</button></a>
        <?php if($user && $user['role']==='admin'){?>
            <a href="manage_users.php"><button class="white-button">Users</button></a>
        <?php } ?>
        <a href="manage_orders.php"><button class="white-button">Orders</button></a>
        <a href="admin.php"><button class="white-button">Inventory</button></a>
        <a href="logout.php"><button class="green-button">Logout</button></a>
    </div>
</nav>

<h2>Update Product</h2>
<form method="POST" action="update.php" enctype="multipart/form-data">
    <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product['product_id']); ?>">
    <label>Product Name:</label>
    <input type="text" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" required><br>
    <label>Description:</label>
    <textarea name="description" required><?php echo htmlspecialchars($product['description']); ?></textarea><br>
    <label>Stock:</label>
    <input type="number" name="stock" value="<?php echo htmlspecialchars($product['stock']); ?>" required><br>
    <label>Price:</label>
    <input type="text" name="price" value="<?php echo htmlspecialchars($product['price']); ?>" required><br> <!-- New Price Field -->
    <label>Condition:</label>
    <input type="text" name="condition" value="<?php echo htmlspecialchars($product['state']); ?>" required><br> <!-- New Condition Field -->
    <label>Image:</label>
    <input type="file" name="image"><br>
    <img src="<?php echo htmlspecialchars($product['image']); ?>" width="100" height="100"><br>
    <button type="submit">Update</button>
</form>

</body>
</html>
