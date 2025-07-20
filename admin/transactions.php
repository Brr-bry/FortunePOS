<?php
require '../includes/db.php';
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
?>

<!DOCTYPE html>
<html lang="en">
  <?php
    $title = 'FortunePOS - Transactions';
    include_once '../includes/head.php';
  ?>
<body>
  <?php include_once '../includes/sidebar.php'; ?>


  <div class="main-content">

    <?php include_once '../includes/header.php'; ?>
    <div class="table-container">
      <table>
        <thead>
          <tr>
            <h2>Transactions</h2>
            <div style="margin-bottom: 20px;">
              <input type="text" id="search-input" class='search-input'  placeholder="Search Order ID, Date"   />
              <span id="search-result"></span>
            </div>

          </tr>

          <tr>
            <th>Order ID</th>
            <th>Date</th>
            <th>Total</th>
            <th>Amount Due</th>
            <th>Amount Received</th>
            <th>Change</th>
            <th>Payment Method</th>
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
            echo "<td>" . number_format($order['amount_due'], 2) . " PHP</td>";
            echo "<td>" . number_format($order['amount_received'], 2) . " PHP</td>";
            echo "<td>" . number_format($order['change_amount'], 2) . " PHP</td>";
            
            echo "<td>{$order['payment_method']}</td>";
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

    function filterTable() {
      const query = document.getElementById('search-input').value.toLowerCase();
      const rows = document.querySelectorAll('.order-row');
      let count = 0;

      rows.forEach(row => {
        const orderId = row.children[0].innerText.toLowerCase();
        const date = row.children[1].innerText.toLowerCase();
        const paymentMethod = row.children[6].innerText.toLowerCase();

        const match = orderId.includes(query) || date.includes(query) || paymentMethod.includes(query);

        row.style.display = match ? '' : 'none';

        // Also hide or show the corresponding items row
        const itemsRow = document.getElementById('items-' + orderId);
        if (itemsRow) {
          itemsRow.style.display = match ? 'none' : 'none'; // Keep it hidden unless explicitly toggled
        }

        if (match) count++;
      });

      document.getElementById('search-result').innerHTML = query
        ? `Results for <b>${query}</b>: ${count}`
        : '';
    }

    document.getElementById('search-input').addEventListener('input', filterTable);


  </script>
</body>
</html>
