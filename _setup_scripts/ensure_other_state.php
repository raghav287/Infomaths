<?php
require 'database.php';

// Check if "Other State" exists
$stmt = $pdo->prepare("SELECT * FROM entrance_exams WHERE exam_name LIKE ?");
$stmt->execute(['%Other State%']);
$exam = $stmt->fetch(PDO::FETCH_ASSOC);

if ($exam) {
    echo "Found 'Other State' exam. ID: " . $exam['id'] . ", Order: " . $exam['display_order'] . "<br>";
} else {
    echo "'Other State' exam NOT found. Adding it now...<br>";
    
    try {
        $stmt = $pdo->prepare("INSERT INTO entrance_exams (exam_name, slug, icon_image, short_description, full_description, display_order, is_active) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            'Other State',
            'other-state-exams',
            '', // No specific icon, code handles fallback
            'Explore other state-level MCA entrance exams.',
            '<p>Details about various other state-level MCA entrance exams will be listed here.</p>',
            100, // High order to put it at the end
            1
        ]);
        echo "Successfully added 'Other State' exam.<br>";
    } catch (PDOException $e) {
        echo "Error adding exam: " . $e->getMessage();
    }
}

// Show all exams and their order
echo "<br><b>Current Exam Order:</b><br>";
$stmt = $pdo->query("SELECT exam_name, display_order FROM entrance_exams ORDER BY display_order ASC");
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo $row['display_order'] . " - " . htmlspecialchars($row['exam_name']) . "<br>";
}
?>
