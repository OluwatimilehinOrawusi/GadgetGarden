<?php


if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$pdo = require_once "../database/database.php";

try {
    if (!isset($_SESSION['user_id'])) {
        throw new Exception("User ID is not set in session.");
    }

    if (!isset($_GET['product_id']) || !is_numeric($_GET['product_id'])) {
        throw new Exception("Invalid product ID");
    }

    $user_id = $_SESSION['user_id'];
    $product_id = intval($_GET['product_id']);

    $statement = $pdo->prepare(' INSERT IGNORE INTO wishlist (user_id, product_id) VALUES (:user_id, :product_id)');

    $statement->bindValue(":user_id", $user_id, PDO::PARAM_INT);
    $statement->bindValue(":product_id", $product_id, PDO::PARAM_INT);
    $statement->execute();
    if($statement->rowCount()>0) {
        echo "Product has been bookmarked successfully<br>";
        echo "<a href = './wishlist.php'>Open Wishlist</a> ";
         echo "<a href='./products.php'>Continue Shopping</a>";
    } else {
        throw new Exception("Failed to add the product to the wishlist.");
    }
} catch (PDOException $e) {
    // Catch PDO exceptions (database related)
    echo "Database error: " . htmlspecialchars($e->getMessage());
} catch (Exception $e) {
    // Catch general exceptions (such as logic or input errors)
    echo "Error: " . htmlspecialchars($e->getMessage());
}

?>