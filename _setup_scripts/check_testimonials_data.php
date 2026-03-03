<?php
require_once 'database.php';
try {
    // Check testimonials table structure
    echo "testimonials table structure:" . PHP_EOL;
    $stmt = $pdo->query('DESCRIBE testimonials');
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($columns as $column) {
        echo "- {$column['Field']}: {$column['Type']}" . PHP_EOL;
    }

    echo PHP_EOL . "video_testimonials table structure:" . PHP_EOL;
    $stmt = $pdo->query('DESCRIBE video_testimonials');
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($columns as $column) {
        echo "- {$column['Field']}: {$column['Type']}" . PHP_EOL;
    }

    // Check sample data
    echo PHP_EOL . "Sample testimonials data:" . PHP_EOL;
    $stmt = $pdo->query('SELECT * FROM testimonials LIMIT 3');
    $testimonials = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($testimonials as $testimonial) {
        echo "- ID: {$testimonial['id']}, Name: {$testimonial['name']}, Designation: {$testimonial['designation']}" . PHP_EOL;
    }

    echo PHP_EOL . "Sample video testimonials data:" . PHP_EOL;
    $stmt = $pdo->query('SELECT * FROM video_testimonials LIMIT 3');
    $video_testimonials = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($video_testimonials as $testimonial) {
        echo "- ID: {$testimonial['id']}, Name: {$testimonial['name']}, Video ID: {$testimonial['video_id']}" . PHP_EOL;
    }

} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}
?>