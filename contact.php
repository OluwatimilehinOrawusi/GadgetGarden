<?php
session_start();
require_once('db_connection.php');
include 'navbar.php';

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - Gadget Garden</title>
    <link rel="stylesheet" href="public/css/contact.css">
</head>
<body>
    <header>
        <div class="navbar">
            <h1>GADGET GARDEN</h1>
            <nav>
                <ul>
                    <li><a href="#">Categories</a></li>
                    <li><a href="#" class="active">Login</a></li>
                    <li><a href="#">Signup</a></li>
                </ul>
            </nav>
        </div>
    </header>

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
                </form>
            </div>
        </div>
    </section>

    <footer>
        <div class="footer-container">
            <div class="footer-left">
                <h3>GADGET GARDEN</h3>
                <p>Your go-to store for everything tech.</p>
                <input type="text" placeholder="Type your questions">
                <button>Submit</button>
            </div>
            <div class="footer-right">
                <div>
                    <h4>Popular</h4>
                    <ul>
                        <li>Phones</li>
                        <li>Tablets</li>
                        <li>Laptops</li>
                        <li>Audio</li>
                        <li>Accessories</li>
                    </ul>
                </div>
                <div>
                    <h4>Menu</h4>
                    <ul>
                        <li>All Categories</li>
                        <li>Gift Cards</li>
                        <li>Special Events</li>
                        <li>Contact Us</li>
                        <li>FAQ</li>
                    </ul>
                </div>
                <div>
                    <h4>Other</h4>
                    <ul>
                        <li>About Us</li>
                        <li>Terms and Conditions</li>
                        <li>Blog</li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>