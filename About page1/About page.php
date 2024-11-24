<?php 

$pdo = require_once "../database/database.php";

$id = $_GET['id'];

$statement = $pdo->prepare('SELECT * FROM products WHERE product_id = :id');
$statement->bindValue(":id", $id);
$statement->execute();
$product = $statement->fetch(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <?php require_once "../partials/header.php"; ?>
        <link rel="stylesheet" href="../public/css/product.css">
    </head>
    <body>
        <?php require_once "../partials/navbar.php"; ?>

        <section id="about-us">
            <div class="about-container">
                <div class="about-content green-box">
                    <h1>About Us</h1>
                    <p>
                        Gadget Garden is a company that puts the planet first. Our mission is to inspire a sustainable future by crafting eco-friendly technology that blends seamlessly with modern life, prioritizing the planet and enhancing everyday experiences.
                    </p>
                    <div class="highlight">
                        <h3>Our Values</h3>
                        <p>We believe in sustainability, innovation, and quality. Our products are designed to minimize environmental impact without compromising on functionality.</p>
                    </div>
                    <div class="highlight">
                        <h3>Our Vision</h3>
                        <p>We envision a world where technology and nature coexist harmoniously, empowering people to live better, greener lives.</p>
                    </div>
                    <div class="highlight">
                        <h3>Our Mission</h3>
                        <p>To create solutions that redefine how people interact with technology while protecting the environment for future generations.</p>
                    </div>
                </div>
                <div class="about-images">
                    <img src="../Assets/world.png" alt="World illustration" class="about-image">
                    <img src="../Assets/Laptop.png" alt="Laptop illustration" class="about-image">
                </div>
            </div>
        </section>

        <div class="product-container">
            <div class="product-image">
                <img src="<?php echo "../" . htmlspecialchars($product["image"]); ?>" alt="Product Image">
            </div>
            
            <div class="product-data">
                <h1 class="product-name"><?php echo htmlspecialchars($product["name"]); ?></h1>
                <p class="product-description">
                    <?php echo htmlspecialchars($product["description"]); ?>
                </p>
                <p class="product-condition">Condition: <?php echo htmlspecialchars($product["state"]); ?></p>
                <p class="product-price">£<?php echo htmlspecialchars($product["price"]); ?></p>
                <a href="./add-products.php?product_id=<?php echo $product["product_id"]; ?>">
                    <button class="green-button">Add to Basket</button>
                </a>
            </div>
        </div>

        <?php require_once "../partials/footer.php"; ?>
    </body>
</html>
