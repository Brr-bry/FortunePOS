<!DOCTYPE html>
<html lang="en">
  <?php 
  include_once '../includes/db.php';

  $stmt = $conn->prepare('SELECT COUNT(*) as total FROM orders');
  $stmt->execute();
  $row = $stmt->fetch(PDO::FETCH_ASSOC);
  $transactionID = $row['total'];

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
    require_once '../includes/head.php';
  ?>
<body>
  <div class="receipt-container">
    <div class="receipt-box">
      <h2>THANK YOU!</h2>
      <h3>Transaction ID: <?php echo $transactionID;?> </h3>
      <table>
        <thead>
          <tr>
            <th>Item</th>
            <th>Qty</th>
            <th>Amount</th>
          </tr>
        </thead>
        <tbody id="receipt-items"></tbody>
        <tfoot>
          <tr>
            <td colspan="2">TAX</td>
            <td id="tax">0 PHP</td>
          </tr>
          <tr class="total-row">
            <td colspan="2"><strong>Total:</strong></td>
            <td><strong id="total">0 PHP</strong></td>
          </tr>
          <tr>
            <td colspan="2"><strong>Amount Received:</strong></td>
            <td><strong id="amount-received">0 PHP</strong></td>
          </tr>
          <tr>
            <td colspan="2"><strong>Change:</strong></td>
            <td><strong id="change">0 PHP</strong></td>
          </tr>
        </tfoot>
      </table>

      <p class="date">Invoice Date: <span id="date"></span></p>
    </div>
  </div>

  <script>
    const cart = JSON.parse(localStorage.getItem("cart") || "[]");
    const receiptItems = document.getElementById("receipt-items");
    const taxCell = document.getElementById("tax");
    const totalCell = document.getElementById("total");
    const dateCell = document.getElementById("date");
    const amountReceivedCell = document.getElementById("amount-received");
    const changeCell = document.getElementById("change");

    const date = new Date().toLocaleDateString('en-GB', {
      day: '2-digit',
      month: 'short',
      year: 'numeric'
    });
    dateCell.textContent = date;

    let subtotal = 0;

    cart.forEach(item => {
      const tr = document.createElement("tr");
      tr.innerHTML = `
        <td>${item.name}</td>
        <td>${item.qty}</td>
        <td>${(item.price * item.qty).toFixed(2)} PHP</td>
      `;
      receiptItems.appendChild(tr);
      subtotal += item.price * item.qty;
    });

    const tax = subtotal * 0.10;
    const total = subtotal + tax;

    taxCell.textContent = `${tax.toFixed(2)} PHP`;
    totalCell.textContent = `${total.toFixed(2)} PHP`;

    amountReceivedCell.textContent = `${parseFloat(localStorage.getItem("amountReceived") || "0.00").toFixed(2)} PHP`;
    changeCell.textContent = `${parseFloat(localStorage.getItem("change") || "0.00").toFixed(2)} PHP`;

    // Optional: clear cart
    localStorage.removeItem("cart");

    setTimeout(() => {
      window.location.href = "./dashboard.php";
    }, 5000);
  </script>
</body>
</html>
