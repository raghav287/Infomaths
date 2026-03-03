<?php
require_once 'database.php';

try {
    $pdo->beginTransaction();

    // Sample courses data
    $courses = [
        ['tab' => 'Undergraduate', 'category' => 'Data Analytics', 'title' => 'Starting Reputed Education & Build your Skills', 'description' => 'Far far away, behind the word mountains, far from the Consonantia.', 'image_path' => 'assets/img/home_1/course_thumb_1.jpg'],
        ['tab' => 'Graduate', 'category' => 'Bachelor Of Arts', 'title' => 'Boost Creativity & Expand Your Horizons', 'description' => 'Discover innovative techniques to enhance your creative thinking.', 'image_path' => 'assets/img/home_1/course_thumb_3.jpg'],
        ['tab' => 'Graduate', 'category' => 'Business Administrator', 'title' => 'Hone Leadership & Achieve Success', 'description' => 'Develop essential leadership skills to excel in any industry.', 'image_path' => 'assets/img/home_1/course_thumb_4.jpg'],
        ['tab' => 'Graduate', 'category' => 'Data Analytics', 'title' => 'Starting Reputed Education & Build your Skills', 'description' => 'Far far away, behind the word mountains, far from the Consonantia.', 'image_path' => 'assets/img/home_1/course_thumb_1.jpg'],
        ['tab' => 'Online', 'category' => 'Business Administrator', 'title' => 'Hone Leadership & Achieve Success', 'description' => 'Develop essential leadership skills to excel in any industry.', 'image_path' => 'assets/img/home_1/course_thumb_4.jpg'],
        ['tab' => 'Online', 'category' => 'Software Engineer', 'title' => 'Master Technology & Elevate Your Career', 'description' => 'Unlock the power of technology to drive your career forward.', 'image_path' => 'assets/img/home_1/course_thumb_2.jpg'],
        ['tab' => 'Online', 'category' => 'Bachelor Of Arts', 'title' => 'Boost Creativity & Expand Your Horizons', 'description' => 'Discover innovative techniques to enhance your creative thinking.', 'image_path' => 'assets/img/home_1/course_thumb_3.jpg'],
        ['tab' => 'Short Course', 'category' => 'Computer Science', 'title' => 'Explore Marketing & Build Your Brand', 'description' => 'Master marketing strategies to grow your personal or business brand.', 'image_path' => 'assets/img/home_1/course_thumb_6.jpg'],
        ['tab' => 'Short Course', 'category' => 'Business Administrator', 'title' => 'Hone Leadership & Achieve Success', 'description' => 'Develop essential leadership skills to excel in any industry.', 'image_path' => 'assets/img/home_1/course_thumb_4.jpg'],
        ['tab' => 'Short Course', 'category' => 'Data Analytics', 'title' => 'Starting Reputed Education & Build your Skills', 'description' => 'Far far away, behind the word mountains, far from the Consonantia.', 'image_path' => 'assets/img/home_1/course_thumb_1.jpg'],
    ];

    $stmt = $pdo->prepare("INSERT INTO courses (tab, category, title, description, image_path, is_active, created_at, updated_at) VALUES (?, ?, ?, ?, ?, 1, NOW(), NOW())");

    foreach ($courses as $course) {
        $stmt->execute([$course['tab'], $course['category'], $course['title'], $course['description'], $course['image_path']]);
    }

    $pdo->commit();
    echo "Sample courses inserted successfully.";

} catch (PDOException $e) {
    $pdo->rollBack();
    echo "Error inserting sample courses: " . $e->getMessage();
}
?>