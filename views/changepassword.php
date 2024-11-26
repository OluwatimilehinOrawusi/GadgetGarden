<?php
session_start();
$pdo = require_once "../database/database.php"; // Ensure this path is correct

if (!isset($_SESSION['user_id'])) {
    header("Location:login.php");
    exit;
}

// Get the logged-in user ID
$user_id = $_SESSION['user_id'];

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form input
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Ensure all required fields are filled
    if (empty($new_password) || empty($confirm_password)) {
        echo "All fields are required.";
        exit;
    }

    // Check if new passwords match
    if ($new_password !== $confirm_password) {
        echo "Passwords do not match!";
        exit;
    }

    // Validate new password strength
    if (strlen($new_password) < 8) {
        echo "Password must be at least 8 characters long.";
        exit;
    }

    // Hash the new password
    $new_password_hash = password_hash($new_password, PASSWORD_DEFAULT);

    // Update the password in the database
    $update_sql = "UPDATE users SET password_hash = :password_hash WHERE user_id = :user_id";
    $update_stmt = $pdo->prepare($update_sql);

    try {
        $update_success = $update_stmt->execute([
            ':password_hash' => $new_password_hash,
            ':user_id' => $user_id,
        ]);

        if ($update_success) {
            echo "Password updated successfully!";
            //takes the user to the login page when the password has been successfully changed
            header("Location:login.php");
            exit();
        } else {
            echo "Error updating password.";
        }
        exit;
    } catch (PDOException $e) {
        // Handle database-related errors
        echo "An error occurred: " . $e->getMessage();
        exit;
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
</head>
<body>
<nav>
    <div class="nav-left">
        <a href="../index.php"><p id="logo-text">GADGET GARDEN</p></a>
    </div>
    <div class="nav-right">
        <a href="../views/products.php"><button class="green-button">Products</button></a>
        <a href="#categories"><button class="white-button">About Us</button></a>
        <?php if (!isset($_SESSION['user_id'])) { ?>
            <a href="./login.php"><button class="green-button">Login</button></a>
            <a href="./signup.php"><button class="white-button">Sign Up</button></a>
        <?php } ?>
        <?php if (isset($_SESSION['user_id'])) { ?>
            <a href="./basket.php"><button class="green-button">Basket</button></a>
            <a href="./logout.php"><button class="white-button">Logout</button></a>
        <?php } ?>
    </div>
</nav>

<div class="container">
    <header>
        <h1 id="header">Change your password</h1>
        <p>You can reset your password here</p><br><br>
    </header> 
    
    <form action="changePassword.php" method="POST" onsubmit="return validateForm()">
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
        
        <button type="submit" class="submit-btn">Update Password</button><br><br>
        <button type="button" class="back-btn" onclick="goToLogin()">Back to Login</button>
        
        <p class="password-guidelines">
            <strong>Password must include:</strong><br>
            - Minimum of 8 characters<br>
            - A mix of uppercase and lowercase letters<br>
            - At least one number<br>
            - At least one special character (e.g., @, #, $, %, etc.)<br>
        </p>
    </form>
</div>

<script>
    function validateForm() {
        const newPassword = document.getElementById("new-password").value;
        const confirmPassword = document.getElementById("confirm-password").value;

        // Check if the passwords match
        if (newPassword !== confirmPassword) {
            alert("Please make sure your passwords match");
            return false; // Prevent form submission
        }

        // Password validation rules to improve security
        const passwordRequirements = [
            /[a-z]/, // At least one lowercase letter
            /[A-Z]/, // At least one uppercase letter
            /[0-9]/, // At least one number
            /[!@#$%^&*(),.?":{}|<>]/ // At least one special character
        ];

        let validPassword = true;
        let message = "Your password must contain:\n";

        if (!passwordRequirements[0].test(newPassword)) {
            validPassword = false;
            message += "- At least one lowercase letter\n";
        }
        if (!passwordRequirements[1].test(newPassword)) {
            validPassword = false;
            message += "- At least one uppercase letter\n";
        }
        if (!passwordRequirements[2].test(newPassword)) {
            validPassword = false;
            message += "- At least one number\n";
        }
        if (!passwordRequirements[3].test(newPassword)) {
            validPassword = false;
            message += "- At least one special character (e.g., @, #, $, %, etc.)\n";
        }

        if (!validPassword) {
            alert(message);
            return false;
        }

        return true;
    }

    function togglePassword(inputId, icon) {
        const input = document.getElementById(inputId);
        if (input.type === "password") {
            input.type = "text";
            icon.classList.remove("fa-eye");
            icon.classList.add("fa-eye-slash");
        } else {
            input.type = "password";
            icon.classList.remove("fa-eye-slash");
            icon.classList.add("fa-eye");
        }
    }

    function goToLogin() {
        window.location.href = "login.php";
    }
</script>

<?php require_once "../partials/footer.php" ?>
</body>
</html>

