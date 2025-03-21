<?php
session_start();
$pdo = require_once "../database/database.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT P.product_id, p.name, p.price, p.image FROM wishlist w JOIN products p ON w.product_id = p.product_id WHERE w.user_id = :user_id");
$stmt->execute(['user_id' => $user_id]);
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../public/css/wishlist.css">
    <?php require_once "../partials/header.php"; ?>
    <title>Wishlist</title>
</head>
<body>
<nav>
            <div class="nav-left">
                <a href="../index.php"><p id="logo-text">GADGET GARDEN</p></a>
            </div>
            <div class="nav-right">
                <a href="../views/aboutpage.php"><button class="white-button">About Us</button></a>
                <?php if (!isset($_SESSION['user_id'])){?>
                <?php echo '<a href="./login.php"><button class="green-button">Login</button></a>' ?>
                 <?php echo '<a href="./signup.php"><button class="white-button">Sign Up</button></a> '?>
                <?php }?>
                <?php if (isset($_SESSION['user_id'])){?>
                <?php echo '<a href="./basket.php"><button class="white-button">Basket</button></a>' ?>
                <?php echo '<a href="./contact.php"><button class="white-button">Contact us</button></a>' ?>
                <?php echo '<a href = "./profile.php"><button class ="white-button">Profile</button></a>' ?>
                <?php echo '<a href="./logout.php"><button class="green-button">Logout</button></a>' ?>

                <?php }?>

            </div>
</nav>       
<h1>Bookmarked Items</h1>
    <div class = "wishlistcon">
        
        <?php if ($items) : ?>
            <?php foreach ($items as $item): ?>
                <div clas = "wishlistcrd">
                    <img class="wishlist-image" src="<?php echo htmlspecialchars($item['image']); ?>" alt = "Image">
                    <h3><?php echo htmlspecialchars($item['name']); ?></h3>
                    <p>£<?php echo htmlspecialchars($item['price']); ?></p>
                    <a href = "product.php?id=<?php echo $item['product_id']; ?>"><button class = "green-button">View</button></a>
                    <a href = "./deletebookmark.php"><button class = "remove-button">Remove</button></a>
            </div>
            <?php endforeach; ?>
                </div>
                <?php else: ?>
                    <h5>You have no bookmarked items</h5>
                    <?php endif; ?>
    </div>
    
    <?php require_once "../partials/footer.php"?>
</body>
</html>