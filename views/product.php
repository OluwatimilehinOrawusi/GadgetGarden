<?php 

$pdo = require_once "../database/database.php";

$id = $_GET['id'];

$statement = $pdo->prepare('SELECT * FROM products WHERE product_id = :id');
$statement->bindValue(":id", $id, PDO::PARAM_INT);
$statement->execute();
$product = $statement->fetch(PDO::FETCH_ASSOC);

// Fetch all reviews for this product
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
$averageResult = $averageStmt->fetch(PDO::FETCH_ASSOC);
$averageRating = round($averageResult["avg_rating"], 1); // Round to 1 decimal place

function displayStars($rating) {
    $fullStars = floor($rating);
    $halfStar = ($rating - $fullStars) >= 0.5 ? 1 : 0;
    $emptyStars = 5 - ($fullStars + $halfStar);
    
    $starsHtml = str_repeat('<span class="full-star">★</span>', $fullStars);
    if ($halfStar) {
        $starsHtml .= '<span class="half-star">☆</span>';
    }
    $starsHtml .= str_repeat('<span class="empty-star">☆</span>', $emptyStars);
    
    return $starsHtml;
}
?>

<!DOCTYPE html>
<html>
<head>
    <?php require_once "../partials/header.php"; ?>
    <link rel="stylesheet" href="../public/css/product.css">
</head>
<body>
    <?php require_once "../partials/navbar.php"; ?>

    <div class="product-container">
        <div class="product-image">
            <img src="<?php echo "..".$product["image"] ?>" alt="Product Image">
        </div>
        
        <div class="product-data">
            <h1 class="product-name"><?php echo htmlspecialchars($product["name"]) ?></h1>
            <p class="product-description"><?php echo htmlspecialchars($product["description"]) ?></p>
            <p class="product-condition">Condition: <?php echo htmlspecialchars($product["state"]) ?></p>
            <p class="product-price">£<?php echo htmlspecialchars($product["price"]) ?></p>
            <a href="add-products.php?product_id=<?php echo $product["product_id"] ?>"><button class="green-button">Add to Basket</button></a>
            <a href="./reviewPage.php?id=<?php echo $id ?>"><u>Write a review</u></a>
        </div>
    </div>

    <!-- 🟢 Average Rating Section -->
    <div class="average-rating">
        <h2>Average Rating</h2>
        <p class="star-rating"><?php echo displayStars($averageRating); ?> (<?php echo $averageRating; ?> / 5)</p>
    </div>

    <!-- 🟢 Reviews Section -->
    <div class="reviews-section">
        <h2>Customer Reviews</h2>

        <?php if (empty($reviews)) : ?>
            <p>No reviews yet. Be the first to leave a review!</p>
        <?php else : ?>
            <?php foreach ($reviews as $review) : ?>
                <div class="review-card">
                    <p><strong><?php echo htmlspecialchars($review["username"]); ?></strong> - 
                        <span class="star-rating"><?php echo displayStars($review["rating"]); ?></span>
                    </p>
                    <p><?php echo htmlspecialchars($review["comment"]); ?></p>
                    <p class="review-date"><?php echo htmlspecialchars($review["review_date"]); ?></p>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <?php require_once "../partials/footer.php"; ?>
</body>
</html>
