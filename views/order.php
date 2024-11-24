<?php  
session_start();


   // Database connection
   $pdo = require_once "../database/database.php";

   // Check if user_id is set in the session
   if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $name = $_POST['name'];
    $address = $_POST['address'];
    $email = $_POST['email'];
    $payment_method = $_POST['payment_method'];
    $card_number = $_POST['card_number'];
    $decoded_data = urldecode($_POST['order']);
    $order = unserialize($decoded_data);
    var_dump($order);

    $total = $_POST['total'];

    try {
    // Prepare SQL statement to insert a new item into the orders
    $statement = $pdo->prepare('INSERT INTO orders (user_id, total_price) VALUES (:user_id, :total)');
 
    // Bind values to the prepared statement
    $statement->bindValue(":user_id", $user_id);
    $statement->bindValue(":total", $total);
   
 
    // Execute the statement
    $statement->execute();

    $order_id = $pdo->lastInsertId();

    
    $count = 0;

    foreach($order as $product){
      // Prepare SQL statement to insert a new item into the order::
      $statement = $pdo->prepare('INSERT INTO order_products (order_id, product_id, quantity) VALUES (:order_id, :product_id, :quantity)');
 
      // Bind values to the prepared statement
      $statement->bindValue(":order_id", $order_id);
      $statement->bindValue(":product_id", $order[$count]['product_id']);
      $statement->bindValue(":quantity",$order[$count]['quantity']);
     
   
      // Execute the statement
      $statement->execute();
      $count = $count + 1;
    }

    Header("Location: ./index.php");
}catch(PDOException $e){
    $e->getMessage();
   }

}
?>