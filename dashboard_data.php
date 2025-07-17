<?php
include 'db.php';

// Get today's date
$today = date('Y-m-d');

// Total items sold and revenue today
$stmt = $conn->prepare("SELECT SUM(quantity) as total_sales, SUM(quantity * price) as total_revenue FROM orders WHERE DATE(created_at) = ?");
$stmt->execute([$today]);
$result = $stmt->fetch(PDO::FETCH_ASSOC);
$total_sales = $result['total_sales'] ?? 0;
$total_revenue = $result['total_revenue'] ?? 0;

// Total unique orders today (based on distinct timestamp)
$stmt3 = $conn->prepare("SELECT COUNT(DISTINCT created_at) as total_orders FROM orders WHERE DATE(created_at) = ?");
$stmt3->execute([$today]);
$total_orders = $stmt3->fetchColumn();

// Top selling items
$stmt2 = $conn->query("SELECT name, SUM(quantity) as sold_qty FROM orders GROUP BY name ORDER BY sold_qty DESC LIMIT 3");
$top_items = $stmt2->fetchAll(PDO::FETCH_ASSOC);

// Return JSON
echo json_encode([
  'total_sales' => $total_sales,
  'total_revenue' => $total_revenue,
  'top_items' => $top_items,
  'total_orders' => $total_orders
]);
?>
