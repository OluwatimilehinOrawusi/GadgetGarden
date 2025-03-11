<?php
session_start();
require_once "../database/database.php";


// Handle delete user action
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];
    
    // Delete the user
    $stmt = $pdo->prepare("DELETE FROM users WHERE user_id = ?");
    $stmt->execute([$user_id]);

    echo "User deleted successfully!";
    header('Location: ' . $_SERVER['PHP_SELF']);  // Redirect back to the current page
    exit;
}

// Handle update user action
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['user_id'])) {
    $user_id = $_POST['user_id'];
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    // Update user details
    $stmt = $pdo->prepare("UPDATE users SET username = ?, phone = ?, email = ?, role = ? WHERE user_id = ?");
    $stmt->execute([$name, $phone, $email, $role, $user_id]);

    echo "User updated successfully!";
    header("Location: manage_users.php");
exit;  // Always call exit() after a redirect to stop further script execution
}

// Fetch user details if user_id is provided
if (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];

    // Fetch user data from the database
    $stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = :user_id");
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        echo "User not found!";
        exit;
    }


}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>Edit User</h2>

    <?php if (isset($user)): ?>
        <!-- User Edit Form -->
        <form action="" method="POST">
            <input type="hidden" name="user_id" value="<?php echo $user['user_id']; ?>">

            <label for="name">Name</label>
        <input type="text" id="name" name="name" value="<?php echo $user['username']; ?>" required>

            <label for="phone">Phone</label>
            <input type="text" id="phone" name="phone" value="<?php echo $user['phone']; ?>">

            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="<?php echo $user['email']; ?>" required>

            <label for="role">Role</label>
            <select name="role" id="role">
                <option value="user" <?php echo $user['role'] == 'user' ? 'selected' : ''; ?>>User</option>
                <option value="manager" <?php echo $user['role'] == 'manager' ? 'selected' : ''; ?>>Manager</option>
                <option value="admin" <?php echo $user['role'] == 'admin' ? 'selected' : ''; ?>>Admin</option>
            </select>

            <button type="submit">Update User</button>
        </form>

        <!-- Delete User Button -->
        <a href="?action=delete&user_id=<?php echo $user['user_id']; ?>" style="color: red; text-decoration: none;">Delete User</a>
    <?php else: ?>
        <p>Please select a user to edit by passing their user_id in the URL, e.g., `?user_id=1`.</p>
    <?php endif; ?>

</body>
</html>

<!-- CSS -->
<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        margin: 0;
        padding: 0;
    }

    h2 {
        text-align: center;
        margin-top: 30px;
    }

    form {
        width: 300px;
        margin: 0 auto;
        background-color: white;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
    }

    label {
        display: block;
        margin-top: 10px;
    }

    input, select {
        width: 100%;
        padding: 8px;
        margin-top: 5px;
        margin-bottom: 10px;
        border-radius: 4px;
        border: 1px solid #ccc;
    }

    button {
        background-color: #4CAF50;
        color: white;
        padding: 10px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    button:hover {
        background-color: #45a049;
    }

    a {
        display: block;
        text-align: center;
        margin-top: 20px;
        color: red;
        text-decoration: none;
    }

    a:hover {
        text-decoration: underline;
    }
</style>
