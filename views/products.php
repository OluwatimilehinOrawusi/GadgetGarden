<?php 
$pdo = require_once "../database/database.php";

$keyword = $_GET['search'] ?? null;
$category = $_GET['category'] ?? null;

if (!empty($category)) {
    $query = "SELECT category_id FROM categories WHERE name = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$category]);
    $category = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($category) {
        $category_id = $category['category_id'];
        $query = "SELECT * FROM products WHERE category_id = ?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$category_id]);
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        echo "<p style='color:red; text-align:center;'>Category not found.</p>";
        $products = [];
    }
} elseif ($keyword) {
    $statement = $pdo->prepare("SELECT * FROM products WHERE name LIKE :keyword");
    $statement->bindValue(":keyword", "%$keyword%");
    $statement->execute();
    $products = $statement->fetchAll(PDO::FETCH_ASSOC);
} else {
    $statement = $pdo->prepare("SELECT * FROM products");
    $statement->execute();
    $products = $statement->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html>
<head>
    <?php require_once "../partials/header.php"; ?>
    <link rel="stylesheet" href="../public/css/products.css">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        #header {
            text-align: center;
            padding: 20px;
            background: #1E5631;
            color: white;
        }

        #search-bar-container {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 10px;
        }

        #search-input {
            width: 60%;
            padding: 10px;
            font-size: 16px;
            border: 2px solid #fff;
            border-radius: 5px;
        }

        .search-btn {
            background: #145A32;
            color: white;
            border: none;
            padding: 10px 15px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
            margin-left: 5px;
            transition: 0.3s;
        }

        .search-btn:hover {
            background: #117A3D;
        }

        #products {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            padding: 20px;
        }

        .card {
            background: white;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            margin: 15px;
            width: 250px;
            text-align: center;
            transition: transform 0.3s;
        }

        .card:hover {
            transform: scale(1.05);
        }

        .product-images {
            max-width: 100%;
            height: auto;
            border-radius: 5px;
        }

        .product-details {
            font-size: 16px;
            color: #333;
            font-weight: bold;
            margin: 10px 0;
        }
    </style>
</head>
<body>

<?php require_once "../partials/navbar.php"; ?>

<section id="header">
    <h1>Explore Our Products</h1>
    <div id="search-bar-container">
        <form id="search-form" method="GET">
            <input id="search-input" type="text" name="search" placeholder="Search products..." value="<?php echo htmlspecialchars($keyword); ?>">
            <button type="submit" class="search-btn">Search</button>
        </form>
    </div>
</section>

<section id="products">
    <?php if (empty($products)) : ?>
        <p style="text-align:center; color:red; font-size:18px;">No products found.</p>
    <?php else: ?>
        <?php foreach ($products as $product): ?>
            <div class="card">
                <div id="product-image-container">
                    <img class="product-images" src="<?php echo '../' . htmlspecialchars($product['image']); ?>" alt="Product Image">
                </div>
                <div class="product-details">
                    <a href="product.php?id=<?php echo $product['product_id']; ?>">
                        <p><?php echo htmlspecialchars($product["name"]); ?></p>
                        <p>£<?php echo number_format($product["price"], 2); ?></p>
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</section>

<?php require_once "../partials/footer.php"; ?>

</body>
</html>
