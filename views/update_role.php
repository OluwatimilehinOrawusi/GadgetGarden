<?php
session_start();
require_once "../database/database.php";

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['user_id'])) {
    $user_id = $_POST['user_id'];
    $new_role = $_POST['role'];

    $stmt = $pdo->prepare("UPDATE users SET role = ? WHERE user_id = ?");
    $stmt->execute([$new_role, $user_id]);

    header("Location: manage_users.php");
    exit;
}

if (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];

    $stmt = $pdo->prepare("SELECT user_id, username, role FROM users WHERE user_id = :user_id");
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
    <title>Update User Role</title>
    <link rel="stylesheet" href="../public/css/navbar.css">
    <link rel="stylesheet" href="../public/css/styles.css">
    <link rel="stylesheet" href="../public/css/update_role.css"> 
</head>
<body>

<h2>Update User Role</h2>

<?php if (isset($user)): ?>
    <form action="" method="POST">
        <input type="hidden" name="user_id" value="<?php echo $user['user_id']; ?>">

        <label for="username">Username</label>
        <input type="text" id="username" name="username" value="<?php echo $user['username']; ?>" disabled>

        <label for="role">Role</label>
        <select name="role" id="role">
            <option value="user" <?php echo $user['role'] == 'user' ? 'selected' : ''; ?>>User</option>
            <option value="manager" <?php echo $user['role'] == 'manager' ? 'selected' : ''; ?>>Manager</option>
            <option value="admin" <?php echo $user['role'] == 'admin' ? 'selected' : ''; ?>>Admin</option>
        </select>

        <button type="submit">Update Role</button>
    </form>
<?php else: ?>
    <p>Please select a user to update by passing their user_id in the URL, e.g., `?user_id=1`.</p>
<?php endif; ?>

</body>
</html>
