<?php
session_start();
?>
<nav>
    <div class="nav-left">
        <a href="../index.php"><p id="logo-text">GADGET GARDEN</p></a>
    </div>
    <div class="nav-right">
        <a href="../views/contact.php"><button class="green-button">Contact Us</button></a>
        <a href="../views/aboutpage.php"><button class="white-button">About Us</button></a>
        <?php if (!isset($_SESSION['user_id'])) { ?>
            <a href="./login.php"><button class="green-button">Login</button></a>
            <a href="./signup.php"><button class="white-button">Sign Up</button></a>
        <?php } ?>
        <a href="../views/products.php"><button class="green-button">Products</button></a>
        <?php if (isset($_SESSION['user_id'])) { ?>
            <a href="./basket.php"><button class="white-button">Basket</button></a>
            <a href="./profile.php"><button class="white-button">Profile</button></a>
            
           
            <a href="./admin.php"><button class="white-button">Admin</button></a>
            
            <a href="./logout.php"><button class="green-button">Logout</button></a>
        <?php } ?>
    </div>
</nav>
            