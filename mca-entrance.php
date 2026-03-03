<?php

require_once 'database.php';

$courses = [];
try {
    // UPDATED: Use course_profiles table instead of legacy courses table
    $sql = "SELECT title as course_name, is_active FROM course_profiles WHERE is_active = 1 AND show_in_dropdown = 1 ORDER BY display_order ASC, title ASC";
    $stmt = $pdo->query($sql);
    $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // If database query fails, use default courses
    $courses = [
        ['course_name' => 'MCA Entrance (NIMCET)', 'is_active' => 1],
        ['course_name' => 'MCA Entrance (CUCET PG)', 'is_active' => 1],
        ['course_name' => 'MCA Entrance (PU MCA)', 'is_active' => 1],
        ['course_name' => 'MCA Entrance (MAHCET)', 'is_active' => 1],
        ['course_name' => 'MCA Entrance (State Level)', 'is_active' => 1],
        ['course_name' => 'MCA Foundation Course', 'is_active' => 1],
        ['course_name' => 'Other', 'is_active' => 1]
    ];
}

// Fetch active hero slides
$heroSlides = [];
try {
    $sql = "SELECT * FROM hero_slider WHERE status = 'active' ORDER BY sort_order ASC, id ASC";
    $stmt = $pdo->query($sql);
    $heroSlides = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // If database query fails, use default hero slides
    $heroSlides = [
        [
            'title' => 'Welcome to INFOMATHS',
            'subtitle' => 'Your trusted partner for MCA entrance excellence since 1999. Expert faculty, proven results, and comprehensive training for top rankings.',
            'background_image' => 'assets/img/cgc_banner.webp',
            'button1_text' => 'Apply Now',
            'button1_link' => '#',
            'button2_text' => 'Join Now',
            'button2_link' => '#'
        ],
        [
            'title' => 'ABOUT INFOMATHS',
            'subtitle' => 'Leading MCA entrance coaching institute since 1999. Started in Chandigarh, now a multi-location training specialist helping thousands achieve their career goals.',
            'background_image' => 'assets/img/cgc_banner_2.png',
            'button1_text' => 'Apply Now',
            'button1_link' => '#',
            'button2_text' => 'Join Now',
            'button2_link' => '#'
        ]
    ];
}

// Fetch active entrance exams
$entranceExams = [];
try {
    $sql = "SELECT * FROM entrance_exams WHERE is_active = 1 ORDER BY display_order ASC, exam_name ASC";
    $stmt = $pdo->query($sql);
    $entranceExams = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Fail silently
}

// Fallback to default static list if database is empty
if (empty($entranceExams)) {
    $entranceExams = [
        [
            'exam_name' => 'NIMCET',
            'slug' => 'nimcet',
            'icon_image' => 'assets/img/others/nimcet.png'
        ],
        [
            'exam_name' => 'MAH MCA CET',
            'slug' => 'mah-mca-cet',
            'icon_image' => 'assets/img/others/mahcet.png'
        ],
        [
            'exam_name' => 'PG CUET MCA',
            'slug' => 'pg-cuet-mca',
            'icon_image' => 'assets/img/others/cuet.png'
        ],
        [
            'exam_name' => 'VITMEE',
            'slug' => 'vitmee',
            'icon_image' => 'assets/img/others/vitmee.png'
        ],
        [
            'exam_name' => 'PU CET PG',
            'slug' => 'pu-cet-pg',
            'icon_image' => 'assets/img/others/pucet.png'
        ],
        [
            'exam_name' => 'Other State',
            'slug' => 'other-state',
            'icon_image' => '' // Handle empty icon in loop
        ]
    ];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <!-- Meta Tags -->
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="author" content="ThemeDox" />
    <!-- Favicon Icon -->
    <link rel="icon" href="assets/img/favicon.png" />
    <!-- Site Title -->
    <title>Infomaths</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="assets/css/fontawesome.min.css" />
    <link rel="stylesheet" href="assets/css/slick.min.css" />
    <link rel="stylesheet" href="assets/css/odometer.css" />
    <link rel="stylesheet" href="assets/css/animate.css" />
    <link rel="stylesheet" href="assets/css/jquery-ui.min.css" />
    <link rel="stylesheet" type="text/css" href="assets/css/slick.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/owl-carousel/1.3.3/owl.carousel.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/owl-carousel/1.3.3/owl.theme.min.css" />
    <link rel="stylesheet" href="assets/css/style.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css" />
    <style>
    @font-face {
        font-family: 'TT Firs Text Trial Italic';
        src: url('assets/fonts/TT Firs Text Trial Light.ttf') format('truetype');
        font-weight: 400;
        font-style: Light;
    }

    /* NIMCET Results Slider Card Visual Improvements */
    .nimcet-slider {
        margin: 0 auto;
        max-width: 1200px;
    }

    .nimcet-slider .slider {
        display: flex;
        align-items: stretch;
    }

    .slide-card {
        background: linear-gradient(135deg, #232526 0%, #414345 100%);
        color: #fff;
        border-radius: 18px;
        box-shadow: 0 4px 24px rgba(0, 0, 0, 0.18);
        padding: 32px 24px 24px 24px;
        margin: 0 12px;
        text-align: center;
        position: relative;
        min-width: 320px;
        max-width: 350px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .slide-card:hover {
        transform: translateY(-8px) scale(1.03);
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.22);
    }

    .slide-header {
        font-size: 2rem;
        font-weight: 700;
        color: #ffd700;
        margin-bottom: 8px;
        letter-spacing: 1px;
        text-shadow: 0 2px 8px rgba(0, 0, 0, 0.10);
    }

    .slide-subtitle {
        font-size: 1.1rem;
        font-weight: 500;
        margin-bottom: 10px;
        color: #fff;
        opacity: 0.85;
    }

    .slide-logo {
        display: block;
        margin: 0 auto 10px auto;
        border-radius: 50%;
        background: #fff;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.10);
        padding: 6px;
    }

    .slide-rank {
        font-size: 2.2rem;
        font-weight: 800;
        background: #000;
        color: #ffd700;
        border-radius: 8px;
        padding: 8px 24px;
        margin-bottom: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.10);
        letter-spacing: 2px;
        display: inline-block;
    }

    .slide-appno {
        font-size: 1rem;
        color: #ccc;
        margin-bottom: 8px;
        font-family: 'Euclid Circular A', Arial, sans-serif;
    }

    .slide-name {
        font-size: 1.3rem;
        font-weight: 700;
        color: #fff;
        letter-spacing: 1px;
        margin-bottom: 0;
        text-transform: uppercase;
    }

    /* Slick Dots and Arrows Position & Style Fix */
    .nimcet-slider {
        position: relative;
        padding-bottom: 60px;
    }

    .nimcet-slider .slick-dots {
        position: absolute !important;
        left: 50%;
        transform: translateX(-50%);
        bottom: 10px;
        display: flex !important;
        justify-content: center;
        gap: 8px;
        margin: 0;
        padding: 0;
    }

    .nimcet-slider .slick-dots li {
        margin: 0 4px;
    }

    .nimcet-slider .slick-dots li button {
        width: 16px;
        height: 16px;
        border-radius: 50%;
        background: #ffd700;
        border: none;
        color: transparent;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.10);
        transition: background 0.2s, transform 0.2s;
    }

    .nimcet-slider .slick-dots li.slick-active button {
        background: #232526;
        border: 2px solid #ffd700;
        transform: scale(1.2);
    }

    .nimcet-slider .slick-dots li button:before {
        display: none;
    }

    .nimcet-slider .slick-arrow {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background: #ffd700;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        z-index: 2;
        border: none;
        color: #232526;
        font-size: 22px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.10);
        transition: background 0.2s, color 0.2s;
    }

    .nimcet-slider .slick-arrow.slick-prev {
        left: -20px;
    }

    .nimcet-slider .slick-arrow.slick-next {
        right: -20px;
    }

    .nimcet-slider .slick-arrow:hover {
        background: #232526;
        color: #ffd700;
    }

    /* Testimonials Slider Arrows */
    /* .td_slider .slick-arrow {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(255, 255, 255, 0.9);
            border-radius: 50%;
            width: 40px;
            height: 40px;
            z-index: 2;
            border: none;
            color: #1e4e64;
            font-size: 18px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        } */

    /* .td_slider .slick-arrow:hover {
            background: #1e4e64;
            color: #fff;
            transform: translateY(-50%) scale(1.1);
        } */

    .td_slider .slick-prev {
        left: -20px;
    }

    .td_slider .slick-next {
        right: -20px;
    }

    @media (max-width: 768px) {
        .td_slider .slick-arrow {
            width: 35px;
            height: 35px;
            font-size: 16px;
        }

        .td_slider .slick-prev {
            left: -15px;
        }

        .td_slider .slick-next {
            right: -15px;
        }

        .photo_grid {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-wrap: wrap;
        }
    }
    </style>

    <style>
    /* 1. Define the rotation animation */
    @keyframes spin {
        from {
            transform: rotate(0deg);
        }

        to {
            transform: rotate(360deg);
        }
    }

    /* 2. Apply the animation when hovering over the main box */
    .td_college:hover .td_college_icon img {
        animation: spin 4s linear infinite;
    }

    /*Start Program Card*/
    .program-card {
        position: relative;
        background: #fff;
        border-radius: 8px;
        overflow: hidden;
        text-align: center;
        padding: 40px 15px;
        cursor: pointer;
        transform-style: preserve-3d;
        perspective: 1000px;
        will-change: transform;
        transition: transform 0.2s ease;
        /* reset animation on mouseleave */
    }

    /* Background image */
    .program-card::before {
        content: "";
        position: absolute;
        inset: 0;
        background-image: var(--bg-image);
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        filter: blur(0.5px);
        transform: scale(1.1);
        opacity: 0;
        transition: opacity 0.6s ease, transform 0.8s ease;
        z-index: 0;
    }

    /* Overlay */
    .program-card::after {
        content: "";
        position: absolute;
        inset: 0;
        background-color: #1e4e64;
        opacity: 0;
        transition: opacity 0.6s ease;
        z-index: 0;
    }

    .program-card:hover::before {
        opacity: 1;
        transform: scale(1.2);
    }

    .program-card:hover::after {
        opacity: 0.6;
    }

    .program-card-content {
        position: relative;
        z-index: 1;
        transition: color 0.4s ease, transform 0.4s ease;
        will-change: transform;
    }

    .program-card-icon img {
        width: 35px;
        margin-bottom: 15px;
        transition: filter 0.4s ease;
    }

    .program-card-title {
        font-size: 20px;
        font-weight: 600;
        color: #222;
        transition: color 0.4s ease;
    }

    .program-card:hover .program-card-icon img {
        filter: brightness(0) invert(1);
    }

    .program-card:hover .program-card-title {
        color: #fff;
    }

    .program-card-link {
        position: absolute;
        inset: 0;
        z-index: 2;
    }


    /*End Program Card*/
    @media (max-width: 991px) {
        .td_top_bar_right {
            gap: 9px !important;
        }
    }

    /* Header Transparency Effect */
    /* .td_site_header {
            background-color: transparent !important;
            transition: all 0.4s ease;
        }

        .td_site_header .td_main_header {
            background-color: transparent !important;
            transition: all 0.4s ease;
        } */

    /* .td_site_header.td_gescout_sticky,
        .td_site_header.td_sticky_active {
            background-color: #1e4e64 !important;
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
        }

        .td_site_header.td_gescout_sticky .td_main_header,
        .td_site_header.td_sticky_active .td_main_header {
            background-color: #1e4e64 !important;
        } */

    /* Ensure text remains visible on blue background */
    /* Programs Mega Menu Font Size */
    .td_mega_wrapper a {
        font-size: 12px !important;
    }

    .td_mega_wrapper h4 {
        font-size: 14px !important;
        font-weight: 600;
    }

    @media screen and (min-width: 1100px) {
        .td_nav .td_nav_list .td_mega_wrapper {
            width: 1196px !important;
            display: flex !important;
            position: fixed;
            border-radius: 7px;
            top: 100px !important;
            left: 50%;
            line-height: 1.2em;
            transform: translateX(-50%);
            padding: 5px 15px 10px;
            border: none;


        }
    }

    @media screen and (min-width: 1200px) {
        .td_nav .td_nav_list .td_mega_wrapper {
            width: 1196px !important;
            display: flex !important;
            position: fixed;
            border-radius: 7px;
            top: 100px !important;
            line-height: 1.2em;
            left: 50%;
            transform: translateX(-50%);
            padding: 5px 15px 10px;
            border: none;

        }
    }

    @media screen and (min-width: 1400px) {
        .td_nav .td_nav_list .td_mega_wrapper {
            width: 1196px !important;
            display: flex !important;
            border: none;
            line-height: 1.2em;
            position: fixed;
            top: 100px !important;
            left: 50%;
            transform: translateX(-50%);
            padding: 5px 15px 10px;

        }
    }


    /* Placement Records Slider Styles */
    .placement-records-slider-container {
        position: relative;
        overflow: hidden;
    }

    .placement-record-card {
        position: relative;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;

    }

    .placement-record-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
    }

    .placement-image-container {
        position: relative;
        width: 100%;
        height: 100%;
        overflow: hidden;
    }

    .placement-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .placement-record-card:hover .placement-image {
        transform: scale(1.05);
    }

    .placement-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: linear-gradient(transparent, rgba(0, 0, 0, 0.8));
        padding: 40px 20px 20px;
        transform: translateY(100%);
        transition: transform 0.3s ease;
    }

    .placement-record-card:hover .placement-overlay {
        transform: translateY(0);
    }

    .placement-info {
        color: white;
        text-align: center;
    }

    .placement-info .student-name {
        font-size: 1.25rem;
        font-weight: 700;
        margin-bottom: 8px;
        color: #fff;
    }

    .placement-info .company-name {
        font-size: 1rem;
        font-weight: 600;
        margin-bottom: 5px;
        color: #ffd700;
    }

    .placement-info .package {
        font-size: 1.1rem;
        font-weight: 700;
        color: #00ff88;
        margin-bottom: 0;
    }

    .placement-slider-navigation {
        display: flex;
        justify-content: center;
        gap: 20px;
        margin-top: 30px;
    }

    .placement-nav-btn {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: #1e4e64;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(30, 78, 100, 0.3);
    }

    .placement-nav-btn:hover {
        background: #0056b3;
        transform: translateY(-3px);
        box-shadow: 0 6px 20px rgba(30, 78, 100, 0.4);
    }

    .placement-nav-btn svg {
        width: 24px;
        height: 24px;
    }

    /* Badge styling for placement record cards */


    /* Responsive adjustments */
    @media (max-width: 768px) {
        .placement-record-card {
            height: 300px;
        }

        .placement-info .student-name {
            font-size: 1.1rem;
        }

        .placement-info .company-name {
            font-size: 0.9rem;
        }

        .placement-info .package {
            font-size: 1rem;
        }

        .placement-nav-btn {
            width: 45px;
            height: 45px;
        }
    }

    /* Animation for slider entrance */
    @keyframes slideInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .placement-records-slider-container.wow.fadeInUp {
        animation: slideInUp 0.6s ease-out;
    }

    /* Top Recruiters Slider Styles */
    .top-recruiters-slider {
        overflow: hidden;
    }

    /* ChatGPT Style Search Modal Styles */
    .search-modal {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 10000;
        display: none;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .search-modal.active {
        display: flex;
        opacity: 1;
    }

    .search-modal-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.6);
        backdrop-filter: blur(4px);
    }

    .search-modal-content {
        position: relative;
        width: 90%;

        margin: 5% auto;
        background: #ffffff;
        border-radius: 16px;
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.25);
        animation: modalSlideIn 0.3s ease-out;

        overflow: hidden;
        display: flex;
        flex-direction: column;
    }

    @keyframes modalSlideIn {
        from {
            transform: translateY(-50px);
            opacity: 0;
        }

        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    .search-modal-header {
        display: flex;
        justify-content: flex-end;
        padding: 16px 20px 0;
    }

    .search-modal-close {
        background: none;
        border: none;
        padding: 8px;
        cursor: pointer;
        border-radius: 8px;
        color: #6b7280;
        transition: all 0.2s ease;
    }

    .search-modal-close:hover {
        background: #f3f4f6;
        color: #374151;
    }

    .search-modal-body {
        padding: 0 24px 24px;
        flex: 1;
        overflow-y: auto;
    }

    .search-input-container {
        position: relative;
        display: flex;
        align-items: center;
        background: #f9fafb;
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        padding: 0 16px;
        transition: all 0.2s ease;
        margin-bottom: 24px;
    }

    .search-input-container:focus-within {
        border-color: #1e4e64;
        background: #ffffff;
        box-shadow: 0 0 0 3px rgba(30, 78, 100, 0.1);
    }

    .search-icon {
        color: #9ca3af;
        margin-right: 12px;
        flex-shrink: 0;
    }

    .search-modal-input {
        flex: 1;
        border: none;
        background: transparent;
        outline: none;
        padding: 16px 0;
        font-size: 16px;
        color: #111827;
        font-family: inherit;
    }

    .search-modal-input::placeholder {
        color: #9ca3af;
    }

    .search-submit-btn {
        background: #1e4e64;
        border: none;
        border-radius: 8px;
        padding: 8px;
        color: white;
        cursor: pointer;
        transition: background 0.2s ease;
        margin-left: 8px;
        flex-shrink: 0;
    }

    .search-submit-btn:hover {
        background: #0056b3;
    }

    .search-suggestions {
        margin-bottom: 24px;
    }

    .suggestions-header {
        font-size: 14px;
        font-weight: 600;
        color: #6b7280;
        margin-bottom: 12px;
    }

    .suggestion-pills {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .suggestion-pill {
        background: #f3f4f6;
        border: 1px solid #e5e7eb;
        border-radius: 20px;
        padding: 8px 16px;
        font-size: 14px;
        color: #374151;
        cursor: pointer;
        transition: all 0.2s ease;
        white-space: nowrap;
    }

    .suggestion-pill:hover {
        background: #1e4e64;
        color: white;
        border-color: #1e4e64;
    }

    /* AI Summary Styles */
    .search-summary {
        margin-bottom: 24px;
        background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
        border: 1px solid #0ea5e9;
        border-radius: 12px;
        padding: 20px;
        position: relative;
        overflow: hidden;
    }

    .search-summary::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, #0ea5e9, #3b82f6, #8b5cf6);
        animation: shimmer 2s linear infinite;
    }

    @keyframes shimmer {
        0% {
            transform: translateX(-100%);
        }

        100% {
            transform: translateX(100%);
        }
    }

    .summary-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 16px;
    }

    .summary-title {
        display: flex;
        align-items: center;
        gap: 8px;
        font-weight: 600;
        color: #0f172a;
        font-size: 16px;
    }

    .summary-title svg {
        color: #0ea5e9;
    }

    .summary-regenerate {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        padding: 6px;
        cursor: pointer;
        transition: all 0.2s ease;
        color: #64748b;
    }

    .summary-regenerate:hover {
        background: #f1f5f9;
        color: #334155;
        border-color: #cbd5e1;
    }

    .summary-content {
        font-size: 15px;
        line-height: 1.6;
        color: #334155;
        margin-bottom: 16px;
        min-height: 60px;
    }

    .summary-content.loading {
        display: flex;
        align-items: center;
        gap: 12px;
        color: #64748b;
        font-style: italic;
    }

    .summary-content.loading::before {
        content: '';
        width: 16px;
        height: 16px;
        border: 2px solid #e2e8f0;
        border-top: 2px solid #0ea5e9;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    .summary-sources {
        border-top: 1px solid #e2e8f0;
        padding-top: 12px;
    }

    .sources-label {
        font-size: 12px;
        font-weight: 600;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .sources-list {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 8px;
    }

    .source-link {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 4px 12px;
        font-size: 12px;
        color: #475569;
        text-decoration: none;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        max-width: 200px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .source-link:hover {
        background: #f8fafc;
        border-color: #cbd5e1;
        color: #334155;
    }

    .source-link::before {
        content: '';
        width: 6px;
        height: 6px;
        background: #22c55e;
        border-radius: 50%;
        flex-shrink: 0;
    }

    .search-results {
        max-height: 400px;
        overflow-y: auto;
    }

    .results-header {
        font-size: 14px;
        font-weight: 600;
        color: #6b7280;
        margin-bottom: 16px;
        padding-bottom: 8px;
        border-bottom: 1px solid #e5e7eb;
    }

    .results-container {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .search-result-item {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 16px;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .search-result-item:hover {
        border-color: #1e4e64;
        box-shadow: 0 4px 12px rgba(30, 78, 100, 0.1);
    }

    .result-title {
        font-size: 16px;
        font-weight: 600;
        color: #1e4e64;
        margin-bottom: 8px;
        line-height: 1.4;
    }

    .result-snippet {
        font-size: 14px;
        color: #6b7280;
        line-height: 1.5;
        margin-bottom: 8px;
    }

    .result-url {
        font-size: 12px;
        color: #059669;
        text-decoration: none;
    }

    .search-loading {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 40px;
        color: #6b7280;
    }

    .loading-spinner {
        width: 32px;
        height: 32px;
        border: 3px solid #e5e7eb;
        border-top: 3px solid #1e4e64;
        border-radius: 50%;
        animation: spin 1s linear infinite;
        margin-bottom: 12px;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .search-modal-content {
            width: 95%;
            margin: 2% auto;
            max-height: 95vh;
        }

        .search-modal-body {
            padding: 0 16px 16px;
        }

        .search-input-container {
            padding: 0 12px;
        }

        .search-modal-input {
            padding: 14px 0;
            font-size: 16px;
        }

        .suggestion-pills {
            gap: 6px;
        }

        .suggestion-pill {
            padding: 6px 12px;
            font-size: 13px;
        }
    }

    @media (max-width: 480px) {
        .search-modal-content {
            width: 98%;
            margin: 1% auto;
        }
    }

    .marquee-slider-image {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        height: 80px;
        width: 100px;
        padding: 15px;
        border-radius: 10px;
        margin: 0 10px;
        transition: all 0.3s ease;
        flex-shrink: 0;
    }

    .marquee-slider-image:hover {

        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
    }

    .marquee-slider-image img {
        max-height: 100px;
        max-width: 110px;
        object-fit: contain;
        border-radius: 20px;
        transition: all 0.3s ease;
    }

    .marquee-slider-image:hover img {
        transform: scale(1.1);
    }

    /* Responsive adjustments for recruiters slider */
    @media (max-width: 768px) {
        .marquee-slider-image {
            height: 60px;
            width: 100px;
            padding: 10px;
            margin: 0 5px;
        }

        .marquee-slider-image img {
            max-height: 35px;
            max-width: 80px;
        }
    }


    /*App section*/
    .app-section_custom {
        min-height: 500px;
        padding: 40px 20px;
        background: #00001b;
        /* Background */
    }

    .app-container_custom {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
    }

    .td_cta_text_custom {
        flex: 1 1 50%;
        color: #fff;
        max-width: 600px;
    }

    .td_section_subtitle_custom {
        font-size: 16px;
        text-transform: uppercase;
        opacity: 0.8;
        margin-bottom: 10px;
    }

    .td_section_title_custom {
        color: #fff;
        /* FIXED: white heading */
        font-size: 42px;
        line-height: 1.2;
        margin-bottom: 20px;
    }

    .td_btns_group_custom {
        display: flex;
        gap: 15px;
        flex-wrap: wrap;
    }

    .td_btn_custom {
        display: inline-flex;
        align-items: center;
        padding: 12px 20px;
        border-radius: 30px;
        background: #fff;
        color: #0a3c75;
        font-weight: 600;
        text-decoration: none;
        transition: 0.3s;
    }

    .td_btn_custom:hover {
        background: #1e4e64;
        color: #fff;
    }

    .td_btn_custom svg {
        margin-right: 8px;
    }

    /* FIXED: match HTML class name */
    .td_cta_thumb {
        flex: 1 1 45%;
        text-align: center;
    }

    .td_cta_thumb img {
        max-width: 100%;
        height: auto;
    }

    /* Responsive */
    @media (max-width: 992px) {
        .td_section_title_custom {
            font-size: 32px;
        }
    }

    @media (max-width: 768px) {
        .app-container_custom {
            flex-direction: column;
            text-align: center;
        }

        .td_cta_text_custom,
        .td_cta_thumb {
            flex: 1 1 100%;
            max-width: 100%;
        }

        .td_btns_group_custom {
            justify-content: center;
        }

        .td_section_title_custom {
            font-size: 28px;
        }
    }

    /* Notification Bell Styles */
    .td_notification_btn {
        position: relative;
    }

    .notification-badge {
        position: absolute;
        top: -5px;
        right: -5px;
        background: #dc3545;
        color: white;
        border-radius: 50%;
        width: 18px;
        height: 18px;
        font-size: 10px;
        font-weight: bold;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid white;
    }

    /* Notification Modal Styles */
    .notification-modal {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 10000;
        display: none;
        opacity: 0;
        transition: opacity 0.3s ease-in-out;
        backdrop-filter: blur(8px);
    }

    .notification-modal.active {
        display: flex;
        opacity: 1;
        align-items: center;
        justify-content: center;
    }

    .notification-modal-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.6);
        backdrop-filter: blur(5px);
    }

    .notification-modal-content {
        position: relative;
        background: white;
        border-radius: 20px;
        width: 90%;
        max-width: 1200px;
        max-height: 90vh;
        overflow: hidden;
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
        transform: scale(0.95);
        transition: transform 0.3s ease-in-out;
        z-index: 10001;
    }

    .notification-modal.active .notification-modal-content {
        transform: scale(1);
    }

    .notification-modal-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 20px 24px;
        border-bottom: 1px solid #e5e7eb;
        background: #f8f9fa;
    }

    .notification-modal-header h3 {
        margin: 0;
        font-size: 20px;
        font-weight: 600;
        color: #1f2937;
    }

    .notification-modal-close {
        background: none;
        border: none;
        color: #6b7280;
        cursor: pointer;
        padding: 8px;
        border-radius: 50%;
        transition: all 0.2s ease;
    }

    .notification-modal-close:hover {
        background: #e5e7eb;
        color: #374151;
    }

    .notification-modal-body {
        padding: 0;
        max-height: 60vh;
        overflow-y: auto;
    }

    .notification-filters {
        padding: 20px 24px 0;
        border-bottom: 1px solid #e5e7eb;
        background: white;
        position: sticky;
        top: 0;
        z-index: 1;
    }

    .filter-tabs {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
        margin-bottom: 16px;
    }

    .filter-tab {
        padding: 8px 16px;
        border: 1px solid #d1d5db;
        background: white;
        color: #6b7280;
        border-radius: 20px;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.2s ease;
        white-space: nowrap;
    }

    .filter-tab:hover {
        border-color: #1e4e64;
        color: #1e4e64;
    }

    .filter-tab.active {
        background: #1e4e64;
        color: white;
        border-color: #1e4e64;
    }

    .filter-badge {
        position: absolute;
        top: -2px;
        right: -2px;
        background: #dc2626;
        color: white;
        border-radius: 50%;
        width: 16px;
        height: 16px;
        font-size: 10px;
        font-weight: 600;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid white;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.12);
    }

    .filter-badge:empty {
        display: none;
    }

    .filter-tab {
        position: relative;
    }

    .notifications-container {
        padding: 16px 0;
    }

    .notification-item {
        display: flex;
        align-items: flex-start;
        gap: 16px;
        padding: 16px 24px;
        border-bottom: 1px solid #f3f4f6;
        transition: background-color 0.2s ease;
        position: relative;
    }

    .notification-item:hover {
        background: #f8f9fa;
    }

    .notification-item:last-child {
        border-bottom: none;
    }

    .notification-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .notification-icon.engineering {
        background: #e7f3ff;
        color: #0066cc;
    }

    .notification-icon.pharmacy {
        background: #f0fff4;
        color: #16a34a;
    }

    .notification-icon.management {
        background: #fef7e7;
        color: #d97706;
    }

    .notification-icon.computer {
        background: #f3e8ff;
        color: #7c3aed;
    }

    .notification-icon.hotel {
        background: #fef2f2;
        color: #dc2626;
    }

    .notification-icon.dsw {
        background: #e0f2fe;
        color: #0277bd;
    }

    .notification-icon.rise {
        background: #fff8e1;
        color: #f57c00;
    }

    .notification-content {
        flex: 1;
    }

    .notification-content h4 {
        margin: 0 0 8px 0;
        font-size: 16px;
        font-weight: 600;
        color: #1f2937;
    }

    .notification-content p {
        margin: 0 0 8px 0;
        font-size: 14px;
        color: #6b7280;
        line-height: 1.5;
    }

    .notification-time {
        font-size: 12px;
        color: #9ca3af;
    }

    .notification-status {
        position: absolute;
        top: 16px;
        right: 16px;
        width: 8px;
        height: 8px;
        border-radius: 50%;
        flex-shrink: 0;
    }

    .notification-status.unread {
        background: #dc3545;
    }

    .notification-status.read {
        background: #e5e7eb;
    }

    /* Responsive Design for Notification Modal */
    @media (max-width: 768px) {
        .notification-modal-content {
            width: 95%;
            margin: 2% auto;
            max-height: 90vh;
        }

        .notification-modal-header {
            padding: 16px 20px;
        }

        .notification-filters {
            padding: 16px 20px 0;
        }

        .filter-tabs {
            gap: 6px;
        }

        .filter-tab {
            padding: 6px 12px;
            font-size: 13px;
        }

        .notification-item {
            padding: 12px 20px;
            gap: 12px;
        }

        .notification-icon {
            width: 36px;
            height: 36px;
        }

        .notification-content h4 {
            font-size: 15px;
        }

        .notification-content p {
            font-size: 13px;
        }
    }

    @media (max-width: 480px) {
        .notification-modal-content {
            width: 98%;
            margin: 1% auto;
        }

        .filter-tabs {
            justify-content: flex-start;
        }

        .filter-tab {
            padding: 5px 10px;
            font-size: 12px;
        }
    }

    .notice-container {
        max-width: 500px;
        margin: auto;
        border: 1px solid #ddd;
        background: #fff;
    }

    .notice-title {
        text-align: center;
        font-weight: bold;
        padding: 10px;
        font-size: 18px;
    }

    .notice-box {
        height: 300px;
        overflow: hidden;
        position: relative;
        padding: 10px;
    }

    /* Animation */
    .notice-vertical {
        list-style: none;
        padding: 0;
        margin: 0;
        position: absolute;
        width: 100%;
        animation: scrollUp 20s linear infinite;
    }

    /* Pause when hovered */
    .notice-box:hover .notice-vertical {
        animation-play-state: paused;
    }

    .notice-vertical li {
        padding: 15px 0 5px 0;
        border-bottom: 1px solid rgb(219, 219, 219);
    }

    .notice-vertical a {

        color: #007bff;
    }

    @keyframes scrollUp {
        from {
            top: 100%;
        }

        to {
            top: -200%;
        }
    }

    .course_profile_card {
        padding: 30px 20px;
        border: 1px solid rgb(235, 235, 235);
        border-radius: 5px;
        background: white;
    }

    .course_profile_card {
        transition: all 0.3s ease;
    }

    .course_profile_card:hover {

        box-shadow: 0 5px 30px rgba(0, 0, 0, 0.15);
    }
    </style>
