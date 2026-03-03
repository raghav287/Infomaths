<?php
require 'database.php';
try {
    $stmt = $pdo->query('SELECT id, title, link FROM course_profiles');
    print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
} catch (Exception $e) {
    echo $e->getMessage();
}
?>
