<?php
//Session start
session_start();

//Conncects to database
$pdo = require_once "../database/database.php";

$user = null;
if (isset($_SESSION['user_id'])) {
    $stmt = $pdo->prepare("SELECT role FROM users WHERE user_id = :user_id");
    $stmt->execute(['user_id' => $_SESSION['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
}

$user_id = $_SESSION['user_id'] ?? null;

//If user is not logged in redirect to log in page
if (!$user_id) {
    header("Location: login.php");
    exit();
}

// Fetch user's previous orders for dropdown
$stmt = $pdo->prepare("SELECT order_id FROM orders WHERE user_id = :user_id");
$stmt->execute([':user_id' => $user_id]);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle return request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_return'])) {
    $order_id = $_POST['order_id'] ?? '';
    $reason = htmlspecialchars(trim($_POST['reason']));
    $details = htmlspecialchars(trim($_POST['details']));

    if ($order_id && $reason) {
        // Insert return request
        $stmt = $pdo->prepare("INSERT INTO returns (user_id, order_id, reason, details) VALUES (:user_id, :order_id, :reason, :details)");
        $stmt->execute([
            ':user_id' => $user_id,
            ':order_id' => $order_id,
            ':reason' => $reason,
            ':details' => $details
        ]);

        // Update return status in orders table
        $stmt = $pdo->prepare("UPDATE orders SET return_status = 'Pending' WHERE order_id = :order_id");
        $stmt->execute([':order_id' => $order_id]);

        //To run if return order was successful
        $success_message = "Your return request has been submitted.";
    } else {
        //To run if return order was unsuccessful
        $error_message = "Please select an order and provide a reason.";
    }
}
?>


<!-- HTML -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Return Order - Gadget Garden</title>
    
    <!-- Header link -->
    <?php require_once "../partials/header.php"; ?>
    
    <!-- Styles sheet links -->
    <link rel="stylesheet" href="../public/css/navbar.css">
    <link rel="stylesheet" href="../public/css/styles.css">
    <link rel="stylesheet" href="../public/css/returnOrder.css">
    <link rel="stylesheet" href="../public/css/chatbot.css">


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
                

<main class="container">
    <!-- main header -->
    <h1>Return an Order</h1>

    <?php if (!empty($success_message)): ?>
        <p class="success"><?= $success_message; ?></p>
    <?php elseif (!empty($error_message)): ?>
        <p class="error"><?= $error_message; ?></p>
    <?php endif; ?>

    <!-- Select order drop down -->
    <form action="" method="POST" class="return-form">
        <div class="form-group">
            <label for="order_id">Select an Order to Return:</label>
            <select id="order_id" name="order_id" required>
                <option value="">Select an order</option>
                <?php foreach ($orders as $order): ?>
                    <option value="<?= htmlspecialchars($order['order_id']); ?>">
                        Order #<?= htmlspecialchars($order['order_id']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>


        <!-- Return reasons drop down -->
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

        <!-- Submit button -->
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

<?php require_once "../partials/footer.php"; ?>
</body>
</html>
