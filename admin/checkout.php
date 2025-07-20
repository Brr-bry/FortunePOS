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

  $title = 'FortunePOS - Checkout';
  require_once '../includes/head.php';
?>
<body>
  <div class="main-content">
  <div class="checkout-container">
    <div class="checkout-box">
      <div class="cart-left">
        <h2>SHOPPING CART</h2>
        <div id="cart-items"></div>

        <div class="summary-box">
          <h3>ORDER SUMMARY</h3>
          <p class="date" id="order-date"></p>
          <div class="summary-details">
            <div class="row">
              <span>Sub Total</span>
              <span id="subtotal">0 PHP</span>
            </div>
            <div class="row">
              <span>Tax (10%)</span>
              <span id="tax">0 PHP</span>
            </div>
            <div class="row total-row">
              <strong>Total</strong>
              <strong id="total">0 PHP</strong>
            </div>
          </div>
          <div class="row" style="margin-bottom:15px;">
            <label for="payment-method"><strong>Payment Method:</strong></label>
            <select id="payment-method">
              <option value="Cash">Cash</option>
              <option value="GCash">GCash</option>
            </select>
          </div>
            <button id="complete-order" class="complete-order-btn">Complete Order</button>
            <button id="go-back" class="complete-order-btn delete"  style="margin-top:20px; background-color:#dc3545; ">Go Back</button>
          
          
        </div>
      </div>
    </div>
  </div>
  </div>
  <script>
    // Get cart data from browser storage (saved earlier)
    const cart = JSON.parse(localStorage.getItem("cart") || "[]");

    // Get references to the elements we want to update
    const cartItemsContainer = document.getElementById("cart-items");
    const subtotalSpan = document.getElementById("subtotal");
    const taxSpan = document.getElementById("tax");
    const totalSpan = document.getElementById("total");

    const dateNow = new Date().toLocaleDateString('en-GB', {
      day: '2-digit', month: 'short', year: 'numeric'
    });
    document.getElementById("order-date").textContent = dateNow;

    let subtotal = 0;

    cart.forEach(item => {
      const itemDiv = document.createElement("div");
      itemDiv.className = "item";
      itemDiv.innerHTML = `
        <div class="product-line">
          <div>
            <div><strong>${item.name}</strong></div>
            <div>${item.price.toFixed(2)} PHP ×${item.qty}</div>
          </div>
        </div>
      `;
      cartItemsContainer.appendChild(itemDiv);
      subtotal += item.price * item.qty; 
    });

    const tax = subtotal * 0.10;
    const total = subtotal + tax;

    subtotalSpan.textContent = `${subtotal.toFixed(2)} PHP`;
    taxSpan.textContent = `${tax.toFixed(2)} PHP`;
    totalSpan.textContent = `${total.toFixed(2)} PHP`;

    document.getElementById("complete-order").addEventListener("click", function () {
      const paymentMethod = document.getElementById("payment-method").value;
      localStorage.setItem("paymentMethod", paymentMethod);
      localStorage.setItem("totalDue", total.toFixed(2));
      localStorage.setItem("cart", JSON.stringify(cart));
      window.location.href = "./payment.php";
    });

    const backBtn = document.getElementById('go-back');
    
    backBtn.addEventListener("click", () => {
      window.location.href = "./order.php";
    });
  </script>
</body>
</html>
