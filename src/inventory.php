<?php
session_start();
require 'db.php';

// Add or Edit
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $action = $_POST['action'];
  $name = trim($_POST['name']);
  $stock = (int)$_POST['stock'];
  $price = (float)$_POST['price'];

  if ($action === "add") {
    $stmt = $conn->prepare("INSERT INTO products (name, stock, price) VALUES (?, ?, ?)");
    $stmt->execute([$name, $stock, $price]);
  } elseif ($action === "edit") {
    $id = (int)$_POST['id'];
    $stmt = $conn->prepare("UPDATE products SET name=?, stock=?, price=? WHERE id=?");
    $stmt->execute([$name, $stock, $price, $id]);
  }
  header("Location: inventory.php");
  exit;
}

// Delete
if (isset($_GET['delete'])) {
  $id = (int)$_GET['delete'];
  $stmt = $conn->prepare("DELETE FROM products WHERE id=?");
  $stmt->execute([$id]);
  header("Location: inventory.php");
  exit;
}

$stmt = $conn->query("SELECT * FROM products ORDER BY id ASC");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Inventory Management</title>
  <link rel="stylesheet" href="inventory.css">
</head>
<body>
  <div class="container">
    <aside class="sidebar">
      <div class="logo">🛒</div>
      <nav>
        <a href="index.php"><img src="icons/home.png" alt="Home"></a>
        <a href="order.php"><img src="icons/checkout.png" alt="Checkout"></a>
        <a href="inventory.php"><img src="icons/inventory.png" alt="Inventory"></a>
        <a href="users.php"><img src="icons/user.png" alt="Users"></a>
        <a href="transactions.php"><img src="icons/transaction.png" alt="Transactions" /></a>
        <a href="logout.php"><img src="icons/power.png" alt="Logout"></a>
      </nav>
    </aside>
    <main class="main-content">
      <header class="inventory-header">
        <h1>Retail Business Co.</h1>
        <input type="search" placeholder="Search...">
      </header>
      <section class="inventory">
        <div class="card">
          <h2>Inventory Management</h2>
          <table>
            <thead>
              <tr>
                <th>Product Name</th>
                <th>Stock Left</th>
                <th>Price</th>
              </tr>
            </thead>
            <tbody id="product-table">
              <?php foreach ($products as $product): ?>
                <tr onclick="selectRow(this)">
                  <td><?= htmlspecialchars($product['name']) ?></td>
                  <td><?= $product['stock'] ?></td>
                  <td><?= number_format($product['price'], 2) ?> PHP</td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
          <div class="buttons">
            <button class="add" onclick="openAdd()">Add</button>
            <button class="edit" onclick="openEdit()">Edit</button>
            <button class="delete" onclick="openDelete()">Delete</button>
          </div>
        </div>
      </section>
    </main>
  </div>

  <!-- Modal -->
  <div id="modal" class="modal">
    <form method="POST" class="modal-content" action="inventory.php">
      <h3 id="modal-title">Add Product</h3>
      <input type="hidden" name="action" id="action" value="add">
      <input type="hidden" name="id" id="product-id">
      <input type="text" name="name" id="product-name" placeholder="Product Name" required>
      <input type="number" name="stock" id="product-stock" placeholder="Stock Left" required>
      <input type="number" step="0.01" name="price" id="product-price" placeholder="Price (PHP)" required>
      <div class="modal-buttons">
        <button type="submit" class="add">Save</button>
        <button type="button" onclick="closeModal()" class="delete">Cancel</button>
      </div>
    </form>
  </div>

  <script>
    let selectedRow = null;
    const ids = <?= json_encode(array_column($products, 'id')) ?>;

    function selectRow(row) {
      const rows = document.querySelectorAll("tbody tr");
      rows.forEach(r => r.style.background = "");
      row.style.background = "#f2f2f2";
      selectedRow = row;
    }

    function openAdd() {
      document.getElementById("modal-title").innerText = "Add Product";
      document.getElementById("action").value = "add";
      document.getElementById("product-id").value = "";
      document.getElementById("product-name").value = "";
      document.getElementById("product-stock").value = "";
      document.getElementById("product-price").value = "";
      document.getElementById("modal").style.display = "flex";
    }

    function openEdit() {
      if (!selectedRow) return alert("Please select a product row first.");
      const cells = selectedRow.children;
      const index = Array.from(selectedRow.parentNode.children).indexOf(selectedRow);
      const id = ids[index];

      document.getElementById("modal-title").innerText = "Edit Product";
      document.getElementById("action").value = "edit";
      document.getElementById("product-id").value = id;
      document.getElementById("product-name").value = cells[0].innerText;
      document.getElementById("product-stock").value = cells[1].innerText;
      document.getElementById("product-price").value = parseFloat(cells[2].innerText);
      document.getElementById("modal").style.display = "flex";
    }

    function openDelete() {
      if (!selectedRow) return alert("Please select a product row first.");
      const index = Array.from(selectedRow.parentNode.children).indexOf(selectedRow);
      const id = ids[index];
      if (confirm("Are you sure you want to delete this product?")) {
        window.location.href = "inventory.php?delete=" + id;
      }
    }

    function closeModal() {
      document.getElementById("modal").style.display = "none";
    }
  </script>
</body>
</html>
