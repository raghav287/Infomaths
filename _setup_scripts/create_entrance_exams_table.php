<?php
require_once 'database.php';

try {
    $sql = "CREATE TABLE IF NOT EXISTS entrance_exams (
        id INT AUTO_INCREMENT PRIMARY KEY,
        exam_name VARCHAR(255) NOT NULL,
        slug VARCHAR(255) NOT NULL UNIQUE,
        icon_image VARCHAR(255),
        short_description TEXT,
        full_description LONGTEXT,
        meta_title VARCHAR(255),
        meta_keyword VARCHAR(255),
        meta_description TEXT,
        display_order INT DEFAULT 0,
        is_active TINYINT(1) DEFAULT 1,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

    $pdo->exec($sql);
    echo "Table 'entrance_exams' created successfully.<br>";

    // Insert Default Data (NIMCET, MAH MCA CET, etc.) if table is empty
    $stmt = $pdo->query("SELECT COUNT(*) FROM entrance_exams");
    if ($stmt->fetchColumn() == 0) {
        $exams = [
            [
                'exam_name' => 'NIMCET',
                'slug' => 'nimcet',
                'icon_image' => 'assets/img/others/nimcet.png',
                'short_description' => 'NIT MCA Common Entrance Test',
                'full_description' => '<h2>About NIMCET</h2><p>The NIT MCA Common Entrance Test (NIMCET) is a national level entrance exam conducted by National Institutes of Technology (NITs) for admission to their Master of Computer Applications (MCA) programme.</p>',
                'meta_title' => 'NIMCET Exam Details - Syllabus, Pattern, Eligibility',
                'meta_keyword' => 'NIMCET, NIMCET 2026, NIT MCA, MCA Entrance',
                'meta_description' => 'Complete details about NIMCET exam including syllabus, eligibility criteria, exam pattern and important dates.'
            ],
            [
                'exam_name' => 'MAH MCA CET',
                'slug' => 'mah-mca-cet',
                'icon_image' => 'assets/img/others/mahcet.png',
                'short_description' => 'Maharashtra MCA Common Entrance Test',
                'full_description' => '<h2>About MAH MCA CET</h2><p>MAH MCA CET is a state-level entrance exam conducted by the State Common Entrance Test Cell, Maharashtra, for admission to MCA courses in various colleges in Maharashtra.</p>',
                'meta_title' => 'MAH MCA CET Exam Details',
                'meta_keyword' => 'MAH MCA CET, Maharashtra MCA, MCA Entrance',
                'meta_description' => 'All you need to know about MAH MCA CET exam for admission to MCA colleges in Maharashtra.'
            ],
             [
                'exam_name' => 'PG CUET MCA',
                'slug' => 'pg-cuet-mca',
                'icon_image' => 'assets/img/others/cuet.png',
                'short_description' => 'Central University Entrance Test (PG)',
                'full_description' => '<h2>About CUET PG</h2><p>CUET PG is an all-India level entrance exam for admission to postgraduate programmes in Central Universities and other participating universities.</p>',
                'meta_title' => 'CUET PG MCA Exam Details',
                'meta_keyword' => 'CUET PG, CUET MCA, Central University MCA',
                'meta_description' => 'Details about CUET PG for MCA admissions.'
            ],
             [
                'exam_name' => 'VITMEE',
                'slug' => 'vitmee',
                'icon_image' => 'assets/img/others/vitmee.png',
                'short_description' => 'VIT Master\'s Entrance Examination',
                'full_description' => '<h2>About VITMEE</h2><p>VITMEE is conducted by Vellore Institute of Technology (VIT) for admission to MCA and M.Tech programmes at its campuses.</p>',
                'meta_title' => 'VITMEE Exam Details',
                'meta_keyword' => 'VITMEE, VIT MCA, Vellore Institute of Technology',
                'meta_description' => 'Information regarding the VITMEE exam for MCA admission.'
            ],
             [
                'exam_name' => 'PU CET PG',
                'slug' => 'pu-cet-pg',
                'icon_image' => 'assets/img/others/pucet.png',
                'short_description' => 'Panjab University Common Entrance Test (PG)',
                'full_description' => '<h2>About PU CET PG</h2><p>PU CET (PG) is conducted by Panjab University, Chandigarh, for admission to various postgraduate courses including MCA.</p>',
                'meta_title' => 'PU CET PG MCA Exam Details',
                'meta_keyword' => 'PU CET PG, Panjab University MCA, PU Chandigarh',
                'meta_description' => 'Details about the PU CET PG exam for MCA admission at Panjab University.'
            ]
        ];

        $insertStmt = $pdo->prepare("INSERT INTO entrance_exams (exam_name, slug, icon_image, short_description, full_description, meta_title, meta_keyword, meta_description) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        
        foreach ($exams as $exam) {
            $insertStmt->execute([
                $exam['exam_name'],
                $exam['slug'],
                $exam['icon_image'],
                $exam['short_description'],
                $exam['full_description'],
                $exam['meta_title'],
                $exam['meta_keyword'],
                $exam['meta_description']
            ]);
        }
        echo "Default entrance exams data inserted successfully.<br>";
    } else {
        echo "Table already has data.<br>";
    }

} catch (PDOException $e) {
    die("DB Error: " . $e->getMessage());
}
?>
