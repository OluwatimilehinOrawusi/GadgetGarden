<?php
//starts the session
session_start();






//get the product ID and store it in a variable
$productID = isset($_GET['product_id']) ? (int)$_GET['product_id'] : 0;

//should check if there is actually a product that exists
if($product_id > 0){
    //should get the reviews from the product that the user is looking at
    $sql = "SELECT r.rating, r.review_text, r.review_date, u.username 
    FROM Reviews AS r
    JOIN Users AS u ON r.user_id = u.user_id
    WHERE r.product_id = ? 
    ORDER BY r.review_date DESC";


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
</head>
<body>
    <!-- Navigation Bar -->
    <!---should add the navbar partial to the page--->
    <?php require '../partials/navbar.php'; ?>

    
    

     <!-----Review writing field---->   
    <div class="review-content">
      <h2>Review Order</h2>
      <textarea placeholder="Write a review"></textarea>
      <button class="submit-btn">Submit</button>
    </div>

    <?php require '../partials/footer.php' ?>
</body>
</html>