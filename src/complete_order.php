<?php
require 'db.php';

$data = json_decode(file_get_contents("php://input"), true);
$cart = $data['cart'] ?? [];

if (empty($cart)) {
  http_response_code(400);
  echo "Cart is empty.";
  exit;
}

$total = 0;
foreach ($cart as $item) {
  $total += $item['price'] * $item['qty'];
}

// INSERT the main order
$insertOrder = $conn->prepare("INSERT INTO orders (total) VALUES (?)");
$insertOrder->execute([$total]);
$order_id = $conn->lastInsertId();

// Insert items and update stock
foreach ($cart as $item) {
  $name = $item['name'];
  $qty = (int)$item['qty'];
  $price = (float)$item['price'];

  $stmt = $conn->prepare("SELECT stock FROM products WHERE name = ?");
  $stmt->execute([$name]);
  $row = $stmt->fetch(PDO::FETCH_ASSOC);

  if (!$row) {
    http_response_code(404);
    echo "Product not found: " . $name;
    exit;
  }

  $currentStock = (int)$row['stock'];
  if ($currentStock < $qty) {
    http_response_code(400);
    echo "Not enough stock for: " . $name;
    exit;
  }

  $newStock = $currentStock - $qty;
  $update = $conn->prepare("UPDATE products SET stock = ? WHERE name = ?");
  $update->execute([$newStock, $name]);

  $insertItem = $conn->prepare("INSERT INTO order_items (order_id, product_name, quantity, price) VALUES (?, ?, ?, ?)");
  $insertItem->execute([$order_id, $name, $qty, $price]);
}

echo "success";
?>
