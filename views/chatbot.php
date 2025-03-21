<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chatbot</title>
    <link rel="stylesheet" href="chatbot.css"> <!-- Link to the external CSS file -->
</head>
<body>

    <!-- Chatbot Button -->
    <div class="chat-icon" onclick="toggleChat()">💬</div>

    <!-- Chatbot Container -->
    <div class="chat-container" id="chat-container">
        <div class="chat-header">
            <h3>Chatbot</h3>
            <button onclick="minimizeChat()">➖</button>
            <button onclick="closeChat()">✖</button>
        </div>

        <div class="chat-box" id="chat-box"></div>

        <div class="chat-options">
            <p class="bot-message"><strong>Bot:</strong> Select an option:</p>
            <button onclick="sendMessage('delivery times')">Delivery Times</button>
            <button onclick="sendMessage('returns')">Returns</button>
            <button onclick="sendMessage('refunds')">Refunds</button>
            <button onclick="sendMessage('rate us')">Rate Us</button>
            <button onclick="sendMessage('contact us')">Contact Us</button>
        </div>

        <input type="text" id="user-input" class="chat-input" placeholder="Type your question..." onkeypress="handleKeyPress(event)">
    </div>

    <script>
        function toggleChat() {
            let chatContainer = document.getElementById("chat-container");
            chatContainer.style.display = (chatContainer.style.display === "none" || chatContainer.style.display === "") ? "block" : "none";
        }

        function minimizeChat() {
            let chatContainer = document.getElementById("chat-container");
            chatContainer.style.height = "50px";
            chatContainer.querySelector(".chat-box").style.display = "none";
            chatContainer.querySelector(".chat-options").style.display = "none";
            chatContainer.querySelector(".chat-input").style.display = "none";
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
                "delivery times": "Our standard delivery time is 3-5 business days.",
                "returns": "You can return any product within 30 days of purchase.",
                "refunds": "Refunds are processed within 5-7 business days.",
                "rate us": "How would you rate us? ⭐⭐⭐⭐⭐",
                "contact us": "<a href='contact.php' class='chat-link'>Visit our contact page</a>"
            };

            let response = responses[userInput.toLowerCase()] || "I didn't understand that. Try selecting an option above.";

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

</body>
</html>
