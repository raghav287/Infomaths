<?php
require_once 'database.php';

try {
    $pdo->beginTransaction();

    // Clear existing courses
    $pdo->exec("DELETE FROM courses");

    // New courses with category-specific tabs
    $courses = [
        // MCA Entrance tab
        ['tab' => 'MCA Entrance', 'category' => 'MCA Entrance', 'title' => 'MCA Entrance Complete Preparation', 'description' => 'Comprehensive MCA entrance exam preparation with mathematics, reasoning, and computer science topics.', 'image_path' => 'assets/img/home_1/course_thumb_1.jpg'],
        ['tab' => 'MCA Entrance', 'category' => 'MCA Entrance', 'title' => 'MCA Mathematics Mastery', 'description' => 'Focused training on mathematics section including algebra, calculus, and discrete mathematics.', 'image_path' => 'assets/img/home_1/course_thumb_2.jpg'],
        ['tab' => 'MCA Entrance', 'category' => 'MCA Entrance', 'title' => 'MCA Online Test Series', 'description' => 'Extensive online test series with detailed solutions and performance analysis.', 'image_path' => 'assets/img/home_1/course_thumb_3.jpg'],

        // PU / Clerical tab
        ['tab' => 'PU / Clerical', 'category' => 'PU / Clerical', 'title' => 'PU Exam Preparation Course', 'description' => 'Complete preparation for PU (Panchayat Secretary) exams with current affairs and general knowledge.', 'image_path' => 'assets/img/home_1/course_thumb_4.jpg'],
        ['tab' => 'PU / Clerical', 'category' => 'PU / Clerical', 'title' => 'Clerical Exam Training', 'description' => 'Comprehensive training for clerical positions with aptitude, English, and computer skills.', 'image_path' => 'assets/img/home_1/course_thumb_5.jpg'],
        ['tab' => 'PU / Clerical', 'category' => 'PU / Clerical', 'title' => 'Government Job Preparation', 'description' => 'All-round preparation for various government clerical positions with mock interviews.', 'image_path' => 'assets/img/home_1/course_thumb_6.jpg'],

        // IIT - JAM tab
        ['tab' => 'IIT - JAM', 'category' => 'IIT - JAM', 'title' => 'IIT-JAM Mathematics', 'description' => 'Advanced mathematics preparation for IIT-JAM exam with problem-solving techniques.', 'image_path' => 'assets/img/home_1/course_thumb_1.jpg'],
        ['tab' => 'IIT - JAM', 'category' => 'IIT - JAM', 'title' => 'IIT-JAM Physics Course', 'description' => 'Comprehensive physics coaching covering all IIT-JAM syllabus topics.', 'image_path' => 'assets/img/home_1/course_thumb_2.jpg'],
        ['tab' => 'IIT - JAM', 'category' => 'IIT - JAM', 'title' => 'UGC-NET Preparation', 'description' => 'Dual preparation for IIT-JAM and UGC-NET with research methodology.', 'image_path' => 'assets/img/home_1/course_thumb_3.jpg'],

        // BCA - BSC tab
        ['tab' => 'BCA - BSC', 'category' => 'BCA - BSC', 'title' => 'BCA Programming Fundamentals', 'description' => 'Programming basics for BCA students including C, C++, and Java.', 'image_path' => 'assets/img/home_1/course_thumb_4.jpg'],
        ['tab' => 'BCA - BSC', 'category' => 'BCA - BSC', 'title' => 'BSC Computer Science', 'description' => 'Computer science foundation course for BSC students with practical labs.', 'image_path' => 'assets/img/home_1/course_thumb_5.jpg'],
        ['tab' => 'BCA - BSC', 'category' => 'BCA - BSC', 'title' => 'Data Structures & Algorithms', 'description' => 'Advanced data structures and algorithms for BCA/BSC final year students.', 'image_path' => 'assets/img/home_1/course_thumb_6.jpg'],
    ];

    $stmt = $pdo->prepare("INSERT INTO courses (tab, category, title, description, image_path, is_active, created_at, updated_at) VALUES (?, ?, ?, ?, ?, 1, NOW(), NOW())");

    foreach ($courses as $course) {
        $stmt->execute([$course['tab'], $course['category'], $course['title'], $course['description'], $course['image_path']]);
    }

    $pdo->commit();
    echo "Courses updated with category-specific tabs successfully.";

} catch (PDOException $e) {
    $pdo->rollBack();
    echo "Error updating courses: " . $e->getMessage();
}
?>