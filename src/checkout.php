<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Checkout - Fortune POS</title>
  <link rel="stylesheet" href="checkout.css" />
</head>
<body>
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
          <button id="complete-order" class="complete-order-btn">Complete Order</button>
        </div>
      </div>
      <div class="cart-right">
        <!-- Right side reserved for future -->
      </div>
    </div>
  </div>

  <script>
    const cart = JSON.parse(localStorage.getItem("cart") || "[]");
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
            <div>${item.price} PHP ×${item.qty}</div>
          </div>
        </div>
      `;
      cartItemsContainer.appendChild(itemDiv);
      subtotal += item.price * item.qty;
    });

    const tax = subtotal * 0.10;
    const total = subtotal + tax;

    subtotalSpan.textContent = `${subtotal} PHP`;
    taxSpan.textContent = `${tax.toFixed(0)} PHP`;
    totalSpan.textContent = `${total.toFixed(0)} PHP`;

    // ✅ Send order to backend when Complete Order is clicked
    document.getElementById("complete-order").addEventListener("click", function () {
      fetch("complete_order.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({ cart }),
      })
        .then((res) => res.text())
        .then((data) => {
          if (data.trim() === "success") {
            window.location.href = "receipt.html"; // redirect after success
          } else {
            alert("Order failed: " + data);
          }
        })
        .catch((err) => {
          alert("Something went wrong.");
          console.error(err);
        });
    });
  </script>
</body>
</html>
