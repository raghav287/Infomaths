<?php
// check_cms.php
require 'database.php';

echo "<h2>CMS Verification Check</h2>";

// 1. Check Course Profiles
echo "<h3>1. Checking Course Profiles (SEO & Descriptions)</h3>";
$stmt = $pdo->query("SELECT id, title, slug, meta_title, LENGTH(description) as desc_len FROM course_profiles LIMIT 5");
if ($stmt->rowCount() > 0) {
    echo "<ul>";
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<li>ID: {$row['id']} | Title: {$row['title']} | Slug: {$row['slug']} | Desc Len: {$row['desc_len']} bytes</li>";
    }
    echo "</ul>";
} else {
    echo "<p style='color:red'>No Course Profiles found!</p>";
}

// 2. Check Alumni Reviews
echo "<h3>2. Checking Alumni Reviews</h3>";
$stmt = $pdo->query("SELECT id, name, role, display_order, is_active FROM alumni_reviews ORDER BY display_order ASC LIMIT 5");
if ($stmt->rowCount() > 0) {
    echo "<ul>";
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<li>ID: {$row['id']} | Name: {$row['name']} | Role: {$row['role']} | Active: {$row['is_active']}</li>";
    }
    echo "</ul>";
} else {
    echo "<p style='color:red'>No Alumni Reviews found!</p>";
}

// 3. Check Frontend Pages Access
echo "<h3>3. Checking Links</h3>";
echo "<ul>";
echo "<li><a href='mca-entrance.php' target='_blank'>MCA Entrance (Main Page) - Check Course Links & Alumni Slider</a></li>";
echo "<li><a href='course-details.php?slug=adwintage' target='_blank'>Sample Course Detail (AdWINtage)</a></li>";
echo "<li><a href='admin/course-profile-management.php' target='_blank'>Admin - Course Profiles</a></li>";
echo "<li><a href='admin/alumni-management.php' target='_blank'>Admin - Alumni Reviews</a></li>";
echo "</ul>";
?>
