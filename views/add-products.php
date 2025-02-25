<?php 

// Start the session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Database connection
$pdo = require_once "../database/database.php";

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

    // Prepare SQL statement to insert a new item into the basket
    $statement = $pdo->prepare(
        'INSERT INTO basket (user_id, product_id, quantity) 
         VALUES (:user_id, :product_id, :quantity)'
    );

    // Bind values to the prepared statement
    $statement->bindValue(":user_id", $user_id, PDO::PARAM_INT);
    $statement->bindValue(":product_id", $product_id, PDO::PARAM_INT);
    $statement->bindValue(":quantity", $quantity, PDO::PARAM_INT);

    // Execute the statement
    $statement->execute();

    // Check if the query was successful
    if ($statement->rowCount() > 0) {
        echo "Product added to your basket successfully!<br><br>";
        echo "<a href='./basket.php'>Proceed to Checkout</a> | ";
        echo "<a href='./products.php'>Continue Shopping</a>";
    } else {
        throw new Exception("Failed to add the product to the basket.");
    }
} catch (PDOException $e) {
    // Catch PDO exceptions (database related)
    echo "Database error: " . htmlspecialchars($e->getMessage());
} catch (Exception $e) {
    // Catch general exceptions (such as logic or input errors)
    echo "Error: " . htmlspecialchars($e->getMessage());
}

?>
