<?php
session_start();
require_once "../database/database.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT role FROM users WHERE user_id = :user_id");
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user || ($user['role'] !== 'admin' && $user['role'] !== 'manager')) {
    header("Location: ../index.php");
    exit();
}

$is_admin = ($user['role'] === 'admin');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['delete']) && $is_admin) {
        $product_id = intval($_POST['delete']);
        $stmt = $pdo->prepare("DELETE FROM products WHERE product_id = ?");
        $stmt->execute([$product_id]);
        header("Location: admin.php");
        exit();
    }

    if (isset($_POST['add_product'])) {
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
}

$products = $pdo->query("SELECT p.*, c.name AS category_name FROM products p LEFT JOIN categories c ON p.category_id = c.category_id")->fetchAll(PDO::FETCH_ASSOC);
$categories = $pdo->query("SELECT * FROM categories")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin Panel - Manage Products</title>
    <link rel="stylesheet" href="../public/css/admin.css">
</head>
<body>

<nav>
    <div class="nav-left">
        <a href="../index.php"><p id="logo-text">GADGET GARDEN</p></a>
    </div>
    <div class="nav-right">
        <a href="admin_dashboard.php"><button class="white-button">Dashboard</button></a>
        <a href="manage_orders.php"><button class="white-button">Orders</button></a>
        <a href="admin.php"><button class="white-button">Products</button></a>

        <?php if ($is_admin) : ?>
            <a href="manage_users.php"><button class="white-button">Users</button></a>
        <?php endif; ?>

        <a href="logout.php"><button class="green-button">Logout</button></a>
    </div>
</nav>

<h1>Inventory Management</h1>

<form method="POST">
    <input type="text" name="name" placeholder="Product Name" required>
    <input type="number" name="price" placeholder="Price (£)" step="0.01" required>
    <textarea name="description" placeholder="Product Description" required></textarea>
    <input type="number" name="stock" placeholder="Stock Quantity" required>
    <input type="text" name="image" placeholder="Image URL" required>

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
                <a href="edit-product.php?id=<?php echo $product['product_id']; ?>">Edit</a>
                
                <?php if ($is_admin) : ?>
                    | 
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="delete" value="<?php echo $product['product_id']; ?>">
                        <button type="submit" onclick="return confirm('Are you sure?')">Delete</button>
                    </form>
                <?php endif; ?>
            </td>
        </tr>
    <?php } ?>
</table>

</body>
</html>
