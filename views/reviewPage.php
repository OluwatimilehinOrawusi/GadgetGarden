<?php
// Start the session
session_start();

// Connect to the database
require_once('../database/database.php');

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_review'])) {
    $user_id = $_SESSION['user_id']; // If a user is logged in, retrieve the user_id 
    $product_id = $_POST['product_id'];
    $rating = intval($_POST['rating']);
    $review_text = htmlspecialchars(trim($_POST['review_text']));

    // Validate form inputs
    if ($user_id && $product_id && $rating > 0 && $rating <= 5 && !empty($review_text)) {
        try {
            // SQL to insert the review
            $stmt = $db->prepare("INSERT INTO Reviews (user_id, product_id, rating, review_text) VALUES (:product_id, :rating, :review_text)");
            
            // Bind parameters
            $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
            $stmt->bindParam(':rating', $rating, PDO::PARAM_INT);
            $stmt->bindParam(':review_text', $review_text, PDO::PARAM_STR);

            // Execute the query and check for success
            if ($stmt->execute()) {
                echo "<script>alert('Review submitted successfully!');</script>";
            } else {
                echo "<script>alert('Error submitting review.');</script>";
            }
        } catch (PDOException $e) {
            echo "<script>alert('Database error: " . $e->getMessage() . "');</script>";
        }
    } else {
        echo "<script>alert('Please fill in all fields correctly.');</script>";
    }
}
?>






<!----Start of the html code--->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Review Item - Gadget Garden</title>
    <?php require '../partials/header.php' ?>
    <link rel="stylesheet" href="../../public/css/navbar.css">
    <link rel="stylesheet" href="../../public/css/styles.css">
    <link rel="stylesheet" href="../../public/css/reviewPage.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
    <!-- Navigation Bar -->
    <!---should add the navbar partial to the page--->
    <?php require '../partials/navbar.php'; ?>

<!------ Seperation between the form and navbar--->
    <div style="height: 70px;"></div>
    
    


  <!-- Review writing field -->
  <div class="review-content">
        <h2>Submit Your Review</h2>
        <form method="POST" action="">

<!----product ID section to be removed in later prints---->
            <label for="product_id">Product ID:</label>
            <input type="number" id="product_id" name="product_id" required>
            <br>
            <br>


            <label for="rating">Rating (1-5):</label>
            <div class="star-rating">
                <input type="radio" id="star1" name="rating" value="1" required>
                <label for="star1" title="1 stars">★</label>
                
                <input type="radio" id="star2" name="rating" value="2" required>
                <label for="star2" title="2 stars">★</label>

                <input type="radio" id="star3" name="rating" value="3" required>
                <label for="star3" title="3 stars">★</label>

                <input type="radio" id="star4" name="rating" value="4" required>
                <label for="star4" title="4 stars">★</label>
                
                <input type="radio" id="star5" name="rating" value="5" required>
                <label for="star5" title="5 stars">★</label>
            </div>

            <br>
            <br>
            <label for="review_text">Review:</label>
            <textarea id="review_text" name="review_text" placeholder="Write your review here" required></textarea>

            <button type="submit" name="submit_review" class="submit-btn">Submit</button>
        </form>
    </div>
    
<!------ Seperation between the form and footer--->
    <div style="height: 70px;"></div>

    <?php require '../partials/footer.php' ?>
</body>
</html>