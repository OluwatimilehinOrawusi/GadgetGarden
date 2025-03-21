<?php
session_start();

// Require the database connection
$pdo = require_once "../database/database.php";

// Handle return form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_return'])) {
    $order_id = htmlspecialchars(trim($_POST['order_id']));
    $reason = htmlspecialchars(trim($_POST['reason']));
    $details = htmlspecialchars(trim($_POST['details']));
    $user_id = $_SESSION['user_id'] ?? null;

    // Validate form inputs
    if ($user_id && $order_id && $reason) {
        $stmt = $pdo->prepare("INSERT INTO returns (user_id, order_id, reason, details) VALUES (:user_id, :order_id, :reason, :details)");
        $stmt->execute([
            ':user_id' => $user_id,
            ':order_id' => $order_id,
            ':reason' => $reason,
            ':details' => $details
        ]);
        $success_message = "Your return request has been submitted.";
    } else {
        $error_message = "Please fill in all required fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Return Order - Gadget Garden</title>
        <?php require_once "../partials/header.php"; ?>
        <link rel="stylesheet" href="../public/css/returnOrder.css">
        <link rel="stylesheet" href="../public/css/navbar.css">
        <link rel="stylesheet" href="../public/css/styles.css">
        <link rel="stylesheet" href="../public/css/chatbot.css">


    </head>
    <body>

    <nav>
            <div class="nav-left">
                <a href="../index.php"><p id="logo-text">GADGET GARDEN</p></a>
            </div>
            <div class="nav-right">
                <a href="../views/aboutpage.php"><button class="white-button">About Us</button></a>
                <?php if (!isset($_SESSION['user_id'])){?>
                <?php echo '<a href="./login.php"><button class="green-button">Login</button></a>' ?>
                 <?php echo '<a href="./signup.php"><button class="white-button">Sign Up</button></a> '?>
                <?php }?>
                <?php if (isset($_SESSION['user_id'])){?>
                <?php echo '<a href="./basket.php"><button class="white-button">Basket</button></a>' ?>
                <?php echo '<a href="./contact.php"><button class="white-button">Contact us</button></a>' ?>
                <?php echo '<a href = "./profile.php"><button class ="white-button">Profile</button></a>' ?>
                <?php echo '<a href="./logout.php"><button class="green-button">Logout</button></a>' ?>

                <?php }?>

            </div>
</nav>       

        <main class="container">
            <h1>Return an Order</h1>
            <?php if (!empty($success_message)): ?>
                <p class="success"><?= $success_message; ?></p>
            <?php elseif (!empty($error_message)): ?>
                <p class="error"><?= $error_message; ?></p>
            <?php endif; ?>

            <form action="" method="POST" class="return-form">
                <div class="form-group">
                    <label for="order_id">Order ID:</label>
                    <input type="text" id="order_id" name="order_id" required>
                </div>
                <div class="form-group">
                    <label for="reason">Reason for Return:</label>
                    <select id="reason" name="reason" required>
                        <option value="">Select a reason</option>
                        <option value="Damaged item">Damaged item</option>
                        <option value="Wrong item sent">Wrong item sent</option>
                        <option value="Not satisfied">Not satisfied</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="details">Additional Details:</label>
                    <textarea id="details" name="details" rows="5"></textarea>
                </div>
                <button type="submit" name="submit_return">Submit Return</button>
            </form>
        </main>

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

    <!-----Links the footer partial to the page----->
        <?php require_once "../partials/footer.php"; ?>
    </body>
</html>
