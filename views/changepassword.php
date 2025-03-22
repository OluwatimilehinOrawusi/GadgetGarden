<?php
session_start();
$pdo = require_once "../database/database.php"; // Ensure this path is correct

$user = null;
if (isset($_SESSION['user_id'])) {
    $stmt = $pdo->prepare("SELECT role FROM users WHERE user_id = :user_id");
    $stmt->execute(['user_id' => $_SESSION['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
}

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
            // Takes the user to the login page when the password has been successfully changed
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
     <link rel="stylesheet" href="../public/css/chatbot.css">
    <link href='https://fonts.googleapis.com/css?family=Inter' rel='stylesheet'> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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
  <br>
  <br>


   
        
<div class="container">
    <header>
        <h1 id="header">Change your password</h1>
        <p>You can reset your password here</p><br><br>
    </header> 
        
        <div class="input-group">
             <form action="changePassword.php" method="POST" onsubmit="return validateForm()">
            <div class="input-group"> 
            <label for="new-password">New Password</label>
            <div class="password-wrapper">
                <input type="password" id="new-password" name="new_password" required>
                <i class="fas fa-eye toggle-password" onclick="togglePassword('new-password', this)"></i>
            </div>
        </div>
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
            <!-- Chat Icon -->
<div class="chat-icon" onclick="toggleChat()">💬</div>

<!-- Chat Container -->
<div class="chat-container" id="chat-container">
    <div class="chat-header">
        <span>Chatbot</span>
        <button onclick="minimizeChat()">➖</button>
        <button onclick="closeChat()">❌</button>
        <button onclick="terminateChat()">⛔</button>
    </div>
    <div class="chat-box" id="chat-box"></div>
    
    <div class="chat-options">
        <p class="bot-message message"><strong>Bot:</strong> Select an option:</p>
        <button onclick="sendMessage('delivery times')">Delivery Times</button>
        <button onclick="sendMessage('returns')">Returns</button>
        <button onclick="sendMessage('refunds')">Refunds</button>
        <button onclick="sendMessage('rate us')">Rate Us</button>
        <button onclick="sendMessage('contact us')">Contact Us</button>
    </div>

    <input type="text" id="user-input" class="chat-input" placeholder="Type here..." onkeypress="handleKeyPress(event)">
</div>

<script>
    function toggleChat() {
        let chatContainer = document.getElementById("chat-container");
        if (!chatContainer.classList.contains("open")) {
            chatContainer.style.display = "block";
            setTimeout(() => chatContainer.classList.add("open"), 10);
        } else {
            chatContainer.classList.remove("open");
            setTimeout(() => chatContainer.style.display = "none", 300);
        }
    }

    function minimizeChat() {
        let chatContainer = document.getElementById("chat-container");
        chatContainer.classList.remove("open");
        setTimeout(() => { chatContainer.style.display = "none"; }, 300);
    }

    function closeChat() {
        let chatContainer = document.getElementById("chat-container");
        chatContainer.classList.remove("open");
        setTimeout(() => { chatContainer.style.display = "none"; }, 300);
    }

    function terminateChat() {
        let chatContainer = document.getElementById("chat-container");
        chatContainer.classList.remove("open");
        setTimeout(() => { chatContainer.style.display = "none"; }, 300);
        alert("Chat terminated. Refresh to start a new chat.");
    }

    function sendMessage(userInput) {
        let chatBox = document.getElementById("chat-box");
        
        let userMessage = document.createElement("div");
        userMessage.className = "message user-message";
        userMessage.innerHTML = "<strong>You:</strong> " + userInput;
        chatBox.appendChild(userMessage);
        
        let responses = {
            "delivery times": "Our standard delivery time is 3-5 business days.",
            "returns": "You can return any product within 30 days of purchase.",
            "refunds": "Refunds are processed within 5-7 business days after we receive the returned item.",
            "rate us": "How would you rate us? <div class='rating-stars'><span onclick='rate(1)'>★</span><span onclick='rate(2)'>★</span><span onclick='rate(3)'>★</span><span onclick='rate(4)'>★</span><span onclick='rate(5)'>★</span></div>",
            "contact us": "Need help? <a href='./contact.php' style='text-decoration: underline; font-weight: bold; color: blue;'>Contact Us</a>."
        };

        let response = responses[userInput.toLowerCase()] || "I'm sorry, I didn't understand that. Try selecting an option.";

        let botMessage = document.createElement("div");
        botMessage.className = "message bot-message";
        botMessage.innerHTML = "<strong>Bot:</strong> <span class='typing-indicator'><span></span><span></span><span></span></span>";
        chatBox.appendChild(botMessage);

        setTimeout(() => {
            botMessage.innerHTML = "<strong>Bot:</strong> " + response;
        }, 1500);

        chatBox.scrollTop = chatBox.scrollHeight;
    }

    function rate(stars) {
        let starElements = document.querySelectorAll('.rating-stars span');
        starElements.forEach((star, index) => {
            if (index < stars) {
                star.classList.add("active");
            } else {
                star.classList.remove("active");
            }
        });

        setTimeout(() => {
            alert("Thank you for rating us " + stars + " stars!");
        }, 300);
    }

    function handleKeyPress(event) {
        if (event.key === "Enter") {
            let userInput = document.getElementById("user-input").value.trim();
            if (userInput !== "") {
                document.getElementById("user-input").value = "";
                sendMessage(userInput);
            }
        }
    }
</script>
<?php require_once "../partials/footer.php" ?>

</body>
</html>
