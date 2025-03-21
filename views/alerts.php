<?php
session_start();
require_once "../database/database.php";


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
    <link rel="stylesheet" href="style.css">
    <style>
        /* Basic reset */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

/* Body and container styling */
body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    color: #333;
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


