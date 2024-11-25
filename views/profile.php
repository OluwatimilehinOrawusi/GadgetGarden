<?php     
//start the session_session
session_start();

//connect to db
require_once ("../../database/database.php");

if (!isset($_SESSION['username'])) {
  header("Location: login.php?error=Please+log+in");
  exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'])) {
  $order_id = intval($_POST['order_id']);
  $order_total = floatval($_POST['order_total']);
  $sold_total = floatval($_POST['sold_total']);
  $dispatch_address = trim($_POST['dispatch_address']);

  $stmt = $db->prepare("UPDATE profile SET order_total = ?, sold_total = ?, dispatch_address = ? WHERE order_id = ?");
  $stmt->bind_param("ddsi", $order_total, $sold_total, $dispatch_address, $order_id);

  if ($stmt->execute()) {
    // Redirect to the profile page or show a success message
    header("Location: profile.php?success=Order+updated+successfully");
    exit();
} else {
    echo "Error updating order: " . $db->error;
}
} else {
die("Invalid request.");
}
?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Gadget Garden</title>
    <?php require '../../partials/header.php'; ?>
    <link rel="stylesheet" href="../../../public/css/navbar.css" />
    <link rel="stylesheet" href="../../../public/css/styles.css" />
    <link rel="stylesheet" href="profile.css" />

  </head>
  <body>
  <?php require '../../partials/navbar.php'; ?>
    <header class="header">
      <div class="header-content">
        <h1>My Profile</h1>
      </div>
      
    </header>

    <main class="main-content">
      <h2>Hi,Neaj</h2>
      <section class="info-section">
        <div class="info-card">
          <div class="info-header">
            <h3>Personal Info</h3>
            <button class="edit-button">Edit</button>
          </div>
          <div class="info-content">
            <p><strong>First Name:</strong> Neaj</p>
            <p><strong>Last Name:</strong> Chowdhury</p>
          </div>
        </div>

        <div class="info-card">
          <div class="info-header">
            <h3>Email Address</h3>
            <button class="edit-button">Edit</button>
          </div>
          <div class="info-content">
            <p><strong>Email Address:</strong> 220056342@aston.ac.uk</p>
            <p><strong>Account ID:</strong> 2168364892747</p>
          </div>
        </div>

        <div class="info-card">
          <div class="info-header">
            <h3>Reset Password</h3>
            <button class="edit-button">Edit</button>
          </div>
          <div class="info-content">
            <p>
              <a href="reset-password.html" class="reset-link"
                >Reset Password</a
              >
            </p>
          </div>
        </div>

        <div class="info-card">
          <div class="info-header">
            <h3>My Orders<br /><br /></h3>
          </div>

          <div class="info-header">
            <h3>Headphones</h3>
            <button class="edit-button">Edit</button>
          </div>
          <div class="order-details">
            <img src="headphones.jpg" alt="Headphones" class="order-image" />
            <div class="info-content">
              <p><strong>Order ID:</strong> EJH27GD73GD</p>
              <p>
                <strong>Dispatched to:</strong> 18 Bromsbrook Street,
                Birmingham, B27 9PJ
              </p>
              <p><strong>Order total:</strong> £50.00</p>
              <p><strong>Payment Method:</strong> Card</p>
            </div>
          </div>

          <div class="info-header">
            <h3>Smart TV</h3>
            <button class="edit-button">Edit</button>
          </div>
          <div class="order-details">
            <img src="tv.jpg" alt="TV" class="order-image" />
            <div class="info-content">
              <p><strong>Order ID:</strong> 78D9284741XZ</p>
              <p>
                <strong>Dispatched to:</strong> 18 Bromsbrook Street,
                Birmingham, B27 9PJ
              </p>
              <p><strong>Order total:</strong> £3120.00</p>
              <p><strong>Payment Method:</strong> Card</p>
            </div>
          </div>

          <div class="info-header">
            <h3>Games Controller</h3>
            <button class="edit-button">Edit</button>
          </div>
          <div class="order-details">
            <img
              src="gamingcontroller.jpg"
              alt="Gaming Controller"
              class="order-image"
            />
            <div class="info-content">
              <p><strong>Order ID:</strong> H84H9SI3H984</p>
              <p>
                <strong>Dispatched to:</strong> 18 Bromsbrook Street,
                Birmingham, B27 9PJ
              </p>
              <p><strong>Order total:</strong> £45.00</p>
              <p><strong>Payment Method:</strong> Card</p>
            </div>
          </div>
        </div>
        



        <div class="info-card">
          <div class="info-header">
            <h3>My Sales<br /><br /></h3>
          </div>

          <div class="info-header">
            <h3>Ipad Air</h3>
            <button class="edit-button">Edit</button>
          </div>
          <div class="order-details">
            <img src="headphones.jpg" alt="Headphones" class="order-image" />
            <div class="info-content">
              <p><strong>Order ID:</strong> HD8EH47FH01</p>
              <p>
                <strong>delivered to:</strong> 789 Bellow Close,
                London, SW12 9JJ 

              </p>
              <p><strong>Sold for:</strong> £400.00</p>
              
            </div>
          </div>

        </div>
      </section>

      
    </main>
  
  </body>
  <?php require '../../partials/footer.php'; ?>
</html>
