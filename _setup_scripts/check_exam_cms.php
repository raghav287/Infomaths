<?php
require 'database.php';

echo "<h2>Entrance Exam CMS Verification</h2>";

// 1. Check Table
try {
    $stmt = $pdo->query("SELECT * FROM entrance_exams");
    $exams = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "<b>Database Check:</b> Found " . count($exams) . " exams.<br>";
    foreach ($exams as $exam) {
        echo "- " . htmlspecialchars($exam['exam_name']) . " (Slug: " . htmlspecialchars($exam['slug']) . ") - Active: " . $exam['is_active'] . "<br>";
    }
} catch (PDOException $e) {
    echo "<b style='color:red'>Database Error:</b> " . $e->getMessage() . "<br>";
}

// 2. Check File Existence
$files = ['admin/exam-management.php', 'exam-details.php', 'create_entrance_exams_table.php'];
echo "<br><b>File Check:</b><br>";
foreach ($files as $file) {
    if (file_exists($file)) {
        echo "- $file found.<br>";
    } else {
        echo "- <span style='color:red;'>$file NOT FOUND</span><br>";
    }
}

// 3. Simulated Page Access (local check)
$nimcetSlug = 'nimcet'; 
// Note: We can't do full HTTP request easily without CURL and knowing localhost port, 
// but we can check if file details load via PHP include logic if we wanted.
// For now, just confirming the slug exists in DB is good enough for automated check.
$stmt = $pdo->prepare("SELECT COUNT(*) FROM entrance_exams WHERE slug = ?");
$stmt->execute([$nimcetSlug]);
if ($stmt->fetchColumn() > 0) {
    echo "<br><b>Detail Page Check:</b> Slug '$nimcetSlug' is valid in DB, ready for exam-details.php?slug=$nimcetSlug<br>";
} else {
    echo "<br><b>Detail Page Check:</b> Slug '$nimcetSlug' NOT found in DB.<br>";
}
?>
