<?php
session_start();
require_once '../database/database.php';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $phone = trim($_POST['phone']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $memorable_phrase = $_POST['memorable_phrase'];

    if ($password !== $confirm_password) {
        $errors[] = "The passwords do not match, please try again.";
    }

    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    try {
        $sql = "INSERT INTO users(username, email, phone, password_hash,memorable_phrase) VALUES(?,?,?,?,?)";
        $stmt = $pdo->prepare($sql);
        $stmt -> execute([$username, $email,$phone, $password_hash,$memorable_phrase]);

        header("Location: ./profile.php");
        exit;
    } catch (PDOException $e) {
        echo "An error occurred, please try again later: " . $e->getMessage();
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link href="../public/css/signup.css" rel="stylesheet"/>
    <link rel="stylesheet" href="../public/css/navbar.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GADGET GARDEN</title>
    <?php require_once "../partials/header.php" ?>
</head>

<body>
<nav>
    <div class="nav-left">
        <a href="../index.php"><p id="logo-text">GADGET GARDEN</p>
    </div>
    <div class="nav-right">
        <a href="./aboutpage.php"><button class="white-button">About Us</button></a>

        <?php if (!isset($_SESSION['user_id'])) { ?>
            <a href="./login.php"><button class="green-button">Login</button></a>
            <a href="./signup.php"><button class="white-button">Sign Up</button></a>
        <?php } ?>

        <?php if (isset($_SESSION['user_id'])) { ?>
            <a href="./basket.php"><button class="green-button">Basket</button></a>
            <a href="./contact.php"><button class="green-button">Contact Us</button></a>
            <a href="./profile.php"><button class="white-button">Profile</button></a>
            <a href="./products.php"><button class="green-button">Products</button></a>

            <?php if ($user && ($user['role'] === 'admin' || $user['role'] === 'manager')){ ?>
                <a href="./dashboard.php"><button class="white-button">Admin Dashboard</button></a>
            <?php } ?>

            <a href="./logout.php"><button class="green-button">Logout</button></a>
        
        <?php } ?>
    </div>
</nav>

<div class="webpage">
    <div class="intro">
        <h2>Grow Your Tech Sustainably - Buy, Sell, and Renew at Gadget Garden!</h2>
        <div class="image1">
            <img src="../public/assets/ggLaptopSignIn.png" class="images" alt="Laptop Promo"/>
        </div>
    </div>

    <div class="signup">
        <h3>Create Account</h3>
        <form method="POST" action="./signup.php" id="myForm">
            <div class="creating">
                <label>Username</label>
                <input required type="text" name="username" placeholder="Username">

                <label for="email">E-mail</label>
                <input required type="email" name="email" placeholder="Email">

                <label for="phone">Phone Number</label>
                <input required type="text" name="phone" placeholder="Phone">

                <label for="password">Password</label>
                <input required type="password" name="password" placeholder="Password">

  <label for="cpassword">Confirm Password</label>

  <input required ="text" id="cpassword" name="confirm_password" placeholder="Confirm Password">

                <button type="submit">Create Account</button>

                <h6>Already have an account? <a href="./login.php">Log in</a></h6>
            </div>
        </form>
    </div>
</div>

<?php require_once '../partials/footer.php' ?>
</body>
</html>
