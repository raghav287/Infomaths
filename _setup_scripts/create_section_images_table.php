<?php
try {
    $pdo = new PDO('mysql:host=localhost;dbname=infomaths', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Create section_images table
    $sql = "CREATE TABLE IF NOT EXISTS section_images (
        id INT AUTO_INCREMENT PRIMARY KEY,
        section_name VARCHAR(50) NOT NULL,
        image_path VARCHAR(255) NOT NULL,
        alt_text VARCHAR(255) DEFAULT '',
        display_order INT DEFAULT 0,
        uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";

    $pdo->exec($sql);
    echo 'section_images table created successfully';
} catch(PDOException $e) {
    echo 'Error: ' . $e->getMessage();
}
?>