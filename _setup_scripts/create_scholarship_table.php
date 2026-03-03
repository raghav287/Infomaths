<?php
require_once 'database.php';

try {
    $sql = "CREATE TABLE IF NOT EXISTS scholarship_registrations (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        email VARCHAR(255) NOT NULL,
        mobile VARCHAR(20) NOT NULL,
        course VARCHAR(255) NOT NULL,
        message TEXT,
        registration_date DATETIME DEFAULT CURRENT_TIMESTAMP
    )";

    $pdo->exec($sql);
    echo "Table 'scholarship_registrations' created successfully.";
} catch (PDOException $e) {
    echo "Error creating table: " . $e->getMessage();
}
?>
