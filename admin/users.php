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

if ( $_SESSION['role'] !== 'Admin') {
  echo "
  <div style='display:flex;justify-content:center;align-items:center;height:100vh;font-family:sans-serif;flex-direction:column;text-align:center;'>
    <h2 style='color:#c0392b;'>Access Denied</h2>
    <p>You do not have permission to view this page.</p>
    <p style='color:#999;'>Redirecting...</p>
    <script>setTimeout(() => window.location.href = './dashboard.php', 5000);</script>
  </div>";
  exit();
}

require '../includes/db.php';
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
      echo "<script>alert('Username already exists.'); window.location.href='./users.php';</script>";
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
  header("Location: ./users.php");
  exit;
}

$stmt = $conn->query("SELECT username, role FROM users ORDER BY id ASC");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
  <?php
    $title = 'FortunePOS - Users';
    include_once '../includes/head.php';
  ?>
<body>
  <?php include_once '../includes/sidebar.php'; ?>

  <div class="main-content">
    <?php include_once '../includes/header.php'; ?>
    <div class="user-page">
      <div class="user-header">
        <h3>User Management</h3>
        

        <button onclick="openAddUserForm()" class="checkout-btn">+ Add User</button>
      </div>
      <div style="margin-bottom: 20px;">
          <input type="text" id="search-user-input" class='search-input' placeholder="Search by Username..." />
          <span id="search-user-result"></span>
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

    function filterUserTable() {
      const query = document.getElementById('search-user-input').value.toLowerCase();
      const rows = document.querySelectorAll('.cart-table tbody tr');
      let count = 0;

      rows.forEach(row => {
        const username = row.children[0].innerText.toLowerCase();
        const role = row.children[1].innerText.toLowerCase();

        const match = username.includes(query) || role.includes(query);
        row.style.display = match ? '' : 'none';

        if (match) count++;
      });

      document.getElementById('search-user-result').innerHTML = query
        ? `Results for <b>${query}</b>: ${count}`
        : '';
    }

    document.getElementById('search-user-input').addEventListener('input', filterUserTable);
  </script>
</body>
</html>
