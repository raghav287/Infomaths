<?php
require_once 'database.php';
try {
    $stmt = $pdo->prepare('SELECT DISTINCT category FROM toppers ORDER BY category');
    $stmt->execute();
    $categories = $stmt->fetchAll(PDO::FETCH_COLUMN);
    echo 'Available categories:' . PHP_EOL;
    foreach ($categories as $category) {
        echo '- ' . $category . PHP_EOL;
    }
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage() . PHP_EOL;
}
?>