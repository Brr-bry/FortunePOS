<?php
require 'db.php';

// Load products with stock
$stmt = $conn->prepare("SELECT * FROM products WHERE stock > 0");
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Order - Fortune POS</title>
  <link rel="stylesheet" href="order.css" />
</head>
<body>
  <div class="sidebar">
    <div class="logo">🛒</div>
    <ul>
      <li><a href="index.html"><img src="icons/home.png" alt="Home" /></a></li>
      <li><a href="order.php"><img src="icons/checkout.png" alt="Order" /></a></li>
      <li><a href="inventory.php"><img src="icons/inventory.png" alt="Inventory" /></a></li>
      <li><a href="users.php"><img src="icons/user.png" alt="Users" /></a></li>
      <li><a href="transactions.php"><img src="icons/transaction.png" alt="Transactions" /></a></li>
      <li><a href="logout.php"><img src="icons/power.png" alt="Logout" /></a></li>
    </ul>
  </div>

  <div class="main-content">
    <div class="header">
      <h2>Retail Business Co.</h2>
      <input type="search" placeholder="Search..." />
    </div>

    <div class="order-page">
      <div class="product-list">
        <h3>Available Products</h3>
        <?php foreach ($products as $product): ?>
          <div class="product" 
               data-id="<?= $product['id'] ?>" 
               data-name="<?= htmlspecialchars($product['name']) ?>" 
               data-price="<?= $product['price'] ?>" 
               data-stock="<?= $product['stock'] ?>">
            <p><?= htmlspecialchars($product['name']) ?> (₱<?= number_format($product['price'], 2) ?>) - Stock: <span class="stock"><?= $product['stock'] ?></span></p>
            <input type="number" min="1" max="<?= $product['stock'] ?>" value="1" class="qty-input">
            <button onclick="addToCart(this)">Add</button>
          </div>
        <?php endforeach; ?>
      </div>

      <div class="cart-section">
        <h3>Cart</h3>
        <table class="cart-table" id="cart-table">
          <thead>
            <tr>
              <th>Item</th>
              <th>Qty</th>
              <th>Price</th>
              <th>Remove</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
        <div class="total" id="total">Total: ₱0</div>
        <button class="checkout-btn" onclick="goToCheckout()">Checkout</button>
      </div>
    </div>
  </div>

<script>
const cart = [];

function addToCart(button) {
  const productDiv = button.closest('.product');
  const id = productDiv.dataset.id;
  const name = productDiv.dataset.name;
  const price = parseFloat(productDiv.dataset.price);
  const stock = parseInt(productDiv.dataset.stock);
  const qtyInput = productDiv.querySelector('.qty-input');
  const qty = parseInt(qtyInput.value);

  if (qty < 1 || qty > stock) {
    alert("Invalid quantity");
    return;
  }

  const existing = cart.find(item => item.id === id);
  if (existing) {
    if (existing.qty + qty > stock) {
      alert("Not enough stock available.");
      return;
    }
    existing.qty += qty;
  } else {
    cart.push({ id, name, price, qty, stock });
  }

  productDiv.querySelector(".stock").textContent = stock - (existing ? existing.qty : qty);
  updateCartUI();
}

function updateCartUI() {
  const tbody = document.querySelector('#cart-table tbody');
  tbody.innerHTML = '';
  let total = 0;

  cart.forEach((item, i) => {
    const row = document.createElement('tr');
    row.innerHTML = `
      <td>${item.name}</td>
      <td>${item.qty}</td>
      <td>₱${(item.price * item.qty).toFixed(2)}</td>
      <td><button onclick="removeItem(${i})">X</button></td>
    `;
    tbody.appendChild(row);
    total += item.price * item.qty;
  });

  document.getElementById('total').textContent = `Total: ₱${total.toFixed(2)}`;
}

function removeItem(index) {
  const item = cart[index];
  const productDiv = document.querySelector(`.product[data-id="${item.id}"]`);
  const stockSpan = productDiv.querySelector(".stock");
  const newStock = parseInt(stockSpan.textContent) + item.qty;
  stockSpan.textContent = newStock;
  cart.splice(index, 1);
  updateCartUI();
}

function goToCheckout() {
  if (cart.length === 0) {
    alert("Your cart is empty.");
    return;
  }
  localStorage.setItem("cart", JSON.stringify(cart));
  window.location.href = "checkout.php";
}
</script>
</body>
</html>
