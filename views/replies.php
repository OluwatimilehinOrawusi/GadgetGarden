<?php
session_start();

$pdo = require_once "../database/database.php";

// Fetch the user ID from the session
$user_id = $_SESSION['user_id'] ?? null;

// If user is not logged in, redirect to login page
if (!$user_id) {
    header("Location: ../login.php");
    exit();
}

// Fetch user details
$stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = :user_id");
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Fetch questions and replies only for the logged-in user
$stmt = $pdo->prepare("
    SELECT c.id AS query_id, c.name, c.email, c.phone, c.message, c.created_at AS question_date,
           r.reply_message, r.created_at AS reply_date
    FROM contact c
    LEFT JOIN replies r ON c.id = r.query_id
    WHERE c.user_id = :user_id
    ORDER BY c.created_at DESC, r.created_at ASC
");


$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();

$questions = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Questions - Gadget Garden</title>

    <link rel="stylesheet" href="../public/css/navbar.css">
    <link rel="stylesheet" href="../public/css/styles.css">
    <link rel="stylesheet" href="../public/css/replies.css">
</head>
<body>

<?php if ($user && ($user['role'] === 'admin' || $user['role'] === 'manager')){ ?>
    <!-- Admin Navbar -->
    <nav>
    <div class="nav-left">
        <a href="../index.php"><p id="logo-text">GADGET GARDEN</p></a>
    </div>
    <div class="nav-right">
        <a href="./dashboard.php"><button class="white-button">Dashboard</button></a>
        <?php if($user&&$user['role']==='admin'){?>
        <a href="manage_users.php"><button class="white-button">Users</button></a>
        <?php } ?>
        <a href="manage_orders.php"><button class="white-button">Orders</button></a>
        <a href="admin.php"><button class="white-button">Inventory</button></a>
        <a href="logout.php"><button class="green-button">Logout</button></a>
    </div>
</nav>
<?php } else {?>
    <nav>
    <div class="nav-left">
        <a href="../index.php"><p id="logo-text">GADGET GARDEN</p></a>
    </div>
    <div class="nav-right">
        <a href="../views/contact.php"><button class="green-button">Contact Us</button></a>
        <a href="../views/aboutpage.php"><button class="white-button">About Us</button></a>
        <?php if (!isset($_SESSION['user_id'])) { ?>
            <a href="./login.php"><button class="green-button">Login</button></a>
            <a href="./signup.php"><button class="white-button">Sign Up</button></a>
        <?php } ?>
        <a href="../views/products.php"><button class="green-button">Products</button></a>
        <?php if (isset($_SESSION['user_id'])) { ?>
            <a href="./basket.php"><button class="white-button">Basket</button></a>
            <a href="./profile.php"><button class="white-button">Profile</button></a>       
            <a href="./logout.php"><button class="green-button">Logout</button></a>
        <?php }  ?>
    </div>
</nav>

<?php } ?>


            

    <div class="container">
        <h1>Your Questions and Replies</h1>

        <table>
            <thead>
                <tr>
                    <th>Query ID</th>
                    <th>Customer Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Message</th>
                    <th>Reply</th>
                    <th>Date Replied</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($questions as $question): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($question['query_id']); ?></td>
                        <td><?php echo htmlspecialchars($question['name']); ?></td>
                        <td><?php echo htmlspecialchars($question['email']); ?></td>
                        <td><?php echo htmlspecialchars($question['phone']); ?></td>
                        <td><?php echo htmlspecialchars($question['message']); ?></td>
                        <td>
                            <?php if ($question['reply_message']): ?>
                                <p><?php echo htmlspecialchars($question['reply_message']); ?></p>
                            <?php else: ?>
                                <p>No reply yet.</p>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($question['reply_date']): ?>
                                <p><?php echo htmlspecialchars($question['reply_date']); ?></p>
                            <?php else: ?>
                                <p>No reply yet.</p>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
