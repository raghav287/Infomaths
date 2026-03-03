<?php
require 'database.php';

try {
    // Clear existing data
    $pdo->exec("TRUNCATE TABLE course_profiles");

    // Insert The "Real" adWINtage Data as requested
    $sql = "INSERT INTO course_profiles (title, subtitle, link, display_order, is_active) VALUES (?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);

    $courses = [
        [
            'adWINtage',
            '1 YR INTEGRATED REGULAR BATCH',
            'course-details.php?course=nimcet-1yr',
            10,
            1
        ],
        [
            'adWINtage',
            '2 YR INTEGRATED REGULAR BATCH',
            'course-details.php?course=nimcet-2yr',
            20,
            1
        ],
        [
            'adWINtage',
            'ONLINE LIVE BATCH',
            'course-details.php?course=online-live',
            30,
            1
        ],
        [
            'adWINtage',
            'SELF STUDY BATCH',
            'course-details.php?course=self-study',
            40,
            1
        ]
    ];

    foreach ($courses as $course) {
        $stmt->execute($course);
    }

    echo "Course Batches Restored to 'adWINtage' as requested.";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
