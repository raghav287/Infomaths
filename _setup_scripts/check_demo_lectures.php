<?php
require 'database.php';
try {
    $stmt = $pdo->query('SELECT * FROM demo_lectures');
    $lectures = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo 'Found ' . count($lectures) . ' demo lectures:' . PHP_EOL;
    foreach($lectures as $lecture) {
        echo 'ID: ' . $lecture['id'] . ', Title: ' . $lecture['title'] . ', Type: ' . $lecture['video_type'] . ', Active: ' . ($lecture['is_active'] ? 'Yes' : 'No') . PHP_EOL;
    }
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage() . PHP_EOL;
}
?>