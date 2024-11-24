<?php
session_start();

$pdo = require_once "../database/database.php"; 

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
          
            
        $_SESSION['success'] = "Thank you for your message, We will get back to you shortly!";
    } else {
        $_SESSION['error'] = "Please fill in all required fields.";
    }

    header('Location: contact.php');
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<html>
<head>
   <?php require_once "../partials/header.php" ?>
    <link rel="stylesheet" href="../public/css/contact.css">
    <link rel="stylesheet" href="../public/css/navbar.css">
    <link rel="stylesheet" href="../public/css/styles.css">
    

</head>
<body>
    <?php require_once "../partials/navbar.php" ?>
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
