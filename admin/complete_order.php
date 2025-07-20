<?php


session_start();
  if (!isset($_SESSION['username'])) {
    echo "
    <div style='display:flex;justify-content:center;align-items:center;height:100vh;font-family:sans-serif;flex-direction:column;text-align:center;'>
      <h2 style='color:#c0392b;'>Access Denied</h2>
      <p>You do not have permission to view this page.</p>
      <p style='color:#999;'>Redirecting...</p>
      <script>setTimeout(() => window.location.href = '../login.php', 5000);</script>
    </div>";
    exit();
  }

require '../includes/db.php';

$data = json_decode(file_get_contents("php://input"), true);
$cart = $data['cart'] ?? [];
$amount_due = $data['amount_due'] ?? 0;
$amount_received = $data['amount_received'] ?? 0;
$change_amount = $data['change_amount'] ?? 0;
$payment_method = $data['payment_method'] ?? '';

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
$insertOrder = $conn->prepare("INSERT INTO orders (total, amount_due, amount_received, change_amount, payment_method) VALUES (?, ?, ?, ?, ?)");
$insertOrder->execute([$total, $amount_due, $amount_received, $change_amount, $payment_method]);
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
