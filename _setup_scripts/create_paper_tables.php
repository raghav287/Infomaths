<?php
require_once 'database.php';

try {
    // Create university_categories table
    $sqlCategories = "CREATE TABLE IF NOT EXISTS university_categories (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        slug VARCHAR(255) NOT NULL UNIQUE,
        image_path VARCHAR(255),
        display_order INT DEFAULT 0,
        is_active TINYINT(1) DEFAULT 1,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    $pdo->exec($sqlCategories);
    echo "Table 'university_categories' created successfully.<br>";

    // Create university_papers table
    $sqlPapers = "CREATE TABLE IF NOT EXISTS university_papers (
        id INT AUTO_INCREMENT PRIMARY KEY,
        category_id INT NOT NULL,
        title VARCHAR(255) NOT NULL,
        image_path VARCHAR(255),
        pdf_file VARCHAR(255),
        type ENUM('paper', 'result') DEFAULT 'paper',
        display_order INT DEFAULT 0,
        is_active TINYINT(1) DEFAULT 1,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (category_id) REFERENCES university_categories(id) ON DELETE CASCADE
    )";
    $pdo->exec($sqlPapers);
    echo "Table 'university_papers' created successfully.<br>";

    // Insert Default Categories (from the HTML)
    $defaults = [
        ['name' => 'NIMCET', 'slug' => 'nimcet', 'image' => 'uni1.jpg'],
        ['name' => 'MAHCET/IP', 'slug' => 'mahcet-ip', 'image' => 'uni2.jpg'],
        ['name' => 'Punjab University', 'slug' => 'punjab-university', 'image' => 'uni3.jpg'],
        ['name' => 'Delhi University', 'slug' => 'delhi-university', 'image' => 'uni4.jpg'],
        ['name' => 'JNU', 'slug' => 'jnu', 'image' => 'jnu.jpg'],
        ['name' => 'KIIT/BHU/PCU/VIT', 'slug' => 'others', 'image' => 'uni6.jpg']
    ];

    $checkStmt = $pdo->prepare("SELECT COUNT(*) FROM university_categories");
    $checkStmt->execute();
    if ($checkStmt->fetchColumn() == 0) {
        $insertStmt = $pdo->prepare("INSERT INTO university_categories (name, slug, image_path, display_order) VALUES (?, ?, ?, ?)");
        $order = 0;
        foreach ($defaults as $cat) {
            $insertStmt->execute([$cat['name'], $cat['slug'], $cat['image'], $order++]);
        }
        echo "Default categories inserted.<br>";
    }

} catch (PDOException $e) {
    echo "Error creating tables: " . $e->getMessage();
}
?>
