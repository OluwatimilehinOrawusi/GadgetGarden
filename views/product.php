<?php
// Connect to Database
$pdo = require_once "../database/database.php";

// Validate and sanitize the product ID from the URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid product ID.");
}

// Fetch product details from database
$id = intval($_GET['id']);
$statement = $pdo->prepare('SELECT * FROM products WHERE product_id = :id');
$statement->bindValue(":id", $id, PDO::PARAM_INT);
$statement->execute();
$product = $statement->fetch(PDO::FETCH_ASSOC);
// Check if the product exists
if (!$product) {
    die("Product not found.");
}

// Assign stock value safely
$stockQuantity = isset($product['stock']) ? intval($product['stock']) : 0;
$reviewStmt = $pdo->prepare("
    SELECT r.rating, r.review_text AS comment, r.created_at AS review_date, u.username
    FROM reviews r
    JOIN users u ON r.user_id = u.user_id
    WHERE r.product_id = ?
    ORDER BY r.created_at DESC
");

$reviewStmt->execute([$id]);
$reviews = $reviewStmt->fetchAll(PDO::FETCH_ASSOC);

// Calculate average rating
$averageStmt = $pdo->prepare("SELECT AVG(rating) AS avg_rating FROM reviews WHERE product_id = ?");
$averageStmt->execute([$id]);
$avgResult = $averageStmt->fetch(PDO::FETCH_ASSOC);
$averageRating = isset($avgResult['avg_rating']) ? round($avgResult['avg_rating'], 1) : 0;

// Function to generate star ratings
function displayStars($rating) {
    $fullStars = floor($rating);
    $halfStar = ($rating - $fullStars) >= 0.5 ? 1 : 0;
    $emptyStars = 5 - $fullStars - $halfStar;
    return str_repeat('★', $fullStars) . str_repeat('☆', $halfStar) . str_repeat('☆', $emptyStars);
}
?>





<!-----HTML------->
<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once "../partials/header.php"; ?>
    <link rel="stylesheet" href="../public/css/product.css">
</head>
<body>
    <?php require_once "../partials/navbar.php"; ?>
    <div class="product-container">
        <div class="product-image">
            <img src="<?php echo htmlspecialchars($product["image"]); ?>" alt="Product Image">
        </div>

        <!-----Product information----->
        <div class="product-data">
            <h1 class="product-name"><?php echo htmlspecialchars($product["name"]); ?></h1>
            <p class="product-description"><?php echo htmlspecialchars($product["description"]); ?></p>
            <p class="product-condition">Condition: <?php echo htmlspecialchars($product["state"]); ?></p>
            <p class="product-price">£<?php echo htmlspecialchars($product["price"]); ?></p>
            
            <!-----Product normal stock----->
            <?php if ($stockQuantity > 3) : ?>
                <a href="add-products.php?product_id=<?php echo $product["product_id"]; ?>">
                    <button class="green-button">Add to Basket</button>
                </a>

            <!-----Product Low stock----->    
            <?php elseif ($stockQuantity > 0) : ?>
                <p class="low-stock-warning">Only <?php echo $stockQuantity; ?> left in stock!</p>
                <a href="add-products.php?product_id=<?php echo $product["product_id"]; ?>">
                    <button class="green-button">Add to Basket</button>
                </a>

            <!-----Product out of stock----->
            <?php else: ?>
                <p class="out-of-stock-warning"> Out of Stock</p>
                <button class="out-of-stock-button" disabled>Out of Stock</button>
            <?php endif; ?>
            <a href="./reviewPage.php?id=<?php echo $id; ?>"><u>Write a review</u></a>
        </div>
    </div>

    <!-----Review Section----->
    <div class="reviews-section">
        <h2>Customer Reviews</h2>
        <?php if ($averageRating > 0) : ?>
            <div class="average-rating">
                <strong>Average Rating:</strong>
                <span class="star-rating"><?php echo displayStars($averageRating); ?></span>
                (<?php echo $averageRating; ?> / 5)
            </div>
        <?php else : ?>
            <p>No reviews yet. Be the first to leave a review!</p>
        <?php endif; ?>
        <?php if (!empty($reviews)) : ?>
            <?php foreach ($reviews as $review) : ?>
                <div class="review-card">
                    <p><strong><?php echo htmlspecialchars($review["username"]); ?></strong> -
                    <span class="star-rating"><?php echo displayStars($review["rating"]); ?></span></p>
                    <p><?php echo htmlspecialchars($review["comment"]); ?></p>
                    <p class="review-date"><?php echo htmlspecialchars($review["review_date"]); ?></p>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    <?php require_once "../partials/footer.php"; ?>
</body>
</html>