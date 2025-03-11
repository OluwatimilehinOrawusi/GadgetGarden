<?php 
session_start();

$pdo = require_once "../database/database.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $query_id = $_POST['query_id'];
    $reply_message = $_POST['reply_message'];

    if (!empty($query_id) && !empty($reply_message)) {
        // Insert the reply into the replies table
        $stmt = $pdo->prepare("INSERT INTO replies (query_id, reply_message) VALUES (:query_id, :reply_message)");
        $stmt->bindParam(':query_id', $query_id);
        $stmt->bindParam(':reply_message', $reply_message);

        if ($stmt->execute()) {
            $_SESSION['success_message'] = "Reply sent successfully!";  // Ensure success message is set here
        } else {
            $_SESSION['error_message'] = "Failed to send the reply.";  // You can also add an error message if needed
        }
    } else {
        $_SESSION['error_message'] = "Please fill in all fields.";
    }
}

header("Location: admin_contact.php");  // Redirect to the admin contact page
exit();
?>
