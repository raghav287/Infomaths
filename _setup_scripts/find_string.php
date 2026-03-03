<?php
$file = 'mca-entrance.php';
$search = 'Our team members';
$lines = file($file);
foreach ($lines as $lineNumber => $line) {
    if (strpos($line, $search) !== false) {
        echo "Found at line " . ($lineNumber + 1) . ": " . trim($line) . "\n";
    }
}
$search2 = 'Meet Our Expert Members';
foreach ($lines as $lineNumber => $line) {
    if (strpos($line, $search2) !== false) {
        echo "Found at line " . ($lineNumber + 1) . ": " . trim($line) . "\n";
    }
}
?>
