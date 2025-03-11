<?php 
//Connect to database
require_once "../database/database.php";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['product_id'], $_POST['action'])) {
    //Storing product ID into variable
    $product_id = $_POST['product_id'];
    $action = $_POST['action'];

    //Block of code to run if the approve button is pressed
    if ($action === "approve") { $stmt = $pdo->prepare("SELECT * FROM upload_products WHERE product_id = ?");
        $stmt->execute([$product_id]);
        $product=$stmt->fetch(PDO::FETCH_ASSOC);

        //Insert the uploaded product into the products table
        if ($product) { $stmt = $pdo->prepare("
        INSERT INTO products(product_id,name,description,price,stock,state,category_id,image)
        VALUES (?,?,?,?,?,?,?,?)
        ");

        //The values from the upload products table which will be inserted into the products table
        $stmt->execute([
            $product['product_id'],
            $product['name'],
            $product['description'],
            $product['price'],
            $product['quantity'],
            $product['condition'],
            $product['category_id'],
            $product['image_path'],


        ]);
        //Delete the uploaded product from the upload product table after successful insert into the products table
        $stmt = $pdo->prepare("DELETE FROM upload_products WHERE product_id = ?"); $stmt->execute([$product_id]);
    }
}
else { 
     //Delete the uploaded product from the upload product table
    $stmt = $pdo->prepare("DELETE FROM upload_products WHERE product_id = ?"); $stmt->execute([$product_id]); 
}

}
header("Location: ReviewCustomerUploads.php ");
exit();