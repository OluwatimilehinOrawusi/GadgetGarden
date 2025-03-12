<?php
// Start session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('You must log in to upload a product to Gadget Garden.');</script>";
    header("Location: login.php");
    exit();
}

//Connects to the Gadget Gardern database
require_once('../database/database.php');

//Code to run once the submit button is pressed
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submitbutton'])) {

  
    // Form information(to be stored in variables for database)
    $product_name = $_POST['product_name'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity_product'];
    $condition = $_POST['state'];
    $description = $_POST['description'];
    $user_id = $_SESSION['user_id'];
    $category = $_POST['category'];

    //Assign the categoryID
    If($category == "laptops"){
        $category_id = 1;
    } else if($category == "audio"){
        $category_id = 2;
    }else if($category == "phones"){
        $category_id = 3;        
    }else if($category == "gaming"){
        $category_id = 4;
    }else if($category == "wearables"){
        $category_id = 5;
    }else if($category == "tablets"){
        $category_id = 6;
    }else if($category == "accessories"){
        $category_id = 7;
    }else if($category == "computers"){
        $category_id = 8;
    }else{
        $category_id = null;
    }


    // Get the next product ID (one higher than the max from both tables)
    $stmt = $pdo->query("
        SELECT MAX(product_id) AS max_id FROM (
            SELECT product_id FROM products 
            UNION ALL 
            SELECT product_id FROM upload_products
        ) AS all_products
    ");
    $max_id = $stmt->fetch(PDO::FETCH_ASSOC)['max_id'];
    $new_product_id = $max_id ? $max_id + 1 : 1; // Default to 1 if no products exist

    // File handling
    $target_dir = "../Uploads/ProductImageUploads/";//to be changed if the file structure of the website changes
    $file_name = basename($_FILES["image"]["name"]);
    $target_file = $target_dir . time() . "_" . $file_name;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Allowed file types
    $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
    if (!in_array($imageFileType, $allowed_types)) {
        echo "<script>alert('Invalid file type. Only JPG, JPEG, PNG & GIF files are allowed.');</script>";
        exit();
    }

    // Move uploaded file
    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        try {
            // Insert into `upload_products`
            $stmt = $pdo->prepare("
                INSERT INTO upload_products 
                (user_id, product_id, Admin_approve, name, price, quantity,category_id, `condition`, description, image_path)  
                VALUES (:user_id, :product_id, 0, :product_name, :price, :quantity, :category_id, :condition, :description, :target_file)
            ");

            // Bind parameters
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->bindParam(':product_id', $new_product_id, PDO::PARAM_INT);
            $stmt->bindParam(':product_name', $product_name, PDO::PARAM_STR);
            $stmt->bindParam(':price', $price, PDO::PARAM_STR);
            $stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);
            $stmt->bindParam(':category_id', $category_id, PDO::PARAM_INT);
            $stmt->bindParam(':condition', $condition, PDO::PARAM_STR);
            $stmt->bindParam(':description', $description, PDO::PARAM_STR);
            $stmt->bindParam(':target_file', $target_file, PDO::PARAM_STR);

            //Completion Alert
            if ($stmt->execute()) {
                echo "<script>alert('Product uploaded successfully and is pending admin approval!'); window.location.href = '../index.php';</script>";
            } else {
                echo "<script>alert('Error uploading product. Please try again.');</script>";
            }
        } catch (PDOException $e) {
            echo "<script>alert('Database error: " . addslashes($e->getMessage()) . "');</script>";
        }
    } else {
        echo "<script>alert('Error uploading image. Please try again.');</script>";
    }
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
        <label for="price">Price</label>
        <input type="number" id="price" name="price" required />
        

        <!-------Product Quantity------>
        <label for="quantity_product">Product Quantity</label>
        <input type="number" id="quantity_product" name="quantity_product" required />

        <!-------Product Category------>
        <label for="category">Category</label>
        <select id = "category" name = "category" reqired>
            <option value = "gaming">Gaming</option>
            <option value = "phones">Phones</option>
            <option value = "wearables">Wearables</option>
            <option value = "laptops">Laptops</option>
            <option value = "tablets">Tablets</option>
            <option value = "accessories">Accessories</option>
            <option value = "computers">Computers</option>
            <option value = "audio">Audio</option>
        </select>
        

        <!-------Product condition------>
        <label for="state">Condition</label>
            <select id="state" name="state" required>
                <option value="Like New">Like New</option>
                <option value="Very Good">Very Good</option>
                <option value="Good">Good</option>
                <option value="Poor">Poor</option>
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
