<?php
session_start();

$pdo = require_once "./database/database.php";

$user_id = $_SESSION['user_id'] ?? null;

// Fetch the admin status of the user
$stmt = $pdo->prepare("SELECT admin FROM users WHERE user_id = :user_id");
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

            <?php if ($user && $user['admin']) { ?>
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
                "contact us": "Please log in to access our contact page: <a href='./login.php'>Login</a>"
            };
            
            let response = responses[userInput.toLowerCase()] || "I'm sorry, I didn't understand that. Try selecting an option above.";
            
            let botMessage = document.createElement("div");
            botMessage.className = "message bot-message";
            botMessage.innerHTML = "<strong>Bot:</strong> " + response;
            chatBox.appendChild(botMessage);
            
            chatBox.scrollTop = chatBox.scrollHeight;
            setTimeout(showFeedbackForm, 2000); // Show feedback form after a delay
        }

        function handleKeyPress(event) {
            if (event.key === "Enter") {
                let userInput = document.getElementById("user-input").value;
                document.getElementById("user-input").value = "";
                sendMessage(userInput);
            }
        }
  
     function showFeedbackForm() {
        document.getElementById("feedback-form").style.display = "block";
    }

    function submitFeedback() {
        let rating = document.querySelector('input[name="rating"]:checked');
        let comment = document.getElementById("feedback-comment").value;
        
        if (!rating) {
            alert("Please provide a rating before submitting your feedback.");
            return;
        }
        
        alert("Thank you for your feedback!\nRating: " + rating.value + "\nComment: " + comment);
        
        document.getElementById("feedback-form").style.display = "none";
    }
</script>

<div id="feedback-form" style="display:none; margin-top: 20px;">
    <h3>Rate your experience</h3>
    <div>
        <input type="radio" name="rating" value="1"> 1
        <input type="radio" name="rating" value="2"> 2
        <input type="radio" name="rating" value="3"> 3
        <input type="radio" name="rating" value="4"> 4
        <input type="radio" name="rating" value="5"> 5
    </div>
    <textarea id="feedback-comment" placeholder="Leave your comment here..." rows="4" style="width: 100%; margin-top: 10px;"></textarea>
    <button onclick="submitFeedback()">Submit Feedback</button>
</div>

    
<?php require_once "./partials/footer.php"; ?>

</body>
</html>
