<?php

// production database configuration
$host = 'localhost';
$dbname = 'u586615155_infomaths';
$username = 'root';
$password = '';

// Localhost database configuration
// $host = 'localhost';
// $dbname = 'infomaths';
// $username = 'root';
// $password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>