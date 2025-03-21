<?php
session_start();

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $pdo = require_once "../database/database.php";
    $query_id = $_POST['query_id'] ??  null;

    if($query_id) {
        $stmt =$pdo->prepare("DELETE FROM contact WHERE query_id = :query_id");
        $stmt->bindParam(':query_id', $query_id);

        if($stmt->execute()) {
            $_SESSION['success_message'] = "Message deleted successfully";
        } else {
            $_SESSION['error_message'] = "Failed to delete message";
        }
    }

    header("Location: admin_contact.php");
    exit();
}
?>