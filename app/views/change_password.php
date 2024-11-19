<!--html section begins here-->
<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once "../partials/header.php" ?>
    <link rel="stylesheet" href="../../public/css/navbar.css">
    <link rel="stylesheet" href="../../public/css/styles.css">
    <link rel="stylesheet" href="../../public/css/change_password.css">
    <link href='https://fonts.googleapis.com/css?family=Inter' rel='stylesheet'>
    
</head>
<body>
<?php require_once "../partials/navbar.php" ?>
    <div class="container">
        <header>
            <h1 id="header" >Change your password</h1>
            <p>You can reset your password here</p><br><br>
        </header> 
        
        <form action="change_password.php" method="POST" onsubmit="return validateForm()">
            
            <div class="input-group"> 
                <label for="new-password">New Password</label>
                <input type="password" id="new-password" name="new_password" required><br><br>
                <button type="button" class="toggle-password" onclick="togglePassword('new-password', this)">Show</button>
            </div>
            
            <div class="input-group">
                <label for="confirm-password">Confirm New Password</label>
                <input type="password" id="confirm-password" name="confirm_password" required><br><br>
                <button type="button" class="toggle-password" onclick="togglePassword('confirm-password', this)">Show</button>
            </div>
            
            <button type="submit" class="submit-btn">Update Password</button><br><br>
            <button type="button" class="back-btn" onclick="goToLogin()">Back to Login</button>
        </form>
    </div>
    <?php require_once "../partials/footer.php" ?>
<!--JSS section begins here-->
    <script>
        // Validate that new and confirm passwords match
        function validateForm() {
            const newPassword = document.getElementById("new-password").value;
            const confirmPassword = document.getElementById("confirm-password").value;

            if (newPassword !== confirmPassword) {
                alert("Please make sure your passwords match");
                return false; // Prevent form submission
            }
            return true; // Allow form submission if passwords match
        }

        // Toggle password visibility
        function togglePassword(inputId, button) {
            const input = document.getElementById(inputId);
            if (input.type === "password") {
                input.type = "text";
                button.textContent = "Hide";
            } else {
                input.type = "password";
                button.textContent = "Show";
            }
        }

        // Redirect to the login page
        function goToLogin() {
            alert("Redirecting to the login page..."); 
        }
    </script>
</body>
</html>


