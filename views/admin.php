<?php
session_start();
require_once "../database/database.php";

// Redirect non-logged-in users
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get user ID
$user_id = $_SESSION['user_id'];

// Check if user is an admin
$stmt = $pdo->prepare("SELECT admin FROM users WHERE user_id = :user_id");
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Redirect if not admin
if (!$user || !$user['admin']) {
    header("Location: ../index.php");
    exit();
}

// DELETE PRODUCT
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['delete'])) {
    $product_id = intval($_POST['delete']);
    $stmt = $pdo->prepare("DELETE FROM products WHERE product_id = ?");
    $stmt->execute([$product_id]);
    header("Location: admin.php");
    exit();
}

// ADD PRODUCT
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['add_product'])) {
    $name = htmlspecialchars(trim($_POST['name']));
    $price = filter_var($_POST['price'], FILTER_VALIDATE_FLOAT);
    $description = htmlspecialchars(trim($_POST['description']));
    $stock = filter_var($_POST['stock'], FILTER_VALIDATE_INT);
    $image = filter_var($_POST['image'], FILTER_SANITIZE_URL);
    $category_id = filter_var($_POST['category'], FILTER_VALIDATE_INT);

    if ($price === false || $stock === false || $price < 0 || $stock < 0) {
        die("Invalid price or stock value.");
    }

    $stmt = $pdo->prepare("INSERT INTO products (name, price, description, stock, image, category_id) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$name, $price, $description, $stock, $image, $category_id]);
    header("Location: admin.php");
    exit();
}

// FETCH PRODUCTS
$products = $pdo->query("SELECT p.*, c.name AS category_name FROM products p LEFT JOIN categories c ON p.category_id = c.category_id")->fetchAll(PDO::FETCH_ASSOC);

// FETCH CATEGORIES
$categories = $pdo->query("SELECT * FROM categories")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin Panel - Manage Products</title>
    <link rel="stylesheet" href="../public/css/admin.css">
</head>
<body>
    <h1>Inventory Management</h1>

    <!-- ADD PRODUCT FORM -->
    <form method="POST">
        <input type="text" name="name" placeholder="Product Name" required>
        <input type="number" name="price" placeholder="Price (£)" step="0.01" required>
        <textarea name="description" placeholder="Product Description" required></textarea>
        <input type="number" name="stock" placeholder="Stock Quantity" required>
        <input type="text" name="image" placeholder="Image URL" required>

        <!-- CATEGORY DROPDOWN -->
        <select name="category" required>
            <option value="">Select Category</option>
            <?php foreach ($categories as $category) { ?>
                <option value="<?php echo $category['category_id']; ?>"><?php echo htmlspecialchars($category['name']); ?></option>
            <?php } ?>
        </select>

        <button type="submit" name="add_product">Add Product</button>
    </form>

    <h2>Product List</h2>
    <table>
        <tr>
            <th>Name</th>
            <th>Category</th>
            <th>Price</th>
            <th>Stock</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($products as $product) { ?>
            <tr>
                <td><?php echo htmlspecialchars($product['name']); ?></td>
                <td><?php echo htmlspecialchars($product['category_name'] ?? 'Uncategorized'); ?></td>
                <td>£<?php echo number_format($product['price'], 2); ?></td>
                <td><?php echo htmlspecialchars($product['stock']); ?></td>
                <td>
                    <a href="edit-product.php?id=<?php echo $product['product_id']; ?>">Edit</a> |
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="delete" value="<?php echo $product['product_id']; ?>">
                        <button type="submit" onclick="return confirm('Are you sure?')">Delete</button>
                    </form>
                </td>
            </tr>
        <?php } ?>
    </table>
</body>
</html>
