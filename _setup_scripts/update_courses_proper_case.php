<?php
require 'database.php';

try {
    // Clear existing data
    $pdo->exec("TRUNCATE TABLE course_profiles");

    $sql = "INSERT INTO course_profiles (title, subtitle, link, display_order, is_active) VALUES (?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);

    // Proper Case List
    $courses = [
        [
            'adWINtage', // Kept as requested
            '1-Year Integrated Regular Batch', // Proper Case
            'course-details.php?course=adwintage',
            10,
            1
        ],
        [
            'Weekender', // Proper Case
            '1-Year Integrated (Weekends) for Outstation Students', // Proper Case
            'course-details.php?course=weekender',
            20,
            1
        ],
        [
            'Marathon', // Proper Case
            '2-Year Integrated Batch for 2nd Year Students', // Proper Case
            'course-details.php?course=marathon',
            30,
            1
        ],
        [
            'Super-Marathon', // Proper Case
            '3-Year Integrated Batch for 1st Year Students', // Proper Case
            'course-details.php?course=super-marathon',
            40,
            1
        ],
        [
            'Target Course', // Proper Case
            'Short Term Crash Course (60-90 Days)', // Proper Case
            'course-details.php?course=target',
            50,
            1
        ],
        [
            'Pigeon A', // Proper Case
            'Correspondence Study Material Batch for Outstation Students', // Proper Case
            'course-details.php?course=pigeon-a',
            60,
            1
        ],
        [
            'Online Course', // Proper Case (Cleaned up "..")
            'On Android App COURSEDU', // Proper Case + App Name
            'course-details.php?course=online',
            70,
            1
        ],
        [
            'Hybrid Course',
            'Flexible Combination of Online & Offline Learning',
            'course-details.php?course=hybrid',
            80,
            1
        ]
    ];

    foreach ($courses as $course) {
        $stmt->execute($course);
    }

    echo "Courses updated to Proper Case (Title Case) successfully.";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
