<?php 



$pdo = require_once "../database/database.php" ;

$keyword = $_GET['search'] ?? null;


if ($keyword){
    $statement = $pdo->prepare('SELECT * FROM products WHERE name   like :keyword');
    $statement->bindValue(":keyword", "%$keyword%");
}else{
    $statement = $pdo->prepare('SELECT * FROM products');
}
$statement->execute();
$products = $statement->fetchAll(PDO::FETCH_ASSOC);





?>

<!DOCTYPE html>
<html>
    <head>
    <?php require_once "../partials/header.php" ?>
    <link rel="stylesheet" href="../public/css/products.css">
    </head>
    <body>
    <?php require_once "../partials/navbar.php" ?>
    <section id="header">
        <div id="search-bar-container">
        <h1 id="heading">Explore our products</h1>
            <form id="search-form">
                <input id="search-input" type="text" name="search">
            </form>
        
        
        </div>
        
    </section>
    <section id="products">

        <?php foreach($products as $i => $product){ ?>
            
                <div class="card">
                
                    <div id="product-image-container">
                    <img class="product-images" src="<?php echo '../'.$product['image']?>">
                    </div>
                    <a href="<?php echo "./product.php?id=" .$product['product_id']?>">
                    <p><?php echo $product["name"] ?></p>
                    <p>£<?php echo $product["price"] ?></p>
                    </a>
                </div>
            
        <?php } ?>
    </section>
    <?php require_once "../partials/footer.php" ?>
    </body>
</html>