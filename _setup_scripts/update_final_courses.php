<?php
require 'database.php';

try {
    // Clear existing data
    $pdo->exec("TRUNCATE TABLE course_profiles");

    $sql = "INSERT INTO course_profiles (title, subtitle, link, display_order, is_active) VALUES (?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);

    // List based on User Screenshot + Hybrid Request
    $courses = [
        [
            'adWINtage',
            '1 YR INTEGRATED REGULAR BATCH',
            'course-details.php?course=adwintage',
            10,
            1
        ],
        [
            'weekender',
            '1 YR INTEGRATED CARRIED ON WEEKENDS FOR OUTSTATION STUDENTS',
            'course-details.php?course=weekender',
            20,
            1
        ],
        [
            'marathon',
            '2 YR INTEGRATED BATCH FOR 2ND YR STUDENTS',
            'course-details.php?course=marathon',
            30,
            1
        ],
        [
            'Super-Marathon',
            '3 yr integrated batch for 1st yr students',
            'course-details.php?course=super-marathon',
            40,
            1
        ],
        [
            'Target course',
            'short term crash course of 60-90 days',
            'course-details.php?course=target',
            50,
            1
        ],
        [
            'pigeon A',
            'correspondence study material batch for outstation students',
            'course-details.php?course=pigeon-a',
            60,
            1
        ],
        [
            'online course..',
            'on android app COURSEDU',
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

    echo "Course Batches Updated with Exact List + Hybrid.";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
