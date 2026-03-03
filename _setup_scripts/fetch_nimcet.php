<?php
require 'database.php';
try {
    $stmt = $pdo->query("SELECT full_description FROM entrance_exams WHERE id = 1");
    echo $stmt->fetchColumn();
} catch (Exception $e) {
    echo "Error";
}
?>