</head>

<body>

    <?php include 'includes/header.php'; ?>

    <!--  -->
    <style>
    /* New Professional Banner Styles - Premium Dark Theme */
    .pro-banner-section {
        position: relative;
        background-image: url('assets/img/bg/bradcram.jpg');
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
        padding: 200px 0 100px;
        /* Increased top padding to clear header */
        overflow: hidden;
    }

    .pro-banner-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        /* Deep Corporate Navy Gradient - Professional & Trustworthy */
        background: linear-gradient(105deg, rgba(0, 0, 27, 0.96) 0%, rgba(28, 86, 225, 0.8) 100%);
        z-index: 1;
    }

    .pro-banner-content {
        position: relative;
        z-index: 2;
    }

    .pro-banner-title {
        font-size: 3.5rem;
        font-weight: 800;
        color: #fff;
        margin-bottom: 1.2rem;
        line-height: 1.1;
        letter-spacing: -0.5px;
    }

    /* Gold Accent/Highlight Text */
    .pro-highlight-text {
        color: #ffd700;
        /* Gold */
        position: relative;
        display: inline-block;
    }

    .pro-breadcrumb {
        background: rgba(255, 255, 255, 0.08);
        display: inline-flex;
        padding: 8px 20px;
        border-radius: 4px;
        margin-bottom: 2rem;
        border-left: 3px solid #ffd700;
        /* Gold Accent */
    }

    .pro-breadcrumb-item {
        color: rgba(255, 255, 255, 0.9);
        font-size: 0.9rem;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 1px;
        text-decoration: none;
        transition: color 0.3s;
    }

    .pro-breadcrumb-item:hover,
    .pro-breadcrumb-item.active {
        color: #fff;
    }

    .pro-breadcrumb-separator {
        color: rgba(255, 255, 255, 0.4);
        margin: 0 12px;
        font-size: 0.8rem;
    }

    .pro-tagline {
        color: rgba(255, 255, 255, 0.85);
        font-size: 1.15rem;
        max-width: 550px;
        line-height: 1.7;
        margin-bottom: 2.5rem;
        font-weight: 400;
    }

    /* Clean Solid Form Styles - Maximum Professionalism */
    .glass-form-card {
        background: #ffffff;
        /* Solid White */
        border: none;
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.3);
        border-radius: 12px;
        /* Slightly sharper corners */
        padding: 24px 24px;
        /* Reduced padding */
        position: relative;
    }

    /* Decorative top line for form */
    /* .glass-form-card::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, #1C56E1, #ffd700);
            border-radius: 12px 12px 0 0;
        } */

    .glass-form-header {
        text-align: center;
        margin-bottom: 20px;
        /* Reduced margin */
    }

    .glass-form-title {
        color: #00001b;
        /* Deep Navy */
        font-size: 1.4rem;
        /* Slightly reduced font size */
        font-weight: 800;
        margin-bottom: 4px;
    }

    .glass-form-subtitle {
        color: #666;
        font-size: 0.85rem;
        line-height: 1.3;
    }

    .pro-input-group {
        position: relative;
        margin-bottom: 12px;
        /* Reduced margin */
    }

    .pro-input {
        width: 100%;
        padding: 10px 14px;
        /* Reduced padding */
        background: #f8f9fa;
        border: 1px solid #e1e4e8;
        border-radius: 6px;
        font-size: 14px;
        /* Reduced font size */
        color: #333;
        transition: all 0.2s ease;
    }

    .pro-input:focus {
        background: #fff;
        border-color: #1C56E1;
        box-shadow: 0 0 0 4px rgba(28, 86, 225, 0.1);
        outline: none;
    }

    .pro-input::placeholder {
        color: #adb5bd;
    }

    .pro-submit-btn {
        width: 100%;
        padding: 12px;
        /* Reduced padding */
        background: #1C56E1;
        /* Solid Brand Color */
        color: white;
        border: none;
        border-radius: 6px;
        font-size: 15px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(28, 86, 225, 0.25);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }

    .pro-submit-btn:hover {
        transform: translateY(-2px);
        background: #00001b;
        /* Dark on hover */
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
    }

    .pro-feature-list {
        margin-top: 25px;
        display: flex;
        gap: 20px;
        justify-content: center;
        border-top: 1px solid #eee;
        padding-top: 20px;
    }

    .pro-feature-item {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 0.85rem;
        color: #555;
        font-weight: 600;
    }

    .pro-feature-item i {
        color: #2ebf68;
        /* Success Green */
    }

    @media (max-width: 991px) {
        .pro-banner-section {
            padding: 60px 0;
            text-align: center;
        }

        .pro-breadcrumb {
            justify-content: center;
        }

        .pro-tagline {
            margin: 0 auto 30px;
        }

        .glass-form-card {
            margin-top: 30px;
        }
    }
    </style>

    <section class="pro-banner-section">
        <div class="pro-banner-overlay"></div>
        <div class="container pro-banner-content">
            <div class="row align-items-center">
                <!-- Left Side Content -->
                <div class="col-lg-7 mb-5 mb-lg-0 wow fadeInUp" data-wow-delay="0.2s">
                    <div class="pro-breadcrumb">
                        <a href="./" class="pro-breadcrumb-item">Home</a>
                        <span class="pro-breadcrumb-separator"><i class="fa-solid fa-chevron-right"></i></span>
                        <span class="pro-breadcrumb-item active">MCA Entrance Details</span>
                    </div>

                    <h1 class="pro-banner-title">Master Your MCA Entrance Journey</h1>
                    <p class="pro-tagline">
                        Join the league of toppers with India's most trusted coaching institute.
                        Expert guidance, comprehensive study material, and proven results since 1999.
                    </p>

                    <div class="d-flex gap-3 flex-wrap justify-content-center justify-content-lg-start">
                        <div class="d-flex align-items-center text-white gap-2">
                            <i class="fa-solid fa-check-circle" style="color: #ffd700;"></i> <span>Expert Faculty</span>
                        </div>
                        <div class="d-flex align-items-center text-white gap-2 ml-3">
                            <i class="fa-solid fa-check-circle" style="color: #ffd700;"></i> <span>Proven Results</span>
                        </div>
                        <div class="d-flex align-items-center text-white gap-2 ml-3">
                            <i class="fa-solid fa-check-circle" style="color: #ffd700;"></i> <span>24/7 Support</span>
                        </div>
                    </div>
                </div>

                <!-- Right Side Form -->
                <div class="col-lg-5 col-xl-4 ms-auto wow fadeInRight" data-wow-delay="0.4s">
                    <div class="glass-form-card">
                        <div class="glass-form-header">
                            <h3 class="glass-form-title">Book Free Consultation</h3>
                            <p class="glass-form-subtitle">Get expert guidance for your career path</p>
                        </div>

                        <form action="#" method="POST" id="proHeroForm" class="unified-contact-form">
                            <div class="pro-input-group">
                                <input type="text" name="student_name" class="pro-input" placeholder="Your Name"
                                    required>
                            </div>

                            <div class="pro-input-group">
                                <input type="email" name="student_email" class="pro-input" placeholder="Email Address"
                                    required>
                            </div>

                            <div class="pro-input-group d-flex gap-2">
                                <select class="pro-input" style="width: 80px;" disabled>
                                    <option>+91</option>
                                </select>
                                <input type="tel" name="student_mobile" class="pro-input" placeholder="Mobile Number"
                                    required>
                            </div>

                            <!-- Course Interest (Hidden for MCA Page) -->
                            <input type="hidden" name="course_interest" value="MCA Entrance">

                            <div class="pro-input-group">
                                <textarea name="enquiry" class="pro-input" placeholder="Type your message" rows="3"
                                    style="resize: none;"></textarea>
                            </div>

                            <button type="submit" class="pro-submit-btn">
                                <span>Get Started Now</span>
                                <i class="fa-solid fa-arrow-right"></i>
                            </button>
                        </form>


                        <div id="hero-form-message-pro" style="margin-top: 1rem; text-align: center; display: none;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- MCA Details Section -->
    <section>
        <!-- <div class="td_height_120 td_height_lg_80"></div> -->
        <div class="container" style="padding: 80px 0;">
            <div class="row td_gap_y_50">
                <div class="col-lg-8">
                    <div class="td_card td_style_1 td_type_3">
                        <div class="td_card_info">
                            <div class="td_card_info_in">
                                <?php
                                // Fetch dynamic content
                                $entrance_info = [];
                                $mca_syllabus = [];
                                try {
                                    $stmt = $pdo->prepare("SELECT * FROM mca_entrance_content WHERE content_key = ?");

                                    // Fetch Entrance Info
                                    $stmt->execute(['entrance_info']);
                                    $entrance_info = $stmt->fetch(PDO::FETCH_ASSOC);

                                    // Fetch Syllabus
                                    $stmt->execute(['mca_syllabus']);
                                    $mca_syllabus = $stmt->fetch(PDO::FETCH_ASSOC);

                                } catch (PDOException $e) {
                                    // Handle error silently or log
                                }
                                ?>

                                <!-- Dynamic Content: Entrance Information -->
                                <h3 class="td_fs_32 td_mb_20">
                                    <?php echo !empty($entrance_info['title']) ? htmlspecialchars($entrance_info['title']) : 'ENTRANCE INFORMATION'; ?>
                                </h3>
                                <div class="td_mb_30 td_fs_18">
                                    <?php
                                    if (!empty($entrance_info['content_value'])) {
                                        echo $entrance_info['content_value']; // Output raw HTML from editor
                                    } else {
                                        // Fallback static content
                                        echo "MCA is an AICTE-approved postgraduate course offered by most Indian universities. Admission via entrance test requires a Bachelor's degree (50-60% marks; final-year students eligible). Some need graduation-level mathematics (e.g., Delhi University, University of Hyderabad). Test covers Mathematics, Logical/Analytical Reasoning; some include English and Computer Awareness. Limited seats mean only top ranks get admission. To prepare students for top MCA seats, INFOMATHS INDIA—a pioneer with over 80% success rate in premier entrances—offers MEGALEAP, a focused preparatory course for MCA entrance tests at leading Indian institutions.";
                                    }
                                    ?>
                                </div>

                                <!-- Dynamic Content: MCA Syllabus -->
                                <h3 class="td_fs_20 td_mb_20">
                                    <?php echo !empty($mca_syllabus['title']) ? htmlspecialchars($mca_syllabus['title']) : 'MCA Syllabus'; ?>
                                </h3>
                                <div class="td_mb_30 td_fs_18">
                                    <?php
                                    if (!empty($mca_syllabus['content_value'])) {
                                        echo $mca_syllabus['content_value']; // Output raw HTML from editor
                                    } else {
                                        // Fallback static content
                                        echo "Most MCA entrance exams in India (such as NIMCET, CUET PG, MAH MCA CET, BHU PET/CUET, JNU, PU MCA, VIT MCA, and others) focus on testing foundational skills in Mathematics (usually at 10+2 and basic undergraduate level), Logical/Analytical Reasoning, Computer Awareness, and sometimes General English. The syllabus varies slightly by exam, but the core topics overlap significantly, making preparation efficient across multiple tests.";
                                    }
                                    ?>
                                </div>

                                <!-- Explore Top Exams Section (Card Layout) -->
                                <div class="exams-section mb-5">
                                    <h3 class="mb-5"
                                        style="color: #000033; font-weight: 700; font-size: 24px; text-transform: uppercase; letter-spacing: 1px;">
                                        Explore Syllabus of Top MCA Entrance Exams
                                    </h3>
                                    <style>
                                    .exam-card {
                                        background: #fff;
                                        border: 1px solid #eee;
                                        border-radius: 12px;
                                        padding: 30px 20px;
                                        text-align: center;
                                        transition: all 0.3s ease;
                                        height: 100%;
                                        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.03);
                                        display: flex;
                                        flex-direction: column;
                                        align-items: center;
                                        justify-content: space-between;
                                    }

                                    .exam-card:hover {
                                        transform: translateY(-5px);
                                        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
                                    }

                                    .exam-icon-wrapper {
                                        width: 80px;
                                        height: 80px;
                                        background: #f8f9fa;
                                        border-radius: 50%;
                                        display: flex;
                                        align-items: center;
                                        justify-content: center;
                                        margin: 0 auto 20px;
                                        border: 1px solid #e9ecef;
                                    }

                                    .exam-icon {
                                        font-size: 32px;
                                        color: #000033;
                                    }

                                    .exam-title {
                                        font-weight: 800;
                                        font-size: 16px;
                                        /* Adjusted slightly for fit */
                                        margin-bottom: 25px;
                                        color: #000033;
                                        text-transform: uppercase;
                                        letter-spacing: 0.5px;
                                    }

                                    .exam-btn {
                                        background-color: #1C56E1;
                                        /* Brand Color */
                                        color: #fff !important;
                                        padding: 10px 25px;
                                        border-radius: 6px;
                                        text-decoration: none;
                                        font-weight: 600;
                                        font-size: 14px;
                                        transition: background 0.3s;
                                        display: inline-block;
                                    }

                                    .exam-btn:hover {
                                        background-color: #0b3cb3;
                                        color: #fff;
                                    }

                                    .exam-btn i {
                                        margin-left: 5px;
                                    }
                                    </style>

                                    <div class="row g-4">
                                        <?php if (!empty($entranceExams)): ?>
                                        <?php foreach ($entranceExams as $exam): ?>
                                        <div class="col-md-6 col-lg-6 mb-4">
                                            <div class="exam-card">
                                                <div class="exam-icon-wrapper">
                                                    <?php if (!empty($exam['icon_image'])): ?>
                                                    <img src="<?php echo htmlspecialchars($exam['icon_image']); ?>"
                                                        alt="<?php echo htmlspecialchars($exam['exam_name']); ?>"
                                                        class="exam-icon"
                                                        style="object-fit: contain; width: 60%; height: 60%;">
                                                    <?php else: ?>
                                                    <i class="fa-solid fa-layer-group exam-icon"></i>
                                                    <?php endif; ?>
                                                </div>
                                                <h4 class="exam-title">
                                                    <?php echo htmlspecialchars($exam['exam_name']); ?></h4>
                                                <a href="exam-details.php?exam=<?php echo htmlspecialchars($exam['slug']); ?>"
                                                    class="exam-btn">Explore <i
                                                        class="fa-solid fa-arrow-right-long"></i></a>
                                            </div>
                                        </div>
                                        <?php endforeach; ?>
                                        <?php else: ?>
                                        <!-- Fallback if no exams found -->
                                        <div class="col-12 text-center">
                                            <p>No entrance exams found.</p>
                                        </div>
                                        <?php endif; ?>
                                    </div>

                                </div>

                                <h3 class="td_fs_20 td_mb_20">COURSE PROFILE</h3>
                                <p class="td_mb_30 td_fs_18">
                                    Any Student desirous of doing MCA from a good
                                    University/Institute is eligible to apply for this course.
                                    Different Eligibility Criterions are to be met for different
                                    courses at infomaths i.e.
                                </p>
                                <style>
                                .course_profile_card {
                                    background: #fff;
                                    border: 1px solid #e0e0e0;
                                    border-radius: 8px;
                                    padding: 15px 20px;
                                    color: #333;
                                    font-size: 15px;
                                    font-weight: 500;
                                    text-decoration: none;
                                    transition: all 0.3s ease;
                                    display: flex;
                                    align-items: center;
                                    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.02);
                                    height: 100%;
                                    position: relative;
                                    z-index: 1;
                                }

                                .course_profile_card:hover {
                                    border-color: #1C56E1;
                                    background: #1C56E1 !important;
                                    /* Force override all background properties */
                                    transform: translateY(-3px);
                                    box-shadow: 0 5px 15px rgba(28, 86, 225, 0.25);
                                    color: #fff !important;
                                }

                                .course_profile_card:hover::before,
                                .course_profile_card:hover::after {
                                    background: none !important;
                                    background-image: none !important;
                                    opacity: 0 !important;
                                    content: none !important;
                                }

                                .course_profile_card span {
                                    line-height: 1.4;
                                    position: relative;
                                    z-index: 2;
                                }

                                .course_profile_card strong {
                                    color: #1C56E1;
                                    display: block;
                                    font-size: 16px;
                                    margin-bottom: 4px;
                                    transition: color 0.3s;
                                }

                                .course_profile_card:hover strong {
                                    color: #fff !important;
                                    /* Ensure text is visible on dark background */
                                }
                                </style>
                                <div class="td_mb_40 py-4"
                                    style="display: grid; grid-template-columns:repeat(2, 1fr); gap:15px;">
                                    <?php
                                    // Fetch Course Profiles from Database
                                    try {
                                        // UDPATED: Show only 'Profile' courses (not Dropdown/Header courses)
                                        $stmt = $pdo->prepare("SELECT * FROM course_profiles WHERE is_active = 1 AND show_in_dropdown = 0 ORDER BY display_order ASC");
                                        $stmt->execute();
                                        $profiles = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                        if (count($profiles) > 0) {
                                            foreach ($profiles as $profile) {
                                                ?>
                                    <?php
                                                $details_url = '#';
                                                // Use the Clean URL from the database 'link' column if available (e.g. 'course/adwintage')
                                                if (!empty($profile['link']) && $profile['link'] != '#') {
                                                    $details_url = htmlspecialchars($profile['link']);
                                                } elseif (!empty($profile['slug'])) {
                                                    // Fallback to constructing clean URL if link is missing but slug exists
                                                    $details_url = 'course/' . urlencode($profile['slug']);
                                                }
                                                ?>
                                    <a href="<?php echo $details_url; ?>" class="course_profile_card">
                                        <span><strong><?php echo htmlspecialchars($profile['title']); ?></strong>
                                            <?php echo htmlspecialchars($profile['subtitle']); ?></span>
                                    </a>
                                    <?php
                                            }
                                        } else {
                                            // Fallback/Default if no profiles found
                                            ?>
                                    <a href="#" class="course_profile_card">
                                        <span><strong>adWINtage</strong> 1 YR INTEGRATED REGULAR BATCH</span>
                                    </a>
                                    <?php
                                        }
                                    } catch (PDOException $e) {
                                        // Fallback on error
                                        echo '<p class="text-danger">Error loading profiles.</p>';
                                    }
                                    ?>
                                </div>
                                <p class="td_mb_30 td_fs_18">
                                    Course durations vary. The curriculum includes regular classroom sessions, periodic
                                    tests, and follow-up discussions. INFOMATHS INDIA stands out by providing suitable
                                    assignment problems for home practice, delivered alongside expert lectures.
                                    Additionally, frequent tests and quick discussions allow close student-teacher
                                    interaction. This dual benefit strengthens students' ability to handle tricky
                                    questions while boosting confidence. Monthly tests continue even after the course
                                    ends to keep students engaged with the topics. Overall, faculty members provide
                                    personalized academic care to every MCA entrance aspirant.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">

                    <div class="td_card td_style_6 td_white_bg td_radius_10" style="padding: 40px 30px;">
                        <button type="button"
                            onclick="var myModal = new bootstrap.Modal(document.getElementById('scholarshipModal')); myModal.show();"
                            class="open-scholarship-modal td_fs_20 td_semibold td_mb_10"
                            style="background:none; border:none; padding:0; color: #1C56E1; text-decoration: underline; cursor: pointer; text-align: left;">
                            Apply Scholarship for MCA Entrance Exam
                        </button>
                        <h3 class="td_fs_20 td_semibold td_mb_10 mt-3">
                            Infomaths Notice <span class="news-icon"><i class="fas fa-newspaper"></i></span>
                        </h3>
                        <div class="notice-box">
                            <ul class="notice-vertical">
                                <?php
                                $notices = [];
                                try {
                                    $stmt = $pdo->query("SELECT * FROM notices WHERE is_active = 1 ORDER BY display_order ASC, created_at DESC");
                                    $notices = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                } catch (Exception $e) { /* Ignore */
                                }

                                if (!empty($notices)):
                                    foreach ($notices as $notice):
                                        ?>
                                <li>
                                    <a href="<?php echo htmlspecialchars($notice['link']); ?>">
                                        <?php echo htmlspecialchars($notice['content']); ?>
                                    </a>
                                </li>
                                <?php
                                    endforeach;
                                else:
                                    ?>
                                <li><a href="#">No new notices available.</a></li>
                                <?php endif; ?>
                            </ul>
                        </div>

                    </div>
                    <div class="td_card td_style_6 td_white_bg td_radius_10"
                        style="padding: 40px 30px; margin-top:30px;">
                        <h3 class="td_fs_20 td_semibold td_mb_10">
                            Our Gallery <span class="news-icon">📰</span>
                        </h3>
                        <div class="" style="display: flex; flex-wrap: wrap; gap:10px">
                            <div class="td_slider td_style_1" style="width: 100%;">
                                <div class="td_slider_container" style="width: 100%;" data-autoplay="1" data-loop="1"
                                    data-speed="800" data-center="0" data-variable-width="0" data-slides-per-view="1">
                                    <div class="td_slider_wrapper">
                                        <?php
                                        // Fetch PU Results images
                                        try {
                                            $stmt = $pdo->prepare("SELECT * FROM section_images WHERE section_name = ? ORDER BY display_order");
                                            $stmt->execute(['pu_results']);
                                            $pu_images = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                            if (!empty($pu_images)) {
                                                foreach ($pu_images as $image) {
                                                    echo '<div class="td_slide">
                                                        <div class="td_radius_10">
                                                            <img src="' . htmlspecialchars($image['image_path']) . '" alt="' . htmlspecialchars($image['alt_text']) . '" class="td_radius_10 w-100" style="height: 380px; " />
                                                        </div>
                                                    </div>';
                                                }
                                            } else {
                                                // Fallback images
                                                echo '<div class="td_slide">
                                                    <div class="td_radius_10">
                                                        <img src="assets/img/why-choose-new1.jpg" alt="" class="td_radius_10 w-100" style="height: 380px;" />
                                                    </div>
                                                </div>';
                                            }
                                        } catch (PDOException $e) {
                                            echo '<div class="td_slide">
                                                <div class="td_radius_10">
                                                    <img src="assets/img/why-choose-new1.jpg" alt="" class="td_radius_10 w-100" />
                                                </div>
                                            </div>';
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <a href="mca-result.php" class="td_btn td_style_1 td_radius_10 td_medium mt-3"
                                style="margin:auto">
                                <span class="td_btn_in td_white_color td_accent_bg">
                                    <span>See Gallery</span>
                                    <svg width="19" height="20" viewBox="0 0 19 20" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path d="M15.1575 4.34302L3.84375 15.6567" stroke="currentColor"
                                            stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                        <path
                                            d="M15.157 11.4142C15.157 11.4142 16.0887 5.2748 15.157 4.34311C14.2253 3.41142 8.08594 4.34314 8.08594 4.34314"
                                            stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round"></path>
                                    </svg>
                                </span>
                            </a>
                        </div>
                    </div>
                    <!-- Megaleap Box (Redesigned & Positioned Top) -->
                    <div class="megaleap-box-dark mb-4 mt-4"
                        style="background: #1C56E1; border-radius: 12px; padding: 30px; color: #fff; position: relative; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,51,0.2);">
                        <!-- Decorative Circle -->
                        <div
                            style="position: absolute; top: -20px; right: -20px; width: 100px; height: 100px; background: rgba(255, 255, 255, 0.1); border-radius: 50%;">
                        </div>

                        <div style="position: relative; z-index: 2;">
                            <h4 style="color: #fff; font-weight: 700; margin-bottom: 15px; font-size: 20px;">
                                <i class="fa-solid fa-trophy me-2"></i> Targeting Top Ranks?
                            </h4>
                            <p class="mb-4" style="color: rgba(255,255,255,0.9); font-size: 15px; line-height: 1.6;">
                                Join <strong>INFOMATHS' MEGALEAP</strong> — the premier course designed for MCA
                                aspirants aiming for top institutions.
                                <br><br>
                                <span
                                    style="display:block; border-left: 3px solid #fff; padding-left: 10px; font-style: italic; color: rgba(255,255,255,0.7);">
                                    Expert guidance, mock tests, and 24/7 personalized support.
                                </span>
                            </p>
                            <a href="#" class="open-contact-modal"
                                style="background: #fff; color: #1C56E1; padding: 10px 20px; border-radius: 6px; font-weight: 700; text-decoration: none; display: inline-block; transition: all 0.3s;"
                                onmouseover="this.style.background='#f0f0f0'" onmouseout="this.style.background='#fff'">
                                Join Infomaths <i class="fa-solid fa-arrow-right ms-2"></i>
                            </a>
                        </div>
                    </div>
                </div>


            </div>
        </div>
        </div>
    </section>

    <!-- Expert Members Section -->
    <section class="td_shape_section_1 td_gray_bg_3 pb-0">
        <div class="td_height_70 td_height_lg_50"></div>
        <div class="container">
            <div class="td_section_heading td_style_1 text-center wow fadeInUp" data-wow-duration="1s"
                data-wow-delay="0.2s">
                <p
                    class="td_section_subtitle_up td_fs_18 td_semibold td_spacing_1 td_mb_10 text-uppercase td_accent_color">
                    <i></i> The Team Behind Your Success <i></i>
                </p>
                <h2 class="td_section_title td_fs_48 mb-0">Meet Our Expert Members</h2>
            </div>

            <div class="td_height_50 td_height_lg_30"></div>

            <div class="row wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.3s">
                <?php
                // Fetch Faculty from Database
                try {
                    $stmt = $pdo->query("SELECT * FROM faculty ORDER BY uploaded_at DESC");
                    $faculty_members = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    if (!empty($faculty_members)) {
                        foreach ($faculty_members as $member) {
                            ?>
                <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                    <div class="td_team_member text-center"
                        style="background: #fff; padding: 25px; border-radius: 12px; box-shadow: 0 5px 20px rgba(0,0,0,0.05); transition: transform 0.3s ease;">
                        <div class="td_member_img mb-3"
                            style="position: relative; width: 120px; height: 120px; margin: 0 auto; overflow: hidden; border-radius: 50%; border: 3px solid #1C56E1;">
                            <img src="assets/faculty/<?php echo htmlspecialchars($member['image_path']); ?>"
                                alt="<?php echo htmlspecialchars($member['name']); ?>"
                                style="width: 100%; height: 100%; object-fit: cover;">
                        </div>
                        <h4 class="td_member_name td_fs_20 td_mb_5" style="color: #1C56E1; font-weight: 700;">
                            <?php echo htmlspecialchars($member['name']); ?></h4>
                        <p class="td_member_designation td_fs_14 td_mb_0" style="color: #666;">
                            <?php echo htmlspecialchars(substr($member['description'], 0, 50)); ?></p>
                    </div>
                </div>
                <?php
                        }
                    } else {
                        // Fallback
                        echo '<div class="col-12 text-center"><p>Faculty members coming soon.</p></div>';
                    }
                } catch (PDOException $e) {
                    echo '<div class="col-12 text-center"><p class="text-danger">Error loading members.</p></div>';
                }
                ?>
            </div>
            <div class="td_height_50 td_height_lg_50"></div>
        </div>
    </section>
    <!-- Start Event List Section -->
    <section>
        <div class="container" style="padding: 80px 0;">
            <div class="row td_gap_y_30">
                <h3 class="td_section_title td_fs_40 mb-4" style="text-align: center;">University Wise Results/Previous
                    year papers</h3>
                <div class="col-lg-12 img_grid"
                    style="text-align: center; display: grid; grid-template-columns: repeat(3, 1fr); gap:40px">
                    <?php
                    // Fetch University Categories
                    try {
                        $stmt = $pdo->query("SELECT * FROM university_categories WHERE is_active = 1 ORDER BY display_order ASC");
                        $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        if (!empty($categories)) {
                            foreach ($categories as $cat) {
                                ?>
                    <div style="border-radius:20px; transition: transform 0.3s ease;">
                        <a href="papers/<?php echo htmlspecialchars($cat['slug']); ?>"
                            style="display: block; text-decoration: none;">
                            <div
                                style="height: 250px; overflow: hidden; border-radius: 12px; margin-bottom: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
                                <img src="assets/img/im/<?php echo htmlspecialchars($cat['image_path']); ?>"
                                    style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s ease;"
                                    class="hover-zoom" alt="<?php echo htmlspecialchars($cat['name']); ?>">
                            </div>
                            <h4 style="margin-top: 15px; color: #2c3e50; font-weight: 700; font-size: 1.25rem;">
                                <?php echo htmlspecialchars($cat['name']); ?>
                            </h4>
                        </a>
                    </div>
                    <?php
                            }
                        } else {
                            echo '<div class="col-12"><p class="text-center">No categories found.</p></div>';
                        }
                    } catch (PDOException $e) {
                        echo '<div class="col-12"><p class="text-center text-danger">Error loading data.</p></div>';
                    }
                    ?>
                </div>
            </div>
        </div>
        </div>
    </section>
    <!-- Start Event List Section -->


    <!-- Start NIMCET Results Slider Section -->
    <section>
        <div class="container" style="padding: 80px 0;">
            <div class="td_section_heading td_style_1 text-center wow fadeInUp" data-wow-duration="1s"
                data-wow-delay="0.2s">
                <p
                    class="td_section_subtitle_up td_fs_18 td_semibold td_spacing_1 td_mb_10 text-uppercase td_accent_color">

                    <i></i>
                    Top Results by Our Stars
                    <i></i>
                </p>
                <h2 class="td_section_title td_fs_48 mb-0 mt-4">NIMCET Toppers</h2>
            </div>
            <div class="td_height_50 td_height_lg_50"></div>
            <div class="td_slider td_style_1 td_slider_gap_24 td_remove_overflow wow fadeInUp" data-wow-duration="1s"
                data-wow-delay="0.3s">
                <div class="td_slider_container" data-autoplay="1" data-loop="1" data-speed="800" data-center="0"
                    data-variable-width="0" data-slides-per-view="responsive" data-xs-slides="1" data-sm-slides="2"
                    data-md-slides="3" data-lg-slides="4" data-add-slides="4">
                    <div class="td_slider_wrapper">
                        <?php
                        // Fetch NIMCET toppers from database
                        try {
                            $stmt = $pdo->prepare("SELECT * FROM toppers WHERE category = 'NIMCET' ORDER BY uploaded_at DESC");
                            $stmt->execute();
                            $nimcetToppers = $stmt->fetchAll(PDO::FETCH_ASSOC);

                            if (!empty($nimcetToppers)) {
                                foreach ($nimcetToppers as $topper) {
                                    echo '<div class="td_slide">';
                                    echo '<img src="assets/toppers/' . htmlspecialchars($topper['image_path']) . '" alt="' . htmlspecialchars($topper['name']) . '" style="width:250px;height:380px;object-fit:cover;border-radius:10px;margin:0 auto;display:block;">';
                                    echo '</div>';
                                }
                            } else {
                                // Fallback to default images if no toppers found
                                for ($i = 1; $i <= 6; $i++) {
                                    echo '<div class="td_slide">';
                                    echo '<img src="assets/img/inps-logo.png" alt="Topper ' . $i . '" style="width:250px;height:380px;object-fit:cover;border-radius:10px;margin:0 auto;display:block;">';
                                    echo '</div>';
                                }
                            }
                        } catch (PDOException $e) {
                            // Fallback to default images if database error
                            for ($i = 1; $i <= 6; $i++) {
                                echo '<div class="td_slide">';
                                echo '<img src="assets/img/inps-logo.png" alt="Topper ' . $i . '" style="width:250px;height:380px;object-fit:cover;border-radius:10px;margin:0 auto;display:block;">';
                                echo '</div>';
                            }
                        }
                        ?>
                    </div>
                </div>
                <div class="td_height_50 td_height_lg_40"></div>
                <div class="td_pagination td_style_1"></div>
            </div>

        </div>

    </section>

    <!-- MAHCET Toppers Section -->
    <section class="td_gray_bg_3">
        <div class="container" style="padding: 80px 0;">
            <div class="td_section_heading td_style_1 text-center wow fadeInUp" data-wow-duration="1s"
                data-wow-delay="0.2s">
                <h2 class="td_section_title td_fs_48 mb-0">MAHCET Toppers</h2>
            </div>
            <div class="td_height_50 td_height_lg_50"></div>
            <div class="td_slider td_style_1 td_slider_gap_24 wow fadeInUp" data-wow-duration="1s"
                data-wow-delay="0.3s">
                <div class="td_slider_container" data-autoplay="1" data-loop="1" data-speed="800" data-center="0"
                    data-variable-width="0" data-slides-per-view="responsive" data-xs-slides="1" data-sm-slides="2"
                    data-md-slides="3" data-lg-slides="4" data-add-slides="4">
                    <div class="td_slider_wrapper">
                        <?php
                        try {
                            $stmt = $pdo->prepare("SELECT * FROM toppers WHERE category = 'MAHCET' ORDER BY uploaded_at DESC");
                            $stmt->execute();
                            $toppers = $stmt->fetchAll(PDO::FETCH_ASSOC);

                            if (count($toppers) > 0) {
                                foreach ($toppers as $topper) {
                                    echo '<div class="td_slide">';
                                    echo '<img src="assets/toppers/' . htmlspecialchars($topper['image_path']) . '" alt="' . htmlspecialchars($topper['name']) . '" style="width:250px;height:380px;object-fit:cover;border-radius:10px;margin:0 auto;display:block;">';
                                    echo '</div>';
                                }
                            } else {
                                // Fallback to default images if no toppers found
                                for ($i = 1; $i <= 6; $i++) {
                                    echo '<div class="td_slide">';
                                    echo '<img src="assets/img/inps-logo.png" alt="Topper ' . $i . '" style="width:250px;height:380px;object-fit:cover;border-radius:10px;margin:0 auto;display:block;">';
                                    echo '</div>';
                                }
                            }
                        } catch (PDOException $e) {
                            // Fallback to default images if database error
                            for ($i = 1; $i <= 6; $i++) {
                                echo '<div class="td_slide">';
                                echo '<img src="assets/img/inps-logo.png" alt="Topper ' . $i . '" style="width:250px;height:380px;object-fit:cover;border-radius:10px;margin:0 auto;display:block;">';
                                echo '</div>';
                            }
                        }
                        ?>
                    </div>
                </div>
                <div class="td_height_50 td_height_lg_40"></div>
                <div class="td_pagination td_style_1"></div>
            </div>

        </div>

    </section>

    <!-- VIT Toppers Section -->
    <section>
        <div class="container" style="padding: 80px 0;">
            <div class="td_section_heading td_style_1 text-center wow fadeInUp" data-wow-duration="1s"
                data-wow-delay="0.2s">

                <h2 class="td_section_title td_fs_48 mb-0">VIT Toppers</h2>
            </div>
            <div class="td_height_50 td_height_lg_50"></div>
            <div class="td_slider td_style_1 td_slider_gap_24 wow fadeInUp" data-wow-duration="1s"
                data-wow-delay="0.3s">
                <div class="td_slider_container" data-autoplay="1" data-loop="1" data-speed="800" data-center="0"
                    data-variable-width="0" data-slides-per-view="responsive" data-xs-slides="1" data-sm-slides="2"
                    data-md-slides="3" data-lg-slides="4" data-add-slides="4">
                    <div class="td_slider_wrapper">
                        <?php
                        try {
                            $stmt = $pdo->prepare("SELECT * FROM toppers WHERE category = 'VIT' ORDER BY uploaded_at DESC");
                            $stmt->execute();
                            $toppers = $stmt->fetchAll(PDO::FETCH_ASSOC);

                            if (count($toppers) > 0) {
                                foreach ($toppers as $topper) {
                                    echo '<div class="td_slide">';
                                    echo '<img src="assets/toppers/' . htmlspecialchars($topper['image_path']) . '" alt="' . htmlspecialchars($topper['name']) . '" style="width:250px;height:380px;object-fit:cover;border-radius:10px;margin:0 auto;display:block;">';
                                    echo '</div>';
                                }
                            } else {
                                // Fallback to default images if no toppers found
                                for ($i = 1; $i <= 6; $i++) {
                                    echo '<div class="td_slide">';
                                    echo '<img src="assets/img/inps-logo.png" alt="Topper ' . $i . '" style="width:250px;height:380px;object-fit:cover;border-radius:10px;margin:0 auto;display:block;">';
                                    echo '</div>';
                                }
                            }
                        } catch (PDOException $e) {
                            // Fallback to default images if database error
                            for ($i = 1; $i <= 6; $i++) {
                                echo '<div class="td_slide">';
                                echo '<img src="assets/img/inps-logo.png" alt="Topper ' . $i . '" style="width:250px;height:380px;object-fit:cover;border-radius:10px;margin:0 auto;display:block;">';
                                echo '</div>';
                            }
                        }
                        ?>
                    </div>
                </div>
                <div class="td_height_50 td_height_lg_40"></div>
                <div class="td_pagination td_style_1"></div>
            </div>

        </div>

    </section>

    <!-- PU Toppers Section -->
    <section class="td_gray_bg_3">
        <div class="container" style="padding: 80px 0;">
            <div class="td_section_heading td_style_1 text-center wow fadeInUp" data-wow-duration="1s"
                data-wow-delay="0.2s">

                <h2 class="td_section_title td_fs_48 mb-0">PU Toppers</h2>
            </div>
            <div class="td_height_50 td_height_lg_50"></div>
            <div class="td_slider td_style_1 td_slider_gap_24 wow fadeInUp" data-wow-duration="1s"
                data-wow-delay="0.3s">
                <div class="td_slider_container" data-autoplay="1" data-loop="1" data-speed="800" data-center="0"
                    data-variable-width="0" data-slides-per-view="responsive" data-xs-slides="1" data-sm-slides="2"
                    data-md-slides="3" data-lg-slides="4" data-add-slides="4">
                    <div class="td_slider_wrapper">
                        <?php
                        try {
                            $stmt = $pdo->prepare("SELECT * FROM toppers WHERE category = 'PU' ORDER BY uploaded_at DESC");
                            $stmt->execute();
                            $toppers = $stmt->fetchAll(PDO::FETCH_ASSOC);

                            if (count($toppers) > 0) {
                                foreach ($toppers as $topper) {
                                    echo '<div class="td_slide">';
                                    echo '<img src="assets/toppers/' . htmlspecialchars($topper['image_path']) . '" alt="' . htmlspecialchars($topper['name']) . '" style="width:250px;height:380px;object-fit:cover;border-radius:10px;margin:0 auto;display:block;">';
                                    echo '</div>';
                                }
                            } else {
                                // Fallback to default images if no toppers found
                                for ($i = 1; $i <= 6; $i++) {
                                    echo '<div class="td_slide">';
                                    echo '<img src="assets/img/inps-logo.png" alt="Topper ' . $i . '" style="width:250px;height:380px;object-fit:cover;border-radius:10px;margin:0 auto;display:block;">';
                                    echo '</div>';
                                }
                            }
                        } catch (PDOException $e) {
                            // Fallback to default images if database error
                            for ($i = 1; $i <= 6; $i++) {
                                echo '<div class="td_slide">';
                                echo '<img src="assets/img/inps-logo.png" alt="Topper ' . $i . '" style="width:250px;height:380px;object-fit:cover;border-radius:10px;margin:0 auto;display:block;">';
                                echo '</div>';
                            }
                        }
                        ?>
                    </div>
                </div>
                <div class="td_height_50 td_height_lg_40"></div>
                <div class="td_pagination td_style_1"></div>
            </div>

        </div>
        <div class="td_height_50 td_height_lg_60"></div>
    </section>

    <!-- Use real student testimonials and images from the previous section -->
    <section class="td_heading_bg  td_hobble">
        <div class="container" style="padding-top: 80px;">
            <div class="td_section_heading td_style_1 text-center wow fadeInUp" data-wow-duration="1s"
                data-wow-delay="0.2s">
                <p
                    class="td_section_subtitle_up td_fs_18 td_semibold td_spacing_1 td_mb_10 text-uppercase td_accent_color text-white">
                    <i></i>
                    Our Testimonials
                    <i></i>
                </p>
                <h2 class="td_section_title text-white td_fs_48 mb-0">What Our Students Say About Us </h2>
                <p class="td_section_subtitle text-white td_fs_18 mb-0">Hear from our students about their journey and
                    success at Infomaths.</p>
            </div>
            <div class="td_height_50 td_height_lg_50"></div>
            <div class="row d-flex align-items-stretch">
                <div class="col-12 col-md-6">
                    <!-- Written Testimonials Slider -->
                    <div class="td_slider td_style_1 h-100">
                        <div class="td_slider_container" data-autoplay="1" data-loop="1" data-speed="800"
                            data-center="0" data-variable-width="0" data-slides-per-view="1" data-arrows="1"
                            style="min-height: 500px;">
                            <div class="td_slider_wrapper">
                                <?php
                                // Fetch written testimonials from database
                                $stmt = $pdo->query("SELECT * FROM testimonials ORDER BY created_at DESC");
                                $written_testimonials = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                if (count($written_testimonials) > 0) {
                                    foreach ($written_testimonials as $testimonial) {
                                        $content = htmlspecialchars($testimonial['content']);
                                        if (strlen($content) > 260) {
                                            $truncated = substr($content, 0, 260) . '<span style="color: blue; cursor: pointer;" onclick="toggleText(this.closest(\'.expandable\'))">..read more</span>';
                                            $content_display = '<span class="truncated">' . $truncated . '</span><span class="full" style="display:none;">' . $content . '</span>';
                                            $blockquote_class = 'expandable';
                                            $onclick = '';
                                        } else {
                                            $content_display = $content;
                                            $blockquote_class = '';
                                            $onclick = '';
                                        }
                                        echo '<div class="td_slide">
                                            <div class="td_testimonial td_style_1 td_type_1 td_white_bg td_radius_5 mb-4" style="padding-top: 20px; padding-bottom: 20px; ">
                                                <span class="td_quote_icon td_accent_color">
                                                    <svg width="65" height="46" viewBox="0 0 65 46" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path opacity="0.05" d="M13.9286 26.6H1V1H26.8571V27.362L17.956 45H6.26764L14.8213 28.0505L15.5534 26.6H13.9286ZM51.0714 26.6H38.1429V1H64V27.362L55.0988 45H43.4105L51.9642 28.0505L52.6962 26.6H51.0714Z" fill="currentColor" stroke="currentColor" stroke-width="2"/>
                                                    </svg>
                                                </span>
                                                <div class="td_testimonial_meta">
                                                    <img src="' . (isset($testimonial['image_path']) && !empty($testimonial['image_path']) ? 'assets/testimonials/' . htmlspecialchars($testimonial['image_path']) : 'https://www.cgc.edu.in/assets/images/testimonials/default-avatar.webp') . '" alt="' . htmlspecialchars($testimonial['name']) . '">
                                                    <div class="td_testimonial_meta_right">
                                                        <h3 class="td_fs_20 td_semibold td_mb_2">' . htmlspecialchars($testimonial['name']) . '</h3>
                                                        <p class="td_fs_14 mb-0 td_heading_color td_opacity_7">' . htmlspecialchars($testimonial['designation']) . '</p>
                                                    </div>
                                                </div>
                                                <blockquote class="td_testimonial_text td_fs_20 td_medium td_heading_color td_mb_24 td_opacity_9 ' . $blockquote_class . '" ' . $onclick . '>
                                                    ' . $content_display . '
                                                </blockquote>
                                                <div class="td_rating" data-rating="5">
                                                    <i class="fa-regular fa-star"></i>
                                                    <i class="fa-regular fa-star"></i>
                                                    <i class="fa-regular fa-star"></i>
                                                    <i class="fa-regular fa-star"></i>
                                                    <i class="fa-regular fa-star"></i>
                                                    <div class="td_rating_percentage">
                                                        <i class="fa-solid fa-star fa-fw"></i>
                                                        <i class="fa-solid fa-star fa-fw"></i>
                                                        <i class="fa-solid fa-star fa-fw"></i>
                                                        <i class="fa-solid fa-star fa-fw"></i>
                                                        <i class="fa-solid fa-star fa-fw"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>';
                                    }
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <!-- Video Testimonials Slider -->
                    <div class="td_slider td_style_1 h-100">
                        <div class="td_slider_container" data-autoplay="1" data-loop="1" data-speed="800"
                            data-center="0" data-variable-width="0" data-slides-per-view="1" data-arrows="1"
                            style="min-height: 500px;">
                            <div class="td_slider_wrapper">
                                <?php
                                // Fetch video testimonials from database
                                $stmt = $pdo->query("SELECT * FROM video_testimonials ORDER BY created_at DESC");
                                $video_testimonials = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                if (count($video_testimonials) > 0) {
                                    foreach ($video_testimonials as $video) {
                                        // Check video type and display accordingly
                                        if ($video['video_type'] === 'youtube' && !empty($video['video_id'])) {
                                            // YouTube video
                                            echo '<div class="td_slide">
                                                <div class="mb-4" style="height: 350px;">
                                                    <iframe src="https://www.youtube.com/embed/' . $video['video_id'] . '" title="' . htmlspecialchars($video['name']) . ' Testimonial" allowfullscreen style="width:100%; height:100%;"></iframe>
                                                </div>
                                            </div>';
                                        } elseif ($video['video_type'] === 'upload' && !empty($video['video_file'])) {
                                            // Uploaded video file
                                            echo '<div class="td_slide">
                                                <div class="mb-4" style="height: 350px;">
                                                    <video controls style="width: 100%; height: 100%; object-fit: contain;">
                                                        <source src="assets/videos/' . htmlspecialchars($video['video_file']) . '" type="video/mp4">
                                                        <source src="assets/videos/' . htmlspecialchars($video['video_file']) . '" type="video/avi">
                                                        <source src="assets/videos/' . htmlspecialchars($video['video_file']) . '" type="video/mov">
                                                        <source src="assets/videos/' . htmlspecialchars($video['video_file']) . '" type="video/wmv">
                                                        <source src="assets/videos/' . htmlspecialchars($video['video_file']) . '" type="video/flv">
                                                        <source src="assets/videos/' . htmlspecialchars($video['video_file']) . '" type="video/webm">
                                                        Your browser does not support the video tag.
                                                    </video>
                                                </div>
                                            </div>';
                                        }
                                    }
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>



    <!-- Start Team Section -->
    <!-- Start Instructor Section -->
    <section>
        <div class="td_height_112 td_height_lg_75"></div>
        <div class="container">
            <div class="td_section_heading td_style_1 text-center wow fadeInUp" data-wow-duration="1s"
                data-wow-delay="0.2s">
                <p class="td_section_subtitle_up td_fs_18 td_medium td_spacing_1 td_mb_10 td_accent_color">Our Alumni
                </p>
                <h2 class="td_section_title td_fs_48 mb-0">Meet our alumni <br>How They’re Making Us Proud</h2>
            </div>
            <div class="td_height_50 td_height_lg_50"></div>
            <style>
            .alumni-card {
                background: #fff;
                border-radius: 15px;
                padding: 30px 20px;
                text-align: center;
                box-shadow: 0 10px 40px rgba(0, 0, 0, 0.06);
                /* border-bottom: 5px solid #1C56E1; */
                height: 100%;
                transition: transform 0.3s;
                position: relative;
            }

            .alumni-card:hover {
                transform: translateY(-10px);
            }

            .alumni-img-wrapper {
                width: 110px;
                height: 110px;
                margin: 0 auto 20px;
                position: relative;
            }

            .alumni-img {
                width: 100%;
                height: 100%;
                border-radius: 50%;
                object-fit: cover;
                border: 4px solid #fff;
                box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            }

            .quote-icon {
                position: absolute;
                bottom: 0;
                right: 0;
                background: #1C56E1;
                color: #fff;
                width: 30px;
                height: 30px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 14px;
            }

            .alumni-name {
                color: #000033;
                font-weight: 700;
                font-size: 18px;
                margin-bottom: 5px;
            }

            .alumni-role {
                color: #1C56E1;
                font-size: 14px;
                font-weight: 600;
                margin-bottom: 20px;
                display: block;
                text-transform: uppercase;
                letter-spacing: 1px;
            }

            .alumni-text {
                color: #555;
                font-size: 15px;
                line-height: 1.6;
                font-style: italic;
            }
            </style>
            <div class="row wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.25s">
                <div class="col-12">
                    <!-- Alumni Slider -->
                    <div class="td_slider td_style_1 td_slider_gap_24">
                        <div class="td_slider_container" data-autoplay="1" data-loop="1" data-speed="800"
                            data-center="0" data-slides-per-view="responsive" data-xs-slides="1" data-sm-slides="2"
                            data-md-slides="3" data-lg-slides="4" data-add-slides="1">
                            <div class="td_slider_wrapper">
                                <?php
                                // Fetch Alumni Reviews
                                try {
                                    $stmt_alu = $pdo->prepare("SELECT * FROM alumni_reviews WHERE is_active = 1 ORDER BY display_order ASC");
                                    $stmt_alu->execute();
                                    $alumni_list = $stmt_alu->fetchAll(PDO::FETCH_ASSOC);

                                    if (count($alumni_list) > 0) {
                                        foreach ($alumni_list as $alumni) {
                                            ?>
                                <div class="td_slide">
                                    <div class="alumni-card">
                                        <div class="alumni-img-wrapper">
                                            <?php
                                                        $img_src = 'assets/img/im/' . $alumni['image_path'];
                                                        // Simple check or default
                                                        if (empty($alumni['image_path'])) {
                                                            $img_src = 'assets/img/im/alu1.jpg'; // Default placeholder
                                                        }
                                                        ?>
                                            <img src="<?php echo htmlspecialchars($img_src); ?>"
                                                alt="<?php echo htmlspecialchars($alumni['name']); ?>"
                                                class="alumni-img">
                                            <div class="quote-icon"><i class="fa-solid fa-quote-right"></i></div>
                                        </div>
                                        <h4 class="alumni-name"><?php echo htmlspecialchars($alumni['name']); ?></h4>
                                        <span
                                            class="alumni-role"><?php echo htmlspecialchars($alumni['role']); ?></span>
                                        <p class="alumni-text">"<?php echo htmlspecialchars($alumni['quote']); ?>"</p>
                                    </div>
                                </div>
                                <?php
                                        }
                                    } else {
                                        // Fallback if no alumni
                                        ?>
                                <div class="td_slide">
                                    <div class="alumni-card">
                                        <p class="alumni-text">Top results coming soon...</p>
                                    </div>
                                </div>
                                <?php
                                    }
                                } catch (PDOException $e) {
                                    echo '<!-- Error loading alumni -->';
                                }
                                ?>
                            </div>

                        </div>
                    </div>
                    <!-- Standard Theme Pagination (No Arrows, matching index.php reference) -->
                    <div class="td_pagination td_style_1"></div>
                </div>
            </div>
        </div>
        </div>
        <div class="td_height_112 td_height_lg_75"></div>
    </section>
    <!-- End Instructor Section -->
    <!-- End Team Section -->


    <!-- Start App Section -->
    <div class="">
        <section class=" app-section_custom">
            <div class="container app-container_custom">

                <!-- Text Content -->
                <div class="td_cta_text_custom">
                    <div class="td_section_heading_custom">
                        <p class="td_section_subtitle_custom">AVAILABLE ON GOOGLE PLAY & APP STORE</p>
                        <h2 class="td_section_title_custom">Download our App</h2>
                        <p class="td_section_description_custom">Experience the future of entrance exam preparation with
                            our comprehensive mobile app. Access study materials, practice tests, and expert guidance
                            anytime, anywhere.</p>
                    </div>



                    <div class="td_btns_group_custom">
                        <a href="https://play.google.com/store/apps/details?id=co.jarvis.cedu&hl=en_IN"
                            class="td_btn_custom">
                            <span class="td_btn_in_custom">
                                <svg width="23" height="25" viewBox="0 0 23 25" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M1.27919 1.60156C0.99445 1.97711 0.835938 2.43909 0.835938 2.93347V22.5016C0.835938 22.9608 0.973168 23.3922 1.22103 23.7526L11.8891 12.6469L1.27919 1.60156Z"
                                        fill="currentColor" />
                                    <path
                                        d="M12.8722 11.6309L16.3331 8.02815L4.16781 1.01832C3.6398 0.714004 3.034 0.64186 2.46875 0.800429L12.8722 11.6309Z"
                                        fill="currentColor" />
                                    <path
                                        d="M12.868 13.6641L2.35938 24.6039C2.58595 24.6799 2.82042 24.7181 3.05507 24.7181C3.43576 24.7181 3.81663 24.6182 4.16356 24.4182L16.4164 17.3579L12.868 13.6641Z"
                                        fill="currentColor" />
                                    <path
                                        d="M21.1419 10.7995L17.5853 8.75L13.8438 12.6448L17.6726 16.631L21.1419 14.6319C21.8362 14.232 22.2506 13.5155 22.2506 12.7157C22.2506 11.9157 21.8362 11.1994 21.1419 10.7995Z"
                                        fill="currentColor" />
                                </svg>
                                <span>Google play</span>
                            </span>
                        </a>

                        <a href="https://apps.apple.com/in/app/myinstitute/id1472483563" class="td_btn_custom">
                            <span class="td_btn_in_custom">
                                <svg width="20" height="25" viewBox="0 0 20 25" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M14.6212 0.71875C14.677 0.71875 14.7329 0.71875 14.7919 0.71875C14.9289 2.41128 14.2829 3.67594 13.4977 4.59176C12.7273 5.50126 11.6724 6.38335 9.96617 6.24951C9.85235 4.58122 10.4994 3.41036 11.2835 2.49664C12.0107 1.64511 13.3439 0.887371 14.6212 0.71875Z"
                                        fill="currentColor" />
                                    <path
                                        d="M19.7851 18.3371C19.7851 18.3539 19.7851 18.3687 19.7851 18.3845C19.3056 19.8368 18.6216 21.0814 17.7869 22.2364C17.025 23.2851 16.0912 24.6962 14.424 24.6962C12.9833 24.6962 12.0264 23.7698 10.5499 23.7445C8.98808 23.7193 8.12917 24.5191 6.70116 24.7204C6.53781 24.7204 6.37446 24.7204 6.21427 24.7204C5.16566 24.5687 4.3194 23.7382 3.70288 22.99C1.88493 20.7789 0.480112 17.9229 0.21875 14.2681C0.21875 13.9097 0.21875 13.5525 0.21875 13.1942C0.329407 10.5784 1.60039 8.4517 3.28976 7.421C4.18134 6.87298 5.407 6.40612 6.77177 6.61478C7.35668 6.70542 7.95423 6.90565 8.478 7.10378C8.97438 7.29454 9.59512 7.63283 10.1832 7.61492C10.5815 7.60332 10.9778 7.39571 11.3793 7.24922C12.5555 6.82451 13.7084 6.33761 15.2281 6.56631C17.0545 6.84242 18.3507 7.65391 19.1517 8.90592C17.6067 9.88919 16.3853 11.3709 16.5939 13.9013C16.7794 16.1998 18.1157 17.5446 19.7851 18.3371Z"
                                        fill="currentColor" />
                                </svg>
                                <span>Apple Store</span>
                            </span>
                        </a>
                    </div>
                </div>

                <!-- Image -->
                <div class="td_cta_thumb">
                    <img src="assets/img/info-maths-app.png" alt="">
                </div>



                <!--<div class="container wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.25s">-->
                <!--<div class="td_contact_box td_style_1 td_accent_bg td_radius_10">-->
                <!--    <div class="td_contact_box_left">-->
                <!--        <p class="td_fs_18 td_light td_white_color td_mb_4">Get In Touch:</p>-->
                <!--        <h3 class="td_fs_36 mb-0 td_white_color"><a href="mailto:info@cgc.edu.in">info@cgc.edu.in</a></h3>-->
                <!--    </div>-->
                <!--    <div-->
                <!--        class="td_contact_box_or td_fs_24 td_medium td_white_bg td_white_bg td_center rounded-circle td_accent_color">-->
                <!--        or-->
                <!--    </div>-->
                <!--    <div class="td_contact_box_right">-->
                <!--        <p class="td_fs_18 td_light td_white_color td_mb_4">Get In Touch:</p>-->
                <!--        <h3 class="td_fs_36 mb-0 td_white_color"><a href="tel:1800-200-3575">1800-200-3575</a></h3>-->
                <!--    </div>-->
                <!--</div>-->
            </div>
    </div>








    <!-- Start Certificate Section -->
    <!-- <section class="td_heading_bg td_shape_section_9">
        <div class="td_shape_position_3 position-absolute"></div>
        <div class="container" style="padding-top: 80px; padding-bottom: 80px;">
            <div class="td_section_heading td_style_1 text-center wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.2s">

                <h2 class="td_section_title td_fs_40 mb-0 td_white_color">Why Choose Us</h2>
            </div>
            <div class="td_height_50 td_height_lg_50"></div>
            <div class="row align-items-center td_gap_y_40">
                <div class="col-xl-4 wow fadeInLeft" data-wow-duration="1s" data-wow-delay="0.2s">
                    <div class="td_pr_35">
                        <img src="assets/img/im/011.jpg" alt="" class="td_radius_5 w-100">
                    </div>
                </div>
                <div class="col-xl-8 wow fadeInRight" data-wow-duration="1s" data-wow-delay="0.2s">
                    <div class="row td_gap_y_30 td_row_gap_30">
                        <div class="col-md-6">
                            <div class="td_iconbox td_style_4 td_radius_10">
                                <div class="td_iconbox_icon td_mb_20">
                                    <img src="assets/img/im/idea.png" style="width: 50px; height: 50px; object-fit: cover;" alt="">
                                </div>
                                <h3 class="td_iconbox_title td_fs_30 td_mb_20 td_semibold td_white_color">Video Solutions</h3>
                                <p class="td_iconbox_subtitle mb-0 td_fs_14 td_white_color td_opacity_7">Faculty-solved video solutions for all module questions + highly researched study material.</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="td_iconbox td_style_4 td_radius_10">
                                <div class="td_iconbox_icon td_mb_20">
                                    <img src="assets/img/im/team.png" style="width: 50px; height: 50px; object-fit: cover;" alt="">
                                </div>
                                <h3 class="td_iconbox_title td_fs_30 td_mb_20 td_semibold td_white_color">Professional Faculty</h3>
                                <p class="td_iconbox_subtitle mb-0 td_fs_14 td_white_color td_opacity_7">Trainers are working Analytics professionals + strong full-time faculty team.</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="td_iconbox td_style_4 td_radius_10">
                                <div class="td_iconbox_icon td_mb_20">
                                    <img src="assets/img/im/seminar.png" style="width: 50px; height: 50px; object-fit: cover;" alt="">
                                </div>
                                <h3 class="td_iconbox_title td_fs_30 td_mb_20 td_semibold td_white_color">Guest Lectures</h3>
                                <p class="td_iconbox_subtitle mb-0 td_fs_14 td_white_color td_opacity_7">Guest lectures on career building regularly updated study material.</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="td_iconbox td_style_4 td_radius_10">
                                <div class="td_iconbox_icon td_mb_20">
                                    <img src="assets/img/im/terminal(1).png" style="width: 50px; height: 50px; object-fit: cover;" alt="">
                                </div>
                                <h3 class="td_iconbox_title td_fs_30 td_mb_20 td_semibold td_white_color">Hi-Tech Infra</h3>
                                <p class="td_iconbox_subtitle mb-0 td_fs_14 td_white_color td_opacity_7">Hi-tech infrastructure with advanced gadgets for engaging sessions.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section> -->
    <!-- End Certificate Section -->

    <!-- Start CTA Section -->
    <!-- <section class="td_cta td_style_2 td_accent_bg td_hobble" style="background: white;">
        <div class="td_height_112 td_height_lg_75"></div>
        <div class="container">
            <div class="td_cta_in wow fadeIn" data-wow-duration="1s" data-wow-delay="0.2s">
                <div class="td_section_heading td_style_1">
                    <p class="td_section_subtitle_up td_fs_18 td_semibold td_spacing_1 td_mb_10 text-uppercase td_heading_color" style="color:black;">CLAIM SCHOLARSHIP</p>
                    <h2 class="td_section_title td_fs_48 td_mb_20 td_white_color" style="color:black;">Empowering Excellence Through Financial Support</h2>
                    <p class="td_section_subtitle td_fs_18 td_mb_28 td_white_color td_opacity_9" style="color:black;">At Infomaths, we believe that financial constraints should not hinder talented students from pursuing their dreams in the field of Master of Computer Applications (MCA). Our scholarship programs are designed to reward merit, support need-based candidates, and promote diversity in the tech industry.</p>
                    <a href="#" class="td_btn td_style_1 td_radius_10 td_medium mt-3" style="margin:auto">
                        <span class="td_btn_in td_white_color td_accent_bg">
                            <span>Apply Now</span>
                            <svg width="19" height="20" viewBox="0 0 19 20" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M15.1575 4.34302L3.84375 15.6567" stroke="currentColor" stroke-width="1.5"
                                    stroke-linecap="round" stroke-linejoin="round"></path>
                                <path
                                    d="M15.157 11.4142C15.157 11.4142 16.0887 5.2748 15.157 4.34311C14.2253 3.41142 8.08594 4.34314 8.08594 4.34314"
                                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round"></path>
                            </svg>
                        </span>
                    </a>
                </div>
            </div>
        </div>
        <img class="td_cta_img wow fadeInRight none_img" data-wow-duration="1s" data-wow-delay="0.3s" src="assets/img/home_4/scholar.png" alt="">

        <div class="td_height_120 td_height_lg_80"></div>
    </section> -->
    <!-- End CTA Section -->



    <section class="td_gray_bg_3">
        <div class="container" style="padding: 80px 0;">
            <div class="td_section_heading td_style_1 text-center wow fadeInUp" data-wow-duration="1s"
                data-wow-delay="0.2s">
                <p
                    class="td_section_subtitle_up td_fs_18 td_semibold td_spacing_1 td_mb_10 text-uppercase td_accent_color">
                    <i></i>
                    Check Our Demo Lectures
                    <i></i>
                </p>
                <h2 class="td_section_title td_fs_48 mb-0">Sample Video Lectures</h2>
            </div>
            <div class="td_height_50 td_height_lg_50"></div>
            <div class="row td_gap_y_30">
                <?php
                try {
                    $stmt = $pdo->prepare("SELECT * FROM demo_lectures WHERE is_active = 1 ORDER BY display_order ASC LIMIT 4");
                    $stmt->execute();
                    $demoLectures = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    if (!empty($demoLectures)) {
                        foreach ($demoLectures as $lecture) {
                            echo '<div class="col-lg-3 col-md-6 wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.2s">';
                            echo '<div class="td_video_item td_style_1">';

                            if ($lecture['video_type'] === 'youtube') {
                                // Extract YouTube video ID from URL
                                $youtubeUrl = $lecture['video_url'];
                                $videoId = '';
                                if (preg_match('/(?:youtube\\.com\\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\\.be\/)([^"&?\/\\s]{11})/', $youtubeUrl, $matches)) {
                                    $videoId = $matches[1];
                                }

                                if (!empty($videoId)) {
                                    echo '<div class="td_video_thumb">';
                                    echo '<iframe width="100%" height="220px" src="https://www.youtube.com/embed/' . htmlspecialchars($videoId) . '" title="' . htmlspecialchars($lecture['title']) . '" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen style="border-radius: 10px;"></iframe>';
                                    echo '</div>';
                                }
                            } elseif ($lecture['video_type'] === 'upload' && !empty($lecture['video_file'])) {
                                echo '<div class="td_video_thumb" style="height:220px;">';
                                echo '<video controls class="w-100" style="border-radius: 10px; background: #000;"' . (!empty($lecture['thumbnail']) ? ' poster="' . htmlspecialchars($lecture['thumbnail']) . '"' : '') . '>';
                                echo '<source src="' . htmlspecialchars($lecture['video_file']) . '" type="video/mp4">';
                                echo 'Your browser does not support the video tag.';
                                echo '</video>';
                                echo '</div>';
                            }

                            echo '</div>';
                            echo '</div>';
                        }
                    } else {
                        // Fallback content when no demo lectures are available
                        for ($i = 1; $i <= 4; $i++) {
                            echo '<div class="col-lg-3 col-md-6 wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.2s">';
                            echo '<div class="td_video_item td_style_1">';
                            echo '<div class="td_video_thumb">';
                            echo '<img src="assets/img/video-placeholder.jpg" alt="Demo Lecture ' . $i . '" class="w-100" style="border-radius: 10px;" />';
                            echo '</div>';
                            echo '</div>';
                            echo '</div>';
                        }
                    }
                } catch (PDOException $e) {
                    // Fallback content on database error
                    for ($i = 1; $i <= 4; $i++) {
                        echo '<div class="col-lg-3 col-md-6 wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.2s">';
                        echo '<div class="td_video_item td_style_1">';
                        echo '<div class="td_video_thumb">';
                        echo '<img src="assets/img/video-placeholder.jpg" alt="Demo Lecture ' . $i . '" class="w-100" style="border-radius: 10px;" />';
                        echo '</div>';
                        echo '</div>';
                        echo '</div>';
                    }
                }
                ?>
            </div>
        </div>
    </section>


    <!-- Start Certificate Section -->
    <!-- <section class="td_heading_bg td_shape_section_9">
        <div class="td_shape_position_3 position-absolute"></div>
        <div class="td_height_90 td_height_lg_40"></div>
        <div class="container">
            <div class="td_section_heading td_style_1 text-center wow fadeInUp" data-wow-duration="1s"
                data-wow-delay="0.2s">
                <p
                    class="td_section_subtitle_up td_fs_18 td_semibold td_spacing_1 td_mb_10 text-uppercase td_white_color">
                    Why Choose Us
                </p>
                <h2 class="td_section_title td_fs_48 mb-0 td_white_color">
                    25 Years of Excellence in Entrance Exam Coaching
                </h2>
            </div>
            <div class="td_height_50 td_height_lg_50"></div>
            <div class="row align-items-center td_gap_y_40">
                <div class="col-xl-6 wow fadeInLeft" data-wow-duration="1s" data-wow-delay="0.2s">
                    <div class="td_pr_35">
                        <div class="td_slider td_style_1">
                            <div class="td_slider_container" data-autoplay="1" data-loop="1" data-speed="800"
                                data-center="0" data-variable-width="0" data-slides-per-view="1">
                                <div class="td_slider_wrapper">
                                    <?php
                                    $stmt = $pdo->prepare("SELECT image_path, alt_text FROM section_images WHERE section_name = 'why choose us' ORDER BY display_order ASC");
                                    $stmt->execute();
                                    $images = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                    if (!empty($images)) {
                                        foreach ($images as $image) {
                                            echo '<div class="td_slide">';
                                            echo '<div class="td_radius_10">';
                                            echo '<img src="' . htmlspecialchars($image['image_path']) . '" alt="' . htmlspecialchars($image['alt_text']) . '" class="td_radius_10 w-100" />';
                                            echo '</div>';
                                            echo '</div>';
                                        }
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6 wow fadeInRight" data-wow-duration="1s" data-wow-delay="0.2s">
                    <div class="row td_gap_y_30 td_row_gap_30">
                        <div class="col-md-6">
                            <div class="td_iconbox td_style_4 td_radius_10">
                                <div class="td_iconbox_icon td_mb_16">
                                    <img src="assets/img/home_3/achievement_icon_1.svg" alt="" />
                                </div>
                                <h3 class="td_iconbox_title td_fs_24 td_mb_12 td_semibold td_white_color">
                                    Expert Faculty
                                </h3>
                                <p class="td_iconbox_subtitle mb-0 td_fs_14 td_white_color td_opacity_7">
                                    Distinguished faculty with decades of entrance exam expertise.
                                </p>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="td_iconbox td_style_4 td_radius_10">
                                <div class="td_iconbox_icon td_mb_16">
                                    <img src="assets/img/global.png" alt="" />
                                </div>
                                <h3 class="td_iconbox_title td_fs_24 td_mb_12 td_semibold td_white_color">
                                    Top Rankings
                                </h3>
                                <p class="td_iconbox_subtitle mb-0 td_fs_14 td_white_color td_opacity_7">
                                    Consistent top rankings in NIMCET, MAHCET, CUCET PG, and state MCA exams.
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="td_iconbox td_style_4 td_radius_10">
                                <div class="td_iconbox_icon td_mb_16">
                                    <img src="assets/img/home_3/achievement_icon_4.svg" alt="" />
                                </div>
                                <h3 class="td_iconbox_title td_fs_24 td_mb_12 td_semibold td_white_color">
                                    Comprehensive Study Materials
                                </h3>
                                <p class="td_iconbox_subtitle mb-0 td_fs_14 td_white_color td_opacity_7">
                                    Complete study ecosystem with books, online resources, and test series.
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="td_iconbox td_style_4 td_radius_10">
                                <div class="td_iconbox_icon td_mb_16">
                                    <img src="assets/img/home_3/achievement_icon_3.svg" alt="" />
                                </div>
                                <h3 class="td_iconbox_title td_fs_24 td_mb_12 td_semibold td_white_color">
                                    25 Years of Excellence
                                </h3>
                                <p class="td_iconbox_subtitle mb-0 td_fs_14 td_white_color td_opacity_7">
                                    Founded in 1999, helping thousands build promising careers.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="td_height_100 td_height_lg_80"></div>
    </section> -->
    <!-- End Certificate Section -->

    <!-- Start Registration Section -->
    <section>
        <div class="container" style="padding: 80px 0;">
            <div class="td_section_heading td_style_1 td_type_1 wow fadeInUp" data-wow-duration="1s"
                data-wow-delay="0.2s" style="display:flex; justify-content:center; align-items:center;">
                <div class="td_section_heading_left" style="text-align: center;">
                    <p
                        class="td_section_subtitle_up td_fs_18 td_semibold td_spacing_1 td_mb_10 text-uppercase td_accent_color">
                        <i></i>
                        Free Services
                        <i></i>
                    </p>
                    <h2 class="td_section_title td_fs_48 mb-0">Register for Free Services</h2>
                </div>
            </div>
            <div class="td_height_50 td_height_lg_50"></div>
            <div class="row td_gap_y_24" id="free-services-container">
                <!-- Free Sample Class -->
                <div class="col-xl-3 col-lg-4 col-sm-6 wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.25s">
                    <div class="td_iconbox td_style_2 text-center td_hobble free-service-card" style="cursor: pointer;">
                        <div class="td_iconbox_in td_hover_layer_4">
                            <div class="td_hover_layer_3">
                                <div class="td_iconbox_icon td_mb_16">
                                    <img src="assets/img/home_1/demo-class.png" alt="">
                                </div>
                                <h3 class="td_iconbox_title td_fs_20 td_semibold td_opacity_8 td_mb_16">Free Sample
                                    Class</h3>
                                <a href="register.php" class="td_iconbox_subtitle mb-0 td_accent_color">Register Now</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Free Study Material -->
                <div class="col-xl-3 col-lg-4 col-sm-6 wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.3s">
                    <div class="td_iconbox td_style_2 text-center td_hobble free-service-card" style="cursor: pointer;">
                        <div class="td_iconbox_in td_hover_layer_4">
                            <div class="td_hover_layer_3">
                                <div class="td_iconbox_icon td_mb_16">
                                    <img src="assets/img/home_1/test-series.png" alt="">
                                </div>
                                <h3 class="td_iconbox_title td_fs_20 td_semibold td_opacity_8 td_mb_16">Free Study
                                    Material</h3>
                                <a href="register.php" class="td_iconbox_subtitle mb-0 td_accent_color">Register Now</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Free Test Series -->
                <div class="col-xl-3 col-lg-4 col-sm-6 wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.35s">
                    <div class="td_iconbox td_style_2 text-center td_hobble free-service-card" style="cursor: pointer;">
                        <div class="td_iconbox_in td_hover_layer_4">
                            <div class="td_hover_layer_3">
                                <div class="td_iconbox_icon td_mb_16">
                                    <img src="assets/img/home_1/pyq.png" alt="">
                                </div>
                                <h3 class="td_iconbox_title td_fs_20 td_semibold td_opacity_8 td_mb_16">Free Test Series
                                </h3>
                                <a href="register.php" class="td_iconbox_subtitle mb-0 td_accent_color">Register Now</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Free Notification -->
                <div class="col-xl-3 col-lg-4 col-sm-6 wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.4s">
                    <div class="td_iconbox td_style_2 text-center td_hobble free-service-card" style="cursor: pointer;">
                        <div class="td_iconbox_in td_hover_layer_4">
                            <div class="td_hover_layer_3">
                                <div class="td_iconbox_icon td_mb_16">
                                    <img src="assets/img/home_1/counselling.png" alt="">
                                </div>
                                <h3 class="td_iconbox_title td_fs_20 td_semibold td_opacity_8 td_mb_16">Free
                                    Notification</h3>
                                <a href="register.php" class="td_iconbox_subtitle mb-0 td_accent_color">Register Now</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Direct binding to the cards
                var cards = document.querySelectorAll('.free-service-card');
                cards.forEach(function(card) {
                    card.addEventListener('click', function(e) {
                        e.preventDefault();
                        e.stopPropagation(); // Stop bubbling immediately
                        console.log('Free service card clicked');

                        // Try jQuery modal first
                        if (window.jQuery && window.jQuery.fn.modal) {
                            window.jQuery('#contactPopupModal').modal('show');
                        } else {
                            // Bootstrap 5 vanilla
                            var modalEl = document.getElementById('contactPopupModal');
                            if (modalEl) {
                                var modal = new bootstrap.Modal(modalEl);
                                modal.show();
                            }
                        }
                    });
                });
            });
            </script>
        </div>

        <!-- Scholarship Application Link -->
        <div class="text-center mt-4 wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.5s">
            <button type="button" id="scholarshipBtn" class="open-scholarship-modal td_fs_20 td_semibold td_mb_10"
                style="background:none; border:none; padding:0; color: #1C56E1; text-decoration: underline; cursor: pointer;">
                Apply Scholarship for MCA Entrance Exam
            </button>
            <script>
            document.getElementById('scholarshipBtn').addEventListener('click', function(e) {
                e.preventDefault();
                var modalId = 'scholarshipModal';
                var modalEl = document.getElementById(modalId);
                if (modalEl) {
                    if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                        var modal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
                        modal.show();
                    } else if (window.jQuery && window.jQuery.fn.modal) {
                        window.jQuery('#' + modalId).modal('show');
                    } else {
                        // Fallback
                        modalEl.style.display = 'block';
                        modalEl.classList.add('show');
                        document.body.classList.add('modal-open');
                        $('<div>').addClass('modal-backdrop fade show').appendTo(document.body);
                    }
                } else {
                    console.error('Modal element not found');
                }
            });
            </script>
        </div>


    </section>
    <!-- End Registration Section -->

    <!-- Start Footer Section -->
    <!-- Start Footer Section -->
    <?php include 'includes/footer-new.php'; ?>
    <!-- End Footer Section -->
    <!-- Start Scroll Up Button -->
    <!-- <div class="td_scrollup">
        <i class="fa-solid fa-arrow-up"></i>
    </div> -->
    <!-- End Scroll Up Button -->
    <!-- Script -->
    <script src="assets/js/jquery-3.7.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/owl-carousel/1.3.3/owl.carousel.min.js"></script>
    <script src="assets/js/jquery.slick.min.js"></script>
    <script src="assets/js/odometer.js"></script>
    <script src="assets/js/gsap.min.js"></script>
    <script src="assets/js/jquery-ui.min.js"></script>
    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css" />
    <script src="assets/js/wow.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"></script>
    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>
    <script src="assets/js/main.js"></script>

    <!-- Header Transparency Script -->
    <script>
    // Placement Records Slider JavaScript
    $(document).ready(function() {
        $('.placement-records-slider').slick({
            infinite: true,
            slidesToShow: 4,
            slidesToScroll: 1,
            autoplay: true,
            autoplaySpeed: 3000,
            pauseOnHover: true,
            dots: false,
            arrows: false,
            responsive: [{
                    breakpoint: 1200,
                    settings: {
                        slidesToShow: 3,
                        slidesToScroll: 1
                    }
                },
                {
                    breakpoint: 768,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 1
                    }
                },
                {
                    breakpoint: 480,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1,
                        centerMode: true,
                        centerPadding: '20px'
                    }
                }
            ]
        });

        // Custom navigation buttons
        $('.placement-prev-btn').click(function() {
            $('.placement-records-slider').slick('slickPrev');
        });

        $('.placement-next-btn').click(function() {
            $('.placement-records-slider').slick('slickNext');
        });

        // Add hover effects and animations
        $('.placement-record-card').hover(
            function() {
                $(this).find('.placement-overlay').addClass('show');
            },
            function() {
                $(this).find('.placement-overlay').removeClass('show');
            }
        );
    });

    // Top Recruiters Slider JavaScript
    $(document).ready(function() {
        $('.top-recruiters-slider').slick({
            infinite: true,
            slidesToShow: 8,
            slidesToScroll: 1,
            autoplay: true,
            autoplaySpeed: 2000,
            pauseOnHover: true,
            dots: false,
            arrows: false,
            responsive: [{
                    breakpoint: 1200,
                    settings: {
                        slidesToShow: 6,
                        slidesToScroll: 1
                    }
                },
                {
                    breakpoint: 992,
                    settings: {
                        slidesToShow: 4,
                        slidesToScroll: 1
                    }
                },
                {
                    breakpoint: 768,
                    settings: {
                        slidesToShow: 3,
                        slidesToScroll: 1
                    }
                },
                {
                    breakpoint: 480,
                    settings: {
                        slidesToShow: 3,
                        slidesToScroll: 1
                    }
                }
            ]
        });
    });

    // ChatGPT Style Search Modal JavaScript
    $(document).ready(function() {
        const API_KEY = 'AIzaSyCsRaU-hQ2edPeMf4tE6rgP1to15BH4RcY';
        const SEARCH_ENGINE_ID = '67ea2c4ed606f4ea5';
        const API_URL = `https://www.googleapis.com/customsearch/v1?key=${API_KEY}&cx=${SEARCH_ENGINE_ID}`;

        // Convert Markdown bold (**text**) to HTML bold tags
        function markdownToHtml(text) {
            // Convert **bold** to <b>bold</b>
            let html = text.replace(/\*\*(.*?)\*\*/g, '<b>$1</b>');
            // Convert * bullets to <li>...</li>
            // Handles bullets at line start: * ...
            let bulletRegex = /^\s*\*\s+(.*)$/gm;
            let hasBullets = bulletRegex.test(html);
            html = html.replace(bulletRegex, '<li>$1</li>');
            // If any <li> present, wrap in <ul>
            if (/<li>/.test(html)) {
                html = '<ul style="margin:0 0 1em 1.2em; padding:0;">' + html + '</ul>';
            }
            return html;
        }
        // Modal controls
        const searchModal = $('#searchModal');
        const searchModalTrigger = $('#searchModalTrigger');
        const closeSearchModal = $('#closeSearchModal');
        const modalSearchInput = $('#modalSearchInput');
        const searchResults = $('#searchResults');
        const searchLoading = $('#searchLoading');
        const resultsContainer = $('#resultsContainer');
        const resultsCount = $('#resultsCount');
        const submitSearchBtn = $('#submitSearch');
        const searchSummary = $('#searchSummary');
        const summaryContent = $('#summaryContent');
        const sourcesList = $('#sourcesList');
        const regenerateSummary = $('#regenerateSummary');

        // Store original body overflow value
        let originalBodyOverflow = $('body').css('overflow');

        // Open modal
        searchModalTrigger.on('click', function(e) {
            e.preventDefault();
            // Store current overflow state before changing it
            originalBodyOverflow = $('body').css('overflow');
            searchModal.addClass('active');
            modalSearchInput.focus();
            $('body').css('overflow', 'hidden');
        });

        // Close modal
        closeSearchModal.on('click', function() {
            closeModal();
        });

        // Close modal when clicking overlay
        $('.search-modal-overlay').on('click', function() {
            closeModal();
        });

        // Close modal with Escape key
        $(document).on('keydown', function(e) {
            if (e.key === 'Escape' && searchModal.hasClass('active')) {
                closeModal();
            }
        });

        function closeModal() {
            searchModal.removeClass('active');
            // Restore original overflow value or remove the property entirely
            if (originalBodyOverflow && originalBodyOverflow !== 'visible') {
                $('body').css('overflow', originalBodyOverflow);
            } else {
                $('body').css('overflow', '');
            }
            modalSearchInput.val('');
            searchResults.hide();
            searchSummary.hide();
            $('.search-suggestions').show();
        }

        // Handle suggestion pills
        $('.suggestion-pill').on('click', function() {
            const query = $(this).data('query');
            modalSearchInput.val(query);
            performSearch(query);
        });

        // Handle search input
        modalSearchInput.on('keypress', function(e) {
            if (e.which === 13) { // Enter key
                e.preventDefault();
                const query = $(this).val().trim();
                if (query) {
                    performSearch(query);
                }
            }
        });

        // Handle search button click
        submitSearchBtn.on('click', function(e) {
            e.preventDefault();
            const query = modalSearchInput.val().trim();
            if (query) {
                performSearch(query);
            }
        });

        // Perform search function with AI summarization
        async function performSearch(query) {
            if (!query.trim()) return;

            // Show loading state
            searchLoading.show();
            searchResults.hide();
            searchSummary.hide();
            $('.search-suggestions').hide();

            try {
                const response = await fetch(`${API_URL}&q=${encodeURIComponent(query)}&num=10`);

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const data = await response.json();

                // Hide loading
                searchLoading.hide();

                if (data.items && data.items.length > 0) {
                    // Display results first
                    displayResults(data.items, query);

                    // Generate AI summary
                    await generateAISummary(query, data.items);
                } else {
                    displayNoResults(query);
                }
            } catch (error) {
                console.error('Search error:', error);
                searchLoading.hide();
                displayError();
            }
        }

        // Generate AI Summary using multiple approaches
        async function generateAISummary(query, searchItems) {
            try {
                // Show summary section with loading state
                searchSummary.show();
                summaryContent.addClass('loading').html('Generating AI summary...');

                // Use all fetched results (up to 10)
                const allResults = searchItems.map(item => ({
                    title: item.title,
                    snippet: item.snippet,
                    link: item.link,
                    displayLink: item.displayLink
                }));

                // Only use Gemini API summary
                let summary = '';
                try {
                    summary = await generateOpenAISummary(query, allResults);
                } catch (error) {
                    console.error('Gemini summary failed:', error);
                    displaySummaryError();
                    return;
                }
                displaySummary(summary, allResults, query);
            } catch (error) {
                console.error('Summary generation error:', error);
                displaySummaryError();
            }
        }

        // Generate summary using Gemini API (frontend, for dev/testing only)
        async function generateOpenAISummary(query, searchContent) {
            // Use PHP proxy endpoint for Gemini summary
            const endpoint = 'index.php?gemini_proxy=1';
            // Improved, formatting-friendly prompt
            const prompt =
                `You are an expert education assistant. Summarize the following search results for the query: "${query}".\n\n` +
                "Please write a professional, well-structured summary in 3-5 bullet points, using proper grammar and formatting.\n" +
                "Highlight key facts, institutions, and opportunities.\n" +
                "Format the summary for students and parents.\n" +
                searchContent.map((item, i) => `${i + 1}. Title: ${item.title}\nSnippet: ${item.snippet}`)
                .join('\n\n');

            const body = {
                contents: prompt
            };

            const response = await fetch(endpoint, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(body)
            });

            if (!response.ok) {
                throw new Error('Gemini API error');
            }

            const data = await response.json();
            // Gemini API returns 'candidates' array for text response
            return data.candidates?. [0]?.content?.parts?. [0]?.text || "No summary generated.";
        }

        // ...existing code...
        // Remove static template summary functions and fallback code
        // Only Gemini AI summary will be used for search summaries
        // ...existing code...

        // Display summary
        function displaySummary(summary, searchContent, query) {
            // Convert Markdown bold to HTML and line breaks
            summaryContent.removeClass('loading').html(markdownToHtml(summary).replace(/\n/g, '<br>'));

            // Display sources
            const sources = searchContent.slice(0, 5).map(item => {
                return `<a href="${item.link}" target="_blank" class="source-link" title="${item.title}">${item.displayLink}</a>`;
            }).join('');

            sourcesList.html(sources);
        }

        function displaySummaryError() {
            summaryContent.removeClass('loading').html(`
            <div style="color: #ef4444; font-style: italic;">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="margin-right: 8px; vertical-align: top;">
                <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"/>
                <line x1="12" y1="8" x2="12" y2="12" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                <line x1="12" y1="16" x2="12.01" y2="16" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
              </svg>
              Unable to generate summary at this time. Please review the search results below.
            </div>
          `);
        }

        // Regenerate summary functionality
        regenerateSummary.on('click', async function() {
            const query = modalSearchInput.val().trim();
            if (query) {
                const searchResultItems = $('#resultsContainer .search-result-item');
                if (searchResultItems.length > 0) {
                    summaryContent.addClass('loading').html('Regenerating summary...');
                    // Extract data from displayed results
                    const mockItems = Array.from(searchResultItems).map(item => ({
                        title: $(item).find('.result-title').text(),
                        snippet: $(item).find('.result-snippet').text(),
                        link: $(item).attr('onclick').match(/'([^']+)'/)[1],
                        displayLink: $(item).find('.result-url').text()
                    }));
                    try {
                        const newSummary = await generateOpenAISummary(query, mockItems);
                        displaySummary(newSummary, mockItems, query);
                    } catch (error) {
                        displaySummaryError();
                    }
                }
            }
        });

        // Display search results
        function displayResults(items, query) {
            resultsContainer.empty();
            resultsCount.text(`Found ${items.length} results for "${query}"`);

            items.forEach(item => {
                const resultItem = createResultItem(item);
                resultsContainer.append(resultItem);
            });

            searchResults.show();
        }

        // Create result item HTML
        function createResultItem(item) {
            const title = item.title || 'Untitled';
            const snippet = item.snippet || 'No description available';
            const link = item.link || '#';
            const displayLink = item.displayLink || new URL(link).hostname;

            return `
            <div class="search-result-item" onclick="window.open('${link}', '_blank')">
              <div class="result-title">${escapeHtml(title)}</div>
              <div class="result-snippet">${escapeHtml(snippet)}</div>
              <div class="result-url">${escapeHtml(displayLink)}</div>
            </div>
          `;
        }

        // Display no results
        function displayNoResults(query) {
            resultsContainer.html(`
            <div style="text-align: center; padding: 40px; color: #6b7280;">
              <svg width="48" height="48" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="margin-bottom: 16px; opacity: 0.5;">
                <circle cx="11" cy="11" r="8" stroke="currentColor" stroke-width="2"/>
                <path d="m21 21-4.35-4.35" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
              <div style="font-size: 16px; font-weight: 600; margin-bottom: 8px;">No results found</div>
              <div style="font-size: 14px;">Try searching with different keywords or check your spelling.</div>
            </div>
          `);
            resultsCount.text(`No results found for "${query}"`);
            searchResults.show();
        }

        // Display error
        function displayError() {
            resultsContainer.html(`
            <div style="text-align: center; padding: 40px; color: #ef4444;">
              <svg width="48" height="48" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="margin-bottom: 16px;">
                <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"/>
                <line x1="12" y1="8" x2="12" y2="12" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                <line x1="12" y1="16" x2="12.01" y2="16" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
              </svg>
              <div style="font-size: 16px; font-weight: 600; margin-bottom: 8px;">Search Error</div>
              <div style="font-size: 14px;">There was a problem performing your search. Please try again.</div>
            </div>
          `);
            resultsCount.text('Search Error');
            searchResults.show();
        }

        // Escape HTML to prevent XSS
        function escapeHtml(unsafe) {
            return unsafe
                .replace(/&/g, "&amp;")
                .replace(/</g, "&lt;")
                .replace(/>/g, "&gt;")
                .replace(/"/g, "&quot;")
                .replace(/'/g, "&#039;");
        }

        // Auto-focus search input when modal opens
        searchModal.on('transitionend', function() {
            if (searchModal.hasClass('active')) {
                modalSearchInput.focus();
            }
        });

        // Real-time search suggestions (optional)
        let searchTimeout;
        modalSearchInput.on('input', function() {
            const query = $(this).val().trim();

            // Clear previous timeout
            clearTimeout(searchTimeout);

            // Set new timeout for auto-search (optional - can be removed if not wanted)
            if (query.length >= 3) {
                searchTimeout = setTimeout(() => {
                    // Uncomment the line below if you want real-time search as user types
                    // performSearch(query);
                }, 500);
            }
        });
    });

    // Hero Contact Form Handling
    $(document).ready(function() {
        const heroContactForm = document.getElementById('heroContactForm');
        const heroFormMessage = document.getElementById('hero-form-message');

        if (heroContactForm) {
            heroContactForm.addEventListener('submit', function(e) {
                e.preventDefault();

                // Get form data
                const formData = new FormData(heroContactForm);
                const data = {
                    student_name: formData.get('student_name'),
                    student_email: formData.get('student_email'),
                    student_mobile: formData.get('student_mobile'),
                    student_state: formData.get('student_state'),
                    student_city: formData.get('student_city'),
                    course_interest: formData.get('course_interest'),
                    enquiry: formData.get('enquiry'),
                    consent: formData.get('consent')
                };

                // Basic validation
                if (!data.student_name || !data.student_email || !data.student_mobile || !data
                    .student_state || !data.student_city || !data.course_interest) {
                    showHeroMessage('Please fill in all required fields.', 'error');
                    return;
                }

                if (!isValidEmail(data.student_email)) {
                    showHeroMessage('Please enter a valid email address.', 'error');
                    return;
                }

                // Mobile validation (10 digits)
                const mobileRegex = /^[0-9]{10}$/;
                if (!mobileRegex.test(data.student_mobile)) {
                    showHeroMessage('Please enter a valid 10-digit mobile number.', 'error');
                    return;
                }

                if (!data.consent) {
                    showHeroMessage('Please agree to allow CGC Landran team to contact you.', 'error');
                    return;
                }

                // Show loading state
                const submitBtn = heroContactForm.querySelector('button[type="submit"]');
                const originalText = submitBtn.textContent;
                submitBtn.textContent = 'SUBMITTING...';
                submitBtn.disabled = true;

                // Simulate form submission (replace with actual backend endpoint)
                setTimeout(() => {
                    // For demonstration - in production, send to your backend
                    console.log('Hero form data:', data);

                    // Show success message
                    showHeroMessage(
                        '🎉 Application submitted successfully! Our admissions team will contact you within 24 hours.',
                        'success');

                    // Reset form
                    heroContactForm.reset();

                    // Reset button
                    submitBtn.textContent = originalText;
                    submitBtn.disabled = false;

                    // Optional: Redirect to thank you page or trigger analytics
                    // gtag('event', 'form_submit', { 'event_category': 'hero_application' });

                }, 2000);

                // In production, replace the setTimeout with actual AJAX call:
                /*
                fetch('/process-application.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(data)
                })
                .then(response => response.json())
                .then(result => {
                    if (result.success) {
                        showHeroMessage('🎉 Application submitted successfully! Our admissions team will contact you within 24 hours.', 'success');
                        heroContactForm.reset();
                    } else {
                        showHeroMessage('Sorry, there was an error submitting your application. Please try again.', 'error');
                    }
                })
                .catch(error => {
                    showHeroMessage('Sorry, there was an error submitting your application. Please try again.', 'error');
                })
                .finally(() => {
                    submitBtn.textContent = originalText;
                    submitBtn.disabled = false;
                });
                */
            });
        }

        function showHeroMessage(message, type) {
            heroFormMessage.textContent = message;
            heroFormMessage.style.display = 'block';
            heroFormMessage.style.padding = '12px';
            heroFormMessage.style.borderRadius = '8px';
            heroFormMessage.style.marginTop = '1rem';
            heroFormMessage.style.fontSize = '14px';
            heroFormMessage.style.fontWeight = '500';

            if (type === 'success') {
                heroFormMessage.style.background = '#d4edda';
                heroFormMessage.style.color = '#155724';
                heroFormMessage.style.border = '1px solid #c3e6cb';
            } else {
                heroFormMessage.style.background = '#f8d7da';
                heroFormMessage.style.color = '#721c24';
                heroFormMessage.style.border = '1px solid #f5c6cb';
            }

            // Hide message after 6 seconds
            setTimeout(() => {
                heroFormMessage.style.display = 'none';
            }, 6000);
        }

        function isValidEmail(email) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(email);
        }

        // Add smooth animations for form fields
        const heroFormInputs = document.querySelectorAll('#heroContactForm input, #heroContactForm select');
        heroFormInputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.style.transform = 'translateY(-1px)';
                this.style.boxShadow = '0 4px 12px rgba(30, 78, 100, 0.15)';
            });

            input.addEventListener('blur', function() {
                this.style.transform = 'translateY(0)';
                this.style.boxShadow = 'none';
            });
        });
    });



    // Start Program card

    document.querySelectorAll(".program-card").forEach(card => {
        let bg = card.getAttribute("data-bg");
        card.style.setProperty("--bg-image", `url(${bg})`);

        card.addEventListener("mousemove", e => {
            const rect = card.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;
            const centerX = rect.width / 2;
            const centerY = rect.height / 2;

            const rotateX = ((y - centerY) / centerY) * 10;
            const rotateY = ((x - centerX) / centerX) * -10;

            card.style.transform = `rotateX(${rotateX}deg) rotateY(${rotateY}deg) scale(1.05)`;
        });

        card.addEventListener("mouseleave", () => {
            card.style.transform = "rotateX(0) rotateY(0) scale(1)";
        });
    });

    //   End Program card

    // Notification Modal functionality
    const notificationModal = $('#notificationModal');
    const notificationModalTrigger = $('#notificationModalTrigger');
    const closeNotificationModal = $('#closeNotificationModal');
    const filterTabs = $('.filter-tab');
    const notificationItems = $('.notification-item');

    // Store original body overflow for notification modal
    let notificationBodyOverflow = $('body').css('overflow');

    // Cookie helper functions
    function setCookie(name, value, days) {
        let expires = "";
        if (days) {
            const date = new Date();
            date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
            expires = "; expires=" + date.toUTCString();
        }
        document.cookie = name + "=" + (value || "") + expires + "; path=/";
    }

    function getCookie(name) {
        const nameEQ = name + "=";
        const ca = document.cookie.split(';');
        for (let i = 0; i < ca.length; i++) {
            let c = ca[i];
            while (c.charAt(0) === ' ') c = c.substring(1, c.length);
            if (c.indexOf(nameEQ) === 0) return c.substring(nameEQ.length, c.length);
        }
        return null;
    }

    function deleteCookie(name) {
        document.cookie = name + "=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
    }

    // Save notification states to cookie
    function saveNotificationStates() {
        const states = {};
        notificationItems.each(function() {
            const notificationId = $(this).data('id');
            const status = $(this).find('.notification-status');
            const isRead = status.hasClass('read');
            if (notificationId) {
                states[notificationId] = isRead;
            }
        });
        setCookie('notificationStates', JSON.stringify(states), 30); // Save for 30 days
        console.log('Notification states saved:', states);
    }

    // Load notification states from cookie
    function loadNotificationStates() {
        const savedStates = getCookie('notificationStates');
        if (savedStates) {
            try {
                const states = JSON.parse(savedStates);
                console.log('Loading notification states:', states);

                // First, reset all notifications to unread
                notificationItems.each(function() {
                    const status = $(this).find('.notification-status');
                    status.removeClass('read').addClass('unread');
                });

                // Then apply saved states
                notificationItems.each(function() {
                    const notificationId = $(this).data('id');
                    const status = $(this).find('.notification-status');

                    if (notificationId && states.hasOwnProperty(notificationId)) {
                        if (states[notificationId] === true) {
                            status.removeClass('unread').addClass('read');
                        } else {
                            status.removeClass('read').addClass('unread');
                        }
                    }
                });
            } catch (e) {
                console.log('Error loading notification states:', e);
                // Clear corrupted cookie
                deleteCookie('notificationStates');
            }
        }
    }

    // Open notification modal
    notificationModalTrigger.on('click', function(e) {
        e.preventDefault();
        notificationBodyOverflow = $('body').css('overflow');
        notificationModal.addClass('active');
        $('body').css('overflow', 'hidden');
    });

    // Close notification modal
    closeNotificationModal.on('click', function() {
        closeNotificationModalFunc();
    });

    // Close notification modal when clicking overlay
    $('.notification-modal-overlay').on('click', function() {
        closeNotificationModalFunc();
    });

    // Close notification modal with Escape key
    $(document).on('keydown', function(e) {
        if (e.key === 'Escape' && notificationModal.hasClass('active')) {
            closeNotificationModalFunc();
        }
    });

    function closeNotificationModalFunc() {
        notificationModal.removeClass('active');
        $('body').css('overflow', notificationBodyOverflow);
    }

    // Filter functionality
    filterTabs.on('click', function() {
        const selectedCategory = $(this).data('category');

        // Update active tab
        filterTabs.removeClass('active');
        $(this).addClass('active');

        // Filter notifications
        notificationItems.each(function() {
            const itemCategory = $(this).data('category');

            if (selectedCategory === 'all') {
                $(this).show();
            } else {
                if (itemCategory === selectedCategory) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            }
        });
    });

    // Mark notification as read when clicked
    notificationItems.on('click', function() {
        const status = $(this).find('.notification-status');
        if (status.hasClass('unread')) {
            status.removeClass('unread').addClass('read');

            // Save notification states to cookie
            saveNotificationStates();

            // Update notification badge count
            updateNotificationBadge();
        }
    });

    function updateNotificationBadge() {
        const unreadCount = $('.notification-status.unread').length;
        const badge = $('.notification-badge');

        if (unreadCount > 0) {
            badge.text(unreadCount).show();
        } else {
            badge.hide();
        }

        // Update filter badges
        updateFilterBadges();
    }

    function updateFilterBadges() {
        // Count unread notifications for each category
        const categories = ['all'];

        // Collect all unique categories from filter tabs
        filterTabs.each(function() {
            const category = $(this).data('category');
            if (category !== 'all' && !categories.includes(category)) {
                categories.push(category);
            }
        });

        categories.forEach(function(category) {
            let count = 0;

            if (category === 'all') {
                // For "All Notifications", count all unread notifications
                count = $('.notification-status.unread').length;
            } else {
                // For specific categories, count notifications that match this category and are unread
                $('.notification-item').each(function() {
                    const itemCategory = $(this).data('category');
                    const status = $(this).find('.notification-status');

                    if (itemCategory === category && status.hasClass('unread')) {
                        count++;
                    }
                });
            }

            const filterBadge = $('#badge-' + category);
            if (count > 0) {
                filterBadge.text(count).show();
            } else {
                filterBadge.hide();
            }
        });
    }

    // Initialize notification system
    function initializeNotifications() {
        // Load saved notification states from cookies
        loadNotificationStates();

        // Initialize notification badge count and filter badges
        updateNotificationBadge();

        // Add debug function to window for manual cookie clearing
        window.clearNotificationCookies = function() {
            deleteCookie('notificationStates');
            console.log('Notification cookies cleared. Refreshing...');
            location.reload();
        };

        console.log('Notification system initialized. Use clearNotificationCookies() to reset states.');
    }

    // Initialize notifications when page loads
    initializeNotifications();
    </script>

    <!-- Testimonials Slider Video Play Detection -->
    <script>
    $(document).ready(function() {
        // Initialize testimonials sliders
        $('.td_slider .td_slider_container').each(function() {
            const $slider = $(this);

            // Initialize Slick slider if not already initialized
            if (!$slider.hasClass('slick-initialized')) {
                $slider.slick({
                    autoplay: true,
                    autoplaySpeed: 800,
                    pauseOnHover: false,
                    pauseOnFocus: false,
                    arrows: true,
                    dots: false,
                    infinite: true,
                    slidesToShow: 1,
                    slidesToScroll: 1
                });
            }
        });

        // Function to pause slider autoplay
        function pauseSliderAutoplay($slider) {
            if ($slider.hasClass('slick-initialized')) {
                $slider.slick('slickPause');
            }
        }

        // Function to resume slider autoplay
        function resumeSliderAutoplay($slider) {
            if ($slider.hasClass('slick-initialized')) {
                $slider.slick('slickPlay');
            }
        }

        // Handle HTML5 video events
        $('.td_slider video').each(function() {
            const $video = $(this);
            const $slider = $video.closest('.td_slider_container');

            $video.on('play', function() {
                pauseSliderAutoplay($slider);
            });

            $video.on('pause ended', function() {
                // Resume autoplay after a short delay to prevent immediate restart
                setTimeout(function() {
                    resumeSliderAutoplay($slider);
                }, 1000);
            });
        });

        // Handle YouTube iframe videos
        // Load YouTube API if there are YouTube videos
        if ($('.td_slider iframe[src*="youtube.com"]').length > 0) {
            // Load YouTube IFrame API
            if (!window.YT) {
                const tag = document.createElement('script');
                tag.src = 'https://www.youtube.com/iframe_api';
                const firstScriptTag = document.getElementsByTagName('script')[0];
                firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
            }

            // YouTube API ready callback
            window.onYouTubeIframeAPIReady = function() {
                $('.td_slider iframe[src*="youtube.com"]').each(function() {
                    const $iframe = $(this);
                    const $slider = $iframe.closest('.td_slider_container');
                    let videoId = '';
                    const src = $iframe.attr('src');
                    const match = src.match(/[?&]v=([^#\&\?]*)/);
                    if (match) {
                        videoId = match[1];
                    } else {
                        const embedMatch = src.match(/embed\/([^#\&\?]*)/);
                        if (embedMatch) {
                            videoId = embedMatch[1];
                        }
                    }

                    if (!videoId) return; // Skip if no ID found

                    // Create YouTube player
                    const player = new YT.Player($iframe[0], {
                        events: {
                            'onStateChange': function(event) {
                                if (event.data === YT.PlayerState.PLAYING) {
                                    pauseSliderAutoplay($slider);
                                } else if (event.data === YT.PlayerState.PAUSED ||
                                    event.data === YT.PlayerState.ENDED) {
                                    // Resume autoplay after a short delay
                                    setTimeout(function() {
                                        resumeSliderAutoplay($slider);
                                    }, 1000);
                                }
                            }
                        }
                    });
                });
            };
        }
    });
    </script>
    <!--Start of Tawk.to Script-->
    <!-- <script type="text/javascript">
var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
(function(){
var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
s1.async=true;
s1.src='https://embed.tawk.to/692576422913d51960c69513/1jat5btqn';
s1.charset='UTF-8';
s1.setAttribute('crossorigin','*');
s0.parentNode.insertBefore(s1,s0);
})();
</script> -->
    <!--End of Tawk.to Script-->
    <?php include_once 'add_scholarship_modal.php'; ?>
</body>

<script>
function toggleText(el) {
    const truncated = el.querySelector('.truncated');
    const full = el.querySelector('.full');
    if (full.style.display === 'none') {
        truncated.style.display = 'none';
        full.style.display = 'inline';
    } else {
        truncated.style.display = 'inline';
        full.style.display = 'none';
    }
}
</script>
<script src="assets/js/jquery-3.7.1.min.js"></script>
<script src="assets/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/jquery.slick.min.js"></script>
<script>
$(document).ready(function() {
    $('.nimcet-slider .slider').slick({
        slidesToShow: 3,
        slidesToScroll: 1,
        arrows: true,
        dots: true,
        responsive: [{
                breakpoint: 992,
                settings: {
                    slidesToShow: 2
                }
            },
            {
                breakpoint: 600,
                settings: {
                    slidesToShow: 1
                }
            }
        ]
    });
});
</script>

<!-- Video Player Professional Styles & JavaScript -->
<style>
/* Professional Video Player Styles */
.td_video_thumb {
    position: relative;
    overflow: hidden;
    border-radius: 12px;
    box-shadow: 0 4px 24px rgba(0, 0, 0, 0.10);
    background: #181818;
}

.video-thumbnail {
    border-radius: 12px;
    transition: filter 0.3s;
    filter: brightness(0.95);
}

.td_video_play_btn.td_center.play-video-btn {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background: rgba(0, 0, 0, 0.7);
    border: none;
    border-radius: 50%;
    width: 64px;
    height: 64px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 2rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
    transition: background 0.2s, transform 0.2s;
    z-index: 2;
    cursor: pointer;
}

.td_video_play_btn.td_center.play-video-btn:hover {
    background: #e50914;
    transform: translate(-50%, -50%) scale(1.08);
}

.video-player-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(18, 18, 18, 0.92);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 10;
    border-radius: 12px;
    transition: opacity 0.3s;
}

/* Fix for Free Services Links */
.td_iconbox {
    position: relative !important;
}

.td_iconbox_link {
    position: absolute !important;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 100 !important;
    display: block !important;
    cursor: pointer;
    background: rgba(255, 255, 255, 0);
    /* Transparent but clickable */
}

.video-player-container {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    height: 100%;
    min-height: 220px;
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    z-index: 11;
    background: transparent;
}

.video-player-container video {
    max-width: 92%;
    max-height: 340px;
    border-radius: 10px;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.25);
    background: #000;
}

.close-video-btn {
    position: absolute;
    top: 18px;
    right: 22px;
    background: rgba(0, 0, 0, 0.7);
    color: #fff;
    border: none;
    border-radius: 50%;
    width: 38px;
    height: 38px;
    font-size: 1.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    z-index: 20;
    transition: background 0.2s;
}

.close-video-btn:hover {
    background: #e50914;
    color: #fff;
}

@media (max-width: 600px) {
    .video-player-container video {
        max-width: 100%;
        max-height: 180px;
    }

    .td_video_thumb {
        border-radius: 8px;
    }
}
</style>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle video play buttons
    const playButtons = document.querySelectorAll('.play-video-btn');
    playButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const videoThumb = this.closest('.td_video_thumb');
            const thumbnail = videoThumb.querySelector('.video-thumbnail');
            const videoContainer = videoThumb.querySelector('.video-player-container');
            const overlay = videoThumb.querySelector('.video-player-overlay');
            // Hide thumbnail and play button, show overlay and video player
            thumbnail.style.display = 'none';
            this.style.display = 'none';
            if (overlay) overlay.style.display = 'flex';
            videoContainer.style.display = 'flex';
            // Auto-play the video
            const video = videoContainer.querySelector('video');
            video.play();
        });
    });
    // Handle close video buttons
    const closeButtons = document.querySelectorAll('.close-video-btn');
    closeButtons.forEach(button => {
        button.addEventListener('click', function() {
            const videoThumb = this.closest('.td_video_thumb');
            const thumbnail = videoThumb.querySelector('.video-thumbnail');
            const videoContainer = videoThumb.querySelector('.video-player-container');
            const overlay = videoThumb.querySelector('.video-player-overlay');
            const playBtn = videoThumb.querySelector('.play-video-btn');
            // Stop video and hide player, show thumbnail and play button
            const video = videoContainer.querySelector('video');
            video.pause();
            video.currentTime = 0;
            videoContainer.style.display = 'none';
            if (overlay) overlay.style.display = 'none';
            thumbnail.style.display = 'block';
            playBtn.style.display = 'flex';
        });
    });
});
</script>

