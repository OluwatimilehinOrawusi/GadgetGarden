<?php
session_start();
require_once "../database/database.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

try {
    if (!isset($_GET['product_id']) || !is_numeric($_GET['product_id'])) {
        throw new Exception("Invalid product ID.");
    }

    $user_id = $_SESSION['user_id'];
    $product_id = intval($_GET['product_id']);
    $quantity = 1; 

    
    $stmt = $pdo->prepare("SELECT stock FROM products WHERE product_id = :product_id");
    $stmt->bindValue(":product_id", $product_id, PDO::PARAM_INT);
    $stmt->execute();
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$product) {
        throw new Exception("Product not found.");
    }

    if ($product['stock'] <= 0) {
        throw new Exception("Sorry, this product is out of stock.");
    }

    $stmt = $pdo->prepare("SELECT quantity FROM basket WHERE user_id = :user_id AND product_id = :product_id");
    $stmt->execute(["user_id" => $user_id, "product_id" => $product_id]);
    $existing = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($existing) {
        
        $new_quantity = $existing['quantity'] + 1;
        if ($new_quantity > $product['stock']) {
            throw new Exception("Cannot add more than available stock.");
        }

        $stmt = $pdo->prepare("UPDATE basket SET quantity = :quantity WHERE user_id = :user_id AND product_id = :product_id");
        $stmt->execute(["quantity" => $new_quantity, "user_id" => $user_id, "product_id" => $product_id]);
    } else {
       
        $stmt = $pdo->prepare(
            "INSERT INTO basket (user_id, product_id, quantity) 
            VALUES (:user_id, :product_id, :quantity)"
        );

        $stmt->bindValue(":user_id", $user_id, PDO::PARAM_INT);
        $stmt->bindValue(":product_id", $product_id, PDO::PARAM_INT);
        $stmt->bindValue(":quantity", $quantity, PDO::PARAM_INT);
        $stmt->execute();
    }

   
    header("Location: ./basket.php?message=Product added successfully");
    exit();

} catch (PDOException $e) {
    header("Location: ./products.php?error=" . urlencode("Database error: " . $e->getMessage()));
    exit();
} catch (Exception $e) {
    header("Location: ./products.php?error=" . urlencode($e->getMessage()));
    exit();
}

?>
