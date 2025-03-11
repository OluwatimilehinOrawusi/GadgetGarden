<?php 
session_start();

$pdo = require_once "../database/database.php";

$user_id = $_SESSION['user_id'] ?? null;
$stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = :user_id");
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {  // Remove the semicolon here
    $query_id = $_POST['query_id'];
    $reply_message = $_POST['reply_message'];

    if (!empty($query_id) && !empty($reply_message)) {
        // Insert the reply into the replies table
        $stmt = $pdo->prepare("INSERT INTO replies (query_id, reply_message) VALUES (:query_id, :reply_message)");
        $stmt->bindParam(':query_id', $query_id);
        $stmt->bindParam(':reply_message', $reply_message);

        if ($stmt->execute()) {
            $_SESSION['success'] = "Reply sent successfully!";
        } else {
            $_SESSION['error'] = "Failed to send the reply.";
        }
    } else {
        $_SESSION['error'] = "Please fill in all fields.";
    }
}

header("Location: admin_contact.php");
exit();
?>
