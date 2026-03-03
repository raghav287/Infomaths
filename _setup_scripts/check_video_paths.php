<?php
require 'database.php';
try {
    $stmt = $pdo->query('SELECT id, title, video_file, thumbnail FROM demo_lectures');
    $lectures = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo 'Demo lectures details:' . PHP_EOL;
    foreach($lectures as $lecture) {
        echo 'ID: ' . $lecture['id'] . PHP_EOL;
        echo 'Title: ' . $lecture['title'] . PHP_EOL;
        echo 'Video File: ' . $lecture['video_file'] . PHP_EOL;
        echo 'Thumbnail: ' . $lecture['thumbnail'] . PHP_EOL;
        echo '---' . PHP_EOL;
    }
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage() . PHP_EOL;
}
?>