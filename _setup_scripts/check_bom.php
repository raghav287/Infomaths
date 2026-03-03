<?php
$files = [
    'course-details.php',
    'exam-details.php',
    'mca-entrance.php',
    'database.php',
    'header.php',
    'header-styles.php',
    'footer.php',
    'populate_real_content.php',
    'update_seo_links.php',
    'fix_course_db_and_data.php'
];

foreach ($files as $file) {
    if (!file_exists($file)) continue;
    
    $content = file_get_contents($file);
    // BOM for UTF-8 is EF BB BF
    if (substr($content, 0, 3) === "\xEF\xBB\xBF") {
        echo "BOM FOUND in: $file\n";
    } else {
        // echo "No BOM in: $file\n";
    }
}
?>
