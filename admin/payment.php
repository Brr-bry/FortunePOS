<!DOCTYPE html>
<html lang="en">
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

  $title = 'FortunePOS - Payment';
  require_once '../includes/head.php';
?>
<body>
  <div class="main-content">
    <div class="checkout-container">
      <div class="checkout-box">
        <div class="summary-box">
          <h3>Payment</h3>
          <div id="payment-section"></div>
        </div>
      </div>
    </div>
  </div>
  <script>
    const paymentMethod = localStorage.getItem("paymentMethod");
    const totalDue = parseFloat(localStorage.getItem("totalDue") || "0").toFixed(2);
    const paymentSection = document.getElementById("payment-section");


    let html = `<div><strong>Amount Due:</strong> ${totalDue} PHP</div>`;

    if (paymentMethod === "Cash") {
      html += `
        <div style="margin-top:10px;">
          <label for="amount-received"><strong>Amount Received:</strong></label>
          <input type="number" id="amount-received" class='num-input' min="${totalDue}" step="0.01" required>
        </div>
        <div style="margin-top:10px;">
          <strong>Change:</strong> <span id="change">0.00 PHP</span>
        </div>
        <button id="go-back" class="complete-order-btn delete"  style="margin-top:20px; background-color:#dc3545;">Go Back</button>
        <button id="confirm-payment" class="complete-order-btn" style="margin-top:20px;">Confirm Payment</button>
      `;
      paymentSection.innerHTML = html;
      const amountInput = document.getElementById("amount-received");
      const changeSpan = document.getElementById("change");
      amountInput.addEventListener("input", function() {
        const received = parseFloat(amountInput.value) || 0;
        const change = received - parseFloat(totalDue);
        changeSpan.textContent = (change >= 0 ? change.toFixed(2) : "0.00") + " PHP";
      });
      document.getElementById("confirm-payment").addEventListener("click", function() {
        const received = parseFloat(amountInput.value) || 0;
        if (received < parseFloat(totalDue)) {
          alert("Amount received is less than amount due.");
          return;
        }
        localStorage.setItem("amountReceived", received.toFixed(2));
        localStorage.setItem("change", (received - parseFloat(totalDue)).toFixed(2));

        // Send order to backend
        const cart = JSON.parse(localStorage.getItem("cart") || "[]");
        fetch("complete_order.php", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({
            cart,
            amount_due: totalDue,
            amount_received: received.toFixed(2),
            change_amount: (received - parseFloat(totalDue)).toFixed(2),
            payment_method: paymentMethod
          })
        })
          .then(res => res.text())
          .then(data => {
            if (data.trim() === "success") {
              window.location.href = "./receipt.php";
            } else {
              alert("Order failed: " + data);
            }
          })
          .catch(err => {
            alert("Something went wrong.");
            console.error(err);
          });
      });
    } else {
      html += `
        <div style="margin-top:10px;">
          <label>Confirm when payment is received via GCash.</label>
        </div>
        
        <button id="go-back" class="complete-order-btn delete"  style="margin-top:20px; background-color:#dc3545;">Go Back</button>
        <button id="confirm-payment" class="complete-order-btn" style="margin-top:20px;">Payment Received</button>
      `;
      paymentSection.innerHTML = html;
      document.getElementById("confirm-payment").addEventListener("click", function() {
        localStorage.setItem("amountReceived", totalDue);
        localStorage.setItem("change", "0.00");

        // Send order to backend
        const cart = JSON.parse(localStorage.getItem("cart") || "[]");
        fetch("complete_order.php", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({
            cart,
            amount_due: totalDue,
            amount_received: totalDue,
            change_amount: "0.00",
            payment_method: paymentMethod
          })
        })
          .then(res => res.text())
          .then(data => {
            if (data.trim() === "success") {
              window.location.href = "./receipt.php";
            } else {
              alert("Order failed: " + data);
            }
          })
          .catch(err => {
            alert("Something went wrong.");
            console.error(err);
          });
      });
    }
    
    const backBtn = document.getElementById('go-back');
    
    backBtn.addEventListener("click", () => {
      window.location.href = "checkout.php";
    });
  </script>
</body>
</html>