<script>
// Professional Form Handling Script
document.addEventListener('DOMContentLoaded', function() {
    const proHeroForm = document.getElementById('proHeroForm');
    const proFormMessage = document.getElementById('hero-form-message-pro');

    if (proHeroForm) {
        proHeroForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(proHeroForm);
            const data = {
                student_name: formData.get('student_name'),
                student_email: formData.get('student_email'),
                student_mobile: formData.get('student_mobile'),
                course_interest: formData.get('course_interest'),
                enquiry: formData.get('enquiry')
            };

            // Basic validation
            if (!data.student_name || !data.student_email || !data.student_mobile || !data
                .course_interest) {
                showProMessage('Please fill in all required fields.', 'error');
                return;
            }

            // Show loading state
            const submitBtn = proHeroForm.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML =
                '<span>Sending...</span> <div class="spinner-border spinner-border-sm ms-2" role="status"></div>';
            submitBtn.disabled = true;

            // Send to backend
            fetch('submit_form.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(data)
                })
                .then(response => response.json())
                .then(result => {
                    if (result.success) {
                        showProMessage(result.message, 'success');
                        proHeroForm.reset();
                    } else {
                        showProMessage(result.message || 'Error occurred.', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showProMessage('Something went wrong. Please try again.', 'error');
                })
                .finally(() => {
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                });
        });
    }

    function showProMessage(message, type) {
        proFormMessage.textContent = message;
        proFormMessage.style.display = 'block';
        proFormMessage.className = type === 'success' ? 'alert alert-success mt-3' : 'alert alert-danger mt-3';

        setTimeout(() => {
            proFormMessage.style.display = 'none';
        }, 5000);
    }
});
</script>

</html>