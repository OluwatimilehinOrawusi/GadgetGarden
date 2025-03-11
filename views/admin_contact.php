<?php
session_start();

$pdo = require_once "../database/database.php";

$user_id = $_SESSION['user_id'] ?? null;

// Fetch the admin status of the user
$stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = :user_id");
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user || ($user['role'] !== 'admin' && $user['role'] !== 'manager')) {
    header("Location: ../index.php");
    exit();
}

// Fetch all messages from the database, including the query_id
$stmt = $pdo->query("SELECT * FROM contact");
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Queries - Gadget Garden</title>

    <!-- Links to styles and header -->
    <?php require '../partials/header.php'; ?>
    <link rel="stylesheet" href="../public/css/navbar.css">
    <link rel="stylesheet" href="../public/css/styles.css">
    <link rel="stylesheet" href="../public/css/admincontact.css">
</head>
<body>
    <!-- Navigation Bar -->
    <nav>
        <div class="nav-left">
            <a href="../index.php"><p id="logo-text">GADGET GARDEN</p></a>
        </div>
        <div class="nav-right">
            <a href="../views/aboutpage.php"><button class="white-button">About Us</button></a>
            <?php if (!isset($_SESSION['user_id'])): ?>
                <a href="./login.php"><button class="green-button">Login</button></a>
                <a href="./signup.php"><button class="white-button">Sign Up</button></a>
            <?php else: ?>
                <a href="./basket.php"><button class="white-button">Basket</button></a>
                <a href="./contact.php"><button class="white-button">Contact us</button></a>
                <a href="./profile.php"><button class="white-button">Profile</button></a>
                <a href="./logout.php"><button class="green-button">Logout</button></a>
            <?php endif; ?>
        </div>
    </nav>  

    <div class="container">
        <h1>Customer Queries</h1>

        <?php if (isset($_SESSION['success_message'])): ?>
            <div class ="success-message">
                <?php 
                echo $_SESSION['success_message'];
                unset($_SESSION['success_message']); 
                ?>
            </div>
            <?php endif; ?>

        <table>
            <thead>
                <tr>
                    <th>Query ID</th> <!-- Add Query ID header -->
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Message</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($messages as $message): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($message['query_id']); ?></td> <!-- Display query_id -->
                        <td><?php echo htmlspecialchars($message['name']); ?></td>
                        <td><?php echo htmlspecialchars($message['phone']); ?></td>
                        <td><?php echo htmlspecialchars($message['email']); ?></td>
                        <td><?php echo htmlspecialchars($message['message']); ?></td>
                    
                           <td>
                            <!-- Reply Button -->
                            <form action="reply.php" method="POST">
                          <!-- Include the query_id as a hidden field -->
                         <input type="hidden" name="query_id" value="<?php echo htmlspecialchars($message['query_id']); ?>">
                        <textarea name="reply_message" placeholder="Type your reply here..." required></textarea>
                        <button type="submit" class="reply">Reply</button>
                 </form>
                </td>

                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
