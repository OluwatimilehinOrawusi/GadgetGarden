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

    if (!empty($_FILES['image']['name'])) {
        $image = $_FILES['image']['name'];
        $target = "uploads/".basename($image);
        move_uploaded_file($_FILES['image']['tmp_name'], $target);

        $sql = "UPDATE products SET name = ?, description = ?, stock = ?, image = ? WHERE product_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$new_name, $new_description, $new_stock, $image, $product_id]);
    } else {
        $sql = "UPDATE products SET name = ?, description = ?, stock = ? WHERE product_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$new_name, $new_description, $new_stock, $product_id]);
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
    <h2>Update Product</h2>
    <form method="POST" action="update.php" enctype="multipart/form-data">
        <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
        <label>Product Name:</label>
        <input type="text" name="name" value="<?php echo $product['name']; ?>" required><br>
        <label>Description:</label>
        <textarea name="description" required><?php echo $product['description']; ?></textarea><br>
        <label>Stock:</label>
        <input type="number" name="stock" value="<?php echo $product['stock']; ?>" required><br>
        <label>Image:</label>
        <input type="file" name="image"><br>
        <img src="uploads/<?php echo $product['image']; ?>" width="100" height="100"><br>
        <button type="submit">Update</button>
    </form>
</body>
</html>
