<?php
session_start();

$pdo = require_once "./database/database.php";

$user_id = $_SESSION['user_id'] ?? null;

// Use the correct column name for role (change 'user_role' if needed)
$stmt = $pdo->prepare("SELECT role FROM users WHERE user_id = :user_id");
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Store role in session for navbar consistency
if ($user) {
    $_SESSION['user_role'] = $user['role']; // Change 'role' to the correct column name
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gadget Garden</title>
    <link rel="stylesheet" href="./public/css/navbar.css">
    <link rel="stylesheet" href="./public/css/styles.css">
    <link rel="stylesheet" href="./public/css/chatbot.css">
</head>
<body>

<!-- Navigation Bar -->
<nav>
    <div class="nav-left">
        <p id="logo-text">GADGET GARDEN</p>
    </div>
    <div class="nav-right">
        <a href="#categories"><button class="green-button">Categories</button></a>
        <a href="./views/aboutpage.php"><button class="white-button">About Us</button></a>

        <?php if (!isset($_SESSION['user_id'])) { ?>
            <a href="./views/login.php"><button class="green-button">Login</button></a>
            <a href="./views/signup.php"><button class="white-button">Sign Up</button></a>
        <?php } ?>

        <?php if (isset($_SESSION['user_id'])) { ?>
            <a href="./views/basket.php"><button class="green-button">Basket</button></a>
            <a href="./views/contact.php"><button class="green-button">Contact Us</button></a>

            <!-- Show "Admin Dashboard" for Admins & Managers -->
            <?php if (isset($_SESSION['user_role']) && in_array($_SESSION['user_role'], ['admin', 'manager'])) { ?>
                <a href="./views/admin_dashboard.php"><button class="white-button">Admin Dashboard</button></a>
            <?php } else { ?>
                <a href="./views/profile.php"><button class="white-button">Profile</button></a>
            <?php } ?>

            <a href="./views/logout.php"><button class="green-button">Logout</button></a>
        <?php } ?>
    </div>
</nav>

<!-- Hero Section -->
<section id="hero-section">
    <div id="landing-display-container">
        <div id="landing-display">
            <div id="hero-main-img" class="home-images">
                <h1 id="title-text">GADGET GARDEN</h1>
            </div>
            <div id="home-top-right" class="home-images"></div>
            <div id="home-bottom-right" class="home-images"></div>
        </div>
    </div>
</section>

<hr>

<!-- Trending Section -->
<section class="trending">
    <div id="trending-top">
        <h2 class="subtitle">Trending</h2>
        <div id="trending-buttons">
            <a href="./views/products.php?category=phones"><button class="green-button">PHONES</button></a>
            <a href="./views/products.php?category=laptops"><button class="white-button">LAPTOPS</button></a>
            <a href="./views/products.php?category=audio"><button class="green-button">AUDIO</button></a>
            <a href="./views/products.php?category=gaming"><button class="white-button">GAMING</button></a>
        </div>
    </div>
</section>

<hr>

<!-- Categories Section -->
<section id="categories">
    <div id="categories-top">
        <h2 class="subtitle">Explore our <span style="display: block">Categories</span></h2>
        <div id="category-button-container">
            <a href="./views/products.php?category=laptops"><button class="white-button category-buttons">LAPTOPS</button></a>
            <a href="./views/products.php?category=phones"><button class="green-button category-buttons">PHONES</button></a>
            <a href="./views/products.php?category=gaming"><button class="white-button category-buttons">GAMING</button></a>
            <a href="./views/products.php?category=wearables"><button class="green-button category-buttons">WEARABLES</button></a>
            <a href="./views/products.php?category=tablets"><button class="green-button category-buttons">TABLETS</button></a>
            <a href="./views/products.php?category=accessories"><button class="white-button category-buttons">ACCESSORIES</button></a>
            <a href="./views/products.php?category=computers"><button class="green-button category-buttons">COMPUTERS</button></a>
            <a href="./views/products.php?category=audio"><button class="white-button category-buttons">AUDIO</button></a>
        </div>
    </div>
</section>

<hr>

<!-- Why Us Section -->
<section id="why-us">
    <h2 class="subtitle">Why Us</h2>
    <div class="why-us-cards">
        <div class="card">
            <div class="why-us-image why-us-secure home-images"></div>
            <p class="why-us-title">Secure</p>
        </div>
        <div class="card">
            <div class="why-us-image why-us-cheap home-images"></div>
            <p class="why-us-title">Cheap</p>
        </div>
        <div class="card">
            <div class="why-us-image why-us-eco home-images"></div>
            <p class="why-us-title">Eco-Friendly</p>
        </div>
    </div>
</section>

<?php require_once "./partials/footer.php"; ?>

</body>
</html>
