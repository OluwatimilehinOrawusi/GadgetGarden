<?php 
$pdo = require_once "../database/database.php";

$keyword = isset($_GET['search']) ? trim($_GET['search']) : null;
$category = isset($_GET['category']) ? trim($_GET['category']) : null;

if (!empty($category)) {
    // Fetch category ID safely
    $stmt = $pdo->prepare("SELECT category_id FROM categories WHERE name = :category");
    $stmt->bindParam(":category", $category, PDO::PARAM_STR);
    $stmt->execute();
    $categoryData = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($categoryData) {
        $category_id = $categoryData['category_id'];
        $stmt = $pdo->prepare("SELECT * FROM products WHERE category_id = :category_id");
        $stmt->bindParam(":category_id", $category_id, PDO::PARAM_INT);
        $stmt->execute();
    } else {
        $products = []; // No products found for the category
    }
} elseif (!empty($keyword)) {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE name LIKE :keyword");
    $stmt->bindValue(":keyword", "%$keyword%", PDO::PARAM_STR);
    $stmt->execute();
} else {
    $stmt = $pdo->prepare("SELECT * FROM products");
    $stmt->execute();
}

$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once "../partials/header.php"; ?>
    <link rel="stylesheet" href="../public/css/products.css">
</head>
<body>
    <?php require_once "../partials/navbar.php"; ?>
    
    <section id="header">
        <div id="search-bar-container">
            <h1 id="heading">Explore our products</h1>
            <form id="search-form" method="GET" action="products.php">
                <input id="search-input" type="text" name="search" placeholder="Search for products...">
                <button type="submit">Search</button>
            </form>
        </div>
    </section>

    <section id="products">
        <?php if (empty($products)) : ?>
            <p>No products found.</p>
        <?php else : ?>
            <?php foreach ($products as $product) { ?>
                <div class="card">
                    <div id="product-image-container">
                        <img class="product-images" src="<?php echo htmlspecialchars($product['image']); ?>" alt="Product Image">
                    </div>
                    <a href="./product.php?id=<?php echo htmlspecialchars($product['product_id']); ?>">
                        <p><?php echo htmlspecialchars($product["name"]); ?></p>
                        <p>£<?php echo htmlspecialchars($product["price"]); ?></p>
                    </a>
                </div>
            <?php } ?>
        <?php endif; ?>
    </section>

    <?php require_once "../partials/footer.php"; ?>
</body>
</html>
