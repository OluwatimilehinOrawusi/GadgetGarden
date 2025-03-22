<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?error=Please+log+in+to+checkout");
    exit();
}

require_once '../database/db_connection.php';

$user = null;
if (isset($_SESSION['user_id'])) {
    $stmt = $pdo->prepare("SELECT role FROM users WHERE user_id = :user_id");
    $stmt->execute(['user_id' => $_SESSION['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
}

$email = $_SESSION['email'] ?? '';
$full_name = $_SESSION['username'] ?? '';
$total_price = isset($_POST['total']) ? floatval($_POST['total']) : 0.00;
$user_id = $_SESSION['user_id'];
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

<nav>
    <div class="nav-left">
        <p id="logo-text">GADGET GARDEN</p>
    </div>
    <div class="nav-right">
        <a href="./aboutpage.php"><button class="white-button">About Us</button></a>

        <?php if (!isset($_SESSION['user_id'])) { ?>
            <a href="./login.php"><button class="green-button">Login</button></a>
            <a href="./signup.php"><button class="white-button">Sign Up</button></a>
        <?php } ?>

        <?php if (isset($_SESSION['user_id'])) { ?>
            <a href="./basket.php"><button class="green-button">Basket</button></a>
            <a href="./contact.php"><button class="green-button">Contact Us</button></a>
            <a href="./profile.php"><button class="white-button">Profile</button></a>
            <a href="./products.php"><button class="green-button">Products</button></a>

            <?php if ($user && ($user['role'] === 'admin' || $user['role'] === 'manager')){ ?>
                <a href="./dashboard.php"><button class="white-button">Admin Dashboard</button></a>
            <?php } ?>

            <a href="./logout.php"><button class="green-button">Logout</button></a>
        
        <?php } ?>
    </div>
</nav>

<div id="checkout-container">
    <div id="checkout-flex">
        <h1>Checkout</h1>
        <p><strong>TOTAL: £<?php echo number_format($total_price, 2); ?></strong></p>

        <form action="./order.php" method="POST" onsubmit="return validateExpiryDate()">
            <input type="hidden" name="total" value="<?php echo number_format($total_price, 2); ?>">
            <div class="form-group">
                <label for="name">Full Name:</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($full_name); ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
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

function validateExpiryDate() {
    let expiry = document.getElementById("expiry_date").value;
    let parts = expiry.split("/");
    if (parts.length !== 2) {
        alert("Invalid expiry date format. Use MM/YY.");
        return false;
    }
    let month = parseInt(parts[0], 10);
    let year = parseInt(parts[1], 10) + 2000;
    let currentDate = new Date();
    let currentYear = currentDate.getFullYear();
    let currentMonth = currentDate.getMonth() + 1;
    if (year < currentYear || (year === currentYear && month < currentMonth)) {
        alert("Card is expired. Please use a valid card.");
        return false;
    }
    return true;
}
</script>

<?php require_once "../partials/footer.php"; ?>
</body>
</html>
