<?php  
// Start session and enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// Connect to the database
$pdo = require_once "../database/database.php";

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $name = $_POST['name'];
    $address = $_POST['address'];
    $email = $_POST['email'];
    $payment_method = $_POST['payment_method'];
    $card_number = $_POST['card_number'];
    $total = floatval(str_replace(',', '', $_POST['total']));

    try {
        // Insert into orders table
        $statement = $pdo->prepare('INSERT INTO orders (user_id, total_price) VALUES (:user_id, :total)');
        $statement->bindValue(":user_id", $user_id);
        $statement->bindValue(":total", $total);
        $statement->execute();

        $order_id = $pdo->lastInsertId();

        // Get basket items
        $stmt = $pdo->prepare('SELECT product_id, quantity FROM basket WHERE user_id = :user_id AND product_id IS NOT NULL');
        $stmt->bindValue(":user_id", $user_id);
        $stmt->execute();
        $basket_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Insert basket items into order_products
        if ($basket_items) {
            foreach ($basket_items as $item) {
                // Validate product_id and quantity
                if (!isset($item['product_id']) || !is_numeric($item['product_id']) || !isset($item['quantity']) || !is_numeric($item['quantity'])) {
                    echo "❌ Invalid basket item: ";
                    var_dump($item);
                    exit;
                }

                // Insert into order_products
                $statement = $pdo->prepare(
                    'INSERT INTO order_products (order_id, product_id, quantity) 
                     VALUES (:order_id, :product_id, :quantity)'
                );
                $statement->bindValue(":order_id", $order_id, PDO::PARAM_INT);
                $statement->bindValue(":product_id", $item['product_id'], PDO::PARAM_INT);
                $statement->bindValue(":quantity", $item['quantity'], PDO::PARAM_INT);
                $statement->execute();

                // Update stock
                $updateStock = $pdo->prepare(
                    'UPDATE products SET stock = stock - :quantity WHERE product_id = :product_id'
                );
                $updateStock->bindValue(":quantity", $item['quantity'], PDO::PARAM_INT);
                $updateStock->bindValue(":product_id", $item['product_id'], PDO::PARAM_INT);
                $updateStock->execute();
            }
        }

        // Clear basket
        $clearBasket = $pdo->prepare('DELETE FROM basket WHERE user_id = :user_id');
        $clearBasket->bindValue(":user_id", $user_id);
        $clearBasket->execute();

        echo "<script>
            alert('Your order has been placed successfully! Thank you for shopping at Gadget Garden.');
            window.location.href = '../index.php';
        </script>";
        exit();

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        exit;
    }
}
?>
