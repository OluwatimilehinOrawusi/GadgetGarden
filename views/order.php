<?php  
//Starts session


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


session_start();

//connects to the database
$pdo = require_once "../database/db_connection.php";

//Form info saved to variables
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $name = $_POST['name'];
    $address = $_POST['address'];
    $email = $_POST['email'];
    $payment_method = $_POST['payment_method'];
    $card_number = $_POST['card_number'];
    $total = floatval(str_replace(',', '', $_POST['total']));


    try {
        //Inserting the user's ID and the price into the order table
        $statement = $pdo->prepare('INSERT INTO orders (user_id, total_price) VALUES (:user_id, :total)');
        $statement->bindValue(":user_id", $user_id);
        $statement->bindValue(":total", $total);
        $statement->execute();
        $order_id = $pdo->lastInsertId();

        $stmt = $pdo->prepare('SELECT product_id, quantity FROM basket WHERE user_id = :user_id');
        $stmt->bindValue(":user_id", $user_id);
        $stmt->execute();
        $basket_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($basket_items) {
            foreach ($basket_items as $item) {
                $statement = $pdo->prepare('INSERT INTO order_products (order_id, product_id, quantity) VALUES (:order_id, :product_id, :quantity)');
                $statement->bindValue(":order_id", $order_id);
                $statement->bindValue(":product_id", $item['product_id']);
                $statement->bindValue(":quantity", $item['quantity']);
                $statement->execute();

                $updateStock = $pdo->prepare('UPDATE products SET stock = stock - :quantity WHERE product_id = :product_id');
                $updateStock->bindValue(":quantity", $item['quantity']);
                $updateStock->bindValue(":product_id", $item['product_id']);
                $updateStock->execute();
            }
        }

        $clearBasket = $pdo->prepare('DELETE FROM basket WHERE user_id = :user_id');
        $clearBasket->bindValue(":user_id", $user_id);
        $clearBasket->execute();

        echo "<script>
            alert('Your order has been placed successfully! Thank you for shopping at Gadget Garden.');
            window.location.href = '../index.php';
        </script>";
        exit();

    } catch (PDOException $e) {
        echo "<script>alert('Your order wasn't successful. Please try again.');</script>";
    }
}
?>
