<?php
require_once 'database.php';

try {
    $pdo->beginTransaction();

    // Additional sample courses based on category cards
    $courses = [
        // MCA Entrance
        ['tab' => 'Undergraduate', 'category' => 'MCA Entrance', 'title' => 'MCA Entrance Exam Preparation Course', 'description' => 'Comprehensive preparation for MCA entrance exams with expert faculty and practice tests.', 'image_path' => 'assets/img/home_1/course_thumb_1.jpg'],
        ['tab' => 'Online', 'category' => 'MCA Entrance', 'title' => 'Online MCA Coaching Program', 'description' => 'Flexible online coaching for MCA entrance with live classes and recorded sessions.', 'image_path' => 'assets/img/home_1/course_thumb_2.jpg'],

        // PU / Clerical
        ['tab' => 'Graduate', 'category' => 'PU / Clerical', 'title' => 'PU & Clerical Exam Training', 'description' => 'Complete preparation for PU and Clerical government exams with mock tests.', 'image_path' => 'assets/img/home_1/course_thumb_3.jpg'],
        ['tab' => 'Short Course', 'category' => 'PU / Clerical', 'title' => 'Government Exam Crash Course', 'description' => 'Intensive crash course for PU and Clerical exams with focus on key topics.', 'image_path' => 'assets/img/home_1/course_thumb_4.jpg'],

        // IIT - JAM
        ['tab' => 'Graduate', 'category' => 'IIT - JAM', 'title' => 'IIT-JAM Mathematics Preparation', 'description' => 'Advanced mathematics coaching for IIT-JAM and UGC-NET exams.', 'image_path' => 'assets/img/home_1/course_thumb_5.jpg'],
        ['tab' => 'Online', 'category' => 'IIT - JAM', 'title' => 'Online IIT-JAM Coaching', 'description' => 'Interactive online classes for IIT-JAM preparation with doubt clearing sessions.', 'image_path' => 'assets/img/home_1/course_thumb_6.jpg'],

        // BCA - BSC
        ['tab' => 'Undergraduate', 'category' => 'BCA - BSC', 'title' => 'BCA/BSC Computer Science Classes', 'description' => 'Regular college-level classes for BCA and BSC computer science students.', 'image_path' => 'assets/img/home_1/course_thumb_1.jpg'],
        ['tab' => 'Graduate', 'category' => 'BCA - BSC', 'title' => 'Advanced Programming for BCA/BSC', 'description' => 'Advanced programming concepts and practical sessions for BCA/BSC students.', 'image_path' => 'assets/img/home_1/course_thumb_2.jpg'],
        ['tab' => 'Short Course', 'category' => 'BCA - BSC', 'title' => 'BCA/BSC Foundation Course', 'description' => 'Foundation course covering essential topics for BCA and BSC curriculum.', 'image_path' => 'assets/img/home_1/course_thumb_3.jpg'],

        // Additional courses for variety
        ['tab' => 'Online', 'category' => 'Campus Placement', 'title' => 'Campus Placement Training Program', 'description' => 'Comprehensive training for campus placements with aptitude, technical, and interview preparation.', 'image_path' => 'assets/img/home_1/course_thumb_4.jpg'],
        ['tab' => 'Short Course', 'category' => 'Campus Placement', 'title' => 'Interview Skills Workshop', 'description' => 'Focused workshop on interview skills, resume building, and group discussion.', 'image_path' => 'assets/img/home_1/course_thumb_5.jpg'],
    ];

    $stmt = $pdo->prepare("INSERT INTO courses (tab, category, title, description, image_path, is_active, created_at, updated_at) VALUES (?, ?, ?, ?, ?, 1, NOW(), NOW())");

    foreach ($courses as $course) {
        $stmt->execute([$course['tab'], $course['category'], $course['title'], $course['description'], $course['image_path']]);
    }

    $pdo->commit();
    echo "Additional courses inserted successfully.";

} catch (PDOException $e) {
    $pdo->rollBack();
    echo "Error inserting additional courses: " . $e->getMessage();
}
?>