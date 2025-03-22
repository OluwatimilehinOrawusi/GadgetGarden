<?php 

// Start the session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Database connection
$pdo = require_once "../database/database.php";

// Initialize success message variable
$successMessage = "";

try {
    // Check if user_id is set in the session
    if (!isset($_SESSION['user_id'])) {
        throw new Exception("User ID is not set in the session.");
    }
    
    // Validate and sanitize GET parameters
    if (!isset($_GET['product_id']) || !is_numeric($_GET['product_id'])) {
        throw new Exception("Invalid product ID.");
    }
    
    $user_id = $_SESSION['user_id'];
    $product_id = intval($_GET['product_id']);
    $quantity = 1; // Default quantity for the product

    // Check if the product is already in the basket
    $checkStatement = $pdo->prepare(
        'SELECT quantity FROM basket WHERE user_id = :user_id AND product_id = :product_id'
    );
    $checkStatement->bindValue(":user_id", $user_id, PDO::PARAM_INT);
    $checkStatement->bindValue(":product_id", $product_id, PDO::PARAM_INT);
    $checkStatement->execute();

    if ($checkStatement->rowCount() > 0) {
        // Product exists, update quantity
        $updateStatement = $pdo->prepare(
            'UPDATE basket SET quantity = quantity + :quantity WHERE user_id = :user_id AND product_id = :product_id'
        );
        $updateStatement->bindValue(":quantity", $quantity, PDO::PARAM_INT);
        $updateStatement->bindValue(":user_id", $user_id, PDO::PARAM_INT);
        $updateStatement->bindValue(":product_id", $product_id, PDO::PARAM_INT);
        $updateStatement->execute();
        $successMessage = "✅ Quantity updated in your basket!";
    } else {
        // Product does not exist, insert new row
        $insertStatement = $pdo->prepare(
            'INSERT INTO basket (user_id, product_id, quantity) 
             VALUES (:user_id, :product_id, :quantity)'
        );
        $insertStatement->bindValue(":user_id", $user_id, PDO::PARAM_INT);
        $insertStatement->bindValue(":product_id", $product_id, PDO::PARAM_INT);
        $insertStatement->bindValue(":quantity", $quantity, PDO::PARAM_INT);
        $insertStatement->execute();
        $successMessage = "✅ Product added to your basket!";
    }

} catch (PDOException $e) {
    $successMessage = "❌ Database error: " . htmlspecialchars($e->getMessage());
} catch (Exception $e) {
    $successMessage = "❌ Error: " . htmlspecialchars($e->getMessage());
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Basket Update</title>
    <link rel="stylesheet" href="../public/css/addproducts.css">

    <script> 
        setTimeout(function() {
            window.location.href = "./products.php";
        }, 10000);
    </script>
</head>
<body>
    <div class="container fade">
        <h2><?= htmlspecialchars($successMessage); ?></h2>
        <a href="./basket.php" class="button">Proceed to Checkout</a>
        <a href="./products.php" class="button">Continue Shopping</a>
        <p>You will be redirected back in 10 seconds...</p>
    </div>
</body>
</html>
