<?php
session_start();

$pdo = require_once "../database/database.php";

$user_id = $_SESSION['user_id'] ?? null;

// Fetch the admin status of the user
$stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = :user_id");
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user || ($user['role'] !== 'admin' && $user['role'] !== 'manager')) {
header("Location: ../index.php");
exit();
}

//If product id is included in the URL
if (!isset($_GET['product_id']) || !is_numeric($_GET['product_id'])) {
    die("Error: Invalid product ID."); // Temporary debugging message
    header("Location: admin.php");
    exit();
}

$product_id = intval($_GET['product_id']);

//Fetch the product details from database
$stmt = $pdo->prepare("SELECT * FROM products WHERE product_id = ?");
$stmt->execute([$product_id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    header("Location: admin.php");
    exit();
}

$categories = $pdo->query("SELECT * FROM categories")->fetchAll(PDO::FETCH_ASSOC);

// Form submission
if($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars(trim($_POST['name']));
    $price = filter_var($_POST['price'], FILTER_VALIDATE_FLOAT);
    $description = htmlspecialchars(trim($_POST['description']));
    $stock = filter_var($_POST['stock'], FILTER_VALIDATE_INT);
    $image = filter_var($_POST['image'], FILTER_SANITIZE_URL);
    $category_id = filter_var($_POST['category'], FILTER_VALIDATE_INT);


    if ($price !== false && $stock !== false && $price >= 0 && $stock >= 0) {
        $stmt = $pdo->prepare("UPDATE products SET name = ?, price = ?, description = ?, stock = ?, image = ?, category_id = ? WHERE product_id = ?");
        $stmt->execute([$name, $price, $description, $stock, $image, $category_id, $product_id]);


        header("Locationm: admin.php");
        exit();
    }
}

?> 

<!DOCTYPE html>
<html lang = "en">
    <head>
        <title>Edit Product - Gadget Garden</title>
        <link rel="stylesheet" href="../public/css/styles.css">
    </head>
    <body>
        <div class="container">
            <h1>Edit Product</h1>
            <form method="POST">
                <label for="name">Product Name:</label>
                <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($product['name']); ?>" required>

                <label for="price">Price (£):</label>
                <input type="number" name="price" id="price" step="0.01" value="<?php echo htmlspecialchars($product['price']); ?>" required>

                <label for="description">Description:</label>
                <textarea name="description" id="description" required><?php echo htmlspecialchars($product['description']); ?></textarea>

                <label for="stock">Stock:</label>
                <input type="number" name="stock" id="stock" value="<?php echo htmlspecialchars($product['stock']); ?>" required>

                <label for="image">Image URL:</label>
                <input type="text" name="image" id="image" value="<?php echo htmlspecialchars($product['image']); ?>">

                <label for="category">Category:</label>
                <select name="category" id="category" required>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?php echo $category['category_id']; ?>" 
                        <?php echo ($category['category_id'] == $product['category_id']) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($category['name']); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <button type="submit">Update Product</button>
        <a href="admin.php"><button type="button">Cancel</button></a>
                </form>
        </div>
    </body>
</html>


