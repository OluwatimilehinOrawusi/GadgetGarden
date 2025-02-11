<?php 
$pdo = require_once "../database/database.php";

$keyword = isset($_GET['search']) ? trim($_GET['search']) : null;
$category = isset($_GET['category']) ? trim($_GET['category']) : null;

if (!empty($category)) {
    // Fetch category ID safely
    $stmt = $pdo->prepare("SELECT category_id FROM categories WHERE name = ?");
    $stmt->execute([$category]);
    $categoryData = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($categoryData) {
        $category_id = $categoryData['category_id'];
        $stmt = $pdo->prepare("SELECT * FROM products WHERE category_id = ?");
        $stmt->execute([$category_id]);
    } else {
        echo "Category not found: " . htmlspecialchars($category);
    }
} elseif (!empty($keyword)) {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE name LIKE :keyword");
    $stmt->bindValue(":keyword", "%$keyword%");
    $stmt->execute();
} else {
    $stmt = $pdo->prepare("SELECT * FROM products");
    $stmt->execute();
}

$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <?php require_once "../partials/header.php"; ?>
    <link rel="stylesheet" href="../public/css/products.css">
</head>
<body>
    <?php require_once "../partials/navbar.php"; ?>
    
    <section id="header">
        <div id="search-bar-container">
            <h1 id="heading">Explore our products</h1>
            <form id="search-form">
                <input id="search-input" type="text" name="search">
            </form>
        </div>
    </section>

    <section id="products">
        <?php foreach ($products as $product) { ?>
            <div class="card">
                <div id="product-image-container">
                    <img class="product-images" src="<?php echo '../' . htmlspecialchars($product['image']); ?>">
                </div>
                <a href="<?php echo "./product.php?id=" . htmlspecialchars($product['product_id']); ?>">
                    <p><?php echo htmlspecialchars($product["name"]); ?></p>
                    <p>£<?php echo htmlspecialchars($product["price"]); ?></p>
                </a>
            </div>
        <?php } ?>
    </section>

    <?php require_once "../partials/footer.php"; ?>
</body>
</html>
