<?php
session_start();

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title> Gadget Garden </title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="./public/css/navbar.css">
        <link rel="stylesheet" href="./public/css/styles.css">
    </head>
    <body>
        <!-- Hero Section -->
    <section id="hero-section">
    <nav>
            <div class="nav-left">
                <p id="logo-text">GADGET GARDEN</p>
            </div>
            <div class="nav-right">
                <a href="#categories"><button id="categories-button">Categories </button></a>
                <button class="white-button">About Us</button>
                <?php if (!isset($_SESSION['user_id'])){?>
                <?php echo '<a href="./views/login.php"><button class="green-button">Login</button></a>' ?>
                 <?php echo '<a href="./views/signup.php"><button class="white-button">Sign Up</button></a> '?>
                <?php }?>
                <?php if (isset($_SESSION['user_id'])){?>
                <?php echo '<a href="./views/basket.php"><button class="green-button">Basket</button></a>' ?>
                <?php echo '<a href="./views/logout.php"><button class="white-button">Logout</button></a>' ?>

                <?php }?>
            
            </div>
    </nav>
        <div id="landing-display-container">
            <div id="landing-display">
                <div id="hero-main-img" class="home-images">
                    <h1 id="title-text">GADGET GARDEN</H1>
                </div>
                <div id="home-top-right" class="home-images">
                    <img>
                </div>
                <div id="home-bottom-right" class="home-images">
                    <img>
                </div>        
            </div>
        </div>
        
    </section>
    <hr>

    <!-- Trending Section -->
    <section class="trending">
        
        <div id="trending-top">
            <h2 class="subtitle">Trending</h2>
            <div id="trending-buttons">
            
                <button class="green-button">PHONES</button>
                <button class="white-button">LAPTOPS</button>
                <button class="green-button">AUDIO</button>
                <button class="white-button">GAMING</button>
                
            </div>
        </div>
        
        
        <div id="trending-items">
            <div class="home-images"></div>
            <div class="home-images"></div>
            <div class="home-image"></div>
            <div class="home-images"></div>
            <div class="home-images"></div>
            <div class="home-images"></div>
            <div class="home-images"></div>
            <div class="home-images"></div>
            
        </div>
        <div id="shop-now-container">
        <a href="views/products.php"><button class="green-button" id="shop-now-button"> Find out more ↝</button></a>
        </section>
        </div>
        


    <hr>

    <!-- Categories Section -->
    <section  id="categories">
        <div id="categories-top">
            <h2 style="font-size: 3rem; font-weight:400; text-align:center"class="subtitle">Explore our <span style="display: block">Categories</span></h2>
            <div id="category-button-container">
                <button class="white-button category-buttons">LAPTOPS</button>
                <button class="green-button category-buttons">PHONES</button>
                <button class="white-button category-buttons">GAMING</button>
                <button class="green-button category-buttons">WEARABLES</button>
                <button class="green-button category-buttons">TABLETS</button>
                <button class="white-button category-buttons">ACCESSORIES</button>
                <button class="green-button category-buttons">COMPUTERS</button>
                <button class="white-button category-buttons">AUDIO</button>
            </div>
        </div>
        <div class="category-image">
            <div class="home-images" id="category-banner"></div>
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

    
    <?php require_once "./partials/footer.php" ?>
    
    </body>
</html>
