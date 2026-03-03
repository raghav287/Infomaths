<?php
require 'database.php';

try {
    // 1. Add description column
    try {
        $pdo->query("SELECT description FROM course_profiles LIMIT 1");
        echo "Column 'description' already exists.\n";
    } catch (Exception $e) {
        $pdo->exec("ALTER TABLE course_profiles ADD COLUMN description LONGTEXT AFTER subtitle");
        echo "Added 'description' column.\n";
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
