<?php
require 'database.php';

try {
    //Clear existing dummy data
    $pdo->exec("TRUNCATE TABLE course_profiles");

    // Insert Professional Content
    $sql = "INSERT INTO course_profiles (title, subtitle, link, display_order, is_active) VALUES (?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);

    $courses = [
        [
            'NIMCET 2026 Comprehensive Batch',
            '1 Year Integrated Regular Classroom Program',
            'course-details.php?course=nimcet-1yr',
            10,
            1
        ],
        [
            'NIMCET 2027 Foundation Batch',
            '2 Year Complete Course for Undergraduate Students',
            'course-details.php?course=nimcet-2yr',
            20,
            1
        ],
        [
            'Infomaths Online Live',
            'Interactive Daily Live Classes + Recordings',
            'course-details.php?course=online-live',
            30,
            1
        ],
        [
            'Distance Learning Program',
            'Complete Study Material & Online Test Series',
            'course-details.php?course=distance',
            40,
            1
        ],
        [
            'Weekend Special Batch',
            'Dedicated Sessions for Working Professionals',
            'course-details.php?course=weekend',
            50,
            1
        ]
    ];

    foreach ($courses as $course) {
        $stmt->execute($course);
    }

    echo "Course Batches Updated Successfully with Professional Titles.";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
