<?php     
// Start session
session_start();

// Connect to the database
require_once("../database/database.php");

if (!isset($_SESSION['username'])) {
  header("Location: login.php?error=Please+log+in");
  exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
  // Update user details based on the form submitted
  switch ($_POST['action']) {
    case 'update_personal_info':
      $first_name = trim($_POST['first_name']);
      $last_name = trim($_POST['last_name']);

      $stmt = $db->prepare("UPDATE users SET first_name = ?, last_name = ? WHERE username = ?");
      $stmt->bind_param("sss", $first_name, $last_name, $_SESSION['username']);

      if ($stmt->execute()) {
        header("Location: profile.php?success=Personal+info+updated+successfully");
        exit();
      } else {
        echo "Error updating personal info: " . $db->error;
      }
      break;

    case 'update_email':
      $email = trim($_POST['email']);

      $stmt = $db->prepare("UPDATE users SET email = ? WHERE username = ?");
      $stmt->bind_param("ss", $email, $_SESSION['username']);

      if ($stmt->execute()) {
        header("Location: profile.php?success=Email+updated+successfully");
        exit();
      } else {
        echo "Error updating email: " . $db->error;
      }
      break;

    case 'update_order':
      $order_id = intval($_POST['order_id']);
      $dispatch_address = trim($_POST['dispatch_address']);
      $order_total = floatval($_POST['order_total']);

      $stmt = $db->prepare("UPDATE orders SET dispatch_address = ?, order_total = ? WHERE order_id = ?");
      $stmt->bind_param("sdi", $dispatch_address, $order_total, $order_id);

      if ($stmt->execute()) {
        header("Location: profile.php?success=Order+updated+successfully");
        exit();
      } else {
        echo "Error updating order: " . $db->error;
      }
      break;
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
  <link rel="stylesheet" href="../public/css/navbar.css">
  <link rel="stylesheet" href="../public/css/styles.css">
  <link rel="stylesheet" href="../public/css/profile.css">
</head>
<body>
  <nav>
    <div class="nav-left">
      <a href="../index.php"><p id="logo-text">GADGET GARDEN</p></a>
    </div>
    <div class="nav-right">
      <!-- Navigation buttons -->
    </div>
  </nav>
  
  <header class="header">
    <div class="header-content">
      <h1>My Profile</h1>
    </div>
  </header>

  <main class="main-content">
    <?php echo "<h2> Welcome " . htmlspecialchars($_SESSION['username']) . "! </h2>"; ?>
    <section class="info-section">
      <!-- Personal Info -->
      <div class="info-card">
        <div class="info-header">
          <h3>Personal Info</h3>
          <button class="edit-button" data-modal="personal-info-modal">Edit</button>
        </div>
        <div class="info-content">
          <p><strong>First Name:</strong> Neaj</p>
          <p><strong>Last Name:</strong> Chowdhury</p>
        </div>
      </div>

      <!-- Email Address -->
      <div class="info-card">
        <div class="info-header">
          <h3>Email Address</h3>
          <button class="edit-button" data-modal="email-modal">Edit</button>
        </div>
        <div class="info-content">
          <p><strong>Email Address:</strong> 220056342@aston.ac.uk</p>
          <p><strong>Account ID:</strong> 2168364892747</p>
        </div>
      </div>

      <!-- Modals -->
      <div id="personal-info-modal" class="modal">
        <div class="modal-content">
          <span class="close">&times;</span>
          <form action="profile.php" method="post">
            <input type="hidden" name="action" value="update_personal_info">
            <label for="first_name">First Name:</label>
            <input type="text" name="first_name" value="Neaj" required>
            <label for="last_name">Last Name:</label>
            <input type="text" name="last_name" value="Chowdhury" required>
            <button type="submit">Save</button>
          </form>
        </div>
      </div>

      <div id="email-modal" class="modal">
        <div class="modal-content">
          <span class="close">&times;</span>
          <form action="profile.php" method="post">
            <input type="hidden" name="action" value="update_email">
            <label for="email">Email:</label>
            <input type="email" name="email" value="220056342@aston.ac.uk" required>
            <button type="submit">Save</button>
          </form>
        </div>
      </div>
    </section>
  </main>

  <script>
    // JavaScript for handling modals
    document.querySelectorAll('.edit-button').forEach(button => {
      button.addEventListener('click', () => {
        const modal = document.getElementById(button.getAttribute('data-modal'));
        modal.style.display = 'block';
      });
    });

    document.querySelectorAll('.close').forEach(span => {
      span.addEventListener('click', () => {
        span.parentElement.parentElement.style.display = 'none';
      });
    });

    window.addEventListener('click', (event) => {
      if (event.target.classList.contains('modal')) {
        event.target.style.display = 'none';
      }
    });
  </script>
</body>
<?php require '../partials/footer.php'; ?>
</html>

