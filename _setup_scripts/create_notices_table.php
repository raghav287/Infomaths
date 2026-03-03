<?php
require_once 'database.php';

try {
    // Create notices table
    $sql = "CREATE TABLE IF NOT EXISTS notices (
        id INT AUTO_INCREMENT PRIMARY KEY,
        content TEXT NOT NULL,
        link VARCHAR(255) DEFAULT '#',
        display_order INT DEFAULT 0,
        is_active TINYINT(1) DEFAULT 1,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    $pdo->exec($sql);
    echo "Table 'notices' created successfully.<br>";

    // Insert dummy data if empty
    $count = $pdo->query("SELECT COUNT(*) FROM notices")->fetchColumn();
    if ($count == 0) {
        $stmt = $pdo->prepare("INSERT INTO notices (content, link) VALUES (?, ?)");
        $stmt->execute(['NEW MCA BATCHES GOING TO START FM 10-15TH .20%FESTIVE DISCOUNTS APPLY', '#']);
        $stmt->execute(['Enrollment open for NIMCET/CUET PG MCA Entrance 2024 Regular Batch', '#']);
        $stmt->execute(['New Batches FOR MCA ENTRANCE Batch on 1st, 15th and 20th every month.', '#']);
        echo "Dummy notices inserted.<br>";
    }

} catch (PDOException $e) {
    die("DB Error: " . $e->getMessage());
}
?>
