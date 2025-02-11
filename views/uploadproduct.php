<?php
session_start();
require_once "../database/database.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}


?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Upload</title>
</head>
<body>
    <h1>Upload your own product here</h1>

    <form method="post" action="" id = "newproductform">
    <p>Product Name: <input type="text" id="product_name" name="product_name" required /></p>
    <p>Price: <input type="number" id="price_stock" name="price_stock" required /></p>
    <p>Quantity (Stock) : <input type="number" id="quantity_product" name="quantity_product" required /></p>
    <p>State: 
        <select id="state" name="state" required>
            <option value="development">Like New</option>
            <option value="testing">Very Good</option>
            <option value="deployment">Good</option>
            <option value="complete">Poor</option>
        </select>
    </p>
    <p>Description: <textarea rows="3" cols="40" id="description" name="description" required></textarea></p>
    
    <input type="submit" name="submitbutton" id="submitbutton" value="Create New Project" /><br>
    <input type="hidden" name="submitted" value="true" />


    
</body>
</html>