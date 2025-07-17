<?php
session_start();
require 'db.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'Admin') {
  echo "
  <div style='display:flex;justify-content:center;align-items:center;height:100vh;font-family:sans-serif;flex-direction:column;text-align:center;'>
    <h2 style='color:#c0392b;'>Access Denied</h2>
    <p>You do not have permission to view this page.</p>
    <p style='color:#999;'>Redirecting...</p>
    <script>setTimeout(() => window.location.href = 'index.html', 5000);</script>
  </div>";
  exit();
}

// Handle POST: Add or Edit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $original_username = trim($_POST['original_username']);
  $username = trim($_POST['username']);
  $password = trim($_POST['password']);
  $role = $_POST['role'];
  $is_edit = isset($_POST['edit']) && $_POST['edit'] === "1";

  if ($original_username === 'admin') {
    if ($username !== 'admin' || $role !== 'Admin') {
      echo "<script>alert('Cannot modify main admin account.'); window.location.href='users.php';</script>";
      exit;
    }
  }

  if ($is_edit) {
    if ($username !== $original_username) {
      $check = $conn->prepare("SELECT * FROM users WHERE username = ?");
      $check->execute([$username]);
      if ($check->rowCount() > 0) {
        echo "<script>alert('Username already exists.'); window.location.href='users.php';</script>";
        exit;
      }
    }

    if (!empty($password)) {
      $stmt = $conn->prepare("UPDATE users SET username = ?, password = ?, role = ? WHERE username = ?");
      $stmt->execute([$username, $password, $role, $original_username]);
    } else {
      $stmt = $conn->prepare("UPDATE users SET username = ?, role = ? WHERE username = ?");
      $stmt->execute([$username, $role, $original_username]);
    }

  } else {
    if (empty($password)) {
      echo "<script>alert('Password is required when adding a user.'); window.location.href='users.php';</script>";
      exit;
    }

    $check = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $check->execute([$username]);
    if ($check->rowCount() > 0) {
      echo "<script>alert('Username already exists.'); window.location.href='users.php';</script>";
      exit;
    }

    $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
    $stmt->execute([$username, $password, $role]);
  }

  header("Location: users.php");
  exit;
}

// Handle Delete
if (isset($_GET['delete'])) {
  $username = $_GET['delete'];
  if ($username === 'admin') {
    echo "<script>alert('Cannot delete admin account.'); window.location.href='users.php';</script>";
    exit;
  }
  $stmt = $conn->prepare("DELETE FROM users WHERE username = ?");
  $stmt->execute([$username]);
  header("Location: users.php");
  exit;
}

$stmt = $conn->query("SELECT username, role FROM users ORDER BY id ASC");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>User Management - Fortune POS</title>
  <link rel="stylesheet" href="style.css" />
  <link rel="stylesheet" href="users.css" />
</head>
<body>
  <div class="sidebar">
    <div class="logo">🛒</div>
    <ul>
      <li><a href="index.html"><img src="icons/home.png" alt="Home" /></a></li>
      <li><a href="order.html"><img src="icons/checkout.png" alt="Order" /></a></li>
      <li><a href="inventory.html"><img src="icons/inventory.png" alt="Inventory" /></a></li>
      <li><a href="users.php"><img src="icons/user.png" alt="Users" /></a></li>
      <li><a href="logout.php"><img src="icons/power.png" alt="Logout" /></a></li>
    </ul>
  </div>

  <div class="main-content">
    <div class="header">
      <h2>Retail Business Co.</h2>
      <input type="search" placeholder="Search..." />
    </div>

    <div class="user-page">
      <div class="user-header">
        <h3>User Management</h3>
        <button onclick="openAddUserForm()" class="checkout-btn">+ Add User</button>
      </div>

      <table class="cart-table">
        <thead>
          <tr>
            <th>Username</th>
            <th>Role</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($users as $user): ?>
            <tr>
              <td><?= htmlspecialchars($user['username']) ?></td>
              <td><?= htmlspecialchars($user['role']) ?></td>
              <td>
                <button onclick="editUser('<?= $user['username'] ?>', '<?= $user['role'] ?>')" class="edit">Edit</button>
                <?php if ($user['username'] !== 'admin'): ?>
                  <a href="users.php?delete=<?= urlencode($user['username']) ?>" class="delete">Delete</a>
                <?php endif; ?>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Modal -->
  <div id="user-form-modal" class="modal" style="display: none;">
    <form class="modal-content" method="POST" action="users.php">
      <h3 id="form-title">Add User</h3>
      <input type="hidden" name="edit" id="edit-flag" value="0">
      <input type="hidden" name="original_username" id="original-username">

      <input type="text" name="username" id="username" placeholder="Username" required />
      <input type="password" name="password" id="password" placeholder="Password" />
      <select name="role" id="role" required>
        <option value="" disabled selected>Select Role</option>
        <option value="Admin">Admin</option>
        <option value="Staff">Staff</option>
      </select>
      <div class="modal-buttons">
        <button type="submit" class="checkout-btn">Save</button>
        <button type="button" onclick="closeForm()" class="delete">Cancel</button>
      </div>
    </form>
  </div>

  <script>
    function openAddUserForm() {
      document.getElementById("form-title").innerText = "Add User";
      document.getElementById("edit-flag").value = "0";
      document.getElementById("original-username").value = "";
      document.getElementById("username").value = "";
      document.getElementById("username").disabled = false;
      document.getElementById("password").value = "";
      document.getElementById("password").required = true;
      document.getElementById("password").placeholder = "Password"; 
      document.getElementById("role").value = "";
      document.getElementById("role").disabled = false;
      document.getElementById("user-form-modal").style.display = "flex";
    }

    function editUser(username, role) {
      document.getElementById("form-title").innerText = "Edit User";
      document.getElementById("edit-flag").value = "1";
      document.getElementById("original-username").value = username;
      document.getElementById("username").value = username;
      document.getElementById("username").disabled = false;
      document.getElementById("password").value = "";
      document.getElementById("password").required = false;
      document.getElementById("password").placeholder = "New Password (optional)"; 
      document.getElementById("role").value = role;
      document.getElementById("role").disabled = (username === "admin");
      document.getElementById("user-form-modal").style.display = "flex";
    }


    function closeForm() {
      document.getElementById("user-form-modal").style.display = "none";
    }
  </script>
</body>
</html>
