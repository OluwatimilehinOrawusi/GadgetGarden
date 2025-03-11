<?php
$pdo = require_once "../database/database.php";

if (isset($_GET['product_id'])) {

$product_id = isset($_GET['product_id']) ? $_GET['product_id'] : null;
$stmt = $pdo->prepare("SELECT * FROM products WHERE product_id = ?");
$stmt->execute([$product_id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);
$name = $product['name'];
$description = $product['description'];
$stock = $product['stock'];

}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    echo 'Hello';
   $product_id = $_POST['product_id'];
    $new_name = $_POST['name'];
$new_description = $_POST['description'];
$new_stock = $_POST['stock'];
  


    
    // Handling file upload
    if (!empty($_FILES['image']['name'])) {
        $image = $_FILES['image']['name'];
        $target = "uploads/" . basename($image);
        move_uploaded_file($_FILES['image']['tmp_name'], $target);
        
        $sql = "UPDATE products SET name = ?, description = ?, stock = ?, image = ? WHERE product_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$new_name, $new_description, $new_stock, $image, $product_id]);
    } else {
        $sql = "UPDATE products SET name = ?, description = ?, stock = ? WHERE product_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$new_name, $new_description, $new_stock, $product_id]);
    }
    
    echo "Product updated successfully!";
    // Redirect to admin.php after the product update
header("Location: admin.php");
exit;  // Always call exit() after a redirect to stop further script execution
   
}

// Fetch product details
if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];
    $stmt = $pdo->prepare("SELECT * FROM products WHERE product_id = ?");
    $stmt->execute([$product_id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Update Product</title>
    <style>
        /* General Body Styles */
body {
    font-family: Arial, sans-serif;
    background-color: #f9f9f9;
    margin: 0;
    padding: 0;
}

h2 {
    text-align: center;
    color: #333;
    margin-bottom: 20px;
}

/* Form Container */
form {
    background-color: #ffffff;
    padding: 30px;
    max-width: 600px;
    margin: 50px auto;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    border: 2px solid #275E4A; /* Green border */
}

/* Form Label and Input Styling */
label {
    font-weight: bold;
    color: #333;
    margin-top: 15px;
    display: block;
}

input[type="text"],
input[type="number"],
textarea,
input[type="file"] {
    width: 100%;
    padding: 10px;
    margin-top: 5px;
    margin-bottom: 20px;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
}

input[type="text"]:focus,
input[type="number"]:focus,
textarea:focus,
input[type="file"]:focus {
    outline: none;
    border-color: #275E4A;
}

/* Textarea Specific Styles */
textarea {
    height: 150px;
    resize: vertical;
}

/* Button Styling */
button[type="submit"] {
    background-color: #275E4A; /* Green background */
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 16px;
    width: 100%;
    margin-top: 10px;
}

button[type="submit"]:hover {
    background-color: #1f4a38; /* Darker green on hover */
}

/* Image Preview Styling */
img {
    margin-top: 10px;
    border-radius: 8px;
}

/* Responsive Design */
@media screen and (max-width: 768px) {
    form {
        width: 90%;
        padding: 20px;
    }
}

    </style>
</head>
<body>
    <h2>Update Product</h2>
    <form method="POST" action='update.php' enctype="multipart/form-data">
        <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
        <label>Product Name:</label>
        <input type="text" name="name" value="<?php echo $product['name']; ?>" required><br>
        <label>Description:</label>
        <textarea name="description" required><?php echo $product['description']; ?></textarea><br>
        <label>Stock:</label>
        <input type="number" name="stock" value="<?php echo $product['stock']; ?>" required><br>
        <label>Image:</label>
        <input type="file" name="image"><br>
        <img src="uploads/<?php echo $product['image']; ?>" width="100" height="100"><br>
        <button type="submit">Update</button>
    </form>
</body>
</html>
