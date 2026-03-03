<?php
require_once 'database.php';

try {
    $stmt = $pdo->query('DESCRIBE courses');
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach($columns as $col) {
        echo $col['Field'] . ' - ' . $col['Type'] . PHP_EOL;
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>