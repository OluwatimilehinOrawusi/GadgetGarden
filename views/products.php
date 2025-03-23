<?php 


//Connects to the database
$pdo = require_once "../database/database.php" ;

$keyword = $_GET['search'] ?? null;

$category = $_GET['category'] ?? null;


if (!empty($category)) {
        // Step 1: Fetch the category ID from the category_database table
        $query = "SELECT category_id FROM categories WHERE name = ?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$category]);
        $category = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($category) {
            $category_id = $category['category_id'];

            // Step 2: Fetch all products with the matching category ID
            $query = "SELECT * FROM products WHERE category_id = ?";
            $stmt = $pdo->prepare($query);
            $stmt->execute([$category_id]);
            $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Products are now stored in the $products variable
            // Use or return $products as needed in your application
        } else {
            echo "Category not found: " . htmlspecialchars($category_name);
        }
    } elseif ($keyword){
    $statement = $pdo->prepare('SELECT * FROM products WHERE name   like :keyword');
    $statement->bindValue(":keyword", "%$keyword%");
    $statement->execute();
    $products = $statement->fetchAll(PDO::FETCH_ASSOC);
    }else{
    $statement = $pdo->prepare('SELECT * FROM products');
    $statement->execute();
    $products = $statement->fetchAll(PDO::FETCH_ASSOC);
}

?>

<!-- HTML -->
<!DOCTYPE html>
<html>
    <head>
    <?php require_once "../partials/header.php" ?>
    <link rel="stylesheet" href="../public/css/products.css">
    <link rel="stylesheet" href="../public/css/chatbot.css">
    </head>
    <body>
        
    <!-- NAV BAR -->
    <?php require_once "../partials/navbar.php" ?>

    <section id="header">
        <div id="search-bar-container">
        <h1 id="heading">Explore our products</h1>
            <form id="search-form">
                <input id="search-input" type="text" name="search">
            </form>
        
        
        </div>
        
    </section>
    <section id="products">

        <?php foreach($products as $i => $product){ ?>
            
                <div class="card">
                <a href="<?php echo "./product.php?id=" .$product['product_id']?>" class="clickable-div">
                    <div id="product-image-container">
                    <img class="product-images" src="<?php echo $product['image']?>">
                    </div>
                    
                    <p><?php echo $product["name"] ?></p>
                    <p>£<?php echo $product["price"] ?></p>
                    </a>
                </div>
            
        <?php } ?>
    </section>
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
