<!DOCTYPE html>
<html>
    <head>
    <?php require_once "../partials/header.php" ?>
    
    </head>
    <body>
    <nav>
            <div class="nav-left">
                <a href="../index.php"><p id="logo-text">GADGET GARDEN</p></a>
            </div>

    <div id="checkout-container">
        <div class="checkout-flex">
        <h1>Checkout</h1>
        <p>TOTAL: £<?php echo $_POST['total']?></p>
        <form class="checkout-flex" action="./order.php" method="POST">
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

            <button type="submit">Complete Order</button>
        </form>
    </div>
                </div>
    <?php require_once "../partials/footer.php" ?>
    </body>
</html>