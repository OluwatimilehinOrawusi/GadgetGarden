<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$pdo = require_once "../database/database.php";

// Initialize success message variable (correct spelling)
$successMessage = "";

try {
    if (!isset($_SESSION['user_id'])) {
        throw new Exception("User ID is not set in session.");
    }

    if (!isset($_GET['product_id']) || !is_numeric($_GET['product_id'])) {
        throw new Exception("Invalid product ID");
    }

    $user_id = $_SESSION['user_id'];
    $product_id = intval($_GET['product_id']);

    // Use INSERT IGNORE to prevent duplicate wishlist entries
    $statement = $pdo->prepare(
        'INSERT IGNORE INTO wishlist (user_id, product_id) VALUES (:user_id, :product_id)'
    );

    $statement->bindValue(":user_id", $user_id, PDO::PARAM_INT);
    $statement->bindValue(":product_id", $product_id, PDO::PARAM_INT);
    $statement->execute();

    if ($statement->rowCount() > 0) {
        $successMessage = "✅ Product has been added to your wishlist!";
    } else {
        throw new Exception("⚠️ This product is already in your wishlist.");
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
    <title>Wishlist Update</title>
    <link rel="stylesheet" href="../public/css/bookmark.css">

    <script> 
        setTimeout(function() {
            window.location.href = "./products.php";
        }, 10000);
    </script>
</head>
<body>
    <div class="container fade">
        <h2><?= htmlspecialchars($successMessage); ?></h2>
        <a href="./wishlist.php" class="button">View Wishlist</a>
        <a href="./products.php" class="button">Continue Shopping</a>
        <p>You will be redirected in 10 seconds...</p>
    </div>
</body>
</html>
