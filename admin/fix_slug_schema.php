<?php
require_once '../database.php';

echo "<h2>Fixing Database Schema for bank_po_jobs...</h2>";

try {
    // 1. Alter 'slug' column to allow NULL
    $sql1 = "ALTER TABLE bank_po_jobs MODIFY slug VARCHAR(255) NULL";
    $pdo->query($sql1);
    echo "<p style='color:green'>[SUCCESS] 'slug' column modified to allow NULL.</p>";

    // 2. Update existing empty slugs to NULL
    $sql2 = "UPDATE bank_po_jobs SET slug = NULL WHERE slug = ''";
    $stmt = $pdo->query($sql2);
    $count = $stmt->rowCount();
    echo "<p style='color:green'>[SUCCESS] Updated $count existing records with empty slugs to NULL.</p>";

} catch (PDOException $e) {
    echo "<p style='color:red'>[ERROR] " . $e->getMessage() . "</p>";
}

echo "<br><a href='bank-po-job-management.php'>Back to Job Management</a>";
?>
