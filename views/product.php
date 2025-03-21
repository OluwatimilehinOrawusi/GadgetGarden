<?php
// Connect to Database
$pdo = require_once "../database/database.php";

// Validate and sanitize the product ID from the URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid product ID.");
}

// Fetch product details from database
$id = intval($_GET['id']);
$statement = $pdo->prepare('SELECT * FROM products WHERE product_id = :id');
$statement->bindValue(":id", $id, PDO::PARAM_INT);
$statement->execute();
$product = $statement->fetch(PDO::FETCH_ASSOC);
// Check if the product exists
if (!$product) {
    die("Product not found.");
}

// Assign stock value safely
$stockQuantity = isset($product['stock']) ? intval($product['stock']) : 0;
$reviewStmt = $pdo->prepare("
    SELECT r.rating, r.review_text AS comment, r.created_at AS review_date, u.username
    FROM reviews r
    JOIN users u ON r.user_id = u.user_id
    WHERE r.product_id = ?
    ORDER BY r.created_at DESC
");

$reviewStmt->execute([$id]);
$reviews = $reviewStmt->fetchAll(PDO::FETCH_ASSOC);

// Calculate average rating
$averageStmt = $pdo->prepare("SELECT AVG(rating) AS avg_rating FROM reviews WHERE product_id = ?");
$averageStmt->execute([$id]);
$avgResult = $averageStmt->fetch(PDO::FETCH_ASSOC);
$averageRating = isset($avgResult['avg_rating']) ? round($avgResult['avg_rating'], 1) : 0;

// Function to generate star ratings
function displayStars($rating) {
    $fullStars = floor($rating);
    $halfStar = ($rating - $fullStars) >= 0.5 ? 1 : 0;
    $emptyStars = 5 - $fullStars - $halfStar;
    return str_repeat('★', $fullStars) . str_repeat('☆', $halfStar) . str_repeat('☆', $emptyStars);
}
?>





<!-----HTML------->
<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once "../partials/header.php"; ?>
    <link rel="stylesheet" href="../public/css/product.css">
</head>
<body>
    <?php require_once "../partials/navbar.php"; ?>
    <div class="product-container">
        <div class="product-image">
            <img src="<?php echo htmlspecialchars($product["image"]); ?>" alt="Product Image">
        </div>

        <!-----Product information----->
        <div class="product-data">
            <h1 class="product-name"><?php echo htmlspecialchars($product["name"]); ?></h1>
            <p class="product-description"><?php echo htmlspecialchars($product["description"]); ?></p>
            <p class="product-condition">Condition: <?php echo htmlspecialchars($product["state"]); ?></p>
            <p class="product-price">£<?php echo htmlspecialchars($product["price"]); ?></p>
            
            <!-----Product normal stock----->
            <?php if ($stockQuantity > 3) : ?>
                <a href="add-products.php?product_id=<?php echo $product["product_id"]; ?>">
                    <button class="green-button">Add to Basket</button>
                </a>
                <a href="bookmark.php?product_id=<?php echo $product["product_id"] ?>"><button class="green-button">Bookmark Item</button></a>

            <!-----Product Low stock----->    
            <?php elseif ($stockQuantity > 0) : ?>
                <p class="low-stock-warning">Only <?php echo $stockQuantity; ?> left in stock!</p>
                <a href="add-products.php?product_id=<?php echo $product["product_id"]; ?>">
                    <button class="green-button">Add to Basket</button>
                </a>
                <a href="bookmark.php?product_id=<?php echo $product["product_id"] ?>"><button class="green-button">Bookmark Item</button></a>

            <!-----Product out of stock----->
            <?php else: ?>
                <p class="out-of-stock-warning"> Out of Stock</p>
                <button class="out-of-stock-button" disabled>Out of Stock</button>
                <a href="bookmark.php?product_id=<?php echo $product["product_id"] ?>"><button class="green-button">Bookmark Item</button></a>
            <?php endif; ?>
            
            <a href="./reviewPage.php?id=<?php echo $id; ?>"><u>Write a review</u></a>
        </div>
    </div>

    <!-----Review Section----->
    <div class="reviews-section">
        <h2>Customer Reviews</h2>
        <?php if ($averageRating > 0) : ?>
            <div class="average-rating">
                <strong>Average Rating:</strong>
                <span class="star-rating"><?php echo displayStars($averageRating); ?></span>
                (<?php echo $averageRating; ?> / 5)
            </div>
        <?php else : ?>
            <p>No reviews yet. Be the first to leave a review!</p>
        <?php endif; ?>
        <?php if (!empty($reviews)) : ?>
            <?php foreach ($reviews as $review) : ?>
                <div class="review-card">
                    <p><strong><?php echo htmlspecialchars($review["username"]); ?></strong> -
                    <span class="star-rating"><?php echo displayStars($review["rating"]); ?></span></p>
                    <p><?php echo htmlspecialchars($review["comment"]); ?></p>
                    <p class="review-date"><?php echo htmlspecialchars($review["review_date"]); ?></p>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

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

    
<!-- Chat Icon -->
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
