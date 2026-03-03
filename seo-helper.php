<?php
/**
 * SEO Helper Functions for Info Maths Online
 * This file provides functions to dynamically load SEO settings from the database
 */

// Database connection - adjust path as needed
require_once 'database.php';

function get_seo_settings($page_name) {
    global $pdo;

    if ($pdo === null) {
        return null;
    }

    try {
        $stmt = $pdo->prepare("SELECT * FROM seo_settings WHERE page_name = ?");
        $stmt->execute([$page_name]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("SEO Settings Error: " . $e->getMessage());
        return null;
    }
}

function output_seo_meta_tags($page_name) {
    $seo = get_seo_settings($page_name);

    if (!$seo) {
        // Default fallback SEO
        echo '<meta name="description" content="Info Maths Online - Premier maths coaching and educational platform.">' . "\n";
        echo '<meta name="keywords" content="maths coaching, online maths, competitive exams, IIT JEE maths, Info Maths Online">' . "\n";
        echo '<meta property="og:title" content="Info Maths Online - Premier Maths Coaching Platform">' . "\n";
        echo '<meta property="og:description" content="Premier maths coaching and educational platform for competitive exams.">' . "\n";
        echo '<meta property="og:type" content="website">' . "\n";
        echo '<meta name="robots" content="index,follow">' . "\n";
        return;
    }

    // Output meta title
    if (!empty($seo['meta_title'])) {
        echo '<title>' . htmlspecialchars($seo['meta_title']) . '</title>' . "\n";
    }

    // Output meta description
    if (!empty($seo['meta_description'])) {
        echo '<meta name="description" content="' . htmlspecialchars($seo['meta_description']) . '">' . "\n";
    }

    // Output meta keywords
    if (!empty($seo['meta_keywords'])) {
        echo '<meta name="keywords" content="' . htmlspecialchars($seo['meta_keywords']) . '">' . "\n";
    }

    // Output robots meta
    if (!empty($seo['robots_meta'])) {
        echo '<meta name="robots" content="' . htmlspecialchars($seo['robots_meta']) . '">' . "\n";
    }

    // Output Open Graph tags
    if (!empty($seo['og_title'])) {
        echo '<meta property="og:title" content="' . htmlspecialchars($seo['og_title']) . '">' . "\n";
    }

    if (!empty($seo['og_description'])) {
        echo '<meta property="og:description" content="' . htmlspecialchars($seo['og_description']) . '">' . "\n";
    }

    if (!empty($seo['og_image'])) {
        echo '<meta property="og:image" content="' . htmlspecialchars($seo['og_image']) . '">' . "\n";
    }

    echo '<meta property="og:type" content="website">' . "\n";

    // Output canonical URL
    if (!empty($seo['canonical_url'])) {
        echo '<link rel="canonical" href="' . htmlspecialchars($seo['canonical_url']) . '">' . "\n";
    }
}

function get_meta_title($page_name) {
    $seo = get_seo_settings($page_name);
    return $seo && !empty($seo['meta_title']) ? $seo['meta_title'] : 'Info Maths Online - Premier Maths Coaching Platform';
}

function get_meta_description($page_name) {
    $seo = get_seo_settings($page_name);
    return $seo && !empty($seo['meta_description']) ? $seo['meta_description'] : 'Info Maths Online - Premier maths coaching and educational platform for competitive exams.';
}

function get_meta_keywords($page_name) {
    $seo = get_seo_settings($page_name);
    return $seo && !empty($seo['meta_keywords']) ? $seo['meta_keywords'] : 'maths coaching, online maths, competitive exams, IIT JEE maths, Info Maths Online';
}
?>