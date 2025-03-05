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

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submitbutton'])) {

    //Form information entered into variables
    $product_name = $_POST['product_name'];
    $price = $_POST['price_stock'];
    $quantity = $_POST['quantity_product'];
    $condition = $_POST['state'];
    $description = $_POST['description'];
    $user_id = $_SESSION['user_id'];


    //File handling
    $target_dir = //to be added"";
    $file_name = basename($_FILES["image"]["name"]);
    $target_file = $target_dir . time() . "_" . $file_name;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    
    //Lock to image filetypes
    $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
    if (!in_array($imageFileType, $allowed_types)) {
        echo "<script>alert('Invalid file type. Only JPG, JPEG, PNG & GIF files are allowed.');</script>";
        exit();
    }

    // Move uploaded file
    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        // Insert product into database
        $sql = "INSERT INTO products (user_id, name, price, quantity, condition, description, image_path) VALUES (?, ?, ?, ?, ?, ?, ?)";
        
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("isdisss", $user_id, $product_name, $price, $quantity, $condition, $description, $target_file);
            
            if ($stmt->execute()) {
                echo "<script>alert('Product uploaded successfully!'); window.location.href = 'product_list.php';</script>";
            } else {
                echo "<script>alert('Error uploading product. Please try again.');</script>";
            }
            
            $stmt->close();
        }
    } else {
        echo "<script>alert('Error uploading image. Please try again.');</script>";
    }
    
    $conn->close();
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
    <h1 id = "titlestatement">Upload your own product here</h1>

    <form action="uploadProduct.php" method="POST" enctype="multipart/form-data" id = "uploadform">
        
        <!-------Product name------>
        <label for="product_name">Product Name</label>
        <input type="text" id="product_name" name="product_name" required />
        

        <!-------Price------>
        <label for="price_stock">Price</label>
        <input type="number" id="price_stock" name="price_stock" required />
        

        <!-------Product Quantity------>
        <label for="quantity_product">Product Quantity</label>
        <input type="number" id="quantity_product" name="quantity_product" required />
        

        <!-------Product condition------>
        <label for="state">Condition</label>
            <select id="state" name="state" required>
                <option value="likeNew">Like New</option>
                <option value="veryGood">Very Good</option>
                <option value="good">Good</option>
                <option value="poor">Poor</option>
            </select>
        


        <!-------Product description------>
        <label for="description">Description</label>
        <textarea rows="3" cols="40" id="description" name="description" required></textarea>

        <!-------File upload------>
        <div id = "fileupload-container">
        <label for="image">Upload Image</label>
        <input type="file" name="image" accept="image/*" required>
        </div>


        <!-------Upload button------>
        <input type="submit" name="submitbutton" value = "Upload Product" >

        <input type="hidden" name="submitted" value="true" />
    </form>
</div>
        </div>
    
       <?php require_once '../partials/footer.php'; ?>
</body>
</html>
