<?php
session_start();
$pdo = require_once "../database/database.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["query_id"], $_POST["reply_message"])) {
    $query_id = $_POST["query_id"];
    $reply_message = trim($_POST["reply_message"]);

    if (!empty($reply_message)) {
        // Insert the reply into the database
        $stmt = $pdo->prepare("INSERT INTO replies (query_id, reply_message, created_at) VALUES (:query_id, :reply_message, NOW())");
        $stmt->bindParam(":query_id", $query_id);
        $stmt->bindParam(":reply_message", $reply_message);

        if ($stmt->execute()) {
            // Set success message in session
            $_SESSION["success_message"] = "Reply sent successfully!";
        } else {
            $_SESSION["error_message"] = "Failed to send reply. Please try again.";
        }
    } else {
        $_SESSION["error_message"] = "Reply cannot be empty.";
    }
}

// Redirect back to the admin page
header("Location: admin_contact.php");
exit();
?>
