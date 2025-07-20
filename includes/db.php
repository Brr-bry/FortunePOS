<?php
$host = 'localhost';
$dbname = 'fortune_pos';
$user = 'root';
$pass = ''; 

//hii
try { 
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass); 
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
}
catch(PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

?>
