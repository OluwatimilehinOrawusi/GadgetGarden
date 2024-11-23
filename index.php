<!DOCTYPE html>
<html>
    <head>
        <?php require_once "../app/partials/header.php" ?>
    </head>
    <body>
        <!-- Hero Section -->
    <section id="hero-section">
        <?php require_once "../app/partials/navbar.php"?>
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
    </section>

    <hr>

    <!-- Categories Section -->
    <section class="categories">
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

    
    <?php require_once "../app/partials/footer.php" ?>
    
    </body>
</html>
