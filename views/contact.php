<?php
session_start(); // Start a new session in order to store contact data

$pdo = require_once "../database/database.php"; // Conntect to the database

// If server is connected successfully, Then store all the data into the correct tables
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $name =  $_POST['name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $message = $_POST['message'];

    if (!empty($name) && !empty($phone) && !empty($email) && !empty($message)) {
            
            $query = "INSERT INTO contact (user_id, name, phone, email, message) VALUES (:user_id ,:name, :phone, :email, :message)";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':phone', $phone);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':message', $message);

            $stmt->execute() ;
          
            
        $_SESSION['success'] = "Thank you for your message, We will get back to you shortly!"; // Success message once field filled
    } else {
        $_SESSION['error'] = "Please fill in all required fields."; // If fields are left empty
    }

    header('Location: contact.php');
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<html>
    <!---Message container for success/error message--> 
<div class="message-container">
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success">
            <?= $_SESSION['success']; ?>
        </div>
        <?php unset($_SESSION['success']);  ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger">
            <?= $_SESSION['error']; ?>
        </div>
        <?php unset($_SESSION['error']);  ?>
    <?php endif; ?>
</div>
    <link rel="stylesheet" href="../public/css/contact.css">
    <link rel="stylesheet" href="../public/css/navbar.css">
    <link rel="stylesheet" href="../public/css/styles.css">
    

</head>
<body>
<nav>
    <!---nav bar-->
            <div class="nav-left">
                <a href="../index.php"><p id="logo-text">GADGET GARDEN</p></a>
            </div>
            <div class="nav-right">
                <a href="./aboutpage.php"><button class="white-button">About Us</button></a>
                <?php if (!isset($_SESSION['user_id'])){?>
                <?php echo '<a href="./login.php"><button class="green-button">Login</button></a>' ?>
                 <?php echo '<a href="./signup.php"><button class="white-button">Sign Up</button></a> '?>
                <?php }?>
                <a href="./products.php"><button class="green-button" >Products</button></a>
                <?php if (isset($_SESSION['user_id'])){?>
                <?php echo '<a href="./basket.php"><button class="white-button">Basket</button></a>' ?>
                <?php echo '<a href="./logout.php"><button class="green-button">Logout</button></a>' ?>

                <?php }?>

            </div>
</nav>

 <!---HTML for the contact page, includes the input forms, and submit button -->
    <section class="contact-section">
        
        <div class="contact-container">
            <div class="contact-left">
                <h2>CONTACT US</h2>
                <p>"Redefining Your Tech Experience"</p>
                <div class="contact-info">
                    <p><span>📧</span> example@gmail.com</p>
                    <p><span>📞</span> 123456789</p>
                    <p><span>📍</span> Example Location</p>
                </div>
            </div>

            <div class="contact-right">
                <form action="./contact.php
                " method="POST">
                    <div class="form-group">
                        <label for="name">Your Name</label>
                        <input type="text" id="name" name="name" placeholder="Enter your name" required>
                    </div>

                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <input type="tel" id="phone" name="phone" placeholder="Enter your phone number" required>
                    </div>

                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" name="email" placeholder="Enter your email" required>
                    </div>

                    <div class="form-group">
                        <label for="message">Your Message</label>
                        <textarea id="message" name="message" rows="4" placeholder="Write your message" required></textarea>
                    </div>

                    <button type="submit" class="submit-btn">Submit</button>
                    <input type="hidden" name="submitted" value="TRUE" />
                </form>
            </div>
        </div>
    </section>

    <?php require_once "../partials/footer.php" ?>
    </body>

    </html>
