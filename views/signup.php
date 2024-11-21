<?php
// Include the database connection
$pdo = require_once '../database/database.php';

$errors = [];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate form input
    if ($password !== $confirm_password) {
        $errors[] = "passwords do not match";
    }

    // Hash the password
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    // Insert the user into the database
    try {
        // Prepare the SQL query
        $sql = "INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$username, $email, $password_hash]);
        
        // If the query was successful, notify the user
        echo "Registration successful. <a href='./login.php'>Login here</a>";
    } catch (PDOException $e) {
        // Handle any errors (e.g., email already exists)
        echo "Error: " . $e->getMessage();
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
</head>
<body>
    <h2>Sign Up</h2>
    <?php foreach($errors as $error) {?>
        <p><?php echo $error ?></p>
        <?php }?>
    <form action="signup.php" method="POST">
        <label for="username">Username:</label>
        <input type="text" name="username" required><br>

        <label for="email">Email:</label>
        <input type="email" name="email" required><br>

        <label for="password">Password:</label>
        <input type="password" name="password" required><br>

        <label for="confirm_password">Confirm Password:</label>
        <input type="password" name="confirm_password" required><br>

        <input type="submit" value="Sign Up">
    </form>

    <p>Already have an account? <a href="login.html">Login here</a></p>
</body>
</html>
