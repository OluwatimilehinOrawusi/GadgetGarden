<?php 



$pdo = require_once "../database/database.php" ;

$keyword = $_GET['search'] ?? null;

$category = $_GET['category'] ?? null;


if (!empty($category)) {
        // Step 1: Fetch the category ID from the category_database table
        $query = "SELECT category_id FROM categories WHERE name = ?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$category]);
        $category = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($category) {
            $category_id = $category['category_id'];

            // Step 2: Fetch all products with the matching category ID
            $query = "SELECT * FROM products WHERE category_id = ?";
            $stmt = $pdo->prepare($query);
            $stmt->execute([$category_id]);
            $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Products are now stored in the $products variable
            // Use or return $products as needed in your application
        } else {
            echo "Category not found: " . htmlspecialchars($category_name);
        }
    } elseif ($keyword){
    $statement = $pdo->prepare('SELECT * FROM products WHERE name   like :keyword');
    $statement->bindValue(":keyword", "%$keyword%");
    $statement->execute();
    $products = $statement->fetchAll(PDO::FETCH_ASSOC);
    }else{
    $statement = $pdo->prepare('SELECT * FROM products');
    $statement->execute();
    $products = $statement->fetchAll(PDO::FETCH_ASSOC);
}






?>
