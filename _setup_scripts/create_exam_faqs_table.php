<?php
require 'database.php';

try {
    // strict mode check for table creation
    $sql = "CREATE TABLE IF NOT EXISTS entrance_exam_faqs (
        id INT AUTO_INCREMENT PRIMARY KEY,
        exam_id INT NOT NULL,
        question TEXT NOT NULL,
        answer TEXT NOT NULL,
        display_order INT DEFAULT 0,
        is_active TINYINT(1) DEFAULT 1,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (exam_id) REFERENCES entrance_exams(id) ON DELETE CASCADE
    )";

    $pdo->exec($sql);
    echo "Table 'entrance_exam_faqs' created successfully.<br>";

    // Optional: Migrate the existing NIMCET accordion data into this new structure?
    // For now, let's keep it separate to avoid breaking the manual HTML content, 
    // but the user can use this feature for NEW content.

} catch (PDOException $e) {
    echo "Error creating table: " . $e->getMessage();
}
?>
