<?php
session_start();

$pdo = require_once "../database/database.php";

$user_id = $_SESSION['user_id'] ?? null;

// Fetch the admin status of the user
$stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = :user_id");
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user || ($user['role'] !== 'admin' && $user['role'] !== 'manager')) {
header("Location: ../index.php");
exit();
}

