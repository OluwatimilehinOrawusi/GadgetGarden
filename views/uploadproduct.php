<?php
// Start session
session_start();
require_once "../database/database.php";

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('You must log in to upload a product to Gadget Garden.');</script>";
    header("Location: login.php");
    exit();
}
?>

<!-------Upload product HTML------>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Upload</title>
</head>

<body>
    <h1>Upload your own product here</h1>

    <form action="upload.php" method="POST" enctype="multipart/form-data">
        
        <!-------Product name------>
        <p>Product Name: <input type="text" id="product_name" name="product_name" required /></p>
        

        <!-------Price------>
        <p>Price: <input type="number" id="price_stock" name="price_stock" required /></p>
        

        <!-------Product Quantity------>
        <p>Quantity (Stock): <input type="number" id="quantity_product" name="quantity_product" required /></p>
        

        <!-------Product state------>
        <p>State: 
            <select id="state" name="state" required>
                <option value="likeNew">Like New</option>
                <option value="veryGood">Very Good</option>
                <option value="good">Good</option>
                <option value="poor">Poor</option>
            </select>
        </p>


        <!-------Product description------>
        <p>Description: <textarea rows="3" cols="40" id="description" name="description" required></textarea></p>


        <!-------File upload------>
        <p>Upload Image: <input type="file" name="image" accept="image/*" required></p>


        <!-------Upload button------>
        <button type="submit" name="submitbutton">Upload Product</button>

        <input type="hidden" name="submitted" value="true" />
    </form>
    
</body>
</html>
