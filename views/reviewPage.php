<?php
// Start the session
session_start();

// Connect to the database
require_once('../database/database.php');



// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_review'])) {
    $user_id = $_SESSION['user_id']; // If a user is logged in, retrieve the user_id 
    $product_id = $_GET['id'];
    $rating = intval($_POST['rating']);
    $review_text = htmlspecialchars(trim($_POST['review_text']));

    // Validate form inputs
    if ($product_id && $rating > 0 && $rating <= 5 && !empty($review_text)) {
        try {
            // SQL to insert the review
            $stmt = $pdo->prepare("INSERT INTO Reviews (user_id, product_id, rating, review_text) VALUES (:user_id, :product_id, :rating, :review_text)");
            
            // Bind parameters
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_STR);
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

<!-----links styles pages and header--->
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
                <a href="../index.php"><p id="logo-text">GADGET GARDEN</p></a>
            </div>
            <div class="nav-right">
                <a href="../views/contact.php"><button class="green-button" >Contact Us</button></a>
                <a href="../views/aboutpage.php"><button class="white-button">About Us</button></a>
                <?php if (!isset($_SESSION['user_id'])){?>
                <?php echo '<a href="./login.php"><button class="green-button">Login</button></a>' ?>
                 <?php echo '<a href="./signup.php"><button class="white-button">Sign Up</button></a> '?>
                <?php }?>
                <a href="../views/products.php"><button class="green-button" >Products</button></a>
                <?php if (isset($_SESSION['user_id'])){?>
                <?php echo '<a href="./basket.php"><button class="white-button">Basket</button></a>' ?>
                <?php echo '<a href = "./profile.php"><button class ="white-button">Profile</button></a>' ?>
                <?php echo '<a href="./logout.php"><button class="green-button">Logout</button></a>' ?>

                <?php }?>

            </div>
</nav>

<!------ Seperation between the form and navbar--->
    <div style="height: 70px;"></div>
    
    


  <!-- Review writing field -->
  <div class="review-content">
        <h2>Submit Your Review</h2>
        <form method="POST" action="">




                <!-----Star rating button code--->
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

            <br>
            <br>
            <label for="review_text">Review:</label>
            <textarea id="review_text" name="review_text" placeholder="Write your review here" required></textarea>

            <!------submit button----->
            <button type="submit" name="submit_review" class="submit-btn">Submit</button>
        </form>
    </div>
    
<!------ Seperation between the form and footer--->
    <div style="height: 70px;"></div>

    <!-----Links the footer partial to the page----->
    <?php require '../partials/footer.php' ?>
</body>
</html>