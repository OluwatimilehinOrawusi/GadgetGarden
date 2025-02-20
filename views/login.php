<?php
session_start();
$pdo = require_once '../database/database.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $sql = "SELECT user_id, username, password_hash, role FROM users WHERE username = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password_hash'])) {
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['user_role'] = $user['role'];

        if ($user['role'] === 'admin' || $user['role'] === 'manager') {
            header("Location: ../views/admin_dashboard.php");
        } else {
            header("Location: ../index.php");
        }
        exit();
    } else {
        $errors[] = "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign in</title>
    <?php require_once "../partials/header.php"; ?>
    <link rel="stylesheet" href="../public/css/navbar.css">
    <link rel="stylesheet" href="../public/css/styles.css">
    <link rel="stylesheet" href="../public/css/login.css">
</head>
<body>

<nav>
    <div class="nav-left">
        <a href="../index.php"><p id="logo-text">GADGET GARDEN</p></a>
    </div>
    <div class="nav-right">
        <a href="../views/aboutpage.php"><button class="white-button">About Us</button></a>
        <?php if (!isset($_SESSION['user_id'])) { ?>
            <a href="./login.php"><button class="green-button">Login</button></a>
            <a href="./signup.php"><button class="white-button">Sign Up</button></a>
        <?php } else { ?>
            <a href="./basket.php"><button class="white-button">Basket</button></a>
            <a href="./contact.php"><button class="white-button">Contact us</button></a>
            <a href="./profile.php"><button class="white-button">Profile</button></a>
            <a href="./logout.php"><button class="green-button">Logout</button></a>
        <?php } ?>
    </div>
</nav>

<div id="login-page">
    <div id="leftside-container">
        <h1 id="catchphrase">Grow Your Tech Sustainably - Buy, Sell, and Renew at Gadget Garden!</h1>
        <img src="../public/assets/ggLaptopSignIn.png" alt="An image of a laptop with a garden within the screen">
    </div>

    <div id="login-container">
        <h1>Sign in</h1>
        <br> <br>
        
        <?php if (!empty($errors)) : ?>
            <div class="error-message">
                <?php foreach ($errors as $error) {
                    echo "<p>$error</p>";
                } ?>
            </div>
        <?php endif; ?>

        <form method="post" action="login.php" id="loginform">
            <div id="textboxesandlabels">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
                <br> <br>

                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
                <br> <br>
            </div>

            <input type="submit" value="Sign in" id="subbutton"/>
            <input type="hidden" name="submitted" value="true"/>
            <br> <br>

            <p class="register-new">Don't have an account? <a href="./signup.php">Sign up</a></p>
        </form> 
    </div>
</div>

<?php require_once '../partials/footer.php'; ?>
</body>
</html>
