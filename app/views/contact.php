<?php 
    session_start();

    $successMessage = "";

    if (isset($_POST['submitted'])) {
        require_once("database/db_connection.php");

        
    }

    if (isset($_SESSION['success_message'])) {
        $successMessage = $_SESSION['success_message'];
        unset($_SESSION['success_message']); 
    }
?>


<!DOCTYPE html>
<html lang="en">
<html>
<head>
   <?php require_once "../partials/header.php" ?>
    <link rel="stylesheet" href="../../public/css/contact.css">
    <link rel="stylesheet" href="../../public/css/navbar.css">
    <link rel="stylesheet" href="../../public/css/styles.css">
    

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
                <form>
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
