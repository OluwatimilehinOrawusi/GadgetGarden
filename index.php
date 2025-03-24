<?php
// Session start
session_start();
// connects to database
$pdo = require_once "./database/database.php";

$user_id = $_SESSION['user_id'] ?? null;

// Fetch the admin status of the user
$stmt = $pdo->prepare("SELECT role FROM users WHERE user_id = :user_id");
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gadget Garden</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./public/css/navbar.css">
    <link rel="stylesheet" href="./public/css/styles.css">
    <link rel="stylesheet" href="./public/css/chatbot.css">
</head>
<body>

<!-- Navigation Bar -->
<nav>
    <div class="nav-left">
        <p id="logo-text">GADGET GARDEN</p>
    </div>
    <div class="nav-right">
        <a href="#categories"><button class="green-button">Categories</button></a>
        <a href="./views/aboutpage.php"><button class="white-button">About Us</button></a>

        <?php if (!isset($_SESSION['user_id'])) { ?>
            <a href="./views/login.php"><button class="green-button">Login</button></a>
            <a href="./views/signup.php"><button class="white-button">Sign Up</button></a>
        <?php } ?>

        <?php if (isset($_SESSION['user_id'])) { ?>
            <a href="./views/basket.php"><button class="green-button">Basket</button></a>
            <a href="./views/contact.php"><button class="green-button">Contact Us</button></a>
            <a href="./views/profile.php"><button class="white-button">Profile</button></a>

            <?php if ($user && ($user['role'] === 'admin' || $user['role'] === 'manager')){ ?>
                <a href="./views/dashboard.php"><button class="white-button">Admin Dashboard</button></a>
            <?php } ?>

            <a href="./views/logout.php"><button class="green-button">Logout</button></a>
        
        <?php } ?>
    </div>
</nav>

<!-- Hero Section -->
<section id="hero-section">
    <div id="landing-display-container">
        <div id="landing-display">
            <div id="hero-main-img" class="home-images">
                <h1 id="title-text">GADGET GARDEN</h1>
            </div>
            <div id="home-top-right" class="home-images"></div>
            <div id="home-bottom-right" class="home-images"></div>
        </div>
    </div>
</section>

<hr>

<!-- Trending Section -->
<section class="trending">
    <div id="trending-top">
        <h2 class="subtitle">Trending</h2>
        <div id="trending-buttons">
            <a href="./views/products.php?category=phones"><button class="green-button">PHONES</button></a>
            <a href="./views/products.php?category=laptops"><button class="white-button">LAPTOPS</button></a>
            <a href="./views/products.php?category=audio"><button class="green-button">AUDIO</button></a>
            <a href="./views/products.php?category=gaming"><button class="white-button">GAMING</button></a>
        </div>
    </div>

    <div id="trending-items">
            <div class="home-images"></div>
            <div class="home-images"></div>
            <div class="home-image"></div>
            <div class="home-images"></div>
            <div class="home-images"></div>
            <div class="home-images"></div>
            <div class="home-images"></div>
            <div class="home-images"></div>
            
        </div>

    <div id="shop-now-container">
        <a href="views/products.php"><button class="green-button" id="shop-now-button"> Find out more ↝</button></a>
    </div>
</section>

<hr>

<!-- Categories Section -->
<section id="categories">
    <div id="categories-top">
        <h2 class="subtitle">Explore our <span style="display: block">Categories</span></h2>
        <div id="category-button-container">
            <a href="./views/products.php?category=laptops"><button class="white-button category-buttons">LAPTOPS</button></a>
            <a href="./views/products.php?category=phones"><button class="green-button category-buttons">PHONES</button></a>
            <a href="./views/products.php?category=gaming"><button class="white-button category-buttons">GAMING</button></a>
            <a href="./views/products.php?category=wearables"><button class="green-button category-buttons">WEARABLES</button></a>
            <a href="./views/products.php?category=tablets"><button class="green-button category-buttons">TABLETS</button></a>
            <a href="./views/products.php?category=accessories"><button class="white-button category-buttons">ACCESSORIES</button></a>
            <a href="./views/products.php?category=computers"><button class="green-button category-buttons">COMPUTERS</button></a>
            <a href="./views/products.php?category=audio"><button class="white-button category-buttons">AUDIO</button></a>
        </div>
    </div>
    <div class="category-image">
        <div class="home-images" id="category-banner"></div>
    </div>
</section>

<hr>

<!-- Why Us Section -->
<section id="why-us">
    <h2 class="subtitle">Why Us</h2>
    <div class="why-us-cards">
        <div class="card">
            <div class="why-us-image why-us-secure home-images"></div>
            <p class="why-us-title">Secure</p>
        </div>
        <div class="card">
            <div class="why-us-image why-us-cheap home-images"></div>
            <p class="why-us-title">Cheap</p>
        </div>
        <div class="card">
            <div class="why-us-image why-us-eco home-images"></div>
            <p class="why-us-title">Eco-Friendly</p>
        </div>
    </div>
</section>

    

 <!----Chat bot script------>
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
            "contact us": "Need help? Send us an email at example@gmail.com or a call us on 123456789."
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



    
<?php require_once "./partials/Indexfooter.php"; ?>

</body>
</html>
