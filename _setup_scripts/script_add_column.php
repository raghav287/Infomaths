<?php
require_once 'database.php';
try {
    $pdo->exec("ALTER TABLE course_profiles ADD COLUMN show_in_dropdown TINYINT DEFAULT 1");
    echo "Column added successfully.";
} catch (PDOException $e) {
    if (strpos($e->getMessage(), "Duplicate column name") !== false) {
        echo "Column already exists.";
    } else {
        echo "Error: " . $e->getMessage();
    }
}
