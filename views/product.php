<?php 

$pdo = require_once "../database/database.php" ;

$id = $_GET['id'];

$statement = $pdo->prepare('SELECT * FROM products WHERE product_id = :id');
$statement->bindValue(":id", "$id");
$statement->execute();
$product= $statement->fetch(PDO::FETCH_ASSOC);


?>

<!DOCTYPE html>
<html>
    <head>
    <?php require_once "../partials/header.php" ?>
    <link rel="stylesheet" href="../public/css/product.css">
    </head>
    <body>
    <?php require_once "../partials/navbar.php" ?>
    <div class="product-container">
    <!-- Product Image -->
    <div class="product-image">
      <img src="<?php echo "..".$product["image"]?>" alt="Product Image">
    </div>
    
    <!-- Product Data -->
    <div class="product-data">
      <h1 class="product-name"><?php echo $product["name"]?></h1>
      <p class="product-description">
      <?php echo $product["description"]?>
      </p>
      <p class="product-condition">Condition: <?php echo $product["state"]?></p>
      <p class="product-price">£<?php echo $product["price"]?></p>
      <a href="  <?php echo './add-products.php?product_id='.$product["product_id"]?>"><button class="green-button">Add to Basket</button></a>
      <a href="./reviewPage.php"> <u>Write a review</u></a>
    </div>
  </div>

    </div>
    <div>

    </div>
    <?php require_once "../partials/footer.php" ?>
    </body>
</html>