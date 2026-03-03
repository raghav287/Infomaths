<?php
require_once 'database.php';

$headerCourses = [
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
    // 1. Reset everything to: NOT in dropdown
    $pdo->exec("UPDATE course_profiles SET show_in_dropdown = 0");
    
    // 2. Set Header Courses to: show_in_dropdown = 1
    $placeholders = implode(',', array_fill(0, count($headerCourses), '?'));
    $sqlHeader = "UPDATE course_profiles SET show_in_dropdown = 1, is_active = 1 WHERE title IN ($placeholders)";
    $stmtHeader = $pdo->prepare($sqlHeader);
    $stmtHeader->execute($headerCourses);
    
    echo "Updated " . $stmtHeader->rowCount() . " header courses to show in dropdown.\n";

    // 3. Reactivate "adWINtage" and other non-header courses (but keep show_in_dropdown = 0)
    // We want to activate courses that are NOT in the header list
    $sqlActivateOthers = "UPDATE course_profiles SET is_active = 1 WHERE title NOT IN ($placeholders)";
    $stmtActivateOthers = $pdo->prepare($sqlActivateOthers);
    $stmtActivateOthers->execute($headerCourses); // Re-use header array for NOT IN clause

    echo "Reactivated " . $stmtActivateOthers->rowCount() . " other courses (hidden from dropdown).\n";

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
