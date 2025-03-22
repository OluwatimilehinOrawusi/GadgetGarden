<?php
session_start();
require_once "../database/database.php";

$user = null;
if (isset($_SESSION['user_id'])) {
    $stmt = $pdo->prepare("SELECT role FROM users WHERE user_id = :user_id");
    $stmt->execute(['user_id' => $_SESSION['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
}

try {
 
    // SQL to fetch inventory data (name and stock)
    $stmt = $pdo->prepare("SELECT * FROM products");
    $stmt->execute();
    $inventory = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Alerts array to store any inventory issues
    $alerts = [];
    
    // Define minimum stock threshold
    $min_stock = 10;

    // Check each item for stock level issues
    foreach ($inventory as $item) {
        if ($item['stock'] < $min_stock) {
            $alerts[] = ['type' => 'stock', 'message' => $item['name'] . ' is below minimum stock level.'];
        }
    }
} catch (PDOException $e) {
    // Error handling in case of database connection failure
    die("Could not connect to the database: " . $e->getMessage());
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory Alerts</title>
    <link rel="stylesheet" href="../public/css/navbar.css">
    <?php require_once "../partials/header.php" ?>
    <link rel="stylesheet" href="styles.css">
    <style>
       

/* Body and container styling */
body {
    font-family: Arial, sans-serif;
    background-color: white;
   
}

.container {
    width: 80%;
    margin: 0 auto;
    padding: 20px;
    background-color: #fff;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

h1 {
    text-align: center;
    color: #5c5c5c;
}

/* Alerts styling */
.alerts {
    margin-top: 20px;
}

.alert {
    padding: 15px;
    margin: 10px 0;
    border-radius: 5px;
    font-size: 1rem;
}

.alert p {
    margin: 0;
}

.alert.stock {
    background-color: #f8d7da;
    border: 1px solid #f5c6cb;
    color: #721c24;
}

.no-alerts {
    text-align: center;
    font-size: 1.2rem;
    color: #6c757d;
}

/* Optional responsive design */
@media (max-width: 600px) {
    .container {
        width: 95%;
    }
}

        </style>
</head>
<body>

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

    <div class="container">
        <h1>Inventory Alerts</h1>

        <?php if (empty($alerts)): ?>
            <p class="no-alerts">No alerts at the moment.</p>
        <?php else: ?>
            <div class="alerts">
                <?php foreach ($alerts as $alert): ?>
                    <div class="alert <?php echo $alert['type']; ?>">
                        <p><?php echo $alert['message']; ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

</body>
</html>


