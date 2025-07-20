<?php
session_start();
if (isset($_SESSION['username'])) {
  header("Location: ./admin/dashboard.php");
  exit();
}

$error = $_SESSION['login_error'] ?? '';
unset($_SESSION['login_error']);
?>
<!DOCTYPE html>
<html lang="en">
  <?php
    $title = "FortunePOS";

    require_once './includes/head.php';
    echo "<link rel='icon' href='./admin/icons/logo.png'>";
  ?>
<body class="login-body">
  <div class="login-container">
    <h2>Welcome to <span>Fortune POS</span></h2>
    <?php if ($error): ?>
      <div class="error-message"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    
    <form class="login-form" action="process_login.php" method="post" autocomplete="off">
      <label for="username">Username</label>
      <input type="text" id="username" name="username" placeholder="Enter username" autocomplete="off" required />

      <label for="password">Password</label>
      <input type="password" id="password" name="password" placeholder="Enter password" autocomplete="new-password" required />

      <button type="submit">Login</button>
    </form>
  </div>
</body>
</html>
