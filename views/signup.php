<?php
session_start();
require_once '../database/database.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $phone = trim($_POST['phone']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $memorable_phrase = $_POST['memorable_phrase'];

    // Check if passwords match
    if ($password !== $confirm_password) {
        $errors[] = "The passwords do not match. Please try again.";
    }

    // Strong password validation
    if (strlen($password) < 8) {
        $errors[] = "Password must be at least 8 characters long.";
    }
    if (!preg_match('/[A-Z]/', $password)) {
        $errors[] = "Password must contain at least one uppercase letter.";
    }
    if (!preg_match('/[0-9]/', $password)) {
        $errors[] = "Password must contain at least one number.";
    }
    if (!preg_match('/[\W]/', $password)) {
        $errors[] = "Password must contain at least one special character (e.g., !@#$%^&*).";
    }

    if (empty($errors)) {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        try {
            $sql = "INSERT INTO users(username, email, phone, password_hash, memorable_phrase) VALUES(?,?,?,?,?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$username, $email, $phone, $password_hash, $memorable_phrase]);

            header("Location: ./profile.php");
            exit;
        } catch (PDOException $e) {
            echo "An error occurred. Please try again later.";
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="../public/css/signup.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gadget Garden - Signup</title>
</head>
<body>

<div class="signup-container">
    <h3>Create Account</h3>

    <form method="POST" action="./signup.php">
        <label>Username</label>
        <input type="text" name="username" required>

        <label>Email</label>
        <input type="email" name="email" required>

        <label>Phone Number</label>
        <input type="text" name="phone" required>

        <label>Password</label>
        <input type="password" name="password" required>

        <label>Confirm Password</label>
        <input type="password" name="confirm_password" required>

        <label>Memorable Phrase</label>
        <input type="text" name="memorable_phrase" required>

        <!-- Display PHP Validation Errors -->
        <?php if (!empty($errors)): ?>
            <div class="error-box">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li style="color: red;"><?php echo $error; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <button type="submit">Create Account</button>

        <p>Already have an account? <a href="./login.php">Log in</a></p>
    </form>
</div>

</body>
</html>
 