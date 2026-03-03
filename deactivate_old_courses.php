<?php
require_once 'database.php';

$validCourses = [
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
    // First, verify current state
    $placeholders = implode(',', array_fill(0, count($validCourses), '?'));
    
    // Deactivate everything NOT in our valid list
    $sql = "UPDATE course_profiles SET is_active = 0 WHERE title NOT IN ($placeholders)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($validCourses);
    
    echo "Deactivated old courses not in the header menu.\n";
    
    // Reactivate our valid list just in case
    $sqlActive = "UPDATE course_profiles SET is_active = 1 WHERE title IN ($placeholders)";
    $stmtActive = $pdo->prepare($sqlActive);
    $stmtActive->execute($validCourses);
    
    echo "Ensured header courses are active.\n";

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
