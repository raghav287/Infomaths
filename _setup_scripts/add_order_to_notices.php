<?php
require_once 'database.php';

try {
    $pdo->exec("ALTER TABLE notices ADD COLUMN display_order INT DEFAULT 0 AFTER link");
    echo "Column 'display_order' added successfully.";
} catch (PDOException $e) {
    if (strpos($e->getMessage(), "Duplicate column name") !== false) {
        echo "Column already exists.";
    } else {
        die("Error: " . $e->getMessage());
    }
}
?>
