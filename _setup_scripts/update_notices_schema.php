<?php
require_once 'database.php';

try {
    // Add columns if they don't exist
    $columns = [
        "slug VARCHAR(255) DEFAULT NULL",
        "page_title VARCHAR(255) DEFAULT NULL",
        "page_content LONGTEXT DEFAULT NULL",
        "meta_title VARCHAR(255) DEFAULT NULL",
        "meta_description TEXT DEFAULT NULL"
    ];

    foreach ($columns as $col) {
        try {
            $pdo->exec("ALTER TABLE notices ADD COLUMN $col");
            echo "Added column: $col<br>";
        } catch (PDOException $e) {
            // Ignore if column exists
        }
    }
    
    echo "Notices table schema updated successfully.";

} catch (PDOException $e) {
    die("DB Error: " . $e->getMessage());
}
?>
