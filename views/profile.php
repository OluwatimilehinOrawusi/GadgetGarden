<?php     
//start the session_session
session_start();

//connect to db
require_once ("../database/database.php");

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
} 

?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Gadget Garden</title>
    <?php require '../partials/header.php'; ?>
    <link rel="stylesheet" href="../public/css/navbar.css" />
    <link rel="stylesheet" href="../public/css/styles.css" />
    <link rel="stylesheet" href="../public/css/profile.css" />

  </head>
  <body>
  <nav>
            <div class="nav-left">
                <a href="../index.php"><p id="logo-text">GADGET GARDEN</p></a>
            </div>
            <div class="nav-right">
                <a href="../views/contact.php"><button class="green-button" >Contact Us</button></a>
                <a href="#categories"><button class="white-button">About Us</button></a>
                <?php if (!isset($_SESSION['user_id'])){?>
                <?php echo '<a href="./login.php"><button class="green-button">Login</button></a>' ?>
                 <?php echo '<a href="./signup.php"><button class="white-button">Sign Up</button></a> '?>
                <?php }?>
                <a href="../views/contact.php"><button class="green-button" >Products</button></a>
                <?php if (isset($_SESSION['user_id'])){?>
                <?php echo '<a href="./basket.php"><button class="white-button">Basket</button></a>' ?>
                <?php echo '<a href="./logout.php"><button class="green-button">Logout</button></a>' ?>

                <?php }?>

            </div>
</nav>
    <header class="header">
      <div class="header-content">
        <h1>My Profile</h1>
      </div>
      
    </header>

    <main class="main-content">
    <?php echo "<h2> Welcome ".$_SESSION['username']."! </h2>"; ?>
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
              <a href="changepassword.php" class="reset-link"
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
            <img src="../public/assets/headphones.png"  
            alt="Headphones"  
            class="order-image" />
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
            <h3>Playstation</h3>
            <button class="edit-button">Edit</button>
          </div>
          <div class="order-details">
            <img src="../public/assets/playstation.png" 
            alt="TV" class="order-image" />
            <div class="info-content">
              <p><strong>Order ID:</strong> 78D9284741XZ</p>
              <p>
                <strong>Dispatched to:</strong> 18 Bromsbrook Street,
                Birmingham, B27 9PJ
              </p>
              <p><strong>Order total:</strong> £400.00</p>
              <p><strong>Payment Method:</strong> Card</p>
            </div>
          </div>

          <div class="info-header">
            <h3>Gaming Keyboard</h3>
            <button class="edit-button">Edit</button>
          </div>
          <div class="order-details">
            <img
              src="../public/assets/gaming-keyboard.png"
              alt="Gaming keyboard"
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
            <h3>Apple Watch</h3>
            <button class="edit-button">Edit</button>
          </div>
          <div class="order-details">
            <img src="../public/assets/apple-watch.png" alt="watch" class="order-image" />
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
  <?php require '../partials/footer.php'; ?>
</html>
