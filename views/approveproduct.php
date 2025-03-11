<?php require_once "../database/database.php";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['product_id'], $_POST['action'])) {
    $product_id = $_POST['product_id'];
    $action = $_POST['action'];

    if ($action === "approve") { $stmt = $pdo->prepare("SELECT * FROM upload_products WHERE product_id = ?");
        $stmt->execute([$product_id]);
        $product=$stmt->fetch(PDO::FETCH_ASSOC);

        if ($product) { $stmt = $pdo->prepare("
        INSERT INTO products(user_id,product_name,price,quantity`condition`,description,image_path,category_id)
        VALUES (?,?,?,?,?,?,?,?)
        ");

        $stmt->execute([
            $product['user_id'],
            $product['name'],
            $product['price'],
            $product['quantity'],
            $product['condition'],
            $product['description'],
            $product['image_path'],
            $product['category_id']

        ]);

        $stmt = $pdo->prepare("DELETE FROM upload_products WHERE product_id = ?"); $stmt->execute([$product_id]);
    }
}
else { $stmt = $pdo->prepare("DELETE FROM upload_products WHERE product_id = ?"); $stmt->execute([$product_id]); }

}
header("Location: ReviewCustomerUploads.php ");
exit();