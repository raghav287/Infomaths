<?php
require 'database.php';

try {
    $sql = "CREATE TABLE IF NOT EXISTS course_profiles (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        subtitle VARCHAR(255) NOT NULL,
        link VARCHAR(255) NOT NULL,
        display_order INT DEFAULT 0,
        is_active TINYINT(1) DEFAULT 1,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";

    $pdo->exec($sql);
    echo "Table 'course_profiles' created successfully.";

    // Insert dummy data for initialization if empty
    $stmt = $pdo->query("SELECT COUNT(*) FROM course_profiles");
    if ($stmt->fetchColumn() == 0) {
        $insert = "INSERT INTO course_profiles (title, subtitle, link, display_order, is_active) VALUES 
            ('adWINtage', '1 YR INTEGRATED REGULAR BATCH', '#', 10, 1),
            ('adWINtage', '2 YR INTEGRATED REGULAR BATCH', '#', 20, 1),
            ('adWINtage', 'ONLINE LIVE BATCH', '#', 30, 1),
            ('adWINtage', 'SELF STUDY BATCH', '#', 40, 1)";
        $pdo->exec($insert);
        echo "\nDummy data inserted.";
    }

} catch (PDOException $e) {
    die("DB Error: " . $e->getMessage());
}
?>
