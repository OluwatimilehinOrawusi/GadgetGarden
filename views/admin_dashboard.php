<?php
//Session start
session_start();

//Connects to database
require_once "../database/database.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?error=Please+log+in");
    exit();
}

if (!$pdo) {
    echo "Database connection failed.";
    exit();
} else {
    echo "DB connected.<br>";
}

// Check if the user is an admin or manager
$stmt = $pdo->prepare("SELECT role FROM users WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user || ($user['role'] !== 'admin' && $user['role'] !== 'manager')) {
    die("Error: You are not authorized to access this page.");
}

$totalUsers = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$totalOrders = $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn();
$totalRevenue = $pdo->query("SELECT SUM(total_price) FROM orders")->fetchColumn();

$averageOrderValue = ($totalOrders > 0) ? ($totalRevenue / $totalOrders) : 0;

$salesDataQuery = "
    SELECT YEAR(order_date) AS year, MONTH(order_date) AS month, SUM(total_price) AS total_revenue
    FROM orders
    GROUP BY YEAR(order_date), MONTH(order_date)
    ORDER BY YEAR(order_date), MONTH(order_date)
";

$stmt = $pdo->prepare($salesDataQuery);
$stmt->execute();
$salesData = $stmt->fetchAll(PDO::FETCH_ASSOC);

$months = [];
$revenues = [];
foreach ($salesData as $data) {
    $formattedDate = date('F', mktime(0, 0, 0, $data['month'], 10)) . ' (' . $data['year'] . ')';
    $months[] = $formattedDate;
    $revenues[] = $data['total_revenue'];
}

$orderCountQuery = "
    SELECT YEAR(order_date) AS year, MONTH(order_date) AS month, COUNT(order_id) AS order_count
    FROM orders
    GROUP BY YEAR(order_date), MONTH(order_date)
    ORDER BY YEAR(order_date), MONTH(order_date)
";

$stmt = $pdo->prepare($orderCountQuery);
$stmt->execute();
$orderCountsData = $stmt->fetchAll(PDO::FETCH_ASSOC);

$orderCounts = [];
foreach ($orderCountsData as $data) {
    $orderCounts[] = $data['order_count'];
}

$bestSellingProductsQuery = "
    SELECT p.name, SUM(op.quantity) AS total_sales
    FROM order_products op
    JOIN products p ON op.product_id = p.product_id
    GROUP BY p.name
    ORDER BY total_sales DESC
    LIMIT 5
";

$stmt = $pdo->prepare($bestSellingProductsQuery);
$stmt->execute();
$bestSellingProductsData = $stmt->fetchAll(PDO::FETCH_ASSOC);

$productNames = [];
$productSales = [];
foreach ($bestSellingProductsData as $data) {
    $productNames[] = $data['name'];
    $productSales[] = $data['total_sales'];
}

$newCustomersQuery = "
    SELECT YEAR(created_at) AS year, MONTH(created_at) AS month, COUNT(user_id) AS new_customers
    FROM users
    GROUP BY YEAR(created_at), MONTH(created_at)
    ORDER BY YEAR(created_at), MONTH(created_at)
";

$stmt = $pdo->prepare($newCustomersQuery);
$stmt->execute();
$newCustomersData = $stmt->fetchAll(PDO::FETCH_ASSOC);

$customerMonths = [];
$newCustomers = [];
foreach ($newCustomersData as $data) {
    $formattedDate = date('F', mktime(0, 0, 0, $data['month'], 10)) . ' (' . $data['year'] . ')';
    $customerMonths[] = $formattedDate;
    $newCustomers[] = $data['new_customers'];
}
?>


<!-- HTML -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Gadget Garden</title>
    <link rel="stylesheet" href="../public/css/navbar.css">
    <link rel="stylesheet" href="../public/css/styles.css">
    <link rel="stylesheet" href="../public/css/admin.css">
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px; }
        .container { max-width: 900px; margin: auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1); }
        canvas { max-width: 100%; }
        h2 { text-align: center; }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

