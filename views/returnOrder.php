<?php
session_start();
$pdo = require_once "../database/database.php";

$user_id = $_SESSION['user_id'] ?? null;

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

        $success_message = "Your return request has been submitted.";
    } else {
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
        <a href="../index.php"><p id="logo-text">GADGET GARDEN</p></a>
    </div>
    <div class="nav-right">
        <a href="../views/aboutpage.php"><button class="white-button">About Us</button></a>
        <?php if (!isset($_SESSION['user_id'])): ?>
            <a href="./login.php"><button class="green-button">Login</button></a>
            <a href="./signup.php"><button class="white-button">Sign Up</button></a>
        <?php else: ?>
            <a href="./basket.php"><button class="white-button">Basket</button></a>
            <a href="./contact.php"><button class="white-button">Contact us</button></a>
            <a href="./profile.php"><button class="white-button">Profile</button></a>
            <a href="./logout.php"><button class="green-button">Logout</button></a>
        <?php endif; ?>
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

        <button type="submit" name="submit_return">Submit Return</button>
    </form>
</main>

<!-- Chat Icon -->
<div class="chat-icon" onclick="toggleChat()">💬</div>

<!-- Chat Container -->
<div class="chat-container" id="chat-container">
    <div class="chat-header">
        <span>Chatbot</span>
        <button onclick="closeChat()">❌</button>
    </div>
    <div class="chat-box" id="chat-box"></div>
    <div class="chat-options">
        <button onclick="sendMessage('returns')">Returns</button>
        <button onclick="sendMessage('refunds')">Refunds</button>
        <button onclick="sendMessage('delivery')">Delivery</button>
    </div>
    <input type="text" id="user-input" class="chat-input" placeholder="Type here..." onkeypress="handleKeyPress(event)">
</div>

<script>
    function toggleChat() {
        let chatContainer = document.getElementById("chat-container");
        chatContainer.style.display = chatContainer.style.display === "block" ? "none" : "block";
    }

    function closeChat() {
        document.getElementById("chat-container").style.display = "none";
    }

    function sendMessage(userInput) {
        let chatBox = document.getElementById("chat-box");

        let userMessage = document.createElement("div");
        userMessage.className = "message user-message";
        userMessage.innerHTML = "<strong>You:</strong> " + userInput;
        chatBox.appendChild(userMessage);

        let responses = {
            "returns": "You can return items within 30 days of purchase.",
            "refunds": "Refunds take 5-7 business days after return approval.",
            "delivery": "Delivery takes 3-5 business days."
        };

        let botMessage = document.createElement("div");
        botMessage.className = "message bot-message";
        botMessage.innerHTML = "<strong>Bot:</strong> " + (responses[userInput.toLowerCase()] || "I'm not sure, please contact support.");
        chatBox.appendChild(botMessage);

        chatBox.scrollTop = chatBox.scrollHeight;
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
