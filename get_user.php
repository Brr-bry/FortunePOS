<?php
require 'db.php';

$stmt = $conn->query("SELECT username, role FROM users ORDER BY id ASC");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($users);
