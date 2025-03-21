<?php
$pdo = require_once "../database/database.php";

// Check if product_id is set
if (isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];

    // Delete the product from the database
    $stmt = $pdo->prepare("DELETE FROM products WHERE product_id = ?");
    $stmt->execute([$product_id]);

    // Redirect to admin page after deletion
    header("Location: admin.php");
    exit;
} else {
    echo "Product ID is missing!";
    exit;
}
?>

