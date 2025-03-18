<?php
session_start();
$pdo = require_once "../database/database.php";

// Check if the user is logged in and is an admin or manager
$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    header("Location: ../login.php");
    exit();
}

// Handle the form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reply_message']) && isset($_POST['query_id'])) {
    $reply_message = $_POST['reply_message'];
    $query_id = $_POST['query_id'];

    // Insert the reply into the `replies` table
    $stmt = $pdo->prepare("INSERT INTO replies (query_id, reply_message, created_at) VALUES (:query_id, :reply_message, NOW())");
    $stmt->bindParam(':query_id', $query_id, PDO::PARAM_INT);
    $stmt->bindParam(':reply_message', $reply_message, PDO::PARAM_STR);
    $stmt->execute();

    // Update the status of the message in the contact table to 'replied'
    $stmt = $pdo->prepare("UPDATE contact SET status = 'replied' WHERE id = :query_id");
    $stmt->bindParam(':query_id', $query_id, PDO::PARAM_INT);
    $stmt->execute();

    // Redirect back to the admin contact page with a success message
    $_SESSION['success_message'] = "Reply sent successfully.";
    header('Location: admin_contact.php');
    exit();
}
?>
