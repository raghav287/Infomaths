<?php
require 'database.php';

try {
    // 3. Insert Data with SEO Friendly LINK
    // Note: We use the same TITLES and SLUGS as before, but update the LINK format.
    
    // Clear Table first to restart clean or just UPDATE? Truncate is safer for consistency.
    $pdo->exec("TRUNCATE TABLE course_profiles");

    $sql = "INSERT INTO course_profiles (title, slug, subtitle, link, display_order, is_active) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);

    $courses = [
        [
            'adWINtage',
            'adwintage',
            '1-Year Integrated Regular Batch',
            'course/adwintage', // CLEAN URL
            10,
            1
        ],
        [
            'Weekender',
            'weekender',
            '1-Year Integrated (Weekends) for Outstation Students',
            'course/weekender', // CLEAN URL
            20,
            1
        ],
        [
            'Marathon',
            'marathon',
            '2-Year Integrated Batch for 2nd Year Students',
            'course/marathon', // CLEAN URL
            30,
            1
        ],
        [
            'Super-Marathon',
            'super-marathon',
            '3-Year Integrated Batch for 1st Year Students',
            'course/super-marathon', // CLEAN URL
            40,
            1
        ],
        [
            'Target Course',
            'target-course',
            'Short Term Crash Course (60-90 Days)',
            'course/target-course', // CLEAN URL
            50,
            1
        ],
        [
            'Pigeon A',
            'pigeon-a',
            'Correspondence Study Material Batch for Outstation Students',
            'course/pigeon-a', // CLEAN URL
            60,
            1
        ],
        [
            'Online Course',
            'online-course',
            'On Android App COURSEDU',
            'course/online-course', // CLEAN URL
            70,
            1
        ],
        [
            'Hybrid Course',
            'hybrid-course',
            'Flexible Combination of Online & Offline Learning',
            'course/hybrid-course', // CLEAN URL
            80,
            1
        ]
    ];

    foreach ($courses as $course) {
        $stmt->execute($course);
    }

    echo "Database Updated: All links are now SEO Friendly (e.g., 'course/adwintage').";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
