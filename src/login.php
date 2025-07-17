<?php
session_start();
if (isset($_SESSION['username'])) {
  header("Location: index.php");
  exit();
}
$error = $_SESSION['login_error'] ?? '';
unset($_SESSION['login_error']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Login - Fortune POS</title>
  <link rel="stylesheet" href="login.css" />
</head>
<body class="login-body">
  <div class="login-container">
    <h2>Welcome to <span>Fortune POS</span></h2>
    <?php if ($error): ?>
      <div class="error-message"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form class="login-form" action="process_login.php" method="post">
      <label for="username">Username</label>
      <input type="text" id="username" name="username" placeholder="Enter username" required />

      <label for="password">Password</label>
      <input type="password" id="password" name="password" placeholder="Enter password" required />

      <button type="submit">Login</button>
    </form>
  </div>
</body>
</html>
