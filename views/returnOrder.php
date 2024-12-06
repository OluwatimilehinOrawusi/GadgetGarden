<?php
session_start();

// Require the database connection
$pdo = require_once "../database/database.php";

// Handle return form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_return'])) {
    $order_id = htmlspecialchars(trim($_POST['order_id']));
    $reason = htmlspecialchars(trim($_POST['reason']));
    $details = htmlspecialchars(trim($_POST['details']));
    $user_id = $_SESSION['user_id'] ?? null;

    // Validate form inputs
    if ($user_id && $order_id && $reason) {
        $stmt = $pdo->prepare("INSERT INTO returns (user_id, order_id, reason, details) VALUES (:user_id, :order_id, :reason, :details)");
        $stmt->execute([
            ':user_id' => $user_id,
            ':order_id' => $order_id,
            ':reason' => $reason,
            ':details' => $details
        ]);
        $success_message = "Your return request has been submitted.";
    } else {
        $error_message = "Please fill in all required fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Return Order - Gadget Garden</title>
        <?php require_once "../partials/header.php"; ?>
        <link rel="stylesheet" href="../public/css/returnOrder.css">
        <link rel="stylesheet" href="../public/css/navbar.css">
        <link rel="stylesheet" href="../public/css/styles.css">
    </head>
    <body>

    <nav>
            <div class="nav-left">
                <a href="../index.php"><p id="logo-text">GADGET GARDEN</p></a>
            </div>
            <div class="nav-right">
                <a href="./contact.php"><button class="green-button" >Contact Us</button></a>
                <a href="./aboutpage.php"><button class="white-button">About Us</button></a>
                <?php if (!isset($_SESSION['user_id'])){?>
                <?php echo '<a href="./login.php"><button class="green-button">Login</button></a>' ?>
                 <?php echo '<a href="./signup.php"><button class="white-button">Sign Up</button></a> '?>
                <?php }?>
                <a href="./products.php"><button class="green-button" >Products</button></a>
                <?php if (isset($_SESSION['user_id'])){?>
                <?php echo '<a href="./basket.php"><button class="white-button">Basket</button></a>' ?>
                <?php echo '<a href="./logout.php"><button class="green-button">Logout</button></a>' ?>

                <?php }?>

            </div>
</nav>
        

        <main class="container">
            <h1>Return an Order</h1>
            <?php if (!empty($success_message)): ?>
                <p class="success"><?= $success_message; ?></p>
            <?php elseif (!empty($error_message)): ?>
                <p class="error"><?= $error_message; ?></p>
            <?php endif; ?>

            <form action="" method="POST" class="return-form">
                <div class="form-group">
                    <label for="order_id">Order ID:</label>
                    <input type="text" id="order_id" name="order_id" required>
                </div>
                <div class="form-group">
                    <label for="reason">Reason for Return:</label>
                    <select id="reason" name="reason" required>
                        <option value="">Select a reason</option>
                        <option value="Damaged item">Damaged item</option>
                        <option value="Wrong item sent">Wrong item sent</option>
                        <option value="Not satisfied">Not satisfied</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="details">Additional Details:</label>
                    <textarea id="details" name="details" rows="5"></textarea>
                </div>
                <button type="submit" name="submit_return">Submit Return</button>
            </form>
        </main>

    <!-----Links the footer partial to the page----->
        <?php require_once "../partials/footer.php"; ?>
    </body>
</html>
