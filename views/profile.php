<?php     
  session_start();
  require_once ("../database/database.php");
  
  if (!isset($_SESSION['username'])) {
    header("Location: login.php?error=Please+log+in");
    exit();
  }
  
  $username = isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Unknown User';
  $email = isset($_SESSION['email']) ? htmlspecialchars($_SESSION['email']) : 'Email not available';
  $user_id = isset($_SESSION['user_id']) ? htmlspecialchars($_SESSION['user_id']) : 'Unknown ID';
  
  if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['update_profile'])) {
    $new_username = trim(htmlspecialchars($_POST['username']));
    $new_email = trim(htmlspecialchars($_POST['email']));
  
    $stmt = $db->prepare("UPDATE users SET username = ?, email = ? WHERE user_id = ?");
    $stmt->bind_param("ssi", $new_username, $new_email, $user_id);
  
    if ($stmt->execute()) {
      $_SESSION['username'] = $new_username;
      $_SESSION['email'] = $new_email;
      header("Location: profile.php?success=Profile+updated+successfully");
      exit();
    } else {
      echo "Error updating profile: " . $db->error;
    }
  } 
  ?>
  
  <!DOCTYPE html>
  <html lang="en">
  <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Gadget Garden - Profile</title>
      <?php require '../partials/header.php'; ?>
      <link rel="stylesheet" href="../public/css/profile.css">
      <link rel="stylesheet" href="../public/css/navbar.css">
      <link rel="stylesheet" href="../public/css/styles.css">
  </head>
  <body>
  <nav>
      <div class="nav-left">
          <a href="../index.php"><p id="logo-text">GADGET GARDEN</p></a>
      </div>
      <div class="nav-right">
          <a href="../views/aboutpage.php"><button class="white-button">About Us</button></a>
          <?php if (!isset($_SESSION['user_id'])) { ?>
              <a href="./login.php"><button class="green-button">Login</button></a>
              <a href="./signup.php"><button class="white-button">Sign Up</button></a>
          <?php } else { ?>
              <a href="./basket.php"><button class="white-button">Basket</button></a>
              <a href="./contact.php"><button class="white-button">Contact us</button></a>
              <a href="./profile.php"><button class="white-button">Profile</button></a>
              <a href="./logout.php"><button class="green-button">Logout</button></a>
          <?php } ?>
      </div>
  </nav>
  <div id="wholepage">
      <header class="header">
          <div class="header-content">
              <h1>My Profile</h1>
          </div>
      </header>
      <main class="main-content">
          <h2>Welcome <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
          <section class="info-section">
              <div class="info-card">
                  <div class="info-header">
                      <h3>Personal Info</h3>
                      <button class="edit-button">Edit</button>
                  </div>
                  <div class="info-content">
                      <p><b>Username:</b> <?php echo htmlspecialchars($_SESSION['username']); ?></p>
                  </div>
              </div>
              <div class="info-card">
                  <div class="info-header">
                      <h3>Email Address</h3>
                      <button class="edit-button">Edit</button>
                  </div>
                  <div class="info-content">
                      <p><b>Email:</b> <?php echo isset($_SESSION['email']) ? htmlspecialchars($_SESSION['email']) : 'Email not available'; ?></p>
                      <p><b>Account ID:</b> <?php echo htmlspecialchars($_SESSION['user_id']); ?></p>
                  </div>
              </div>
              <div class="info-card">
                  <div class="info-header">
                      <h3>Change Password</h3>
                      <button class="edit-button">Edit</button>
                  </div>
                  <div class="info-content">
                      <a href="./changepassword.php" class="reset-link">Change Password</a>
                  </div>
              </div>
              <div class="info-card">
                  <div class="info-header">
                      <h3>Return Order</h3>
                      <button class="edit-button">Edit</button>
                  </div>
                  <div class="info-content">
                      <a href="./returnOrder.php" class="return-link">Return Order</a>
                  </div>
              </div>
          </section>
      </main>
  </div>
  <?php require '../partials/footer.php'; ?>
  </body>
  </html>
