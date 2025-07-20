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
  header("Location: ./inventory.php");
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
  <?php
    $title = 'FortunePOS - Inventory';
    include_once '../includes/head.php';
  ?>
<body>
  <div class="container">
    <?php
      include_once '../includes/sidebar.php';
    ?>
    <main class="main-content">
      <?php
        include_once '../includes/header.php';
      ?>
      <div style='width:100%; display:flex; flex-direction:row; justify-items: space-around; gap:100px;'>

        
      
      <section class="inventory">
        <div class="card">
          <h2>Inventory Management</h2>
              <input type='text' name='search' class='search-input' placeholder='Search...' id="search-input"/>
              
          <span id="search-result"></span>
          <?php 
            if(isset($_GET['search']) && trim($_GET['search']) != ''){
              ?>
                <span>Results for <b><?php echo $_GET['search'];?></b></span>
              <?php
            }
          ?>
          <table>
            <thead>
              <tr>
                <th>ID</th>
                <th>Product Name</th>
                <th>Stock Left</th>
                <th>Price</th>
              </tr>
            </thead>
            <tbody id="product-table">
              <?php foreach ($products as $product): ?>
                <tr onclick="selectRow(this)">
                  
                  <td><?= $product['id'] ?></td>
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
      <section class='low-stock'>
        <div >
          <h2>Items Low On Stock (3 and Below)</h2>
            <table>
            <thead>
              <tr>
                <th>ID</th>
                <th>Product Name</th>
                <th>Stock Left</th>
              </tr>
            </thead>
            <tbody >
              <?php foreach ($products as $product): 
                if($product['stock'] <= 3){

                 ?>
                <tr>
                  
                  <td><?= $product['id'] ?></td>
                  <td><?= htmlspecialchars($product['name']) ?></td>
                  <td><?= $product['stock'] ?></td>
                </tr>
              <?php } endforeach;  ?>
            </tbody>
            </table>
        </div>
      </section>
      </div>
      

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

    function filterTable() {
      const query = document.getElementById('search-input').value.toLowerCase();
      const rows = document.querySelectorAll('#product-table tr');
      let count = 0;
      rows.forEach(row => {
        const name = row.children[0].innerText.toLowerCase();
        const id = name;
        if (name.includes(query) || id.includes(query)) {
          row.style.display = '';
          count++;
        } else {
          row.style.display = 'none';
        }
      });
      document.getElementById('search-result').innerHTML = query ? `Results for <b>${query}</b>: ${count}` : '';
    }

    document.getElementById('search-input').addEventListener('input', filterTable);
  </script>
</body>
</html>
