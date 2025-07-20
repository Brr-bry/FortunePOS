<?php
include '../includes/db.php';
date_default_timezone_set('Asia/Manila');
error_reporting(E_ALL);
ini_set('display_errors', 1);

$today = date('Y-m-d');

$stmt = $conn->prepare("
  SELECT 
    SUM(oi.quantity) as total_sales, 
    SUM(oi.quantity * oi.price) as total_revenue
  FROM order_items oi
  JOIN orders o ON o.id = oi.order_id
  WHERE DATE(o.created_at) = ?
");
$stmt->execute([$today]);
$result = $stmt->fetch(PDO::FETCH_ASSOC);
$total_sales = $result['total_sales'] ?? 0;
$total_revenue = $result['total_revenue'] ?? 0;

$stmt2 = $conn->prepare("SELECT COUNT(*) as total_orders FROM orders WHERE DATE(created_at) = ?");
$stmt2->execute([$today]);
$result2 = $stmt2->fetch(PDO::FETCH_ASSOC);
$total_orders = $result2['total_orders'] ?? 0;

$stmt3 = $conn->query("
  SELECT product_name as name, SUM(quantity) as sold_qty 
  FROM order_items 
  GROUP BY product_name 
  ORDER BY sold_qty DESC 
  LIMIT 3
");
$top_items = $stmt3->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode([
  'total_sales' => $total_sales,
  'total_revenue' => $total_revenue,
  'top_items' => $top_items,
  'total_orders' => $total_orders
]);
?>
