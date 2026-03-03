<?php
require_once '../database.php';

if ($pdo === null) {
    die("Database connection failed.");
}

try {
    // Create table
    $sql = "CREATE TABLE IF NOT EXISTS mca_entrance_content (
        id INT AUTO_INCREMENT PRIMARY KEY,
        content_key VARCHAR(50) UNIQUE NOT NULL,
        title VARCHAR(255) NOT NULL,
        content_value TEXT NOT NULL,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";
    $pdo->exec($sql);
    echo "Table 'mca_entrance_content' created or already exists.<br>";

    // Initial Data
    $initial_data = [
        'entrance_info' => [
            'title' => 'ENTRANCE INFORMATION',
            'content' => 'MCA is an AICTE-approved postgraduate course offered by most Indian universities. Admission via entrance test requires a Bachelor\'s degree (50-60% marks; final-year students eligible). Some need graduation-level mathematics (e.g., Delhi University, University of Hyderabad). Test covers Mathematics, Logical/Analytical Reasoning; some include English and Computer Awareness. Limited seats mean only top ranks get admission. To prepare students for top MCA seats, INFOMATHS INDIA—a pioneer with over 80% success rate in premier entrances—offers MEGALEAP, a focused preparatory course for MCA entrance tests at leading Indian institutions.'
        ],
        'mca_syllabus' => [
            'title' => 'MCA Syllabus',
            'content' => 'Most MCA entrance exams in India (such as NIMCET, CUET PG, MAH MCA CET, BHU PET/CUET, JNU, PU MCA, VIT MCA, and others) focus on testing foundational skills in Mathematics (usually at 10+2 and basic undergraduate level), Logical/Analytical Reasoning, Computer Awareness, and sometimes General English. The syllabus varies slightly by exam, but the core topics overlap significantly, making preparation efficient across multiple tests.'
        ]
    ];

    foreach ($initial_data as $key => $data) {
        // Check if exists
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM mca_entrance_content WHERE content_key = ?");
        $stmt->execute([$key]);
        if ($stmt->fetchColumn() == 0) {
            $stmt = $pdo->prepare("INSERT INTO mca_entrance_content (content_key, title, content_value) VALUES (?, ?, ?)");
            $stmt->execute([$key, $data['title'], $data['content']]);
            echo "Inserted initial data for '$key'.<br>";
        } else {
            echo "Data for '$key' already exists.<br>";
        }
    }

} catch (PDOException $e) {
    die("DB Error: " . $e->getMessage());
}
?>