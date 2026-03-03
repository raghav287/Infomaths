<?php
require_once 'database.php';

try {
    $pdo->exec("DROP TABLE IF EXISTS demo_lectures");

    $sql = "CREATE TABLE demo_lectures (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        description TEXT,
        video_type ENUM('youtube', 'upload') NOT NULL,
        video_url VARCHAR(500),
        video_file VARCHAR(255),
        thumbnail VARCHAR(255),
        is_active TINYINT(1) DEFAULT 1,
        display_order INT DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";

    $pdo->exec($sql);
    echo "Demo lectures table created successfully.";
} catch (PDOException $e) {
    echo "Error creating table: " . $e->getMessage();
}
?>