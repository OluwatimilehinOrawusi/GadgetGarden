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
    <link rel="stylesheet" href="../../public/css/navbar.css">
    <link rel="stylesheet" href="../../public/css/styles.css">
    <link rel="stylesheet" href="../../public/css/change_password.css">
    <link href='https://fonts.googleapis.com/css?family=Inter' rel='stylesheet'> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!--I have used a Font Awesome CDN link to integrate the eye icon for the password
    visibility in the input group-->

</head>
<body>
<?php require_once "../partials/navbar.php" ?>

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

    <script>
    // Validate that new and confirm passwords match
    function validateForm() {
        const newPassword = document.getElementById("new-password").value;
        const confirmPassword = document.getElementById("confirm-password").value;
        const errorMessageElement = document.getElementById("password-error-message");


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

        // If password is invalid, alert the user
        if (!validPassword) {
            alert(message);
            return false; // Prevent form submission
        }

        return true; // Allow form submission if everything is valid
    }

    // Toggle password visibility
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

    // Redirect to the login page
    function goToLogin() {
        alert("Redirecting to the login page..."); 
    }
    </script>

<?php require_once "../partials/footer.php" ?>
</body>
</html>