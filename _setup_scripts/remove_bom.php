<?php
$files = [
    'header.php',
    'header-styles.php',
    'footer.php'
];

foreach ($files as $file) {
    if (!file_exists($file)) continue;

    $content = file_get_contents($file);
    if (substr($content, 0, 3) === "\xEF\xBB\xBF") {
        $content = substr($content, 3);
        file_put_contents($file, $content);
        echo "Removed BOM from: $file\n";
    }
}
echo "BOM removal complete.";
?>
