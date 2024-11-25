
<?php
session_start();
$pdo = require_once "../database/database.php"; 

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id']; // Logged-in user ID

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the form data
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Check if the new passwords match
    if ($new_password !== $confirm_password) {
        echo "Passwords do not match!";
        exit;
    }

    // Password validation (could be improved with regex)
    if (strlen($new_password) < 8) {
        echo "Password must be at least 8 characters long.";
        exit;
    }

    // Fetch the current password hash from the database
    $sql = "SELECT password_hash FROM users WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($password_hash);
    $stmt->fetch();
    $stmt->close();

    // Check if the current password is correct
    if (password_verify($current_password, $password_hash)) {
        // Hash the new password before updating
        $new_password_hash = password_hash($new_password, PASSWORD_DEFAULT);

        // Update the password in the database
        $update_sql = "UPDATE users SET password_hash = ? WHERE user_id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("si", $new_password_hash, $user_id);
        
        if ($update_stmt->execute()) {
            echo "Password updated successfully!";
        } else {
            echo "Error updating password.";
        }
        $update_stmt->close();
    } else {
        echo "Current password is incorrect!";
    }
}
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once "../partials/header.php" ?>
    <link rel="stylesheet" href="../public/css/navbar.css">
    <link rel="stylesheet" href="../public/css/styles.css">
    <link rel="stylesheet" href="../public/css/change_password.css">
    <link href='https://fonts.googleapis.com/css?family=Inter' rel='stylesheet'> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!--I have used a Font Awesome CDN link to integrate the eye icon for the password
    visibility in the input group-->

</head>
<body>
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

    <div class="container">
        <header>
            <h1 id="header">Change your password</h1>
            <p>You can reset your password here</p><br><br>
        </header> 
        
        <form action="change_password.php" method="POST" onsubmit="return validateForm()">
            <div class="input-group"> 
                <label for="new-password">New Password</label>
                <div class="password-wrapper">
                    <input type="password" id="new-password" name="new_password" required>
                    <i class="fas fa-eye toggle-password" onclick="togglePassword('new-password', this)"></i>
                </div>
            </div>
            
            <div class="input-group">
                <label for="confirm-password">Confirm New Password</label>
                <div class="password-wrapper">
                    <input type="password" id="confirm-password" name="confirm_password" required>
                    <i class="fas fa-eye toggle-password" onclick="togglePassword('confirm-password', this)"></i>
                </div>
            </div>
            
            <!--Submit and back to login buttons-->
            <button type="submit" class="submit-btn">Update Password</button><br><br>
            <button type="button" class="back-btn" onclick="goToLogin()">Back to Login</button>
            
            <!-- guidelines for users' new passwords-->
            <p class="password-guidelines">
                <strong>Password must include:</strong><br>
                - Minimum of 8 characters<br>
                - A mix of uppercase and lowercase letters<br>
                - At least one number<br>
                - At least one special character (e.g., @, #, $, %, etc.)<br>
            </p>
            

        </form>
    </div>
<?php require_once "../partials/footer.php" ?>
</body>
</html>
