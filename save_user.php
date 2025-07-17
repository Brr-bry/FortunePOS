<?php
require 'db.php';

$data = json_decode(file_get_contents("php://input"), true);

$username = trim($data['username']);
$password = trim($data['password']);
$role = $data['role'];

if (!$username || !$password || !$role) {
  echo json_encode(["success" => false, "message" => "Missing fields"]);
  exit;
}

// Check for duplicate username
$stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
$stmt->execute([$username]);
if ($stmt->rowCount() > 0) {
  echo json_encode(["success" => false, "message" => "Username already exists"]);
  exit;
}

// Save user
$stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
$success = $stmt->execute([$username, $password, $role]);

echo json_encode(["success" => $success]);
