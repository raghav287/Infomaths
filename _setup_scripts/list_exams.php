<?php
require 'database.php';
try {
    $stmt = $pdo->query("SELECT id, exam_name, full_description FROM entrance_exams");
    $exams = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($exams, JSON_PRETTY_PRINT);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
