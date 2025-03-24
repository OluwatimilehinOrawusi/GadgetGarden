<?php
ob_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "START<br>";

session_start();
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
