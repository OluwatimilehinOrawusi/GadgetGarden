<?php
session_start();

?>
<nav>
            <div class="nav-left">
                <a href="../index.php"><p id="logo-text">GADGET GARDEN</p></a>
            </div>
            <div class="nav-right">
                <a href="../views/products.php"><button class="green-button" >Products</button></a>
                <a href="#categories"><button class="white-button">About Us</button></a>
                <?php if (!isset($_SESSION['user_id'])){?>
                <?php echo '<a href="./login.php"><button class="green-button">Login</button></a>' ?>
                 <?php echo '<a href="./signup.php"><button class="white-button">Sign Up</button></a> '?>
                <?php }?>
                <?php if (isset($_SESSION['user_id'])){?>
                <?php echo '<a href="./basket.php"><button class="green-button">Basket</button></a>' ?>
                <?php echo '<a href="./logout.php"><button class="white-button">Logout</button></a>' ?>

                <?php }?>

            </div>
</nav>