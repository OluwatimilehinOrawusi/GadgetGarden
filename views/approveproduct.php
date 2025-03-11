<?php require_once "../database/database.php";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['product_id'], $_POST['action'])) {
    $product_id = $_POST['product_id'];
    $action = $_POST['action'];

    if ($action === "approve") { $stmt = $pdo->prepare("SELECT * FROM upload_products WHERE product_id = ?");
        $stmt->execute([$product_id]);
        $product=$stmt->fetch(PDO::FETCH_ASSOC);

        if ($product) { $stmt = $pdo->prepare("
        INSERT INTO products(product_id,name,description,price,stock,state,category_id,image)
        VALUES (?,?,?,?,?,?,?,?)
        ");

        $stmt->execute([
            $product['product_id'],
            $product['name'],
            $product['description'],
            $product['price'],
            $product['quantity'],
            $product['condition'],
            $product['category_id'],
            $product['image_path'],


        ]);

        $stmt = $pdo->prepare("DELETE FROM upload_products WHERE product_id = ?"); $stmt->execute([$product_id]);
    }
}
else { $stmt = $pdo->prepare("DELETE FROM upload_products WHERE product_id = ?"); $stmt->execute([$product_id]); }

}
header("Location: ReviewCustomerUploads.php ");
exit();