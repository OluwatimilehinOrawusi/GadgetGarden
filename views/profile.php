<?php     
session_start();
require_once ("../database/database.php");

if (!isset($_SESSION['username'])) {
    header("Location: login.php?error=Please+log+in");
    exit();
}

$user_id = $_SESSION['user_id'] ?? null;
$username = $_SESSION['username'] ?? 'Unknown User';
$email = $_SESSION['email'] ?? null;

// Fix missing email after login
if (!$email && $user_id) {
    $stmt = $pdo->prepare("SELECT email FROM users WHERE user_id = :user_id");
    $stmt->bindParam(":user_id", $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $email = $user["email"];
        $_SESSION["email"] = $email;
    }
}

$email = $email ?? 'Email not available';

// Fetch User Orders 
$orderQuery = $pdo->prepare("
    SELECT o.order_id, o.order_date, o.total_price, o.order_status, 
           op.product_id, op.quantity, p.name AS product_name, 
           p.price AS product_price, p.image 
    FROM orders o 
    LEFT JOIN order_products op ON o.order_id = op.order_id 
    LEFT JOIN products p ON op.product_id = p.product_id 
    WHERE o.user_id = :user_id
    ORDER BY o.order_id DESC
");
$orderQuery->bindValue(":user_id", $user_id, PDO::PARAM_INT);
$orderQuery->execute();
$orders = $orderQuery->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gadget Garden - Profile</title>
    <?php require '../partials/header.php'; ?>
    <link rel="stylesheet" href="../public/css/profile.css">
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

<div id="wholepage">
    <header class="header">
        <div class="header-content">
            <h1>My Profile</h1>
        </div>
    </header>

    <main class="main-content">
        <h2>Welcome <?php echo htmlspecialchars($username); ?>!</h2>

        <section class="info-section">
            <div class="info-card">
                <div class="info-header">
                    <h3>Personal Info</h3>
                </div>
                <div class="info-content">
                    <p><b>Username:</b> <?php echo htmlspecialchars($username); ?></p>
                </div>
            </div>

            <div class="info-card">
                <div class="info-header">
                    <h3>Email Address</h3>
                </div>
                <div class="info-content">
                    <p><b>Email:</b> <?php echo htmlspecialchars($email); ?></p>
                    <p><b>Account ID:</b> <?php echo htmlspecialchars($user_id); ?></p>
                </div>
            </div>
        </section>

        <!-- My Orders Section -->
        <section class="info-section">
            <div class="info-card">
                <div class="info-header">
                    <h3>My Orders</h3>
                </div>

                <?php if (empty($orders)) : ?>
                    <p>You have no orders yet.</p>
                <?php else : ?>
                    <?php 
                    $previousOrderId = null;
                    foreach ($orders as $order) { 
                        if ($previousOrderId !== $order["order_id"]) { 
                            if ($previousOrderId !== null) {
                                echo "</div>";
                            }

                            // Order status logic
                            $status = ucfirst($order["order_status"] ?? 'Paid');
                            $statusClass = strtolower(str_replace(" ", "-", $status));
                            ?>
                            <div class="order-container">
                                <h3>Order ID: <?php echo htmlspecialchars($order["order_id"]); ?></h3>
                                <p><b>Order Date:</b> <?php echo htmlspecialchars($order["order_date"]); ?></p>
                                <p><b>Order Total:</b> £<?php echo htmlspecialchars($order["total_price"]); ?></p>
                                <p><b>Payment Method:</b> Card</p>
                                <p class="order-status <?php echo htmlspecialchars($statusClass); ?>">
                                    <?php echo htmlspecialchars($status); ?>
                                </p>
                            <?php
                        }

                        if (!empty($order['product_name'])) { 
                            $productImage = !empty($order['image']) ? "../uploads/" . htmlspecialchars($order['image']) : "../public/assets/placeholder.png";
                            ?>
                            <div class="order-details">
                                <img src="<?php echo $productImage; ?>" alt="Product Image" class="order-image" 
                                    onerror="this.src='../public/assets/placeholder.png'">
                                <div class="info-content">
                                    <p><b>Product:</b> <?php echo htmlspecialchars($order["product_name"]); ?></p>
                                    <p><b>Price:</b> £<?php echo htmlspecialchars($order["product_price"]); ?></p>
                                    <p><b>Quantity:</b> <?php echo htmlspecialchars($order["quantity"]); ?></p>
                                </div>
                            </div>
                        <?php } ?>

                        <?php 
                        $previousOrderId = $order["order_id"];
                    }
                    echo "</div>";
                    ?>
                <?php endif; ?>
            </div>
        </section>
    </main>
</div>

<?php require '../partials/footer.php'; ?>
</body>
</html>