<!-- Admin Navigation -->
<nav>
    <div class="nav-left">
        <a href="../index.php"><p id="logo-text">GADGET GARDEN</p></a>
    </div>
    <div class="nav-right">
        <a href="dashboard.php"><button class="white-button">Dashboard</button></a>
        <?php if($user&&$user['role']==='admin'){?>
        <a href="manage_users.php"><button class="white-button">Users</button></a>
        <?php } ?>
        <a href="admin.php"><button class="white-button">Products</button></a>
        <a href="manage_orders.php"><button class="white-button">Orders</button></a>
        <a href="logout.php"><button class="green-button">Logout</button></a>
    </div>
</nav>

<!-- Admin Dashboard -->
<section class="admin-dashboard">
    <h1 class="subtitle">Analytics Page</h1>
    <p class="dashboard-description">Gain Insights on Gadget Garden Sales and Inventory</p>

    <div class="dashboard-grid">
        <div class="dashboard-card">
            <h3>Total Users</h3>
            <p><?php echo number_format($totalUsers); ?></p>
        </div>
        <div class="dashboard-card">
            <h3>Total Orders</h3>
            <p><?php echo number_format($totalOrders); ?></p>
        </div>
        <div class="dashboard-card">
            <h3>Total Revenue</h3>
            <p>£<?php echo number_format($totalRevenue, 2); ?></p>
        </div>
    </div>
</section>
<div class="container">
    <h2>Analytics Dashboard</h2>

    <h3>Sales Revenue (Monthly)</h3>
    <canvas id="salesChart"></canvas>

    <h3>Orders Trend</h3>
    <canvas id="ordersChart"></canvas>

    <h3>Best-Selling Products</h3>
    <canvas id="productsChart"></canvas>

    <h3>Customer Growth</h3>
    <canvas id="customersChart"></canvas>
</div>

<script>

      // Sales Data
      var months = <?php echo json_encode($months); ?>;
    var revenues = <?php echo json_encode($revenues); ?>;

    // Orders Data
    var orderCounts = <?php echo json_encode($orderCounts); ?>;

    // Best Selling Products Data
    var productNames = <?php echo json_encode($productNames); ?>;
    var productSales = <?php echo json_encode($productSales); ?>;

    // New Customers Data
    var customerMonths = <?php echo json_encode($customerMonths); ?>;
    var newCustomers = <?php echo json_encode($newCustomers); ?>;


    // Sales Chart (Monthly Revenue)
    var ctx1 = document.getElementById('salesChart').getContext('2d');
    var salesChart = new Chart(ctx1, {
        type: 'line',
        data: {
            labels: months,
            datasets: [{
                label: 'Revenue',
                data: revenues,
                borderColor: 'rgba(75, 192, 192, 1)',
                fill: false
            }]
        }
    });

    // Orders Chart (Order Counts)
    var ctx2 = document.getElementById('ordersChart').getContext('2d');
    var ordersChart = new Chart(ctx2, {
        type: 'bar',
        data: {
            labels: months,
            datasets: [{
                label: 'Orders',
                data: orderCounts,
                backgroundColor: 'rgba(153, 102, 255, 0.2)',
                borderColor: 'rgba(153, 102, 255, 1)',
                borderWidth: 1
            }]
        }
    });

    // Products Chart (Best Selling Products)
    var ctx3 = document.getElementById('productsChart').getContext('2d');
    var productsChart = new Chart(ctx3, {
        type: 'pie',
        data: {
            labels: productNames,
            datasets: [{
                data: productSales,
                backgroundColor: ['red', 'blue', 'green', 'orange', 'purple']
            }]
        }
    });

    // Customers Chart (New Customers)
    var ctx4 = document.getElementById('customersChart').getContext('2d');
    var customersChart = new Chart(ctx4, {
        type: 'bar',
        data: {
            labels: customerMonths,
            datasets: [{
                label: 'New Customers',
                data: newCustomers,
                backgroundColor: 'rgba(255, 159, 64, 0.2)',
                borderColor: 'rgba(255, 159, 64, 1)',
                borderWidth: 1
            }]
        }
    });
</script>

</body>
</html>
