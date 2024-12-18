<!DOCTYPE html>
<html>
    <head>
        <?php require_once "../partials/header.php"; ?>
         <link rel="stylesheet" href="../public/css/navbar.css">
        <link rel="stylesheet" href="../public/css/styles.css"> 
        <link rel="stylesheet" href="../public/css/checkout.css">
    </head>
    <body>
    <nav>
            <div class="nav-left">
                <a href="../index.php"><p id="logo-text">GADGET GARDEN</p></a>
            </div>
            <div class="nav-right">
                <a href="../views/contact.php"><button class="green-button" >Contact Us</button></a>
                <a href="#categories"><button class="white-button">About Us</button></a>
                <?php if (!isset($_SESSION['user_id'])){?>
                <?php echo '<a href="./login.php"><button class="green-button">Login</button></a>' ?>
                 <?php echo '<a href="./signup.php"><button class="white-button">Sign Up</button></a> '?>
                <?php }?>
                <a href="../views/contact.php"><button class="green-button" >Products</button></a>
                <?php if (isset($_SESSION['user_id'])){?>
                <?php echo '<a href="./basket.php"><button class="white-button">Basket</button></a>' ?>
                <?php echo '<a href="./logout.php"><button class="green-button">Logout</button></a>' ?>

                <?php }?>

            </div>
</nav>
        <div id="checkout-container">
            <div id="checkout-flex">
                <h1>Checkout</h1>
                <p>
                    TOTAL: £<?php echo isset($_POST['total']) ? htmlspecialchars($_POST['total']) : "0.00"; ?>
                </p>
                <form action="./order.php" method="POST">
                    <input type="hidden" name="total" value="<?php echo isset($_POST['total']) ? htmlspecialchars($_POST['total']) : '0.00'; ?>">
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

                    <div class="form-group">
                        <label for="name">Full Name:</label>
                        <input type="text" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="address">Shipping Address:</label>
                        <textarea id="address" name="address" rows="4" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="payment">Payment Method:</label>
                        <select id="payment" name="payment_method" required>
                            <option value="credit_card">Credit Card</option>
                            <option value="paypal">PayPal</option>
                            <option value="bank_transfer">Bank Transfer</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="card_number">Card Number:</label>
                        <input type="text" id="card_number" name="card_number" placeholder="Enter card number" required>
                    </div>
                    <div class="form-group">
                        <label for="expiry_date">Expiry Date:</label>
                        <input type="text" id="expiry_date" name="expiry_date" placeholder="MM/YY" required>
                    </div>
                    <div class="form-group">
                        <label for="cvv">CVV:</label>
                        <input type="text" id="cvv" name="cvv" placeholder="Enter CVV" required>
                    </div>
                    <button id="checkout-button" type="submit">Complete Order</button>
                </form>
            </div>
        </div>

        <?php require_once "../partials/footer.php"; ?>
    </body>
</html>
