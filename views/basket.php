<?php 

// Start the session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if(!isset($_SESSION['user_id'])){
    header("Location: ../index.php");
}

$pdo = require_once "../database/database.php" ;

if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    $id = $_POST['id'];
    $quantity = $_POST['quantity'];
    var_dump($_POST);
    var_dump($id);
    $statement = $pdo->prepare('UPDATE basket SET quantity = :quantity  WHERE product_id = :id ');
    $statement->bindValue(":id", "$id");
    $statement->bindValue(":quantity", "$quantity");
    $statement->execute();
    header('Location: basket.php');
    exit();
}else{

$id = $_SESSION['user_id'];

$statement = $pdo->prepare('SELECT b.basket_id, b.user_id, b.product_id, b.quantity,  p.name, p.price, p.description, p.image FROM basket b JOIN products p ON b.product_id = p.product_id WHERE b.user_id = :id');
$statement->bindValue(":id", "$id");
$statement->execute();
$products= $statement->fetchAll(PDO::FETCH_ASSOC);
// var_dump($products);
$serialized_array = serialize($products);
$total = 0;
foreach($products as $product){
    $total = $total + (float)$product['price'] * (int)$product['quantity'];
}

}

?>

<!DOCTYPE html>
<html>
    <head>
    <?php require_once "../partials/header.php" ?>
    <link rel="stylesheet" href="../public/css/basket.css">
    </head>
    <body>
    <nav>
            <div class="nav-left">
                <a href="../index.php"><p id="logo-text">GADGET GARDEN</p></a>
            </div>
            <div class="nav-right">
                <a href="./contact.php"><button class="green-button" >Contact Us</button></a>
                <a href="./aboutpage.php"><button class="white-button">About Us</button></a>
                <?php if (!isset($_SESSION['user_id'])){?>
                <?php echo '<a href="./login.php"><button class="green-button">Login</button></a>' ?>
                 <?php echo '<a href="./signup.php"><button class="white-button">Sign Up</button></a> '?>
                <?php }?>
                <a href="../views/products.php"><button class="green-button" >Products</button></a>
                <?php if (isset($_SESSION['user_id'])){?>
                <?php echo '<a href="./basket.php"><button class="white-button">Basket</button></a>' ?>
                <?php echo '<a href = "./profile.php"><button class ="white-button">Profile</button></a>' ?>
                <?php echo '<a href="./logout.php"><button class="green-button">Logout</button></a>' ?>

                <?php }?>

            </div>
</nav>
<div class="general-center">
<h1 > Basket </h1>
</div>

                <?php foreach($products as $product){ ?>
                    
                     <div id="card-container">
                     
                        <div class="card"> 
                            <!-- <?php var_dump($product)?> -->
                            <img id="basket-img" src="<?php echo '../'.$product['image']?>">
                            <p><?php echo $product['name']?></p>
                            <p><?php echo $product['price']?></p>
                            <form method="POST" action="./basket.php">
                            <input id='quantity' name="quantity" type="number" value="<?php echo $product['quantity']?>">
                            <input hidden="true" name="id" value="<?php echo $product['product_id']?>">
                            <button type="submit" class="white-button">Update</button>
                            </form>
                            
                         </div>
                         

                    </div>
                    <?php }?>
                    <div class="general-center">
                    <p >TOTAL: £<?php echo $total?>
                    </div>
                    <div  class="general-center">
                        <form action="./checkout.php" method="POST">
                            <input type="hidden" name="order" value=<?php echo urlencode($serialized_array); ?>>
                            <input type="hidden" name="total" value=<?php echo $total?>>
                            <input id="checkout-button"  class="green-button" type="submit" value="Checkout">
                        </form>
                    </div>
                        
                    



    <?php require_once "../partials/footer.php" ?>
    </body>
</html>