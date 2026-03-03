<?php
require_once 'database.php';

try {
    $sql = "ALTER TABLE scholarship_registrations 
            ADD COLUMN qualification VARCHAR(100) AFTER mobile,
            ADD COLUMN percentage VARCHAR(20) AFTER qualification";

    $pdo->exec($sql);
    echo "Table 'scholarship_registrations' updated successfully (added qualification, percentage).";
} catch (PDOException $e) {
    echo "Error updating table: " . $e->getMessage();
}
?>
