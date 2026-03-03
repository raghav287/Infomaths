<?php
include 'database.php';

try {
    $stmt = $pdo->prepare("INSERT INTO upcoming_sessions (image_path, category, title, session_date, session_time, is_active) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute(['assets/img/sessions/future-ready.jpg', 'After XII', 'Future Trends', '2025-11-28', '20:00:00', 1]);
    $stmt->execute(['assets/img/sessions/global-pathway.jpg', 'Study Abroad', 'Global Pathway: School to Success', '2025-11-30', '12:00:00', 1]);
    echo "Sample sessions inserted successfully.";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>