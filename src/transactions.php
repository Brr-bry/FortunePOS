<?php
require 'db.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Transactions</title>
  <link rel="stylesheet" href="style.css" />
  <link rel="stylesheet" href="transactions.css" />
  <style>
    .table-container {
      background-color: #fff;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 0 8px rgba(0, 0, 0, 0.05);
      overflow-x: auto;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }

    th, td {
      padding: 12px 16px;
      border-bottom: 1px solid #e0e0e0;
      text-align: left;
    }

    th {
      background-color: #f4f6f9;
      font-weight: 600;
    }

    tr:hover {
      background-color: #f0f4ff;
    }

    .header h2 {
      font-size: 24px;
    }

    .item-details {
      margin: 5px 0;
      font-size: 14px;
      color: #444;
    }

    .toggle-btn {
      cursor: pointer;
      color: #5a7eff;
      border: none;
      background: none;
      font-weight: bold;
      font-size: 14px;
    }

    .toggle-btn:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>
  <div class="sidebar">
    <div class="logo">🛒</div>
    <ul>
      <li><a href="index.php"><img src="icons/home.png" alt="Home" /></a></li>
      <li><a href="order.php"><img src="icons/checkout.png" alt="Checkout" /></a></li>
      <li><a href="inventory.php"><img src="icons/inventory.png" alt="Inventory" /></a></li>
      <li><a href="users.php"><img src="icons/user.png" alt="Users" /></a></li>
      <li><a href="transactions.php"><img src="icons/transaction.png" alt="Transactions" /></a></li>
      <li><a href="logout.php"><img src="icons/power.png" alt="Logout" /></a></li>
    </ul>
  </div>

  <div class="main-content">
    <div class="header">
      <h2>Transaction History</h2>
      <input type="search" placeholder="Search..." />
    </div>

    <div class="table-container">
      <table>
        <thead>
          <tr>
            <th>Order ID</th>
            <th>Date</th>
            <th>Total</th>
            <th>Items</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $orders = $conn->query("SELECT * FROM orders ORDER BY id DESC");
          while ($order = $orders->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr class='order-row'>";
            echo "<td>{$order['id']}</td>";
            echo "<td>{$order['created_at']}</td>";
            echo "<td>" . number_format($order['total'], 2) . " PHP</td>";
            echo "<td><button class='toggle-btn' data-id='{$order['id']}'>View Items</button></td>";
            echo "</tr>";

            // Items row (initially hidden)
            $items = $conn->prepare("SELECT * FROM order_items WHERE order_id = ?");
            $items->execute([$order['id']]);

            echo "<tr id='items-{$order['id']}' style='display: none;'><td colspan='4'>";
            echo "<div class='item-details-wrapper'>";
            while ($item = $items->fetch(PDO::FETCH_ASSOC)) {
              echo "<div class='item-details'>🛒 {$item['product_name']} × {$item['quantity']} — " . number_format($item['price'], 2) . " PHP</div>";
            }
            echo "</div></td></tr>";
          }
          ?>
        </tbody>
      </table>
    </div>
  </div>

  <script>
    document.querySelectorAll(".toggle-btn").forEach(btn => {
      btn.addEventListener("click", () => {
        const id = btn.getAttribute("data-id");
        const row = document.getElementById("items-" + id);
        if (row) {
          row.style.display = row.style.display === "none" ? "table-row" : "none";
        }
      });
    });
  </script>
</body>
</html>
