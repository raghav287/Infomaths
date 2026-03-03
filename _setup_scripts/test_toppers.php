<?php
require_once 'database.php';
try {
    $stmt = $pdo->prepare('SELECT COUNT(*) as count FROM toppers WHERE category = ?');
    $stmt->execute(['NIMCET']);
    $result = $stmt->fetch();
    echo 'NIMCET toppers count: ' . $result['count'] . PHP_EOL;

    $stmt = $pdo->prepare('SELECT * FROM toppers WHERE category = ? ORDER BY uploaded_at DESC LIMIT 5');
    $stmt->execute(['NIMCET']);
    $toppers = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo 'Sample toppers:' . PHP_EOL;
    foreach ($toppers as $topper) {
        echo '- ' . $topper['name'] . ' (' . $topper['image_path'] . ')' . PHP_EOL;
    }
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage() . PHP_EOL;
}
?>