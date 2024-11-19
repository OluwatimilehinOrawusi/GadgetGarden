<!--html section begins here-->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password - Gadget Garden</title>
    <link rel="stylesheet" href="CSS/change_password.css">
    <link href='https://fonts.googleapis.com/css?family=Inter' rel='stylesheet'>
    
</head>
<body>
    <div class="container">
        <header>
            <h1>Change your password</h1>
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

<!--CSS Section begins here-->
/* Basic Reset */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}
body 
 {
    background-color: #02542D;
    font-family: 'Inter';
}
/* Main container */
.container {
    width: 100%;
    max-width: 500px;
    margin: 0 auto;
    padding: 20px;
    text-align: center;
    color: #fff;
    background: white; /* Dark green similar to the main theme */
    border-radius: 8px;
    margin-top: 100px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    font-family:inherit
}

/* Header */
header h1 {
    font-size: 24px;
    font-weight: bold;
    margin-bottom: 10px;
    color: #0f0f0f;
}

header p {
    font-size: 14px;
    color: #0f0f0f;
}

/* Input Group */
.input-group {
    margin-bottom: 20px;
    text-align: left;
}

.input-group label {
    display: block;
    font-size: 14px;
    font-weight: bold;
    margin-bottom: 5px;
    color: #0f0f0f;
}

.input-group input[type="password"] {
    width: 100%;
    padding: 10px;
    border-radius: 4px;
    border: 1px solid #ccc;
    font-size: 16px;
    font-weight: bold;
    background-color: #fff;
    color: #333;
}

.submit-btn {
    width: 100%;
    padding: 12px;
    font-size: 16px;
    font-weight: bold;
    color: white;
    background-color: #02542D; /* Same colour as the background */
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.submit-btn:hover {
    background-color: #093833;
}

.toggle-password {
    cursor: pointer;
}
