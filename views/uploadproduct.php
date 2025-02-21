<?php
session_start();
require_once "../database/database.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_name = htmlspecialchars(trim($_POST["product_name"]));
    $price = filter_var($_POST["price_stock"], FILTER_VALIDATE_FLOAT);
    $quantity = filter_var($_POST["quantity_product"], FILTER_VALIDATE_INT);
    $state = htmlspecialchars($_POST["state"]);
    $description = htmlspecialchars(trim($_POST["description"]));
    $user_id = $_SESSION['user_id'];

    if ($price === false || $quantity === false || $price < 0 || $quantity < 0) {
        $message = "Invalid price or stock quantity.";
    } else {
        $stmt = $pdo->prepare("INSERT INTO products (name, price, stock, state, description, user_id) 
                               VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$product_name, $price, $quantity, $state, $description, $user_id]);

        if ($stmt) {
            $message = "✅ Product uploaded successfully!";
        } else {
            $message = "❌ Failed to upload product.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Product - Gadget Garden</title>
    <link rel="stylesheet" href="../public/css/navbar.css">
    <link rel="stylesheet" href="../public/css/styles.css">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 90%;
            max-width: 600px;
            margin: 40px auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        h1 {
            color: #333;
            font-size: 28px;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        input, select, textarea {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .upload-btn {
            background: #145A32;
            color: white;
            border: none;
            padding: 12px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
            transition: 0.3s;
        }

        .upload-btn:hover {
            background: #117A3D;
        }

        .message {
            font-size: 18px;
            margin-top: 10px;
            padding: 10px;
            border-radius: 5px;
        }

        .success {
            color: green;
            background: #E6F7E6;
            border: 1px solid green;
        }

        .error {
            color: red;
            background: #FEE2E2;
            border: 1px solid red;
        }
    </style>
</head>
<body>

<?php require_once "../partials/navbar.php"; ?>

<div class="container">
    <h1>Upload Your Product</h1>

    <?php if (!empty($message)): ?>
        <p class="message <?php echo (strpos($message, '✅') !== false) ? 'success' : 'error'; ?>">
            <?php echo $message; ?>
        </p>
    <?php endif; ?>

    <form method="post" action="">
        <p>Product Name: <input type="text" name="product_name" required></p>
        <p>Price (£): <input type="number" step="0.01" name="price_stock" required></p>
        <p>Quantity (Stock): <input type="number" name="quantity_product" required></p>
        <p>State: 
            <select name="state" required>
                <option value="likeNew">Like New</option>
                <option value="veryGood">Very Good</option>
                <option value="good">Good</option>
                <option value="poor">Poor</option>
            </select>
        </p>
        <p>Description: <textarea rows="3" cols="40" name="description" required></textarea></p>
        
        <button type="submit" class="upload-btn">Upload Product</button>
    </form>
</div>

</body>
</html>
