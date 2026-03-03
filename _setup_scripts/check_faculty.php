<?php
require 'database.php';
try {
    $stmt = $pdo->query("SELECT count(*) FROM faculty");
    $count = $stmt->fetchColumn();
    echo "Faculty count: " . $count . "\n";
    
    if ($count > 0) {
        $stmt = $pdo->query("SELECT * FROM faculty LIMIT 3");
        print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
