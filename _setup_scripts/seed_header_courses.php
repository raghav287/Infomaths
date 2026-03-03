<?php
require_once 'database.php';

$coursesDetails = [
    'MCA Entrance',
    'Bank PO SSC',
    'SBI Clerk (Junior Associate)',
    'SBI PO',
    'SBI SO (Specialist Officers)',
    'RBI Grade B Officer',
    'RBI Assistant',
    'IBPS Bank Clerk',
    'IBPS PO',
    'IBPS SO',
    'IBPS RRBs',
    'SSC CGL',
    'SSC CHSL',
    'IIT JAM Maths',
    'CSIR NET JRF',
    'BCA Subject Classes',
    'B.Sc Subject Classes'
];

try {
    $insertedCount = 0;
    $stmt = $pdo->prepare("INSERT INTO course_profiles (title, is_active, display_order) VALUES (?, 1, ?) ON DUPLICATE KEY UPDATE is_active = 1");
    
    foreach ($coursesDetails as $index => $courseName) {
        $stmt->execute([$courseName, $index + 1]);
        $insertedCount++;
    }

    echo "Successfully seeded $insertedCount courses from the header menu.";

} catch (PDOException $e) {
    echo "Error inserting courses: " . $e->getMessage();
}
