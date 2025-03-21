<?php session_start();
$pdo = require_once "../database/database.php";
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if (isset($_GET['product_id'])) {
    $user_id = $_SESSION['user_id'];
    $product_id = $_GET['product_id'];

    $stmt = $pdo->prepare("DELETE FROM wishlist WHERE user_id = :user_id AND product_id = :product_id");
    $stmt -> execute([
        'user_id' => $user_id, 'product_id' => $product_id ]);
}

header("Location: wishlist.php");
exit; ?>