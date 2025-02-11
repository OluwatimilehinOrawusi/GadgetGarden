<?php
session_start();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$pdo = require_once "../database/database.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['delete'])) {
        $product_id = $_POST['delete'];
        $stmt = $pdo->prepare("DELETE FROM products WHERE product_id = ?");
        $stmt->execute([$product_id]);
        header("Location: admin.php");
        exit();
    }

    if (isset($_POST['name'], $_POST['price'], $_POST['description'], $_POST['stock'], $_POST['image'])) {
        $name = htmlspecialchars($_POST['name']);
        $price = filter_var($_POST['price'], FILTER_VALIDATE_FLOAT);
        $description = htmlspecialchars($_POST['description']);
        $stock = filter_var($_POST['stock'], FILTER_VALIDATE_INT);
        $image = filter_var($_POST['image'], FILTER_SANITIZE_URL);

        if ($price === false || $stock === false || $price < 0 || $stock < 0) {
            die("Invalid price or stock value.");
        }

        $stmt = $pdo->prepare("INSERT INTO products (name, price, description, stock, image) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$name, $price, $description, $stock, $image]);
        header("Location: admin.php");
        exit();
    }
}

$products = $pdo->query("SELECT * FROM products")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel - Manage Products</title>
    <link rel="stylesheet" href="../public/css/admin.css">
</head>
<body>
    <h1>Inventory Management</h1>
    <form method="POST">
        <input type="text" name="name" placeholder="Product Name" required>
        <input type="number" name="price" placeholder="Price" required>
        <input type="text" name="description" placeholder="Description" required>
        <input type="number" name="stock" placeholder="Stock" required>
        <input type="text" name="image" placeholder="Image URL" required>
        <button type="submit">Add Product</button>
    </form>

    <h2>Product List</h2>
    <table>
        <tr>
            <th>Name</th>
            <th>Price</th>
            <th>Stock</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($products as $product) { ?>
            <tr>
                <td><?php echo htmlspecialchars($product['name']); ?></td>
                <td>£<?php echo htmlspecialchars($product['price']); ?></td>
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
