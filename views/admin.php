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

    if (isset($_POST['update_stock'])) {
        $product_id = intval($_POST['product_id']);
        $new_stock = filter_var($_POST['stock'], FILTER_VALIDATE_INT);
        if ($new_stock !== false && $new_stock >= 0) {
            $stmt = $pdo->prepare("UPDATE products SET stock = ? WHERE product_id = ?");
            $stmt->execute([$new_stock, $product_id]);
        }
        header("Location: admin.php");
        exit();
    }

    if (isset($_POST['update_price'])) {
        $product_id = intval($_POST['product_id']);
        $new_price = filter_var($_POST['price'], FILTER_VALIDATE_FLOAT);
        if ($new_price !== false && $new_price >= 0) {
            $stmt = $pdo->prepare("UPDATE products SET price = ? WHERE product_id = ?");
            $stmt->execute([$new_price, $product_id]);
        }
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

        if ($price !== false && $stock !== false && $price >= 0 && $stock >= 0) {
            $stmt = $pdo->prepare("INSERT INTO products (name, price, description, stock, image, category_id) 
                                   VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$name, $price, $description, $stock, $image, $category_id]);
        }
        header("Location: admin.php");
        exit();
    }
}

$searchQuery = "";
if (isset($_GET['search'])) {
    $searchQuery = trim($_GET['search']);
    $stmt = $pdo->prepare("SELECT p.*, c.name AS category_name FROM products p 
                           LEFT JOIN categories c ON p.category_id = c.category_id
                           WHERE p.name LIKE :search OR c.name LIKE :search OR p.price LIKE :search");
    $stmt->execute(["search" => "%$searchQuery%"]);
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $products = $pdo->query("SELECT p.*, c.name AS category_name FROM products p 
                             LEFT JOIN categories c ON p.category_id = c.category_id")->fetchAll(PDO::FETCH_ASSOC);
}

$categories = $pdo->query("SELECT * FROM categories")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Inventory Management - Gadget Garden</title>
    <link rel="stylesheet" href="../public/css/navbar.css">
    <link rel="stylesheet" href="../public/css/styles.css">
    <link rel="stylesheet" href="../public/css/admin.css">
   <style>
    /* General Styles for the Page */
/* General Styles for the Page */
body {
    font-family: Arial, sans-serif;
    background-color: #f9f9f9;
    margin: 0;
    padding: 0;
}

.container {
    width: 90%;
    max-width: 1200px;
    margin: 20px auto;
    background-color: white;
    padding: 20px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
}

h1 {
    text-align: center;
    color: #333;
}

/* Styling the Search Bar */
.search-bar {
    margin: 20px 0;
    display: flex;
    justify-content: flex-start;
}

.search-bar input {
    padding: 8px;
    font-size: 16px;
    border-radius: 4px;
    border: 1px solid #ccc;
    width: 300px;
    margin-right: 10px;
}

.search-bar button {
    padding: 8px 16px;
    background-color: #275E4A; /* Darker Green */
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

.search-bar button:hover {
    background-color: #1f4a38; /* Darker shade of green */
}

/* Table Styling */
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

th, td {
    padding: 12px 15px;
    text-align: left;
    border: 1px solid #ddd;
}

th {
    background-color: #275E4A; /* Green color */
    color: white;
}

td {
    background-color: #f9f9f9;
}

td a {
    color: #275E4A; /* Green color */
    text-decoration: none;
    padding: 5px 10px;
    border: 1px solid #275E4A;
    border-radius: 4px;
}

td a:hover {
    background-color: #275E4A; /* Green color */
    color: white;
}

/* Button Styling for Delete and Update */
td button {
    padding: 8px 15px;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

td button.update-btn {
    background-color: #4a9c85; /* Darker Green */
    color: white;
    border: none;
}

td button.update-btn:hover {
    background-color: #3b7c67; /* Even Darker Green */
}

/* Responsive Table for Small Screens */
@media screen and (max-width: 768px) {
    table {
        width: 100%;
        font-size: 14px;
    }

    .search-bar input {
        width: 100%;
        margin-right: 0;
    }

    .search-bar button {
        width: 100%;
    }
}

    </style>
</head>
<body>

<nav>
    <div class="nav-left">
        <a href="../index.php"><p id="logo-text">GADGET GARDEN</p></a>
    </div>
    <div class="nav-right">
        <a href="dashboard.php"><button class="white-button">Dashboard</button></a>
        <a href="admin.php"><button class="white-button">Inventory</button></a>
        <a href="manage_orders.php"><button class="white-button">Orders</button></a>
        <a href="logout.php"><button class="green-button">Logout</button></a>
    </div>
</nav>

<div class="container">
    <h1>Inventory Management</h1>

    <div class="search-bar">
        <form method="GET">
            <input type="text" name="search" placeholder="Search by Name, Category, or Price" value="<?php echo htmlspecialchars($searchQuery); ?>">
            <button type="submit">Search</button>
        </form>
    </div>

    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Category</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $product) : ?>
                <tr>
                    <td><?php echo htmlspecialchars($product['name']); ?></td>
                    <td><?php echo htmlspecialchars($product['category_name'] ?? 'Uncategorized'); ?></td>
                    <td>£<?php echo number_format($product['price'], 2); ?></td>
                    <td><?php echo htmlspecialchars($product['stock']); ?></td>
                    <td>
                        <a href="update.php?product_id=<?php echo $product['product_id']; ?>">Edit</a>
                        <?php if ($is_admin) : ?>
                            <!-- Delete Form -->
<form method="POST" action="delete.php" onsubmit="return confirm('Are you sure you want to delete this product?');">
    <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
    <button class="update-btn" style="background-color: red; color: white; border: none; padding: 10px 15px; cursor: pointer;">
        Delete
    </button>
</form>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

</body>
</html>
