<?php
require 'database.php';
try {
    $stmt = $pdo->query('SHOW TABLES');
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    print_r($tables);
    
    // Also describe toppers if it exists
    if (in_array('toppers', $tables)) {
        echo "\nToppers Schema:\n";
        $stmt = $pdo->query('DESCRIBE toppers');
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $col) {
            echo $col['Field'] . " - " . $col['Type'] . "\n";
        }
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
