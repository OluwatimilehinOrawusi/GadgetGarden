<?php
// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('You must log in to submit a review.');</script>";
    header('Location: ./login.php');
    exit;
}

// Validate product ID from URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<script>alert('Invalid product ID.');</script>";
    header('Location: ../index.php'); // Redirect to homepage
    exit;
}

require_once('../database/database.php');

$user = null;
if (isset($_SESSION['user_id'])) {
    $stmt = $pdo->prepare("SELECT role FROM users WHERE user_id = :user_id");
    $stmt->execute(['user_id' => $_SESSION['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_review'])) {
    $user_id = $_SESSION['user_id'];
    $product_id = intval($_GET['id']);
    $rating = filter_var($_POST['rating'], FILTER_VALIDATE_INT);
    $review_text = htmlspecialchars(trim($_POST['review_text']));

    // Validate form inputs
    if ($product_id && $rating && $rating >= 1 && $rating <= 5 && !empty($review_text)) {
        try {
            // Insert review into database
            $stmt = $pdo->prepare("INSERT INTO reviews (user_id, product_id, rating, review_text) VALUES (:user_id, :product_id, :rating, :review_text)");
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_STR);
            $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
            $stmt->bindParam(':rating', $rating, PDO::PARAM_INT);
            $stmt->bindParam(':review_text', $review_text, PDO::PARAM_STR);

            if ($stmt->execute()) {
                $_SESSION['success_message'] = "Review submitted successfully";
            } else {
                $_SESSION['error_message'] = "Error submiting review";
            }
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage()); // Log error
            $_SESSION['error_message'] = 'A database error occurred. Please try again later.';
        }

        header("Location: product.php?id=$product_id");
        exit;
    } else {
        $_SESSION['error_message'] = 'Please fill in all fields correctly.';
        header("Location: product.php?id=$product_id");
        exit;
    }
}
?>


<!-- HTML -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Review Item - Gadget Garden</title>

    <!-- Links to styles and header -->
    <?php require '../partials/header.php' ?>
    <link rel="stylesheet" href="../public/css/navbar.css">
    <link rel="stylesheet" href="../public/css/styles.css">
    <link rel="stylesheet" href="../public/css/reviewPage.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>

    <!-- Navigation Bar -->
    <nav>
    <div class="nav-left">
        <a href="../index.php"><p id="logo-text">GADGET GARDEN</p>
    </div>
    <div class="nav-right">
        <a href="./aboutpage.php"><button class="white-button">About Us</button></a>

        <?php if (!isset($_SESSION['user_id'])) { ?>
            <a href="./login.php"><button class="green-button">Login</button></a>
            <a href="./signup.php"><button class="white-button">Sign Up</button></a>
        <?php } ?>

        <?php if (isset($_SESSION['user_id'])) { ?>
            <a href="./basket.php"><button class="green-button">Basket</button></a>
            <a href="./contact.php"><button class="green-button">Contact Us</button></a>
            <a href="./profile.php"><button class="white-button">Profile</button></a>
            <a href="./products.php"><button class="green-button">Products</button></a>

            <?php if ($user && ($user['role'] === 'admin' || $user['role'] === 'manager')){ ?>
                <a href="./dashboard.php"><button class="white-button">Admin Dashboard</button></a>
            <?php } ?>

            <a href="./logout.php"><button class="green-button">Logout</button></a>
        
        <?php } ?>
    </div>
</nav>

    <!-- Separation between navbar and content -->
    <div style="height: 70px;"></div>

    <!-- Review Form -->
    <div class="review-content">
        <h2>Submit Your Review</h2>
        <form method="POST" action="">
            <label for="rating">Rating (1-5):</label>
            <div class="star-rating">
                <input type="radio" id="star5" name="rating" value="5" required>
                <label for="star5" title="5 stars">★</label>
                
                <input type="radio" id="star4" name="rating" value="4" required>
                <label for="star4" title="4 stars">★</label>

                <input type="radio" id="star3" name="rating" value="3" required>
                <label for="star3" title="3 stars">★</label>

                <input type="radio" id="star2" name="rating" value="2" required>
                <label for="star2" title="2 stars">★</label>
                
                <input type="radio" id="star1" name="rating" value="1" required>
                <label for="star1" title="1 stars">★</label>
            </div>
            <br><br>
            <label for="review_text">Review:</label>
            <textarea id="review_text" name="review_text" placeholder="Write your review here" required></textarea>
            <button type="submit" name="submit_review" class="submit-btn">Submit</button>
        </form>
    </div>

    <!-- Separation between form and footer -->
    <div style="height: 70px;"></div>

    <!-- Footer -->
    <?php require '../partials/footer.php' ?>
</body>
</html>
