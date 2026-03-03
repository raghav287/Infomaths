<?php
require_once 'database.php';
$stmt = $pdo->query("SELECT id, exam_name, slug FROM bank_po_entrance_exams");
$exams = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo "Current Exams:\n";
foreach ($exams as $exam) {
    echo "- " . $exam['exam_name'] . " (" . $exam['slug'] . ")\n";
}
?>
