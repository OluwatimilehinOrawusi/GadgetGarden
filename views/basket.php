<?php 


if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

$pdo = require_once "../database/database.php";

$user = null;
if (isset($_SESSION['user_id'])) {
    $stmt = $pdo->prepare("SELECT role FROM users WHERE user_id = :user_id");
    $stmt->execute(['user_id' => $_SESSION['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id']) && isset($_POST['quantity'])) {
    $id = intval($_POST['id']);
    $quantity = intval($_POST['quantity']);

    $statement = $pdo->prepare('UPDATE basket SET quantity = :quantity WHERE product_id = :id AND user_id = :user_id');
    $statement->bindValue(":id", $id, PDO::PARAM_INT);
    $statement->bindValue(":quantity", $quantity, PDO::PARAM_INT);
    $statement->bindValue(":user_id", $_SESSION['user_id'], PDO::PARAM_INT);
    $statement->execute();

    header('Location: basket.php');
    exit();
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_id'])) {
    $remove_id = intval($_POST['remove_id']);

    $stmt = $pdo->prepare("DELETE FROM basket WHERE product_id = :remove_id AND user_id = :user_id");
    $stmt->bindValue(":remove_id", $remove_id, PDO::PARAM_INT);
    $stmt->bindValue(":user_id", $_SESSION['user_id'], PDO::PARAM_INT);
    $stmt->execute();

    header('Location: basket.php');
    exit();
}


$user_id = $_SESSION['user_id'];
$statement = $pdo->prepare('
    SELECT b.basket_id, b.user_id, b.product_id, b.quantity, 
           p.name, p.price, p.description, p.image 
    FROM basket b 
    JOIN products p ON b.product_id = p.product_id 
    WHERE b.user_id = :user_id
');
$statement->bindValue(":user_id", $user_id, PDO::PARAM_INT);
$statement->execute();
$products = $statement->fetchAll(PDO::FETCH_ASSOC);


$total = 0;
foreach ($products as $product) {
    $total += (float) $product['price'] * (int) $product['quantity'];
}

$serialized_array = serialize($products);
?>

<!DOCTYPE html>
<html>
<head>
    <?php require_once "../partials/header.php"; ?>
    <link rel="stylesheet" href="../public/css/basket.css">
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

<div class="general-center">
    <h1>Basket</h1>
</div>

<?php if (empty($products)) : ?>
    <div class="general-center">
        <p>Your basket is empty. <a href="products.php">Continue shopping</a></p>
    </div>
<?php else : ?>
    <?php foreach ($products as $product) { ?>
        <div id="card-container">
            <div class="card">
                <img id="basket-img" src="<?php echo htmlspecialchars($product['image']); ?>">
                <p><?php echo htmlspecialchars($product['name']); ?></p>
                <p>£<?php echo number_format($product['price'], 2); ?></p>
                
                
                <form method="POST" action="basket.php">
                    <input id="quantity" name="quantity" type="number" value="<?php echo $product['quantity']; ?>" min="1">
                    <input type="hidden" name="id" value="<?php echo $product['product_id']; ?>">
                    <button type="submit" class="white-button">Update</button>
                </form>

                
                <form method="POST" action="basket.php">
                    <input type="hidden" name="remove_id" value="<?php echo $product['product_id']; ?>">
                    <button type="submit" class="red-button" onclick="return confirm('Are you sure you want to remove this item?')">Remove</button>
                </form>
            </div>
        </div>
    <?php } ?>

    <div class="general-center">
        <p><strong>TOTAL: £<?php echo number_format($total, 2); ?></strong></p>
    </div>

    <div class="general-center">
        <form action="checkout.php" method="POST">
            <input type="hidden" name="order" value="<?php echo urlencode($serialized_array); ?>">
            <input type="hidden" name="total" value="<?php echo $total; ?>">
            <input id="checkout-button" class="green-button" type="submit" value="Checkout">
        </form>
    </div>
<?php endif; ?>

<?php require_once "../partials/footer.php"; ?>
</body>
</html>
