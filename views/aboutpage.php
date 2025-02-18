<?php
session_start();
$pdo = require_once "../database/database.php"; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Gadget Garden</title>
    <?php require_once "../partials/header.php"; ?>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../public/css/about.css">
    <link rel="stylesheet" href="../public/css/navbar.css">
    <link rel="stylesheet" href="../public/css/styles.css">
</head>
<body>
    <nav>
        <div class="nav-left">
            <a href="../index.php"><p id="logo-text">GADGET GARDEN</p></a>
        </div>
        <div class="nav-right">
            <a href="../views/aboutpage.php"><button class="white-button">About Us</button></a>
            <?php if (!isset($_SESSION['user_id'])) { ?>
                <a href="./login.php"><button class="green-button">Login</button></a>
                <a href="./signup.php"><button class="white-button">Sign Up</button></a>
            <?php } else { ?>
                <a href="./basket.php"><button class="white-button">Basket</button></a>
                <a href="./contact.php"><button class="white-button">Contact Us</button></a>
                <a href="./profile.php"><button class="white-button">Profile</button></a>
                <a href="./logout.php"><button class="green-button">Logout</button></a>
            <?php } ?>
        </div>
    </nav>
    
    <section id="about-us">
        <div class="about-container">
            <div class="about-content green-box">
                <div class="highlight">
                    <h1 id="aboutustitle1">About Us</h1>
                    <p>Gadget Garden is a company that puts the planet first. Our mission is to inspire a sustainable future by crafting eco-friendly technology that blends seamlessly with modern life, prioritizing the planet and enhancing everyday experiences.</p>
                </div>
                <div class="highlight">
                    <h3>Our Values</h3>
                    <p>We believe in sustainability, innovation, and quality. Our products are designed to minimize environmental impact without compromising on functionality.</p>
                </div>
                <div class="highlight">
                    <h3>Our Vision</h3>
                    <p>We envision a world where technology and nature coexist harmoniously, empowering people to live better, greener lives.</p>
                </div>
                <div class="highlight">
                    <h3>Our Mission</h3>
                    <p>To create solutions that redefine how people interact with technology while protecting the environment for future generations.</p>
                </div>
            </div>
            <div class="about-images">
                <img src="../public/assets/world.png" alt="World illustration" class="about-image">
                <img src="../public/assets/Laptop.png" alt="Laptop illustration" class="about-image">
            </div>
        </div>
    </section>


    <div class="chat-icon" onclick="toggleChat()">💬</div>
    
    <div class="chat-container" id="chat-container">
        <div class="chat-box" id="chat-box"></div>
        <div class="chat-options">
            <p class="bot-message message"><strong>Bot:</strong> Select one of the following options:</p>
            <button onclick="sendMessage('delivery times')">Delivery Times</button>
            <button onclick="sendMessage('returns')">Returns</button>
            <button onclick="sendMessage('contact us')">Contact Us</button>
        </div>
        <input type="text" id="user-input" class="chat-input" placeholder="Type your question..." onkeypress="handleKeyPress(event)">
    </div>

    <script>
        function toggleChat() {
            let chatContainer = document.getElementById("chat-container");
            chatContainer.style.display = chatContainer.style.display === "none" ? "block" : "none";
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
                "contact us": "Please log in to access our contact page: <a href='login.php'>Login</a>"
            };
            
            let response = responses[userInput.toLowerCase()] || "I'm sorry, I didn't understand that. Try selecting an option above.";
            
            let botMessage = document.createElement("div");
            botMessage.className = "message bot-message";
            botMessage.innerHTML = "<strong>Bot:</strong> " + response;
            chatBox.appendChild(botMessage);
            
            chatBox.scrollTop = chatBox.scrollHeight;
        }

        function handleKeyPress(event) {
            if (event.key === "Enter") {
                let userInput = document.getElementById("user-input").value;
                document.getElementById("user-input").value = "";
                sendMessage(userInput);
            }
        }
    </script>
    
    <?php require_once '../partials/footer.php'; ?>
</body>
</html>
