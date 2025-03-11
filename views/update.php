<?php
$pdo = require_once "../database/database.php";

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
    $new_price = $_POST['price']; // New price field
    $new_condition = $_POST['condition']; // New condition field

    if (!empty($_FILES['image']['name'])) {
        $image = $_FILES['image']['name'];
        $target = "uploads/".basename($image);
        move_uploaded_file($_FILES['image']['tmp_name'], $target);
        $sql = "UPDATE products SET name = ?, description = ?, stock = ?, price = ?, state = ?, image = ? WHERE product_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$new_name, $new_description, $new_stock, $new_price, $new_condition, $image, $product_id]);
    } else {
        $sql = "UPDATE products SET name = ?, description = ?, stock = ?, price = ?, state = ? WHERE product_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$new_name, $new_description, $new_stock, $new_price, $new_condition, $product_id]);
    }

    header("Location: admin.php");
    exit;
}

if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];
    $stmt = $pdo->prepare("SELECT * FROM products WHERE product_id = ?");
    $stmt->execute([$product_id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Update Product</title>
    <link rel="stylesheet" href="../public/css/navbar.css">
    <link rel="stylesheet" href="../public/css/styles.css">
    <link rel="stylesheet" href="../public/css/update_product.css"> <!-- ✅ New CSS File -->
</head>
<body>

<nav>
    <div class="nav-left">
        <a href="../index.php"><p id="logo-text">GADGET GARDEN</p></a>
    </div>
    <div class="nav-right">
        <a href="dashboard.php"><button class="white-button">Dashboard</button></a>
        <a href="manage_users.php"><button class="white-button">Users</button></a>
        <a href="admin.php"><button class="white-button">Products</button></a>
        <a href="manage_orders.php"><button class="white-button">Orders</button></a>
        <a href="logout.php"><button class="green-button">Logout</button></a>
    </div>
</nav>

    <h2>Update Product</h2>
    <form method="POST" action="update.php" enctype="multipart/form-data">
        <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
        <label>Product Name:</label>
        <input type="text" name="name" value="<?php echo $product['name']; ?>" required><br>
        <label>Description:</label>
        <textarea name="description" required><?php echo $product['description']; ?></textarea><br>
        <label>Stock:</label>
        <input type="number" name="stock" value="<?php echo $product['stock']; ?>" required><br>
        <label>Price:</label>
        <input type="text" name="price" value="<?php echo $product['price']; ?>" required><br> <!-- New Price Field -->
        <label>Condition:</label>
        <input type="text" name="condition" value="<?php echo $product['state']; ?>" required><br> <!-- New Condition Field -->
        <label>Image:</label>
        <input type="file" name="image"><br>
        <img src="uploads/<?php echo $product['image']; ?>" width="100" height="100"><br>
        <button type="submit">Update</button>
    </form>
</body>
</html>
