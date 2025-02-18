<?php
// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Redirect users to login if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?error=Please+log+in+to+checkout");
    exit();
}

// Fetch user details from session
$email = isset($_SESSION['email']) ? htmlspecialchars($_SESSION['email']) : '';
$full_name = isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : '';

// Ensure total price is retrieved securely
$total_price = isset($_POST['total']) ? floatval($_POST['total']) : 0.00;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Gadget Garden</title>
    <?php require_once "../partials/header.php"; ?>
    <link rel="stylesheet" href="../public/css/navbar.css">
    <link rel="stylesheet" href="../public/css/styles.css">
    <link rel="stylesheet" href="../public/css/checkout.css">
</head>
<body>

<!-- 🟢 Navigation Bar -->
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
            <a href="./contact.php"><button class="white-button">Contact Us</button></a>
            <a href="./profile.php"><button class="white-button">Profile</button></a>
            <a href="./logout.php"><button class="green-button">Logout</button></a>
        <?php } ?>
    </div>
</nav>

<!-- 🛒 Checkout Section -->
<div id="checkout-container">
    <div id="checkout-flex">
        <h1>Checkout</h1>
        <p><strong>TOTAL: £<?php echo number_format($total_price, 2); ?></strong></p>

        <form action="./order.php" method="POST" onsubmit="return validatePayment()">
            <input type="hidden" name="total" value="<?php echo number_format($total_price, 2); ?>">

            <div class="form-group">
                <label for="name">Full Name:</label>
                <input type="text" id="name" name="name" value="<?php echo $full_name; ?>" required>
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo $email; ?>" required>
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
                <label for="cardholder">Cardholder Name:</label>
                <input type="text" id="cardholder" name="cardholder" placeholder="Name on Card" required>
            </div>

            <div class="form-group">
                <label for="card_number">Card Number:</label>
                <input type="text" id="card_number" name="card_number" placeholder="Enter 16-digit card number" maxlength="16" pattern="\d{16}" required oninput="this.value = this.value.replace(/\D/g, '')">
            </div>

            <div class="form-group">
                <label for="expiry_date">Expiry Date:</label>
                <input type="text" id="expiry_date" name="expiry_date" placeholder="MM/YY" maxlength="5" required oninput="formatExpiryDate(this)">
            </div>

            <div class="form-group">
                <label for="cvv">CVV:</label>
                <input type="text" id="cvv" name="cvv" placeholder="Enter 3-digit CVV" maxlength="3" pattern="\d{3}" required oninput="this.value = this.value.replace(/\D/g, '')">
            </div>

            <button id="checkout-button" type="submit">Complete Order</button>
        </form>
    </div>
</div>

<script>
function formatExpiryDate(input) {
    let value = input.value.replace(/\D/g, '');
    if (value.length >= 2) {
        value = value.substring(0, 2) + '/' + value.substring(2, 4);
    }
    input.value = value;
}

function validatePayment() {
    let cardNumber = document.getElementById("card_number").value;
    let expiryDate = document.getElementById("expiry_date").value;
    let cvv = document.getElementById("cvv").value;

    let cardNumberPattern = /^\d{16}$/;
    let expiryDatePattern = /^(0[1-9]|1[0-2])\/\d{2}$/;
    let cvvPattern = /^\d{3}$/;

    if (!cardNumberPattern.test(cardNumber)) {
        alert("Invalid card number. Must be 16 digits.");
        return false;
    }
    if (!expiryDatePattern.test(expiryDate)) {
        alert("Invalid expiry date. Use MM/YY format.");
        return false;
    }
    if (!cvvPattern.test(cvv)) {
        alert("Invalid CVV. Must be 3 digits.");
        return false;
    }
    return true;
}
</script>

<?php require_once "../partials/footer.php"; ?>
</body>
</html>
