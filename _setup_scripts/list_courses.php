<?php
require_once 'database.php';
try {
    $stmt = $pdo->query("SELECT title FROM course_profiles ORDER BY title ASC");
    $courses = $stmt->fetchAll(PDO::FETCH_COLUMN);
    echo "Current Courses in DB:\n";
    foreach ($courses as $c) {
        echo "- " . $c . "\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
