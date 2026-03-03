<?php
require 'database.php';

try {
    // 1. Update course_profiles table
    echo "Updating course_profiles table...\n";
    $alter_sql = "ALTER TABLE course_profiles 
                  ADD COLUMN description LONGTEXT DEFAULT NULL AFTER link,
                  ADD COLUMN slug VARCHAR(255) DEFAULT NULL AFTER title,
                  ADD COLUMN meta_title VARCHAR(255) DEFAULT NULL,
                  ADD COLUMN meta_keyword VARCHAR(255) DEFAULT NULL,
                  ADD COLUMN meta_description TEXT DEFAULT NULL";
    
    // Check if columns exist to avoid error
    $stmt = $pdo->query("SHOW COLUMNS FROM course_profiles LIKE 'description'");
    if ($stmt->rowCount() == 0) {
        $pdo->exec($alter_sql);
        echo "Added description and SEO columns to course_profiles.\n";
    } else {
        echo "Columns already exist in course_profiles.\n";
    }

    // Generate slugs for existing courses if empty
    $stmt = $pdo->query("SELECT id, title FROM course_profiles WHERE slug IS NULL OR slug = ''");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $row['title'])));
        $update = $pdo->prepare("UPDATE course_profiles SET slug = ? WHERE id = ?");
        $update->execute([$slug, $row['id']]);
        echo "Generated slug for ID {$row['id']}: $slug\n";
    }

    // 2. Create alumni_reviews table
    echo "Creating alumni_reviews table...\n";
    $create_alumni_sql = "CREATE TABLE IF NOT EXISTS alumni_reviews (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        role VARCHAR(255) DEFAULT NULL,
        quote TEXT,
        image_path VARCHAR(255) DEFAULT NULL,
        display_order INT DEFAULT 0,
        is_active TINYINT(1) DEFAULT 1,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    $pdo->exec($create_alumni_sql);
    echo "alumni_reviews table created/verified.\n";
    
    // Insert default alumni data if empty
    $count = $pdo->query("SELECT COUNT(*) FROM alumni_reviews")->fetchColumn();
    if ($count == 0) {
        $files = [
            ['Rohit Sharma', 'Rank 1 - NIMCET', 'Infomaths gave me the right direction and mentorship to crack NIMCET with a top rank.', 'alu1.jpg'],
            ['Parmish Verma', 'Placed at Google', 'The faculty is extremely supportive. The mock tests were a game changer for my preparation.', 'alu2.jpg'],
            ['Ridhi Kumar', 'Rank 5 - JNU', 'I owe my success to the rigorous training and doubt-clearing sessions at Infomaths.', 'alu3.jpg'],
            ['Himanshu Singh', 'Placed at Amazon', 'A perfect place for MCA aspirants. The study material is comprehensive and targeted.', 'alu4.jpg'],
             ['Ankit Gupta', 'Rank 4 - PU CET', 'The best coaching institute for MCA entrance. Highly recommended for serious aspirants.', 'alu1.jpg']
        ];
        
        $insert = $pdo->prepare("INSERT INTO alumni_reviews (name, role, quote, image_path, display_order) VALUES (?, ?, ?, ?, ?)");
        $order = 10;
        foreach ($files as $f) {
            // Check if file exists in correct path or use placeholder logic
            // Assuming images are in assets/img/im/ or similar. We'll just store the filename.
            $insert->execute([$f[0], $f[1], $f[2], $f[3], $order]);
            $order += 10;
        }
        echo "Inserted default alumni data.\n";
    }

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
