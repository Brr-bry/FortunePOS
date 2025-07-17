<?php
require 'db.php';

$data = json_decode(file_get_contents("php://input"), true);
$cart = $data['cart'] ?? [];

if (empty($cart)) {
  http_response_code(400);
  echo "Cart is empty.";
  exit;
}

foreach ($cart as $item) {
  $name = $item['name'];
  $qty = (int)$item['qty'];
  $price = (float)$item['price'];

  // Get current stock
  $stmt = $conn->prepare("SELECT stock FROM products WHERE name = ?");
  $stmt->execute([$name]);
  $row = $stmt->fetch(PDO::FETCH_ASSOC);

  if (!$row) {
    http_response_code(404);
    echo "Product not found: " . $name;
    exit;
  }

  $currentStock = (int)$row['stock'];
  $newStock = $currentStock - $qty;

  if ($newStock < 0) {
    http_response_code(400);
    echo "Not enough stock for: " . $name;
    exit;
  }

  // Update stock
  $update = $conn->prepare("UPDATE products SET stock = ? WHERE name = ?");
  $update->execute([$newStock, $name]);

  // 🔥 INSERT into orders table
  $insert = $conn->prepare("INSERT INTO orders (name, quantity, price, created_at) VALUES (?, ?, ?, NOW())");
  $insert->execute([$name, $qty, $price]);
}

echo "success";
?>
