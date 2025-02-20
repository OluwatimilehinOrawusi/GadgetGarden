<?php
session_start();
require_once "../database/database.php";


if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}


$users = $pdo->query("SELECT user_id, username, email, role FROM users")->fetchAll(PDO::FETCH_ASSOC);


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_role'])) {
    $user_id = intval($_POST['user_id']);
    $new_role = $_POST['role'];

   
    if (!in_array($new_role, ['admin', 'manager', 'user'])) {
        echo "<script>alert('Invalid role selected.');</script>";
    } else {
        $stmt = $pdo->prepare("UPDATE users SET role = ? WHERE user_id = ?");
        $stmt->execute([$new_role, $user_id]);

        echo "<script>alert('User role updated successfully!'); window.location.href='manage_users.php';</script>";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users - Gadget Garden</title>
    <link rel="stylesheet" href="../public/css/navbar.css">
    <link rel="stylesheet" href="../public/css/admin.css">
</head>
<body>


<nav>
    <div class="nav-left">
        <a href="../index.php"><p id="logo-text">GADGET GARDEN</p></a>
    </div>
    <div class="nav-right">
        <a href="admin_dashboard.php"><button class="white-button">Dashboard</button></a>
        <a href="manage_users.php"><button class="white-button">Users</button></a>
        <a href="admin.php"><button class="white-button">Products</button></a>
        <a href="manage_orders.php"><button class="white-button">Orders</button></a>
        <a href="logout.php"><button class="green-button">Logout</button></a>
    </div>
</nav>

<!-- User Management Section -->
<section class="admin-dashboard">
    <h1>Manage Users</h1>
    <table>
        <tr>
            <th>User ID</th>
            <th>Username</th>
            <th>Email</th>
            <th>Role</th>
            <th>Update Role</th>
        </tr>
        <?php foreach ($users as $user) { ?>
            <tr>
                <td><?php echo htmlspecialchars($user['user_id']); ?></td>
                <td><?php echo htmlspecialchars($user['username']); ?></td>
                <td><?php echo htmlspecialchars($user['email']); ?></td>
                <td><?php echo htmlspecialchars($user['role']); ?></td>
                <td>
                    <form method="POST">
                        <input type="hidden" name="user_id" value="<?php echo $user['user_id']; ?>">
                        <select name="role">
                            <option value="user" <?php echo $user['role'] === 'user' ? 'selected' : ''; ?>>User</option>
                            <option value="manager" <?php echo $user['role'] === 'manager' ? 'selected' : ''; ?>>Manager</option>
                            <option value="admin" <?php echo $user['role'] === 'admin' ? 'selected' : ''; ?>>Admin</option>
                        </select>
                        <button type="submit" name="update_role">Update</button>
                    </form>
                </td>
            </tr>
        <?php } ?>
    </table>
</section>

</body>
</html>
