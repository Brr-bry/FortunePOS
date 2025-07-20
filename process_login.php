<?php
session_start();
require './includes/db.php';

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

$stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
$stmt->execute([$username]);
$user = $stmt->fetch();

if ($user && $user['password'] === $password) {
  $_SESSION['username'] = $user['username'];
  $_SESSION['role'] = $user['role'];
  header("Location: ./admin/dashboard.php");
  exit();
} else {
  $_SESSION['login_error'] = "Invalid username or password.";
  header("Location: ./login.php");
  exit();
}
