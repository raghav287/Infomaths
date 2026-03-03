<?php
require_once 'database.php';

try {
    // Insert default INFOMATHS section images
    $images = [
        ['section_name' => 'infomaths', 'image_path' => 'assets/img/indu1.webp', 'alt_text' => 'INFOMATHS Campus View 1', 'display_order' => 1],
        ['section_name' => 'infomaths', 'image_path' => 'assets/img/home_1/home-banner.jpg', 'alt_text' => 'INFOMATHS Study Environment', 'display_order' => 2],
        ['section_name' => 'infomaths', 'image_path' => 'assets/img/indu3.webp', 'alt_text' => 'INFOMATHS Campus View 2', 'display_order' => 3],
        ['section_name' => 'infomaths', 'image_path' => 'assets/img/indu4.webp', 'alt_text' => 'INFOMATHS Campus View 3', 'display_order' => 4]
    ];

    $stmt = $pdo->prepare("INSERT INTO section_images (section_name, image_path, alt_text, display_order) VALUES (?, ?, ?, ?)");

    foreach ($images as $image) {
        $stmt->execute([$image['section_name'], $image['image_path'], $image['alt_text'], $image['display_order']]);
    }

    echo 'Default INFOMATHS section images inserted successfully';
} catch(PDOException $e) {
    echo 'Error: ' . $e->getMessage();
}
?>