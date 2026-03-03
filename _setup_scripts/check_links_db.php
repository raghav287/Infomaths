<?php
require 'database.php';
$stmt = $pdo->query("SELECT title, link FROM course_profiles LIMIT 3");
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
?>
