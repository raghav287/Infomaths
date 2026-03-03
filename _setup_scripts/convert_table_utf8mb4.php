<?php
require 'database.php';
try {
    // Convert table to utf8mb4
    $pdo->exec("ALTER TABLE course_profiles CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "Table 'course_profiles' converted to utf8mb4 successfully.";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
