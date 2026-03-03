<?php
require 'database.php';

try {
    // 1. Add SLUG column if not exists
    try {
        $pdo->query("SELECT slug FROM course_profiles LIMIT 1");
    } catch (Exception $e) {
        $pdo->exec("ALTER TABLE course_profiles ADD COLUMN slug VARCHAR(255) AFTER title");
        echo "Added 'slug' column.\n";
    }

    // 2. Clear Table
    $pdo->exec("TRUNCATE TABLE course_profiles");

    // 3. Insert Data with SLUG and Correct LINK
    $sql = "INSERT INTO course_profiles (title, slug, subtitle, link, display_order, is_active) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);

    $courses = [
        [
            'adWINtage',
            'adwintage',
            '1-Year Integrated Regular Batch',
            'course-details.php?slug=adwintage',
            10,
            1
        ],
        [
            'Weekender',
            'weekender',
            '1-Year Integrated (Weekends) for Outstation Students',
            'course-details.php?slug=weekender',
            20,
            1
        ],
        [
            'Marathon',
            'marathon',
            '2-Year Integrated Batch for 2nd Year Students',
            'course-details.php?slug=marathon', // Note: User had "marathon-2" in link perhaps? I'll use simple slug.
            30,
            1
        ],
        [
            'Super-Marathon',
            'super-marathon',
            '3-Year Integrated Batch for 1st Year Students',
            'course-details.php?slug=super-marathon',
            40,
            1
        ],
        [
            'Target Course',
            'target-course',
            'Short Term Crash Course (60-90 Days)',
            'course-details.php?slug=target-course',
            50,
            1
        ],
        [
            'Pigeon A',
            'pigeon-a',
            'Correspondence Study Material Batch for Outstation Students',
            'course-details.php?slug=pigeon-a',
            60,
            1
        ],
        [
            'Online Course',
            'online-course',
            'On Android App COURSEDU',
            'course-details.php?slug=online-course',
            70,
            1
        ],
        [
            'Hybrid Course',
            'hybrid-course',
            'Flexible Combination of Online & Offline Learning',
            'course-details.php?slug=hybrid-course',
            80,
            1
        ]
    ];

    foreach ($courses as $course) {
        $stmt->execute($course);
    }

    echo "Database Fixed: Added 'slug' column and updated rows with correct links (?slug=...).";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
