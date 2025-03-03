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

    <!-------Styles sheets------>
    <link rel="stylesheet" href="../public/css/navbar.css">
    <link rel="stylesheet" href="../public/css/styles.css">
    <link rel="stylesheet" href="../public/css/uploadproduct.css">
</head>

<body>

<nav>
    <div class="nav-left">
        <a href="../index.php"><p id="logo-text">GADGET GARDEN</p></a>
    </div>
    <div class="nav-right">
        <a href="../views/aboutpage.php"><button class="white-button">About Us</button></a>
        <?php if (!isset($_SESSION['user_id'])) { ?>
            <a href="./login.php"><button class="green-button">Login</button></a>
            <a href="./signup.php"><button class="white-button">Sign Up</button></a>
        <?php } else { ?>
            <a href="./basket.php"><button class="white-button">Basket</button></a>
            <a href="./contact.php"><button class="white-button">Contact us</button></a>
            <a href="./profile.php"><button class="white-button">Profile</button></a>
            <a href="./logout.php"><button class="green-button">Logout</button></a>
        <?php } ?>
    </div>
</nav>
    <div id = "upload-page">
<div id="upload-container">
    <h1>Upload your own product here</h1>

    <form action="upload.php" method="POST" enctype="multipart/form-data" id = "uploadform">
        
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
</div>
        </div>
    
</body>
</html>
