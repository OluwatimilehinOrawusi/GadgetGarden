<?php
session_start();
require_once "../database/database.php";

// Check if user is admin or manager
$stmt = $pdo->prepare("SELECT role FROM users WHERE user_id = :user_id");
$stmt->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user || !in_array($user['role'], ['admin', 'manager'])) {
    die("Access denied.");
}

// Handle Review Deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_review'])) {
    $review_id = intval($_POST['review_id']);
    
    $deleteStmt = $pdo->prepare("DELETE FROM reviews WHERE review_id = :review_id");
    $deleteStmt->bindParam(':review_id', $review_id, PDO::PARAM_INT);
    $deleteStmt->execute();
    
    header("Location: admin_reviews.php");
    exit();
}

// Fetch all reviews
$reviewsStmt = $pdo->prepare("
    SELECT r.review_id, r.rating, r.review_text, r.created_at, u.username, 
           p.name AS product_name, r.product_id 
    FROM reviews r
    JOIN users u ON r.user_id = u.user_id
    JOIN products p ON r.product_id = p.product_id
    ORDER BY r.created_at DESC
");
$reviewsStmt->execute();
$reviews = $reviewsStmt->fetchAll(PDO::FETCH_ASSOC);

// Group reviews by product
$groupedReviews = [];
foreach ($reviews as $review) {
    $groupedReviews[$review['product_name']][] = $review;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Reviews - Admin</title>
    <link rel="stylesheet" href="../public/css/navbar.css">
    <link rel="stylesheet" href="../public/css/styles.css">
    <link rel="stylesheet" href="../public/css/adminReviews.css">
    <script>
        function openTab(tabName) {
            let tabs = document.querySelectorAll(".tab-content");
            tabs.forEach(tab => tab.style.display = "none");
            document.getElementById(tabName).style.display = "block";

            let buttons = document.querySelectorAll(".tab-button");
            buttons.forEach(button => button.classList.remove("active"));
            document.getElementById(tabName + "-btn").classList.add("active");
        }
    </script>
</head>
<body>

<nav>
    <div class="nav-left">
        <a href="../index.php"><p id="logo-text">GADGET GARDEN</p></a>
    </div>
    <div class="nav-right">
        <a href="./dashboard.php"><button class="white-button">Dashboard</button></a>
        <a href="manage_users.php"><button class="white-button">Users</button></a>
        <a href="manage_orders.php"><button class="white-button">Orders</button></a>
        <a href="admin.php"><button class="white-button">Products</button></a>
        <a href="admin_reviews.php"><button class="white-button">Reviews</button></a>
        <a href="logout.php"><button class="green-button">Logout</button></a>
    </div>
</nav>

<div class="container">
    <h1>Manage Reviews</h1>

    <div class="tabs">
        <button id="allReviews-btn" class="tab-button" onclick="openTab('allReviews')">All Reviews</button>
        <button id="perProduct-btn" class="tab-button" onclick="openTab('perProduct')">Reviews Per Product</button>
    </div>

    <!-- All Reviews Tab -->
    <div id="allReviews" class="tab-content">
        <?php if (empty($reviews)) : ?>
            <p class="no-reviews">No reviews available.</p>
        <?php else : ?>
            <table>
                <thead>
                    <tr>
                        <th>Username</th>
                        <th>Product</th>
                        <th>Rating</th>
                        <th>Review</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($reviews as $review) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($review['username']); ?></td>
                        <td><?php echo htmlspecialchars($review['product_name']); ?></td>
                        <td><?php echo htmlspecialchars($review['rating']); ?> / 5</td>
                        <td><?php echo htmlspecialchars($review['review_text']); ?></td>
                        <td><?php echo htmlspecialchars($review['created_at']); ?></td>
                        <td>
                            <form method="POST">
                                <input type="hidden" name="review_id" value="<?php echo $review['review_id']; ?>">
                                <button type="submit" name="delete_review" class="delete-button">Delete</button>
                            </form>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
    

    <!-- Reviews Per Product Tab -->
    <div id="perProduct" class="tab-content" style="display:none;">
        <?php if (empty($groupedReviews)) : ?>
            <p class="no-reviews">No reviews available.</p>
        <?php else : ?>
            <?php foreach ($groupedReviews as $product => $reviews) { ?>
                <h2><?php echo htmlspecialchars($product); ?></h2>
                <table>
                    <thead>
                        <tr>
                            <th>Username</th>
                            <th>Rating</th>
                            <th>Review</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($reviews as $review) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($review['username']); ?></td>
                            <td><?php echo htmlspecialchars($review['rating']); ?> / 5</td>
                            <td><?php echo htmlspecialchars($review['review_text']); ?></td>
                            <td><?php echo htmlspecialchars($review['created_at']); ?></td>
                            <td>
                                <form method="POST">
                                    <input type="hidden" name="review_id" value="<?php echo $review['review_id']; ?>">
                                    <button type="submit" name="delete_review" class="delete-button">Delete</button>
                                </form>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            <?php } ?>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
