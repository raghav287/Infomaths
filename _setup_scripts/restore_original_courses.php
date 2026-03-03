<?php
require 'database.php';

try {
    // Clear existing dummy data
    $pdo->exec("TRUNCATE TABLE course_profiles");

    $courses = [
        ['adWINtage', '1 YR INTERGRATED REGULAR BATCH', 10],
        ['Weekender', '1 YR INTEGRATED CARRIED ON WEEKENDS FOR OUTSTATION STUDENTS', 20],
        ['Marathon', '2 YR INTEGRATED BATCH FOR 2ND YR STUDENTS', 30],
        ['Super-Marathon', '3 YR INTEGRATED BATCH FOR 1ST YR STUDENTS', 40],
        ['Pigeon A', 'Correspondence Study Material Batch for Outstation Students', 50],
        ['Target Course', 'Short-Term Crash Course (60-90 Days)', 60],
        ['Online Course', 'Flexible Online Learning Through Android App – COURSEDU', 70],
        ['Hybrid Course', 'Learn Online or Offline', 80]
    ];

    $stmt = $pdo->prepare("INSERT INTO course_profiles (title, subtitle, link, display_order, is_active) VALUES (?, ?, '#', ?, 1)");

    foreach ($courses as $course) {
        $stmt->execute([$course[0], $course[1], $course[2]]);
    }

    echo "Successfully restored " . count($courses) . " original courses to standard configuration.";

} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>
