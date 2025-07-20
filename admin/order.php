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

$stmt = $conn->prepare("SELECT * FROM products WHERE stock > 0");


$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);


?>

<!DOCTYPE html>
<html lang="en">
  <?php 
    $title = 'FortunePOS - Order';
    include_once '../includes/head.php';
  ?>
<body>
  <?php require_once '../includes/sidebar.php';?>

  <div class="main-content">
    <?php include_once '../includes/header.php';?>

    <div class="order-page">
      
      <div class="product-list">
        <div style='display:flex; flex-direction:row; justify-content:space-around; align-items:center;'>
          <h3>Available Products</h3>
          <input type='text' id='search-input' class='search-input' placeholder='Search products...' oninput='filterProducts()'>
          <span id="search-result"></span>
        </div>
        
        <?php foreach ($products as $product): ?>
          <div class="product"
               data-id="<?= $product['id'] ?>"
               data-name="<?= htmlspecialchars($product['name']) ?>"
               data-price="<?= $product['price'] ?>"
               data-stock="<?= $product['stock'] ?>"
               id="product-<?= $product['id'] ?>">
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

function filterProducts() {
  const query = document.getElementById('search-input').value.toLowerCase();
  const products = document.querySelectorAll('.product');
  let count = 0;
  products.forEach(product => {
    const name = product.dataset.name.toLowerCase();
    const id = product.dataset.id.toLowerCase();
    if (name.includes(query) || id.includes(query)) {
      product.style.display = '';
      count++;
    } else {
      product.style.display = 'none';
    }
  });
  document.getElementById('search-result').innerHTML = query ? `Results for <b>${query}</b>: ${count}` : '';
}
</script>
</body>
</html>
