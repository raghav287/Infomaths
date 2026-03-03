<?php
require_once 'database.php';

try {
    $stmt = $pdo->query('SELECT tab, COUNT(*) as count FROM courses WHERE is_active = 1 GROUP BY tab ORDER BY tab');
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach($result as $row) {
        echo $row['tab'] . ': ' . $row['count'] . ' courses' . PHP_EOL;
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>