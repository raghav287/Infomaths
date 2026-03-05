 <?php
// Gemini API PHP proxy logic
// if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['gemini_proxy'])) {
//     header('Content-Type: application/json');
//     $apiKey = 'AIzaSyBj4wEqfQlJqtmnFRCA8clTS9u10SRsiZk'; 
//     $model = 'gemini-2.5-flash'; 

//     $input = json_decode(file_get_contents('php://input'), true);
//     $contents = $input['contents'] ?? '';

//     if (!$contents) {
//         http_response_code(400);
//         echo json_encode(['error' => 'Missing contents']);
//         exit;
//     }

//     $url = "https://generativelanguage.googleapis.com/v1/models/$model:generateContent?key=$apiKey";

//     $body = json_encode([
//         'contents' => [
//             ['parts' => [['text' => $contents]]]
//         ]
//     ]);

//     $ch = curl_init($url);
//     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//     curl_setopt($ch, CURLOPT_POST, true);
//     curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
//     curl_setopt($ch, CURLOPT_POSTFIELDS, $body);

//     $response = curl_exec($ch);
//     $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
//     curl_close($ch);

//     http_response_code($httpcode);
//     echo $response;
//     exit;
// }

// Include database connection and fetch active courses
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
     <link rel="stylesheet" type="text/css" href="assets/css/slick-theme.min.css" />
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/owl-carousel/1.3.3/owl.carousel.min.css" />
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/owl-carousel/1.3.3/owl.theme.min.css" />
     <link rel="stylesheet" href="assets/css/style.css" />
     <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css" />
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
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
     .td_slider .slick-arrow {
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
     }

     .td_slider .slick-arrow:hover {
         background: #1e4e64;
         color: #fff;
         transform: translateY(-50%) scale(1.1);
     }

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
         padding: 30px 20px;
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
         padding: 2rem !important;
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

     /* SECTION SPACING */
     /* .result-img {
         width: 100%;
         height: 444px; */
     /* Reduced height */
     /* object-fit: cover;
         object-position: center;
     } */

     /* Tablet */
     /* @media (max-width: 991px) {
         .result-img {
             height: 240px;
         }
     } */

     /* Mobile */
     /* @media (max-width: 576px) {
         .result-img {
             height: 180px;
         }
     } */

     .result-img {
         width: 100%;
         aspect-ratio: 3 / 1;
         /* Keeps same slider proportion */
         object-fit: cover;
         object-position: center;
         display: block;
     }

     /* Make sure slider doesn't force height */
     .td_slider_container,
     .td_slider_wrapper,
     .td_slide {
         height: auto !important;
     }

     /* Make slide take full width */
     </style>
 </head>

 <body>
     <!-- Start Preloader -->

     <!-- End Preloader -->
     <?php include 'includes/header.php'; ?>


     <!-- Start Hero Section -->

     <section class="td_hero td_heading_bg td_center td_bg_filed" style="min-height: 700px; padding-top: 100px;">
         <div class="swiper-container hero-swiper">
             <div class="swiper-wrapper">
                 <?php foreach ($heroSlides as $index => $slide): ?>
                 <!-- Slide <?php echo $index + 1; ?> -->
                 <div class="swiper-slide"
                     style="position:relative; background-image: url('<?php echo htmlspecialchars($slide['background_image'] ?: 'assets/img/cgc_banner.webp'); ?>'); min-height: 700px; background-size: cover; background-position: center;">
                     <div
                         style="position:absolute;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.60);z-index:1;">
                     </div>
                     <div class="container pt-5" style="position:relative;z-index:2;">
                         <div class="row align-items-center">
                             <style>
                             @media (max-width: 768px) {
                                 .container.pt-5 {
                                     padding-top: 13em !important;
                                 }

                                 .hero-contact-form {
                                     margin-top: 2rem !important;
                                     margin-bottom: 6rem !important;
                                     max-width: 100% !important;
                                 }
                             }
                             </style>
                             <!-- Left Side - Hero Text -->
                             <div class="col-lg-6 d-flex align-items-center justify-content-center"
                                 style="min-height: 700px;">
                                 <div class="td_hero_text wow fadeInRight" data-wow-duration="0.9s"
                                     data-wow-delay="0.35s" style="width:100%; text-align:left;">
                                     <h1 class="td_hero_title td_white_color td_mb_12" style="line-height:0.2em;">
                                         <span class="td_fs_24"><?php echo htmlspecialchars($slide['title']); ?></span>
                                     </h1>
                                     <p class="td_hero_subtitle td_fs_18 td_white_color td_mb_30">
                                         <?php echo nl2br(htmlspecialchars($slide['subtitle'])); ?>
                                     </p>
                                     <a href="<?php echo htmlspecialchars($slide['button1_link'] ?: '#'); ?>"
                                         class="td_btn td_style_1 td_radius_10 td_medium">
                                         <span class="td_btn_in td_white_color td_accent_bg">
                                             <span><?php echo htmlspecialchars($slide['button1_text'] ?: 'Apply Now'); ?></span>
                                             <svg width="19" height="20" viewBox="0 0 19 20" fill="none"
                                                 xmlns="http://www.w3.org/2000/svg">
                                                 <path d="M15.1575 4.34302L3.84375 15.6567" stroke="currentColor"
                                                     stroke-width="1.5" stroke-linecap="round"
                                                     stroke-linejoin="round" />
                                                 <path
                                                     d="M15.157 11.4142C15.157 11.4142 16.0887 5.2748 15.157 4.34311C14.2253 3.41142 8.08594 4.34314 8.08594 4.34314"
                                                     stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                                     stroke-linejoin="round" />
                                             </svg>
                                         </span>
                                     </a>
                                 </div>
                             </div>
                             <!-- Right Side - Contact Form -->
                             <div class="col-lg-6">
                                 <div class="hero-contact-form wow fadeInLeft" data-wow-duration="0.9s"
                                     data-wow-delay="0.4s"
                                     style="background: rgba(255, 255, 255, 0.95); padding: 1.2rem; border-radius: 12px; box-shadow: 0 12px 25px rgba(0,0,0,0.12); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.2); max-width: 400px; margin-left: auto;">
                                     <form action="#" method="POST" class="hero-contact-form-inner unified-contact-form"
                                         id="heroContactForm<?php echo $index + 1; ?>">
                                         <div class="form-row" style="display: flex; gap: 10px; margin-bottom: 0.6rem;">
                                             <div class="form-group" style="flex: 1; margin-bottom: 0;">
                                                 <input type="text" name="student_name" required
                                                     style="width: 100%; padding: 8px 10px; border: 2px solid #e9ecef; border-radius: 5px; font-size: 12px; transition: all 0.3s ease; background: #f8f9fa;"
                                                     placeholder="Enter Name *"
                                                     onfocus="this.style.borderColor='#1e4e64'; this.style.background='white';"
                                                     onblur="this.style.borderColor='#e9ecef'; this.style.background='#f8f9fa';">
                                             </div>
                                             <div class="form-group" style="flex: 1; margin-bottom: 0;">
                                                 <input type="email" name="student_email" required
                                                     style="width: 100%; padding: 8px 10px; border: 2px solid #e9ecef; border-radius: 5px; font-size: 12px; transition: all 0.3s ease; background: #f8f9fa;"
                                                     placeholder="Enter Email Address *"
                                                     onfocus="this.style.borderColor='#1e4e64'; this.style.background='white';"
                                                     onblur="this.style.borderColor='#e9ecef'; this.style.background='#f8f9fa';">
                                             </div>
                                         </div>
                                         <div class="form-group" style="margin-bottom: 0.6rem;">
                                             <div style="display: flex;">
                                                 <select
                                                     style="padding: 8px 6px; border: 2px solid #e9ecef; border-radius: 5px 0 0 5px; background: #f8f9fa; font-size: 12px; border-right: none; color: #666;">
                                                     <option value="+91">+91</option>
                                                 </select>
                                                 <input type="tel" name="student_mobile" required
                                                     style="flex: 1; padding: 8px 10px; border: 2px solid #e9ecef; border-radius: 0 5px 5px 0; font-size: 12px; transition: all 0.3s ease; background: #f8f9fa;"
                                                     placeholder="Enter Mobile Number *"
                                                     onfocus="this.style.borderColor='#1e4e64'; this.style.background='white'; this.previousElementSibling.style.borderColor='#1e4e64'; this.previousElementSibling.style.background='white';"
                                                     onblur="this.style.borderColor='#e9ecef'; this.style.background='#f8f9fa'; this.previousElementSibling.style.borderColor='#e9ecef'; this.previousElementSibling.style.background='#f8f9fa';">
                                             </div>
                                         </div>
                                         <div class="row" style="margin: 0 -3px;">
                                             <div class="col-6" style="padding: 0 3px;">
                                                 <div class="form-group" style="margin-bottom: 0.6rem;"></div>
                                             </div>
                                             <div class="col-6" style="padding: 0 3px;">
                                                 <div class="form-group" style="margin-bottom: 0.6rem;"></div>
                                             </div>
                                         </div>
                                         <div class="form-group" style="margin-bottom: 0.6rem;">
                                             <select name="course_interest" required
                                                 style="width: 100%; padding: 8px 10px; border: 2px solid #e9ecef; border-radius: 5px; font-size: 12px; transition: all 0.3s ease; background: #f8f9fa; color: #666;"
                                                 onfocus="this.style.borderColor='#1e4e64'; this.style.background='white';"
                                                 onblur="this.style.borderColor='#e9ecef'; this.style.background='#f8f9fa';">
                                                 <option value="">Select Course *</option>
                                                 <?php
                                                    foreach ($courses as $course) {
                                                        if ($course['is_active']) {
                                                            echo '<option value="' . htmlspecialchars($course['course_name']) . '">' . htmlspecialchars($course['course_name']) . '</option>';
                                                        }
                                                    }
                                                    ?>
                                             </select>
                                         </div>
                                         <div class="form-group" style="margin-bottom: 0.6rem;">
                                             <textarea name="enquiry" rows="2"
                                                 style="width: 100%; padding: 8px 10px; border: 2px solid #e9ecef; border-radius: 5px; font-size: 12px; transition: all 0.3s ease; background: #f8f9fa; resize: vertical; min-height: 50px;"
                                                 placeholder="Type Your Enquiry Here"
                                                 onfocus="this.style.borderColor='#1e4e64'; this.style.background='white';"
                                                 onblur="this.style.borderColor='#e9ecef'; this.style.background='#f8f9fa';"></textarea>
                                         </div>
                                         <div style="text-align: center;">
                                             <button type="submit"
                                                 style="background: #09226c !important; color: white; padding: 10px 25px; border: none; border-radius: 20px; font-size: 13px; font-weight: 600; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 3px 10px rgba(220, 53, 69, 0.3); width: 100%;"
                                                 onmouseover="this.style.transform='translateY(-1px)'; this.style.boxShadow='0 5px 14px rgba(220, 53, 69, 0.4)';"
                                                 onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 3px 10px rgba(220, 53, 69, 0.3)';">Submit</button>
                                         </div>
                                     </form>
                                     <div id="hero-form-message<?php echo $index + 1; ?>"
                                         style="margin-top: 1rem; text-align: center; display: none;"></div>
                                 </div>
                             </div>
                         </div>
                     </div>
                 </div>
                 <?php endforeach; ?>
             </div>
             <!-- Swiper navigation -->
             <div class="swiper-button-next" style="background:none; color:#09226c"></div>
             <div class="swiper-button-prev" style="background:none; color:#09226c"> </div>
         </div>
     </section>
     <div class="container">
         <!-- Start Category Cards Section -->

     </div>
     <!-- End Hero Section -->
     <section class="category-cards-section py-5" style="background-color: #f8f9fa;">
         <div class="container">
             <h2 class="text-center mb-4" style="font-weight:700;font-size:2.5rem;color:#2c3e50;">
                 What are <span style="color:#09226c">you looking for?</span>
             </h2>

             <p class="text-center mb-5" style="font-size:1.2rem;color:#7f8c8d;max-width:600px;margin:0 auto;">
                 Prepare for MCA, PU, IIT-JAM, Campus Placement, BBA and more.
                 Achieve your career goals and make your parents proud.
             </p>
             <style>
             .btn-outline-primary {
                 color: #09226c;
                 border-color: #09226c;
             }

             .btn-outline-primary:hover {
                 background-color: #09226c;
                 border-color: #09226c;
                 color: #fff;
             }

             .btn-primary {
                 background-color: #09226c;
                 border-color: #09226c;
                 color: #fff;
             }

             .btn-primary:hover {
                 background-color: #09226c;
                 border-color: #09226c;
                 color: #fff;
             }
             </style>

             <div class="row justify-content-center g-4">

                 <!-- CARD 1 -->
                 <div class="col-12 col-md-6 col-lg-4">
                     <div class="category-card-professional text-center bg-white border rounded shadow-sm h-100">

                         <div class="card-image">
                             <img src="assets/img/home_1/8.jpeg" alt="MCA Entrance">
                         </div>

                         <div class="p-4">
                             <h5 class="fw-bold mb-2 text-dark">MCA Entrance</h5>
                             <!-- <p class="small mb-3">Exam Preparation</p> -->

                             <div class="d-flex justify-content-center gap-2">
                                 <a href="offline.php" class="btn btn-outline-primary btn-sm">Offline</a>
                                 <a href="online.php" class="btn btn-primary btn-sm">Online</a>
                             </div>
                         </div>
                     </div>
                 </div>

                 <!-- CARD 2 -->
                 <div class="col-12 col-md-6 col-lg-4">
                     <div class="category-card-professional text-center bg-white border rounded shadow-sm h-100">

                         <div class="card-image">
                             <img src="assets/img/home_1/7.jpeg" alt="PU / Clerical">
                         </div>

                         <div class="p-4">
                             <h5 class="fw-bold mb-2 text-dark">PO/Clerical/SSC</h5>
                             <!-- <p class="small mb-3">Govt. Exams</p> -->

                             <div class="d-flex justify-content-center gap-2">
                                 <a href="offline.php" class="btn btn-outline-primary btn-sm">Offline</a>
                                 <a href="online.php" class="btn btn-primary btn-sm">Online</a>
                             </div>
                         </div>
                     </div>
                 </div>

                 <!-- CARD 3 -->
                 <div class="col-12 col-md-6 col-lg-4">
                     <div class="category-card-professional text-center bg-white border rounded shadow-sm h-100">

                         <div class="card-image">
                             <img src="assets/img/home_1/2.jpeg" alt="IIT JAM">
                         </div>

                         <div class="p-4">
                             <h5 class="fw-bold mb-2 text-dark">IIT - JAM</h5>
                             <!-- <p class="small mb-3">UGC-NET</p> -->

                             <div class="d-flex justify-content-center gap-2">
                                 <a href="offline.php" class="btn btn-outline-primary btn-sm">Offline</a>
                                 <a href="online.php" class="btn btn-primary btn-sm">Online</a>
                             </div>
                         </div>
                     </div>
                 </div>

                 <!-- CARD 4 -->
                 <div class="col-12 col-md-6 col-lg-4">
                     <div class="category-card-professional text-center bg-white border rounded shadow-sm h-100">

                         <div class="card-image">
                             <img src="assets/img/home_1/4.jpeg" alt="Campus Placement">
                         </div>

                         <div class="p-4">
                             <h5 class="fw-bold mb-2 text-dark">Campus Placement</h5>

                             <div class="d-flex justify-content-center gap-2 mt-3">
                                 <a href="offline.php" class="btn btn-outline-primary btn-sm">Offline</a>
                                 <a href="online.php" class="btn btn-primary btn-sm">Online</a>
                             </div>
                         </div>
                     </div>
                 </div>

                 <!-- CARD 5 -->
                 <div class="col-12 col-md-6 col-lg-4">
                     <div class="category-card-professional text-center bg-white border rounded shadow-sm h-100">

                         <div class="card-image">
                             <img src="assets/img/home_1/5.jpeg" alt="BCA - BSC">
                         </div>

                         <div class="p-4">
                             <h5 class="fw-bold mb-2 text-dark">Internship/Skill Dev</h5>
                             <!-- <p class="small mb-3">Internship Training </p> -->

                             <div class="d-flex justify-content-center gap-2">
                                 <a href="offline.php" class="btn btn-outline-primary btn-sm">Offline</a>
                                 <a href="online.php" class="btn btn-primary btn-sm">Online</a>
                             </div>
                         </div>

                     </div>
                 </div>

                 <!-- CARD 6 -->
                 <div class="col-12 col-md-6 col-lg-4">
                     <div class="category-card-professional text-center bg-white border rounded shadow-sm h-100">

                         <div class="card-image">
                             <img src="assets/img/home_1/6.jpeg" alt="BCA - BSC">
                         </div>

                         <div class="p-4">
                             <h5 class="fw-bold mb-2 text-dark">BCA - BSC</h5>
                             <!-- <p class="small mb-3">College Classes</p> -->

                             <div class="d-flex justify-content-center gap-2">
                                 <a href="offline.php" class="btn btn-outline-primary btn-sm">Offline</a>
                                 <a href="online.php" class="btn btn-primary btn-sm">Online</a>
                             </div>
                         </div>

                     </div>
                 </div>

             </div>
         </div>

         <!-- ADDITIONAL CSS (without changing your existing CSS) -->
         <style>
         .card-image {
             width: 100%;
             height: 200px;
             overflow: hidden;
             border-top-left-radius: 8px;
             border-top-right-radius: 8px;
         }

         .card-image img {
             width: 100%;
             height: 100%;
             object-fit: cover;
         }

         @media (max-width: 768px) {
             .card-image {
                 height: 180px;
             }
         }
         </style>

         <div class="td_height_25 td_height_lg_75"></div>
     </section>


     <!-- <section class="category-cards-section py-5" style="background-color: #f8f9fa;">
         <div class="container-fluid">
             <h2 class="text-center mb-4" style="font-weight:700;font-size:2.5rem;color:#2c3e50;">What are <span
                     style="color:#09226c">you looking for?</span></h2>
             <p class="text-center mb-5" style="font-size:1.2rem;color:#7f8c8d;max-width:600px;margin:0 auto;">Prepare
                 for MCA, PU, IIT-JAM, Campus Placement, BBA and more. Achieve your career goals and make your parents
                 proud.</p>
             <div class="row justify-content-center g-4">
                  Card 1: MCA -->
     <!-- <div class="col-12 col-md-6 col-lg-4 col-xl-2">
         <div class="category-card-professional text-center p-4 bg-white border rounded shadow-sm h-100">
             <div class="card-icon mb-3">
                 <img src="assets/img/mca.png" alt="MCA" style="height:50px;width:50px;object-fit:contain;">
             </div>
             <h5 class="fw-bold mb-2 text-dark">MCA Entrance</h5>
             <p class=" small mb-3"> Exam Preparation</p> -->
     <!-- <style>
             .btn-outline-primary {
                 color: #09226c;
                 border-color: #09226c;
             }

             .btn-outline-primary:hover {
                 background-color: #09226c;
                 border-color: #09226c;
                 color: #fff;
             }

             .btn-primary {
                 background-color: #09226c;
                 border-color: #09226c;
                 color: #fff;
             }

             .btn-primary:hover {
                 background-color: #09226c;
                 border-color: #09226c;
                 color: #fff;
             }
             </style> -->
     <!-- <div class="d-flex justify-content-center gap-2">
                 <a href="offline.php" class="btn btn-outline-primary btn-sm">Offline</a>
                 <a href="online.php" class="btn btn-primary btn-sm">Online</a>
             </div>
         </div>
     </div> -->
     <!-- Card 2: PU / Clerical -->
     <!-- <div class="col-12 col-md-6 col-lg-4 col-xl-2">
         <div class="category-card-professional text-center p-4 bg-white border rounded shadow-sm h-100">
             <div class="card-icon mb-3">
                 <img src="assets/img/icons/pu.png" alt="PU / Clerical"
                     style="height:50px;width:50px;object-fit:contain;">
             </div>
             <h5 class="fw-bold mb-2 text-dark">PU / Clerical</h5>
             <p class=" small mb-3"> Govt. Exams</p>
             <div class="d-flex justify-content-center gap-2">
                 <a href="offline.php" class="btn btn-outline-primary btn-sm">Offline</a>
                 <a href="online.php" class="btn btn-primary btn-sm">Online</a>
             </div>
         </div>
     </div> -->
     <!-- Card 3: IIT - JAM -->
     <!-- <div class="col-12 col-md-6 col-lg-4 col-xl-2">
         <div class="category-card-professional text-center p-4 bg-white border rounded shadow-sm h-100">
             <div class="card-icon mb-3">
                 <img src="assets/img/icons/iit-jam.png" alt="IIT - JAM"
                     style="height:50px;width:50px;object-fit:contain;">
             </div>
             <h5 class="fw-bold mb-2 text-dark">IIT - JAM</h5>
             <p class=" small mb-3"> UGC-NET</p>
             <div class="d-flex justify-content-center gap-2">
                 <a href="offline.php" class="btn btn-outline-primary btn-sm">Offline</a>
                 <a href="online.php" class="btn btn-primary btn-sm">Online</a>
             </div>
         </div>
     </div> -->
     <!-- Card 4: Campus Placement -->
     <!-- <div class="col-12 col-md-6 col-lg-4 col-xl-2">
         <div class="category-card-professional text-center p-4 bg-white border rounded shadow-sm h-100">
             <div class="card-icon mb-3">
                 <img src="assets/img/icons/placement.png" alt="Campus Placement"
                     style="height:50px;width:50px;object-fit:contain;">
             </div>
             <h5 class="fw-bold mb-4 text-dark">Campus Placement</h5>
             <div class="d-flex justify-content-center gap-2">
                 <a href="offline.php" class="btn btn-outline-primary btn-sm">Offline</a>
                 <a href="online.php" class="btn btn-primary btn-sm">Online</a>
             </div>
         </div>
     </div> -->
     <!-- Card 5: BCA - BSC -->
     <!-- <div class="col-12 col-md-6 col-lg-4 col-xl-2">
         <div class="category-card-professional text-center p-4 bg-white border rounded shadow-sm h-100">
             <div class="card-icon mb-3">
                 <img src="assets/img/icons/bca.png" alt="BCA - BSC" style="height:50px;width:50px;object-fit:contain;">
             </div>
             <h5 class="fw-bold mb-2 text-dark">BCA - BSC</h5>
             <p class=" small mb-3"> College Classes</p>
             <div class="d-flex justify-content-center gap-2">
                 <a href="offline.php" class="btn btn-outline-primary btn-sm">Offline</a>
                 <a href="online.php" class="btn btn-primary btn-sm">Online</a>
             </div>
         </div>
     </div>
     </div>
     </div> -->
     <!-- <style>
     .category-card-professional {
         transition: box-shadow 0.3s ease;
         border-color: #e9ecef !important;
     }

     .category-card-professional:hover {
         box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15) !important;
     }

     .category-card-professional .card-icon {
         background-color: #f8f9fa;
         border-radius: 8px;
         width: 70px;
         height: 70px;
         display: flex;
         align-items: center;
         justify-content: center;
         margin: 0 auto 20px;
         border: 1px solid #e9ecef;
     }

     .category-card-professional .btn {
         font-weight: 500;
         border-width: 1px;
         padding: 0.375rem 0.75rem;
     }

     @media (max-width: 768px) {
         .category-cards-section h2 {
             font-size: 2rem;
         }

         .category-card-professional {
             margin-bottom: 20px;
         }
     }
     </style> -->
     <!-- <div class="td_height_25 td_height_lg_75"></div>
     </section> -->
     <!-- End Category Cards Section -->
     <!-- Start About Section -->
     <section>
         <div class="td_height_25 td_height_lg_75"></div>
         <div class="py-5">
             <div class="td_about td_style_1">
                 <div class="container">
                     <div class="row align-items-center td_gap_y_40">
                         <div class="col-lg-6 wow fadeInLeft" data-wow-duration="1s" data-wow-delay="0.25s">
                             <!-- <div class="td_about_thumb_wrap">
                                <div class="td_about_year text-uppercase td_fs_64 td_bold">
                                    EST 1999
                                </div>
                                <div class="td_about_thumb_1 pb-3">
                                    <img src="assets/img/infomaths-about.jpg" alt="" style="border-radius: 5px" />
                                </div>
                                <div class="td_about_thumb_2">
                                    <img src="assets/img/infomaths-about2.jpg" alt="" style="border-radius: 5px" />
                                </div> -->
                             <!-- <a href="https://www.youtube.com/embed/BmjgnjICLKo"
                                class="td_circle_text td_center td_video_open">
                                <svg width="30" height="30" viewBox="0 0 15 19" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M14.086 8.63792C14.6603 9.03557 14.6603 9.88459 14.086 10.2822L2.54766 18.2711C1.88444 18.7303 0.978418 18.2557 0.978418 17.449L0.978418 1.47118C0.978418 0.664496 1.88444 0.189811 2.54767 0.649016L14.086 8.63792Z"
                                        fill="white" />
                                </svg>
                                <img src="assets/img/home_1/cgc-1.png" alt="" class="" />
                            </a> -->

                             <!-- </div> -->
                             <div class="td_about_thumb_wrap"
                                 style="padding:0; margin:0; border-radius:12px; overflow:hidden;">
                                 <iframe width="100%" height="650" src="https://www.youtube.com/embed/UNrIsfEXSWI"
                                     title="Discover Infomaths Cutting-Edge Techniques" frameborder="0"
                                     allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                     allowfullscreen style="display:block;">
                                 </iframe>
                             </div>




                         </div>
                         <div class="col-lg-6 wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.3s">
                             <div class="td_section_heading td_style_1 td_mb_30">
                                 <p
                                     class="td_section_subtitle_up td_fs_18 td_semibold td_spacing_1 td_mb_10 text-uppercase td_accent_color">
                                     About InfoMaths
                                 </p>
                                 <h2 class="td_section_title td_fs_48 mb-0" style="color:#09226c">
                                     Welcome to INFOMATHS
                                 </h2>
                                 <p class="td_section_subtitle td_fs_18 mb-3">
                                     Empowering Aspirants since 1999 <br>
                                     Infomaths is a Premier Coaching I nstitute dedicated to Student excellence.
                                     We
                                     blend expert theoretical knowledge with practical problem-solving to help
                                     students
                                     ace India's
                                     competitive exams.
                                 </p>


                                 <br>

                                 <ul style="list-style:none;padding:0;margin:0 0 1.5rem 0;">
                                     <li style="margin-bottom:1rem;display:flex;align-items:flex-start;">
                                         <span
                                             style="color:#d32f2f;font-size:1.5rem;margin-right:0.5rem;line-height:1;">&#10003;</span>
                                         <span><strong>India's Leading Coaching Institute</strong><br>Proven
                                             track
                                             record with top ranks in MCA entrance exams, BANK PO,SSC, IIT JAM
                                             Maths,
                                             CSIR NET JRF for over two decades.</span>
                                     </li>
                                     <li style="margin-bottom:1rem;display:flex;align-items:flex-start;">
                                         <span
                                             style="color:#d32f2f;font-size:1.5rem;margin-right:0.5rem;line-height:1;">&#10003;</span>
                                         <span><strong>Comprehensive Online & Offline Programs</strong><br>Live
                                             interactive classes, recorded lectures, and extensive study
                                             material
                                             accessible anytime, anywhere 24x7, 365 days.</span>
                                     </li>


                                     <li style="margin-bottom:1rem;display:flex;align-items:flex-start;">
                                         <span
                                             style="color:#d32f2f;font-size:1.5rem;margin-right:0.5rem;line-height:1;">&#10003;</span>
                                         <span><strong>Trusted by Thousands of Aspirant</strong><br>Strong
                                             alumni
                                             network and high student satisfaction, with success stories across
                                             India
                                             and abroad.</span>
                                     </li>
                                 </ul>
                                 </p>
                             </div>
                             <!-- <div class="td_mb_40">
                            <ul class="td_list td_style_5 td_mp_0">
                                <li>
                                    <h3 class="td_fs_24 td_mb_8">MCA Entrance Coaching</h3>
                                    <p class="td_fs_18 mb-0">
                                        Specialized preparation for NIMCET, MAHCET, PU MCA, and other MCA entrance exams with comprehensive syllabus coverage and regular mock tests.
                                    </p>
                                </li>
                                <li>
                                    <h3 class="td_fs_24 td_mb_8">
                                        Online & Offline Classes
                                    </h3>
                                    <p class="td_fs_18 mb-0">
                                        Flexible learning options with both online interactive sessions and classroom coaching, supported by study materials and doubt-clearing sessions.
                                    </p>
                                </li>
                                <li>
                                    <h3 class="td_fs_24 td_mb_8">
                                        Other Competitive Exams
                                    </h3>
                                    <p class="td_fs_18 mb-0">
                                        Comprehensive preparation for Bank PO SSC, IIT JAM Maths, CSIR NET JRF, and other competitive examinations with expert guidance and regular assessments.
                                    </p>
                                </li>
                            </ul>
                        </div> -->
                             <a href="about-infomaths.php" class="td_btn td_style_1 td_radius_10 td_medium">
                                 <span class="td_btn_in td_white_color td_accent_bg">
                                     <span>More About</span>
                                     <svg width="19" height="20" viewBox="0 0 19 20" fill="nfone"
                                         xmlns="http://www.w3.org/2000/svg">
                                         <path d="M15.1575 4.34302L3.84375 15.6567" stroke="currentColor"
                                             stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                         </path>
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
             </div>
         </div>
         <div class="td_height_15 td_height_lg_75"></div>

     </section>


     <!-- Our Upcoming Session Section -->
     <section class="td_gray_bg_6">
         <div class="container py-5">
             <div class="td_section_heading td_style_1 text-center wow fadeInUp" data-wow-duration="1s"
                 data-wow-delay="0.2s">
                 <h2 class="td_section_title td_fs_48 mb-4">Our Upcoming Session</h2>
             </div>
             <div class="row justify-content-center g-4">
                 <?php
                try {
                    $stmt = $pdo->query("SELECT * FROM upcoming_sessions WHERE is_active = 1 ORDER BY session_date ASC, session_time ASC");
                    $sessions = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    if (!empty($sessions)) {
                        foreach ($sessions as $session) {
                            $badgeClass = '';
                            switch (strtolower($session['category'])) {
                                case 'after xii':
                                    $badgeClass = 'bg-danger';
                                    break;
                                case 'study abroad':
                                    $badgeClass = 'bg-warning text-dark';
                                    break;
                                default:
                                    $badgeClass = 'bg-primary-blue';
                            }
                            echo '<div class="col-12 col-md-6 col-lg-5 col-xl-4">';
                            echo '<div class="bg-white border rounded shadow-sm h-100 p-3">';
                            echo '<div class="mb-3">';
                            echo '<img src="' . htmlspecialchars($session['image_path']) . '" alt="' . htmlspecialchars($session['title']) . '" class="img-fluid w-100 rounded" style="max-height: 180px; object-fit: cover;">';
                            echo '</div>';
                            echo '<div class="mb-2">';
                            echo '<span class="badge ' . $badgeClass . ' px-3 py-1" style="font-size: 1.1rem;">' . htmlspecialchars($session['category']) . '</span>';
                            echo '</div>';
                            echo '<h4 class="fw-bold mb-2">' . htmlspecialchars($session['title']) . '</h4>';
                            echo '<ul class="list-unstyled mb-3">';
                            echo '<li class="mb-1"><i class="fa-regular fa-calendar"></i> <b>' . date('D - d M Y', strtotime($session['session_date'])) . '</b></li>';
                            echo '<li class="mb-1"><i class="fa-regular fa-clock"></i> ' . date('h:i A', strtotime($session['session_time'])) . '</li>';
                            echo '</ul>';
                            echo '<a href="register.php" class="btn btn-primary w-100 fw-bold" style="font-size: 1.1rem;">';
                            echo 'Register Now';
                            echo '</a>';
                            echo '</div>';
                            echo '</div>';
                        }
                    } else {
                        echo '<div class="col-12 text-center">';
                        echo '<p class="text-muted">No upcoming sessions available at the moment.</p>';
                        echo '</div>';
                    }
                } catch (PDOException $e) {
                    echo '<div class="col-12 text-center">';
                    echo '<p class="text-muted">Unable to load sessions at this time.</p>';
                    echo '</div>';
                }
                ?>
             </div>
         </div>
         <div class="td_height_15 td_height_lg_75"></div>

     </section>
     <!-- End Our Upcoming Session Section -->

     <section>
         <div class="td_height_60 td_height_lg_75"></div>
         <div class="container">

             <div class="td_section_heading text-center">
                 <h2 class="td_section_title td_fs_48 mb-4">
                     Explore Our Online Learning Programs @coursedu APP
                 </h2>
                 <p
                     class="td_section_subtitle_up td_fs_18 td_semibold td_spacing_1 td_mb_10 text-uppercase td_accent_color">
                     Enhance your MCA entrance preparation with expert-led online courses covering Maths,
                     Aptitude, Computers, and English. Learn anytime, anywhere 24x7 with interactive sessions
                     and complete study material. On our android and IOS App of Infomaths -
                 </p>
             </div>

             <div class="td_height_50 td_height_lg_50"></div>

             <!-- ===== FIRST ROW (3 CARDS) ===== -->
             <div class="row td_gap_y_30">

                 <div class="col-lg-4 col-md-6">
                     <div class="td_post td_style_1 h-100">
                         <img src="assets/img/home_1/post_1.jpg" class="img-fluid">
                         <div class="td_post_info">
                             <h2 class="td_post_title td_fs_24">Banking</h2>
                             <p class="td_post_subtitle">Learn about the evolving education system.</p>
                             <a href="#" class="td_btn td_style_1 td_type_3 td_radius_30 td_medium mt-3">
                                 <span class="td_btn_in td_accent_color">
                                     <span>Other Courses</span>
                                 </span>
                             </a>
                         </div>
                     </div>
                 </div>

                 <div class="col-lg-4 col-md-6">
                     <div class="td_post td_style_1 h-100">
                         <img src="assets/img/home_1/post_2.jpg" class="img-fluid">
                         <div class="td_post_info">
                             <h2 class="td_post_title td_fs_24">MCA</h2>
                             <p class="td_post_subtitle">Understand the new policy structure.</p>
                             <a href="#" class="td_btn td_style_1 td_type_3 td_radius_30 td_medium mt-3">
                                 <span class="td_btn_in td_accent_color">
                                     <span>Other Courses</span>
                                 </span>
                             </a>
                         </div>
                     </div>
                 </div>

                 <div class="col-lg-4 col-md-6">
                     <div class="td_post td_style_1 h-100">
                         <img src="assets/img/home_1/post_3.jpg" class="img-fluid">
                         <div class="td_post_info">
                             <h2 class="td_post_title td_fs_24">BCA</h2>
                             <p class="td_post_subtitle">A practical guide for students.</p>
                             <a href="#" class="td_btn td_style_1 td_type_3 td_radius_30 td_medium mt-3">
                                 <span class="td_btn_in td_accent_color">
                                     <span>Other Courses</span>
                                 </span>
                             </a>
                         </div>
                     </div>
                 </div>

             </div>

             <!-- ===== SECOND ROW (2 CARDS CENTERED) ===== -->
             <div class="row justify-content-center td_gap_y_30 mt-4">

                 <div class="col-lg-4 col-md-6">
                     <div class="td_post td_style_1 h-100">
                         <img src="assets/img/home_1/post_4.jpg" class="img-fluid">
                         <div class="td_post_info">
                             <h2 class="td_post_title td_fs_24">Internship/Skill Training</h2>
                             <p class="td_post_subtitle">Discover how online platforms help.</p>
                             <a href="#" class="td_btn td_style_1 td_type_3 td_radius_30 td_medium mt-3">
                                 <span class="td_btn_in td_accent_color">
                                     <span>Other Courses</span>
                                 </span>
                             </a>
                         </div>
                     </div>
                 </div>

                 <div class="col-lg-4 col-md-6">
                     <div class="td_post td_style_1 h-100">
                         <img src="assets/img/home_1/post_5.jpg" class="img-fluid">
                         <div class="td_post_info">
                             <h2 class="td_post_title td_fs_24">Campus Placement</h2>
                             <p class="td_post_subtitle">Explore digital education growth.</p>
                             <a href="#" class="td_btn td_style_1 td_type_3 td_radius_30 td_medium mt-3">
                                 <span class="td_btn_in td_accent_color">
                                     <span>Other Courses</span>
                                 </span>
                             </a>
                         </div>
                     </div>
                 </div>

             </div>

         </div>
         <div class="td_height_60 td_height_lg_80"></div>
     </section>



     <!-- Use real student testimonials and images from the previous section -->
     <section class="td_heading_bg  td_hobble" style="background-color: #09226c;">
         <div class="td_height_55 td_height_lg_75"></div>
         <div class="container">
             <div class="td_section_heading td_style_1 text-center wow fadeInUp" data-wow-duration="1s"
                 data-wow-delay="0.2s">
                 <p
                     class="td_section_subtitle_up td_fs_18 td_semibold td_spacing_1 td_mb_10 text-uppercase td_accent_color text-white">
                     <i></i>
                     Our Testimonials
                     <i></i>
                 </p>
                 <h2 class="td_section_title text-white td_fs_48 mb-0">What Our Students Say About Us </h2>
                 <p class="td_section_subtitle text-white td_fs_18 mb-0">Hear from our students about their
                     journey and
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
                                            <div class="td_testimonial td_style_1 td_type_1 td_white_bg td_radius_5 mb-4" style="padding-top: 20px; padding-bottom: 20px; height:22rem ">
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
     <!-- End Feature Section -->






     <!-- Start Our Results Section -->
     <section class="td_gray_bg_6">
         <div class="td_height_60 td_height_lg_60"></div>
         <div class="container">
             <div class="td_section_heading td_style_1 text-center wow fadeInUp" data-wow-duration="1s"
                 data-wow-delay="0.2s">
                 <p class="td_section_subtitle_up text-uppercase">
                     Our Results
                 </p>
                 <h2 class="td_section_title td_fs_48 mb-0">
                     25 Years+ of Excellence: <br class="d-none d-md-block">
                     A Legacy of Success in Entrance Exam Coaching
                 </h2>
             </div>

             <div class="td_height_50 td_height_lg_50"></div>

             <div class="row td_gap_y_50">

                 <?php
            $sections = [
                ['slug' => 'mca_results', 'title' => 'MCA Results'],
                ['slug' => 'pu_results', 'title' => 'PU Results'],
                ['slug' => 'campus_placement', 'title' => 'Campus Placement'],
                ['slug' => 'college_results', 'title' => 'College Results']
            ];

            foreach ($sections as $sec):
            ?>
                 <div class="col-12 wow fadeIn" data-wow-duration="1s">
                     <div class="mb-4">

                         <h4 class="mb-3"
                             style="color: #2c3e50; font-weight: 700; font-size: 24px; border-left: 5px solid #ff4d01; padding-left: 15px;">
                             <?php echo $sec['title']; ?>
                         </h4>

                         <div class="td_slider td_style_1">
                             <div class="td_slider_container" data-autoplay="1" data-loop="1" data-speed="800"
                                 data-slides-per-view="1">

                                 <div class="td_slider_wrapper">

                                     <?php
                                    try {
                                        $stmt = $pdo->prepare("SELECT * FROM section_images WHERE section_name = ? ORDER BY display_order");
                                        $stmt->execute([$sec['slug']]);
                                        $images = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                        if (!empty($images)) {
                                            foreach ($images as $image) {
                                                echo '<div class="td_slide">
                                                        <img src="' . htmlspecialchars($image['image_path']) . '" 
                                                             alt="' . htmlspecialchars($image['alt_text']) . '" 
                                                             class="result-img" />
                                                      </div>';
                                            }
                                        } else {
                                            echo '<div class="td_slide">
                                                    <img src="assets/img/placeholder.jpg" 
                                                         class="result-img" />
                                                  </div>';
                                        }
                                    } catch (PDOException $e) { }
                                    ?>

                                 </div>
                             </div>
                         </div>

                     </div>
                 </div>
                 <?php endforeach; ?>

             </div>
         </div>
         <div class="td_height_60 td_height_lg_80"></div>
     </section>
     <!-- End Our Results Section -->


     <!-- Start Section -->

     <!-- End Section -->



     <section>
         <div class="td_height_60 td_height_lg_60"></div>
         <div class="td_about td_style_1">
             <div class="container">
                 <div class="row align-items-center td_gap_y_40">

                     <div class="col-lg-12 wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.3s">
                         <div class="td_section_heading td_style_1 td_mb_30">

                             <p
                                 class="td_section_subtitle_up td_fs_18 td_semibold td_spacing_1 td_mb_10 text-uppercase td_accent_color">
                                 Why Choose Infomaths
                             </p>

                             <h2 class="td_section_title td_fs_48 mb-0">
                                 Your Success Begins Here
                             </h2>

                             <p class="td_section_subtitle td_fs_18 mb-4">
                                 At Infomaths, we combine expertise, innovation, and personalized mentorship to help
                                 students achieve top ranks in competitive exams. Our commitment to excellence and
                                 consistent results make us a trusted name in mathematics coaching.
                             </p>

                             <ul style="list-style:none;padding:0;margin:0 0 1.5rem 0;">
                                 <li style="margin-bottom:1rem;display:flex;align-items:flex-start;">
                                     <span
                                         style="color:#d32f2f;font-size:1.5rem;margin-right:0.5rem;line-height:1;">&#10003;</span>
                                     <span><strong>Proven Track Record</strong><br>
                                         Consistently producing top ranks in NIMCET, MAHCET, PU MCA, IIT JAM, and
                                         other competitive exams.</span>
                                 </li>

                                 <li style="margin-bottom:1rem;display:flex;align-items:flex-start;">
                                     <span
                                         style="color:#d32f2f;font-size:1.5rem;margin-right:0.5rem;line-height:1;">&#10003;</span>
                                     <span><strong>Expert Faculty & Mentorship</strong><br>
                                         Learn from experienced educators with deep subject knowledge and
                                         personalized guidance.</span>
                                 </li>

                                 <li style="margin-bottom:1rem;display:flex;align-items:flex-start;">
                                     <span
                                         style="color:#d32f2f;font-size:1.5rem;margin-right:0.5rem;line-height:1;">&#10003;</span>
                                     <span><strong>Comprehensive Learning Programs</strong><br>
                                         Structured courses, mock tests, doubt-clearing sessions, and study material
                                         designed for success.</span>
                                 </li>

                                 <li style="margin-bottom:1rem;display:flex;align-items:flex-start;">
                                     <span
                                         style="color:#d32f2f;font-size:1.5rem;margin-right:0.5rem;line-height:1;">&#10003;</span>
                                     <span><strong>Online & Offline Flexibility</strong><br>
                                         Attend live classes or access recorded sessions anytime, anywhere.</span>
                                 </li>
                             </ul>

                         </div>

                         <!-- <a href="#" class="td_btn td_style_1 td_radius_10 td_medium">
                                 <span class="td_btn_in td_white_color td_accent_bg">
                                     <span>Learn More</span>
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
                             </a> -->

                     </div>

                 </div>
             </div>
         </div>
         <div class="td_height_30 td_height_lg_60"></div>

     </section>







     <!-- Start Registration Section -->
     <section style="background-color:#f7f7f8;">
         <div class=" td_height_55 td_height_lg_75">
         </div>
         <div class="container">
             <div class="td_section_heading td_style_1 td_type_1 wow fadeInUp" data-wow-duration="1s"
                 data-wow-delay="0.2s">
                 <div class="td_section_heading_left">
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
             <div class="row td_gap_y_24">
                 <div class="col-xl-3 col-lg-4 col-sm-6 wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.25s">
                     <div class="td_iconbox td_style_2 text-center td_hobble">
                         <div class="td_iconbox_in td_hover_layer_4">
                             <div class="td_hover_layer_3">
                                 <div class="td_iconbox_icon td_mb_16">
                                     <img src="assets/img/home_1/demo-class.png" alt="">
                                 </div>
                                 <h3 class="td_iconbox_title td_fs_20 td_semibold td_opacity_8 td_mb_16">Free Demo
                                     Class
                                 </h3>
                                 <p class="td_iconbox_subtitle mb-0 td_accent_color">Register Now</p>
                             </div>
                         </div>
                         <a href="register.php" class="td_iconbox_link"></a>
                     </div>
                 </div>
                 <div class="col-xl-3 col-lg-4 col-sm-6 wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.3s">
                     <div class="td_iconbox td_style_2 text-center td_hobble">
                         <div class="td_iconbox_in td_hover_layer_4">
                             <div class="td_hover_layer_3">
                                 <div class="td_iconbox_icon td_mb_16">
                                     <img src="assets/img/home_1/test-series.png" alt="">
                                 </div>
                                 <h3 class="td_iconbox_title td_fs_20 td_semibold td_opacity_8 td_mb_16">Free Test
                                     Series
                                 </h3>
                                 <a href="register.php" class="td_iconbox_subtitle mb-0 td_accent_color">Register
                                     Now</a>
                             </div>
                         </div>
                         <a href="register.php" class="td_iconbox_link"></a>
                     </div>
                 </div>
                 <div class="col-xl-3 col-lg-4 col-sm-6 wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.35s">
                     <div class="td_iconbox td_style_2 text-center td_hobble">
                         <div class="td_iconbox_in td_hover_layer_4">
                             <div class="td_hover_layer_3">
                                 <div class="td_iconbox_icon td_mb_16">
                                     <img src="assets/img/home_1/pyq.png" alt="">
                                 </div>
                                 <h3 class="td_iconbox_title td_fs_20 td_semibold td_opacity_8 td_mb_16">Free
                                     University
                                     PYQ</h3>
                                 <p class="td_iconbox_subtitle mb-0 td_accent_color">Register Now</p>
                             </div>
                         </div>
                         <a href="register.php" class="td_iconbox_link"></a>
                     </div>
                 </div>
                 <div class="col-xl-3 col-lg-4 col-sm-6 wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.4s">
                     <div class="td_iconbox td_style_2 text-center td_hobble">
                         <div class="td_iconbox_in td_hover_layer_4">
                             <div class="td_hover_layer_3">
                                 <div class="td_iconbox_icon td_mb_16">
                                     <img src="assets/img/home_1/counselling.png" alt="">
                                 </div>
                                 <h3 class="td_iconbox_title td_fs_20 td_semibold td_opacity_8 td_mb_16">Free
                                     Counselling
                                 </h3>
                                 <p class="td_iconbox_subtitle mb-0 td_accent_color">Register Now</p>
                             </div>
                         </div>
                         <a href="register.php" class="td_iconbox_link"></a>
                     </div>
                 </div>

             </div>
         </div>
         <div class="td_height_60 td_height_lg_80"></div>
     </section>
     <!-- End Registration Section -->
     <!-- Start App Section -->
     <div class="">
         <section class=" app-section_custom" style="background-color: #09226c;">
             <div class="container app-container_custom">

                 <!-- Text Content -->
                 <div class="td_cta_text_custom">
                     <div class="td_section_heading_custom">
                         <p class="td_section_subtitle_custom">KEY FEATURES @CoursEdu APP</p>

                         <h2 class="td_section_title_custom">
                             Your complete learning companion. Study, practice, and track progress.
                         </h2>

                         <p class="td_section_description_custom" style="color:white;">
                             <strong style="color:#ed231a">Video Lectures:</strong> Recorded & live classes anytime,
                             anywhere.<br>
                             <strong style="color:#ed231a">PDF & Study Material:</strong> Easy access to notes, e-books
                             & handouts.<br>
                             <strong style="color:#ed231a">Test-Taking Module:</strong> Practice with mock tests &
                             previous year papers.<br>
                             <strong style="color:#ed231a">Online Analysis:</strong> Instant reports, accuracy check &
                             tips.<br>
                             <strong style="color:#ed231a">Attendance Tracking:</strong> Monitor presence and
                             participation seamlessly.<br>
                             <strong style="color:#ed231a">Progress Analysis:</strong> Dashboards to track learning
                             curves & weak areas.
                         </p>
                     </div>



                     <div class=" td_btns_group_custom">
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



     <!-- Start Popular Courses -->
     <section class="td_gray_bg_3">
         <div class="td_height_55 td_height_lg_75"></div>
         <div class="container">
             <div class="td_section_heading td_style_1 text-center wow fadeInUp" data-wow-duration="1s"
                 data-wow-delay="0.15s">
                 <p
                     class="td_section_subtitle_up td_fs_18 td_semibold td_spacing_1 td_mb_10 text-uppercase td_accent_color">
                     Our Online Courses</p>
                 <h2 class="td_section_title td_fs_48 mb-0">Pick A Course To Get Started</h2>
             </div>
             <div class="td_height_30 td_height_lg_30"></div>
             <div class="td_tabs">
                 <!-- <div class="td_height_50 td_height_lg_50"></div> -->
                 <?php
          try {
              $stmt = $pdo->query("SELECT DISTINCT tab FROM courses WHERE is_active = 1 ORDER BY tab");
              $tabs = $stmt->fetchAll(PDO::FETCH_COLUMN);
          } catch (PDOException $e) {
              $tabs = [];
          }

          if (!empty($tabs)) {
              echo '<ul class="td_tab_links td_style_1 td_mp_0 td_fs_20 td_medium td_heading_color wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.2s">';
              foreach ($tabs as $index => $tab_name) {
                  $active_class = ($index == 0) ? ' class="active"' : '';
                  echo '<li' . $active_class . '><a href="#tab_' . ($index + 1) . '">' . htmlspecialchars($tab_name) . '</a></li>';
              }
              echo '</ul>';
              echo '<div class="td_height_50 td_height_lg_50"></div>';
          }
          ?>
                 <div class="td_tab_body">
                     <?php
            if (empty($tabs)) {
                echo '<div class="col-12 text-center"><p>No courses available.</p></div>';
            } else {
                foreach ($tabs as $index => $tab_name) {
                    $active_class = ($index == 0) ? ' active' : '';
                    echo '<div class="td_tab' . $active_class . '" id="tab_' . ($index + 1) . '">';
                    echo '<div class="row td_gap_y_24">';

                    try {
                        $stmt = $pdo->prepare("SELECT * FROM courses WHERE tab = ? AND is_active = 1 ORDER BY created_at DESC");
                        $stmt->execute([$tab_name]);
                        $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        if (!empty($courses)) {
                            foreach ($courses as $course) {
                                echo '<div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.2s">';
                                echo '<div class="td_card td_style_3 d-block td_radius_10">';
                                echo '<a href="course-details.html" class="td_card_thumb">';
                                echo '<img src="' . htmlspecialchars($course['image_path']) . '" alt="' . htmlspecialchars($course['title']) . '">';
                                echo '</a>';
                                echo '<div class="td_card_info td_white_bg">';
                                echo '<div class="td_card_info_in">';
                                echo '<a href="courses-grid-with-sidebar.html" class="td_card_category td_fs_14 td_bold td_heading_color td_mb_14"><span>' . htmlspecialchars($course['category']) . '</span></a>';
                                echo '<h2 class="td_card_title td_fs_24 td_mb_16"><a href="course-details.html">' . htmlspecialchars($course['title']) . '</a></h2>';
                                echo '<p class="td_card_subtitle td_heading_color td_opacity_7 td_mb_20">' . htmlspecialchars($course['description']) . '</p>';
                                echo '<a href="cart.html" class="td_btn td_style_1 td_radius_10 td_medium"><span class="td_btn_in td_white_color td_accent_bg"><span>Enroll Now</span></span></a>';
                                echo '</div>';
                                echo '</div>';
                                echo '</div>';
                                echo '</div>';
                            }
                        } else {
                            echo '<div class="col-12 text-center"><p>No courses available in this category.</p></div>';
                        }
                    } catch (PDOException $e) {
                        echo '<div class="col-12 text-center"><p>Error loading courses.</p></div>';
                    }

                    echo '</div>';
                    echo '</div>';
                }
            }
            ?>
                 </div>
             </div>
         </div>
         <div class="td_height_60 td_height_lg_80"></div>
     </section>
     <!-- End Popular Courses -->
     <!-- Start Demo Lectures Section -->
     <section class="">
         <div class="td_height_55 td_height_lg_75"></div>
         <div class="container">
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
             <div class="td_height_30 td_height_lg_50"></div>
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
         <div class="td_height_60 td_height_lg_80"></div>
     </section>
     <!-- Expert Members Section -->
     <section class="td_shape_section_1 td_gray_bg_3 pb-0">
         <div class="td_height_50 td_height_lg_50"></div>
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
                         <h4 class="td_member_name td_fs_20 td_mb_5" style="color: #09226c; font-weight: 700;">
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
     <!-- Start Infomaths Info Section -->
     <section class="infomaths-info-section">
         <div class="container pt-5">
             <div class="row align-items-center g-4">
                 <div class="col-lg-6 mb-4 mb-lg-0">
                     <h2 class="infomaths-info-title mb-3">
                         <span class="infomaths-highlight">Infomaths</span> - Empowering Success Since 1999
                     </h2>
                     <p class="infomaths-info-desc mb-3">
                         Infomaths, a leader in competitive exam coaching, has been guiding students to success in
                         MCA,
                         PU, IIT-JAM, Campus Placement, BBA, and more for over two decades. Our expert faculty,
                         innovative teaching methods, and commitment to excellence have helped thousands of students
                         achieve their academic and career goals.
                     </p>

                 </div>
                 <div class="col-lg-6">
                     <div class="row g-3">
                         <div class="col-12 col-md-4">
                             <div class="infomaths-cta-card text-center">
                                 <div class="infomaths-cta-icon">
                                     <img src="assets/img/icons/icon_Join-us-as-a-Franchisee.png" width="48" height="48"
                                         alt="Icon 1" />
                                 </div>
                                 <div class="infomaths-cta-title">Join Us As a Franchise</div>
                             </div>
                         </div>
                         <div class="col-12 col-md-4">
                             <div class="infomaths-cta-card text-center">
                                 <div class="infomaths-cta-icon">
                                     <img src="assets/img/icons/icon_Partner-with-us-for-outreach.png" width="48"
                                         height="48" alt="Icon 1" />
                                 </div>
                                 <div class="infomaths-cta-title">Partner with Us For Outreach</div>
                             </div>
                         </div>
                         <div class="col-12 col-md-4">
                             <div class="infomaths-cta-card text-center">
                                 <div class="infomaths-cta-icon">
                                     <img src="assets/img/icons/icon_Contact-us.png" width="48" height="48"
                                         alt="Icon 1" />
                                 </div>
                                 <div class="infomaths-cta-title">Contact Us & know more</div>

                             </div>
                         </div>
                     </div>
                 </div>
             </div>
         </div>
         <style>
         .infomaths-info-section {

             border-radius: 18px;
             margin: 5px 0 32px 0;
         }

         .infomaths-info-title {
             font-size: 2.2rem;
             font-weight: 800;
             color: #222;
         }

         .infomaths-highlight {
             color: #09226c;
         }

         .infomaths-info-desc {
             font-size: 1.13rem;
             color: #444;
             line-height: 1.7;
         }

         .infomaths-cta-card {
             background: #fff;
             border-radius: 18px;
             box-shadow: 0 4px 24px rgba(44, 62, 80, 0.08);
             padding: 32px 12px 24px 12px;
             margin-bottom: 0;
             transition: box-shadow 0.3s, transform 0.3s;
             min-height: 210px;
             display: flex;
             flex-direction: column;
             align-items: center;
             justify-content: center;
         }

         .infomaths-cta-card:hover {
             box-shadow: 0 12px 36px rgba(255, 112, 67, 0.13);
             transform: translateY(-6px) scale(1.03);
         }

         .infomaths-cta-icon {
             margin-bottom: 18px;
         }

         .infomaths-cta-title {
             font-size: 1.1rem;
             font-weight: 700;
             color: #222;
             margin-bottom: 6px;
         }

         .infomaths-cta-desc {
             font-size: 0.98rem;
             color: #444;
         }

         @media (max-width: 992px) {
             .infomaths-info-title {
                 font-size: 1.5rem;
             }

             .infomaths-cta-card {
                 min-height: 180px;
                 padding: 22px 6px 16px 6px;
             }
         }

         @media (max-width: 600px) {
             .infomaths-info-section {
                 padding: 0 2px;
             }

             .infomaths-info-title {
                 font-size: 1.1rem;
             }

             .infomaths-cta-card {
                 min-height: 120px;
                 padding: 12px 2px 10px 2px;
             }
         }
         </style>
     </section>
     <!-- CUET Books Section Start -->
     <section class="cuet-books-section"
         style="background:linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);padding:40px 0 60px 0;">
         <div class="container" style="max-width:1300px;margin:0 auto;">
             <h2 class="cuet-books-title"
                 style="font-size:2.8rem;font-weight:700;text-align:center;margin-bottom:0.5rem;color:#222;">
                 Our Publications
                 </span>
             </h2>
             <p class="cuet-books-subtitle"
                 style="text-align:center;font-size:1.25rem;color:#4a4a4a;margin-bottom:2.5rem;">
                 We provide high-quality physical books and resources for competitive exams, ensuring students have
                 the
                 best study materials.
             </p>
             <div class="cuet-books-cards" style="display:flex;flex-wrap:wrap;justify-content:center;gap:32px;">
                 <?php
                try {
                    $stmt = $pdo->prepare("SELECT * FROM publications ORDER BY display_order ASC, created_at DESC");
                    $stmt->execute();
                    $publications = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    if (!empty($publications)) {
                        foreach ($publications as $pub) {
                            echo '<div class="cuet-book-card" style="background:#f7f7f7;border-radius:16px;box-shadow:0 2px 12px rgba(0,0,0,0.06);padding:24px 24px 32px 24px;max-width:260px;flex:1 1 220px;display:flex;flex-direction:column;align-items:center;">';
                            echo '<img src="assets/img/publications/' . htmlspecialchars($pub['image_path']) . '" alt="' . htmlspecialchars($pub['alt_text']) . '" style="width:100%;height:320px;object-fit:cover;border-radius:8px;margin-bottom:18px;" />';
                            echo '<button class="cuet-explore-btn" style="margin-top:auto;background:#b71c1c;color:#fff;font-weight:600;font-size:1.1rem;padding:10px 28px;border:none;border-radius:8px;cursor:pointer;display:flex;align-items:center;gap:8px;transition:background 0.2s;">';
                            echo 'Explore Now <span style="font-size:1.2em;display:inline-block;transform:translateY(1px);">&#8599;</span>';
                            echo '</button>';
                            echo '</div>';
                        }
                    } else {
                        // Fallback to default images if no publications found
                        for ($i = 1; $i <= 5; $i++) {
                            echo '<div class="cuet-book-card" style="background:#f7f7f7;border-radius:16px;box-shadow:0 2px 12px rgba(0,0,0,0.06);padding:24px 24px 32px 24px;max-width:260px;flex:1 1 220px;display:flex;flex-direction:column;align-items:center;">';
                            echo '<img src="assets/img/cuet-book-' . $i . '.jpg" alt="CUET Book ' . $i . '" style="width:100%;height:320px;object-fit:cover;border-radius:8px;margin-bottom:18px;" />';
                            echo '<button class="cuet-explore-btn" style="margin-top:auto;background:#b71c1c;color:#fff;font-weight:600;font-size:1.1rem;padding:10px 28px;border:none;border-radius:8px;cursor:pointer;display:flex;align-items:center;gap:8px;transition:background 0.2s;">';
                            echo 'Explore Now <span style="font-size:1.2em;display:inline-block;transform:translateY(1px);">&#8599;</span>';
                            echo '</button>';
                            echo '</div>';
                        }
                    }
                } catch (PDOException $e) {
                    // Fallback to default images if database error
                    for ($i = 1; $i <= 5; $i++) {
                        echo '<div class="cuet-book-card" style="background:#f7f7f7;border-radius:16px;box-shadow:0 2px 12px rgba(0,0,0,0.06);padding:24px 24px 32px 24px;max-width:260px;flex:1 1 220px;display:flex;flex-direction:column;align-items:center;">';
                        echo '<img src="assets/img/cuet-book-' . $i . '.jpg" alt="CUET Book ' . $i . '" style="width:100%;height:320px;object-fit:cover;border-radius:8px;margin-bottom:18px;" />';
                        echo '<button class="cuet-explore-btn" style="margin-top:auto;background:#b71c1c;color:#fff;font-weight:600;font-size:1.1rem;padding:10px 28px;border:none;border-radius:8px;cursor:pointer;display:flex;align-items:center;gap:8px;transition:background 0.2s;">';
                        echo 'Explore Now <span style="font-size:1.2em;display:inline-block;transform:translateY(1px);">&#8599;</span>';
                        echo '</button>';
                        echo '</div>';
                    }
                }
                ?>
             </div>
         </div>
     </section>


     <section class="publications-section">
         <div class="container">
             <h2 class="section-title">Our Study Material</h2>
             <p class="section-subtitle">
                 We provide high-quality physical books and resources for competitive exams, ensuring students have the
                 best study materials.
             </p>

             <div class="books-grid">
                 <div class="book-card">
                     <div class="book-image-container">
                         <img src="assets/img/home_1/mca.jpeg" alt="MCA Entrance Book">
                     </div>
                     <h3 class="course-name">MCA Entrance</h3>
                     <button class="explore-btn">
                         Explore Now <span>&#8599;</span>
                     </button>
                 </div>

                 <div class="book-card">
                     <div class="book-image-container">
                         <img src="assets/img/home_1/po.jpeg" alt="CUET Preparation">
                     </div>
                     <h3 class="course-name">Bank PO</h3>
                     <button class="explore-btn">
                         Explore Now <span>&#8599;</span>
                     </button>
                 </div>

                 <div class="book-card">
                     <div class="book-image-container">
                         <img src="assets/img/home_1/ssc.jpeg" alt="NIMCET Guide">
                     </div>
                     <h3 class="course-name">SSC</h3>
                     <button class="explore-btn">
                         Explore Now <span>&#8599;</span>
                     </button>
                 </div>

                 <div class="book-card">
                     <div class="book-image-container">
                         <img src="assets/img/home_1/iit.jpeg" alt="MAH-CET">
                     </div>
                     <h3 class="course-name">IIT JAM MATHS</h3>
                     <button class="explore-btn">
                         Explore Now <span>&#8599;</span>
                     </button>
                 </div>

                 <div class="book-card">
                     <div class="book-image-container">
                         <img src="assets/img/home_1/bssc.jpeg" alt="VITEEE">
                     </div>
                     <h3 class="course-name">BCA/B.SC</h3>
                     <button class="explore-btn">
                         Explore Now <span>&#8599;</span>
                     </button>
                 </div>
             </div>
         </div>
     </section>



     <!-- end  Our study material -->


     <!-- CUET Books Section End -->
     <!-- End Demo Lectures Section -->
     <!-- Start Category Section -->

     <!-- Start Category Section -->

     <!-- Start Category Section -->

     <!-- Start NIMCET Results Slider Section -->
     <!-- <section class="td_gray_bg_3">
        <div class="td_height_100 td_height_lg_75"></div>
        <div class="container">
            <div class="td_section_heading td_style_1 text-center wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.2s">
                <p class="td_section_subtitle_up td_fs_18 td_semibold td_spacing_1 td_mb_10 text-uppercase td_accent_color">

                    <i></i>
                    Our Shinning Stars
                    <i></i>
                </p>
                <h2 class="td_section_title td_fs_48 mb-0">NIMCET Toppers</h2>
            </div>
            <div class="td_height_50 td_height_lg_50"></div>
            <div class="td_slider td_style_1 td_slider_gap_24 td_remove_overflow wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.3s">
                <div class="td_slider_container" data-autoplay="1" data-loop="1" data-speed="800" data-center="0" data-variable-width="0" data-slides-per-view="responsive" data-xs-slides="1" data-sm-slides="2" data-md-slides="3" data-lg-slides="4" data-add-slides="4">
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

    </section> -->
     <!-- MAHCET Toppers Section -->
     <!-- <section class="td_gray_bg_3">
        <div class="td_height_75 td_height_lg_75"></div>
        <div class="container">
            <div class="td_section_heading td_style_1 text-center wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.2s">
                <h2 class="td_section_title td_fs_48 mb-0">MAHCET Toppers</h2>
            </div>
            <div class="td_height_50 td_height_lg_50"></div>
            <div class="td_slider td_style_1 td_slider_gap_24 wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.3s">
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

    </section> -->
     <!-- VIT Toppers Section -->
     <!-- <section class="td_gray_bg_3">
        <div class="td_height_75 td_height_lg_75"></div>
        <div class="container">
            <div class="td_section_heading td_style_1 text-center wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.2s">

                <h2 class="td_section_title td_fs_48 mb-0">VIT Toppers</h2>
            </div>
            <div class="td_height_50 td_height_lg_50"></div>
            <div class="td_slider td_style_1 td_slider_gap_24 wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.3s">
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

    </section> -->
     <!-- PU Toppers Section -->
     <!-- <section class="td_gray_bg_3">
        <div class="td_height_75 td_height_lg_75"></div>
        <div class="container">
            <div class="td_section_heading td_style_1 text-center wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.2s">

                <h2 class="td_section_title td_fs_48 mb-0">PU Toppers</h2>
            </div>
            <div class="td_height_50 td_height_lg_50"></div>
            <div class="td_slider td_style_1 td_slider_gap_24 wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.3s">
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
    </section> -->

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



     <!-- End Category Section -->
     <!-- <section class="td_accent_bg td_rate_section wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.2s">
        <div class="container td_rate_feature_list_wrap">
            <div class="td_moving_box_wrap">
                <div class="td_moving_box_in">
                    <div class="td_moving_box">
                        <ul class="td_rate_feature_list td_mp_0">
                            <li>
                                <div class="td_rate_feature_icon td_center td_white_bg">
                                    <img src="assets/img/home_2/cs_rate_feature_icon_1.svg" alt="" />
                                </div>
                                <div class="td_rate_feature_right">
                                    <h3 class="td_fs_24 td_semibold td_white_color td_mb_4">
                                        5,000+ Successful Students
                                    </h3>
                                    <p class="mb-0 td_white_color td_opacity_7">
                                        Students who achieved success in competitive exams through our coaching
                                    </p>
                                </div>
                            </li>
                            <li>
                                <div class="td_rate_feature_icon td_center td_white_bg">
                                    <img src="assets/img/home_2/cs_rate_feature_icon_2.svg" alt="" />
                                </div>
                                <div class="td_rate_feature_right">
                                    <h3 class="td_fs_24 td_semibold td_white_color td_mb_4">
                                        Exam-Focused Coaching
                                    </h3>
                                    <p class="mb-0 td_white_color td_opacity_7">
                                        Specialized programs for MCA entrance, banking, IIT JAM, CSIR NET exams
                                    </p>
                                </div>
                            </li>
                            <li>
                                <div class="td_rate_feature_icon td_center td_white_bg">
                                    <img src="assets/img/home_2/cs_rate_feature_icon_3.svg" alt="" />
                                </div>
                                <div class="td_rate_feature_right">
                                    <h3 class="td_fs_24 td_semibold td_white_color td_mb_4">
                                        Online & Offline Classes
                                    </h3>
                                    <p class="mb-0 td_white_color td_opacity_7">
                                        Flexible learning options with modern teaching methodologies and resources
                                    </p>
                                </div>
                            </li>
                            <li>
                                <div class="td_rate_feature_icon td_center td_white_bg">
                                    <img src="assets/img/home_2/cs_rate_feature_icon_4.svg" alt="" />
                                </div>
                                <div class="td_rate_feature_right">
                                    <h3 class="td_fs_24 td_semibold td_white_color td_mb_4">
                                        Proven Success Record
                                    </h3>
                                    <p class="mb-0 td_white_color td_opacity_7">
                                        Consistent results with students excelling in various competitive examinations
                                    </p>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="td_moving_box">
                        <ul class="td_rate_feature_list td_mp_0">
                            <li>
                                <div class="td_rate_feature_icon td_center td_white_bg">
                                    <img src="assets/img/home_2/cs_rate_feature_icon_1.svg" alt="" />
                                </div>
                                <div class="td_rate_feature_right">
                                    <h3 class="td_fs_24 td_semibold td_white_color td_mb_4">
                                        10,000+ Successful Students
                                    </h3>
                                    <p class="mb-0 td_white_color td_opacity_7">
                                        Students who achieved success in competitive exams through our coaching
                                    </p>
                                </div>
                            </li>
                            <li>
                                <div class="td_rate_feature_icon td_center td_white_bg">
                                    <img src="assets/img/home_2/cs_rate_feature_icon_2.svg" alt="" />
                                </div>
                                <div class="td_rate_feature_right">
                                    <h3 class="td_fs_24 td_semibold td_white_color td_mb_4">
                                        Exam-Focused Coaching
                                    </h3>
                                    <p class="mb-0 td_white_color td_opacity_7">
                                        Specialized programs for MCA entrance, banking, IIT JAM, CSIR NET exams
                                    </p>
                                </div>
                            </li>
                            <li>
                                <div class="td_rate_feature_icon td_center td_white_bg">
                                    <img src="assets/img/home_2/cs_rate_feature_icon_3.svg" alt="" />
                                </div>
                                <div class="td_rate_feature_right">
                                    <h3 class="td_fs_24 td_semibold td_white_color td_mb_4">
                                        Online & Offline Classes
                                    </h3>
                                    <p class="mb-0 td_white_color td_opacity_7">
                                        Flexible learning options with modern teaching methodologies and resources
                                    </p>
                                </div>
                            </li>
                            <li>
                                <div class="td_rate_feature_icon td_center td_white_bg">
                                    <img src="assets/img/home_2/cs_rate_feature_icon_4.svg" alt="" />
                                </div>
                                <div class="td_rate_feature_right">
                                    <h3 class="td_fs_24 td_semibold td_white_color td_mb_4">
                                        Proven Success Record
                                    </h3>
                                    <p class="mb-0 td_white_color td_opacity_7">
                                        Consistent results with students excelling in various competitive examinations
                                    </p>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section> -->
     <!-- End Rate Section -->


     </div>
     <style>
     .top-recruiters-slider {
         /* Your existing marquee animation styles here */
     }

     .reverse-marquee {
         animation-direction: reverse !important;
     }

     /* App Section Custom Styles */
     .app_features_custom {
         margin: 30px 0;
     }

     .feature_item_custom {
         display: flex;
         align-items: center;
         margin-bottom: 15px;
         font-size: 16px;
         color: #333;
     }

     .feature_item_custom i {
         margin-right: 12px;
         color: #3498db;
         font-size: 18px;
         width: 20px;
     }

     .td_section_description_custom {
         font-size: 18px;
         color: #666;
         margin: 20px 0 30px 0;
         line-height: 1.6;
         max-width: 500px;
     }

     @media (max-width: 768px) {
         .feature_item_custom {
             font-size: 14px;
         }

         .td_section_description_custom {
             font-size: 16px;
         }
     }
     </style>
     <!-- End Top Recruiters Section -->



     <!-- Infomaths in PRINT / MEDIA / Career Counselling Section -->
     <section class="td_gray_bg_3">
         <div class="td_height_50 td_height_lg_75"></div>
         <div class="container">
             <div class="td_section_heading td_style_1 text-center wow fadeInUp" data-wow-duration="1s"
                 data-wow-delay="0.2s">

                 <h2 class="td_section_title td_fs_48 mb-0">Infomaths in PRINT / MEDIA / Career Counselling</h2>
             </div>
             <div class="td_height_50 td_height_lg_50"></div>
             <div class="td_slider td_style_1 td_slider_gap_24 wow fadeInUp" data-wow-duration="1s"
                 data-wow-delay="0.3s">
                 <div class="td_slider_container" data-autoplay="1" data-loop="1" data-speed="800" data-center="0"
                     data-variable-width="0" data-slides-per-view="responsive" data-xs-slides="1" data-sm-slides="2"
                     data-md-slides="2" data-lg-slides="2" data-add-slides="2">
                     <div class="td_slider_wrapper">
                         <?php
                        try {
                            $stmt = $pdo->prepare("SELECT * FROM toppers WHERE category = 'MEDIA' ORDER BY uploaded_at DESC");
                            $stmt->execute();
                            $toppers = $stmt->fetchAll(PDO::FETCH_ASSOC);

                            if (count($toppers) > 0) {
                                foreach ($toppers as $topper) {
                                    echo '<div class="td_slide">';
                                    echo '<div style="text-align:center;">';
                                    echo '<img src="assets/toppers/' . htmlspecialchars($topper['image_path']) . '" alt="' . htmlspecialchars($topper['name']) . '" style="width:650px;height:450px;object-fit:cover;border-radius:10px;margin:0 auto;display:block;">';
                                    echo '<h4 style="margin-top:15px;color:#333;font-size:18px;font-weight:600;">' . htmlspecialchars($topper['name']) . '</h4>';
                                    echo '</div>';
                                    echo '</div>';
                                }
                            } else {
                                // Fallback to default images if no toppers found
                                for ($i = 1; $i <= 6; $i++) {
                                    echo '<div class="td_slide">';
                                    echo '<img src="assets/img/inps-logo.png" alt="Media ' . $i . '" style="width:650px;height:450px;object-fit:cover;border-radius:10px;margin:0 auto;display:block;">';
                                    echo '</div>';
                                }
                            }
                        } catch (PDOException $e) {
                            // Fallback to default images if database error
                            for ($i = 1; $i <= 6; $i++) {
                                echo '<div class="td_slide">';
                                echo '<img src="assets/img/inps-logo.png" alt="Media ' . $i . '" style="width:650px;height:450px;object-fit:cover;border-radius:10px;margin:0 auto;display:block;">';
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
     <!-- Start Our Faculty Section -->

     <!-- Start Research & Innovation Section -->


     </section>
     </div>

     <!-- End App Section -->

     <!-- End Instagram Section -->

     </section>
     <!-- End Video Section -->
     <!-- Start Event Schedule Section -->
     <!-- Start Event Schedule Section -->
     <!-- <section>
      <div class="td_height_112 td_height_lg_75"></div>
      <div class="container">
        <div
          class="td_section_heading td_style_1 text-center wow fadeInUp"
          data-wow-duration="1s"
          data-wow-delay="0.2s"
        >
          <p
            class="td_section_subtitle_up td_fs_18 td_semibold td_spacing_1 td_mb_10 text-uppercase td_accent_color"
          >
            Event schedule
          </p>
          <h2 class="td_section_title td_fs_48 mb-0">
            Upcoming Event Conference 2024 <br />Host by Educve
          </h2>
        </div>
        <div class="td_height_50 td_height_lg_50"></div>
        <div class="row td_gap_y_30">
          <div
            class="col-lg-6 wow fadeInUp"
            data-wow-duration="1s"
            data-wow-delay="0.2s"
          >
            <div class="td_card td_style_1 td_radius_5">
              <a
                href="event-details.html"
                class="td_card_thumb td_mb_30 d-block"
              >
                <img src="assets/img/home_1/event_thumb_1.jpg" alt="" />
                <i class="fa-solid fa-arrow-up-right-from-square"></i>
                <span
                  class="td_card_location td_medium td_white_color td_fs_18"
                >
                  <svg
                    width="16"
                    height="22"
                    viewBox="0 0 16 22"
                    fill="none"
                    xmlns="http://www.w3.org/2000/svg"
                  >
                    <path
                      d="M8.0004 0.5C3.86669 0.5 0.554996 3.86526 0.500458 7.98242C0.48345 9.42271 0.942105 10.7046 1.56397 11.8232C2.76977 13.9928 4.04435 16.8182 5.32856 19.4639C5.9286 20.7002 6.89863 21.5052 8.0004 21.5C9.10217 21.4948 10.0665 20.6836 10.6575 19.4404C11.9197 16.7856 13.1685 13.9496 14.4223 11.835C15.1136 10.6691 15.4653 9.3606 15.4974 8.01758C15.5966 3.86772 12.1342 0.5 8.0004 0.5ZM8.0004 2.00586C11.3235 2.00586 14.0821 4.6775 14.0033 7.97363C13.9749 9.08002 13.6796 10.1416 13.1273 11.0732C11.7992 13.3133 10.5449 16.1706 9.2954 18.7988C8.85773 19.7191 8.35538 19.9924 7.98864 19.9941C7.62183 19.9959 7.12572 19.7246 6.68204 18.8105C5.41121 16.1923 4.12648 13.3534 2.87056 11.0938C2.32971 10.121 1.9798 9.11653 1.9946 8.00586C2.03995 4.67555 4.67723 2.00586 8.0004 2.00586ZM8.0004 4.25C5.94024 4.25 4.25034 5.94266 4.25034 8.00586C4.25034 10.0691 5.94024 11.75 8.0004 11.75C10.0605 11.75 11.7503 10.0691 11.7503 8.00586C11.7503 5.94266 10.0605 4.25 8.0004 4.25ZM8.0004 5.74414C9.25065 5.74414 10.2446 6.75372 10.2446 8.00586C10.2446 9.258 9.25065 10.2559 8.0004 10.2559C6.7501 10.2559 5.75331 9.258 5.75331 8.00586C5.75331 6.75372 6.7501 5.74414 8.0004 5.74414Z"
                      fill="currentColor"
                    />
                  </svg>
                  Tsc Center, Northern Asia
                </span>
              </a>
              <div class="td_card_info">
                <div class="td_card_info_in">
                  <div class="td_mb_30">
                    <ul
                      class="td_card_meta td_mp_0 td_fs_18 td_medium td_heading_color"
                    >
                      <li>
                        <svg
                          class="td_accent_color"
                          width="22"
                          height="24"
                          viewBox="0 0 22 24"
                          fill="none"
                          xmlns="http://www.w3.org/2000/svg"
                        >
                          <path
                            d="M17.3308 11.7869H19.0049C19.3833 11.7869 19.6913 11.479 19.6913 11.1005V9.42642C19.6913 9.04795 19.3833 8.74003 19.0049 8.74003H17.3308C16.9523 8.74003 16.6444 9.04795 16.6444 9.42642V11.1005C16.6444 11.479 16.9523 11.7869 17.3308 11.7869ZM17.3475 9.44316H18.9881V11.0838H17.3475V9.44316ZM17.3308 16.24H19.0049C19.3833 16.24 19.6913 15.9321 19.6913 15.5536V13.8795C19.6913 13.5011 19.3833 13.1932 19.0049 13.1932H17.3308C16.9523 13.1932 16.6444 13.5011 16.6444 13.8795V15.5536C16.6444 15.9321 16.9523 16.24 17.3308 16.24ZM17.3475 13.8963H18.9881V15.5369H17.3475V13.8963ZM12.5535 11.7869H14.2276C14.606 11.7869 14.914 11.479 14.914 11.1005V9.42642C14.914 9.04795 14.606 8.74003 14.2276 8.74003H12.5535C12.175 8.74003 11.8671 9.04795 11.8671 9.42642V11.1005C11.8671 11.479 12.175 11.7869 12.5535 11.7869ZM12.5702 9.44316H14.2108V11.0838H12.5702V9.44316ZM4.67294 17.4375H2.99884C2.62037 17.4375 2.31245 17.7454 2.31245 18.1239V19.798C2.31245 20.1765 2.62037 20.4844 2.99884 20.4844H4.67294C5.05141 20.4844 5.35933 20.1765 5.35933 19.798V18.1239C5.35933 17.7454 5.05141 17.4375 4.67294 17.4375ZM4.6562 19.7812H3.01558V18.1406H4.6562V19.7812ZM4.67294 8.74003H2.99884C2.62037 8.74003 2.31245 9.04795 2.31245 9.42642V11.1005C2.31245 11.479 2.62037 11.7869 2.99884 11.7869H4.67294C5.05141 11.7869 5.35933 11.479 5.35933 11.1005V9.42642C5.35933 9.04791 5.05141 8.74003 4.67294 8.74003ZM4.6562 11.0838H3.01558V9.44316H4.6562V11.0838ZM12.5535 16.1356H14.2276C14.606 16.1356 14.914 15.8277 14.914 15.4493V13.7752C14.914 13.3967 14.606 13.0888 14.2276 13.0888H12.5535C12.175 13.0888 11.8671 13.3967 11.8671 13.7752V15.4493C11.8671 15.8277 12.175 16.1356 12.5535 16.1356ZM12.5702 13.7919H14.2108V15.4325H12.5702V13.7919ZM20.0404 1.60659H18.5373V1.06908C18.5373 0.479578 18.0578 0 17.4683 0H17.3068C16.7174 0 16.2378 0.479578 16.2378 1.06908V1.60659H5.76592V1.06908C5.76592 0.479578 5.28634 0 4.69684 0H4.53541C3.94591 0 3.46633 0.479578 3.46633 1.06908V1.60659H1.96328C0.992734 1.60659 0.203125 2.3962 0.203125 3.36675V22.2422C0.203125 23.2115 0.991656 24 1.96094 24H20.0429C21.0122 24 21.8007 23.2115 21.8007 22.2422V3.36675C21.8006 2.3962 21.011 1.60659 20.0404 1.60659ZM16.9409 1.06908C16.9409 0.867281 17.1051 0.703125 17.3069 0.703125H17.4683C17.6701 0.703125 17.8343 0.867281 17.8343 1.06908V1.60659H16.9409V1.06908ZM4.1695 1.06908C4.1695 0.867281 4.33366 0.703125 4.53545 0.703125H4.69689C4.89869 0.703125 5.06284 0.867281 5.06284 1.06908V1.60659H4.16955V1.06908H4.1695ZM21.0975 22.2422C21.0975 22.8238 20.6244 23.2969 20.0428 23.2969H1.96089C1.37931 23.2969 0.906203 22.8238 0.906203 22.2422V22.24C1.20077 22.4619 1.56691 22.5938 1.96328 22.5938H16.2172C16.6873 22.5938 17.1294 22.4107 17.4618 22.0782L21.0975 18.4425V22.2422ZM17.1031 21.4425C17.1306 21.3288 17.1456 21.2101 17.1456 21.088V18.7413C17.1456 18.2988 17.5057 17.9387 17.9482 17.9387H20.2949C20.417 17.9387 20.5357 17.9237 20.6494 17.8962L17.1031 21.4425ZM21.0975 6.63066H6.11748C5.92333 6.63066 5.76592 6.78806 5.76592 6.98222C5.76592 7.17637 5.92333 7.33378 6.11748 7.33378H21.0975V16.4331C21.0975 16.8756 20.7375 17.2357 20.2949 17.2357H17.9482C17.118 17.2357 16.4425 17.9111 16.4425 18.7413V21.0881C16.4425 21.5306 16.0825 21.8907 15.64 21.8907H1.96328C1.38044 21.8907 0.90625 21.4165 0.90625 20.8336V7.33378H4.71123C4.90539 7.33378 5.0628 7.17637 5.0628 6.98222C5.0628 6.78806 4.90539 6.63066 4.71123 6.63066H0.906203V3.36675C0.906203 2.78391 1.38039 2.30972 1.96323 2.30972H3.46633V3.34341C3.46633 3.93291 3.94591 4.41248 4.53541 4.41248C4.72956 4.41248 4.88697 4.25508 4.88697 4.06092C4.88697 3.86677 4.72956 3.70936 4.53541 3.70936C4.33361 3.70936 4.16945 3.5452 4.16945 3.34341V2.30972H16.2378V3.34341C16.2378 3.93291 16.7174 4.41248 17.3069 4.41248C17.501 4.41248 17.6584 4.25508 17.6584 4.06092C17.6584 3.86677 17.501 3.70936 17.3069 3.70936C17.1051 3.70936 16.9409 3.5452 16.9409 3.34341V2.30972H20.0405C20.6233 2.30972 21.0975 2.78391 21.0975 3.36675V6.63066ZM4.67294 13.0888H2.99884C2.62037 13.0888 2.31245 13.3967 2.31245 13.7752V15.4493C2.31245 15.8277 2.62037 16.1356 2.99884 16.1356H4.67294C5.05141 16.1356 5.35933 15.8277 5.35933 15.4493V13.7752C5.35933 13.3966 5.05141 13.0888 4.67294 13.0888ZM4.6562 15.4325H3.01558V13.7919H4.6562V15.4325ZM7.77616 11.7869H9.45025C9.82872 11.7869 10.1366 11.479 10.1366 11.1005V9.42642C10.1366 9.04795 9.82872 8.74003 9.45025 8.74003H7.77616C7.39769 8.74003 7.08977 9.04795 7.08977 9.42642V11.1005C7.08977 11.479 7.39769 11.7869 7.77616 11.7869ZM7.79289 9.44316H9.43352V11.0838H7.79289V9.44316ZM12.5698 19.7812C12.5611 19.5948 12.4072 19.4464 12.2186 19.4464C12.0244 19.4464 11.867 19.6038 11.867 19.798C11.867 20.1765 12.175 20.4844 12.5534 20.4844H14.2275C14.606 20.4844 14.9139 20.1765 14.9139 19.798V18.1239C14.9139 17.7454 14.606 17.4375 14.2275 17.4375H12.5534C12.175 17.4375 11.867 17.7454 11.867 18.1239V18.6067C11.867 18.8009 12.0244 18.9583 12.2186 18.9583C12.4127 18.9583 12.5702 18.8009 12.5702 18.6067V18.1406H14.2108V19.7812H12.5698ZM7.77616 16.1356H9.45025C9.82872 16.1356 10.1366 15.8277 10.1366 15.4493V13.7752C10.1366 13.3967 9.82872 13.0888 9.45025 13.0888H7.77616C7.39769 13.0888 7.08977 13.3967 7.08977 13.7752V15.4493C7.08977 15.8277 7.39769 16.1356 7.77616 16.1356ZM7.79289 13.7919H9.43352V15.4325H7.79289V13.7919ZM7.77616 20.4844H9.45025C9.82872 20.4844 10.1366 20.1765 10.1366 19.798V18.1239C10.1366 17.7454 9.82872 17.4375 9.45025 17.4375H7.77616C7.39769 17.4375 7.08977 17.7454 7.08977 18.1239V19.798C7.08977 20.1765 7.39769 20.4844 7.77616 20.4844ZM7.79289 18.1406H9.43352V19.7812H7.79289V18.1406Z"
                            fill="currentColor"
                          />
                        </svg>
                        <span>Jan 23 , 2024</span>
                      </li>
                      <li>
                        <svg
                          class="td_accent_color"
                          width="24"
                          height="24"
                          viewBox="0 0 24 24"
                          fill="none"
                          xmlns="http://www.w3.org/2000/svg"
                        >
                          <g>
                            <path
                              d="M12 24C18.616 24 24 18.616 24 12C24 5.38401 18.6161 0 12 0C5.38394 0 0 5.38401 0 12C0 18.616 5.38401 24 12 24ZM12 1.59997C17.736 1.59997 22.4 6.26396 22.4 12C22.4 17.736 17.736 22.4 12 22.4C6.26396 22.4 1.59997 17.736 1.59997 12C1.59997 6.26396 6.26402 1.59997 12 1.59997Z"
                              fill="currentColor"
                            />
                            <path
                              d="M15.4992 15.8209C15.6472 15.9408 15.8232 15.9969 15.9992 15.9969C16.2352 15.9969 16.4672 15.8929 16.6232 15.6969C16.8992 15.3529 16.8431 14.8489 16.4992 14.5729L12.7992 11.6129V5.59686C12.7992 5.15686 12.4392 4.79688 11.9992 4.79688C11.5592 4.79688 11.1992 5.15686 11.1992 5.59686V11.9969C11.1992 12.2409 11.3112 12.4689 11.4992 12.6209L15.4992 15.8209Z"
                              fill="currentColor"
                            />
                          </g>
                          <defs>
                            <clipPath>
                              <rect width="24" height="24" fill="white" />
                            </clipPath>
                          </defs>
                        </svg>
                        <span>10.00 am - 11.30 am</span>
                      </li>
                    </ul>
                  </div>
                  <h2 class="td_card_title td_fs_32 td_semibold td_mb_20">
                    <a href="event-details.html"
                      >Innovate 2024: BBA Admission Conference</a
                    >
                  </h2>
                  <p class="td_mb_30 td_fs_18">
                    Education is a dynamic and evolving field that plays a
                    crucial role in shaping individuals and societies. While
                    there are significant challenges,
                  </p>
                  <a
                    href="event-details.html"
                    class="td_btn td_style_1 td_radius_10 td_medium"
                  >
                    <span class="td_btn_in td_white_color td_accent_bg">
                      <span>Learn More</span>
                      <svg
                        width="19"
                        height="20"
                        viewBox="0 0 19 20"
                        fill="none"
                        xmlns="http://www.w3.org/2000/svg"
                      >
                        <path
                          d="M15.1575 4.34302L3.84375 15.6567"
                          stroke="currentColor"
                          stroke-width="1.5"
                          stroke-linecap="round"
                          stroke-linejoin="round"
                        ></path>
                        <path
                          d="M15.157 11.4142C15.157 11.4142 16.0887 5.2748 15.157 4.34311C14.2253 3.41142 8.08594 4.34314 8.08594 4.34314"
                          stroke="currentColor"
                          stroke-width="1.5"
                          stroke-linecap="round"
                          stroke-linejoin="round"
                        ></path>
                      </svg>
                    </span>
                  </a>
                </div>
              </div>
            </div>
          </div>
          <div
            class="col-lg-6 td_gap_y_30 flex-wrap d-flex wow fadeInRight"
            data-wow-duration="1s"
            data-wow-delay="0.3s"
          >
            <div class="td_card td_style_1 td_type_1">
              <a href="event-details.html" class="td_card_thumb d-block">
                <img src="assets/img/home_1/event_thumb_2.jpg" alt="" />
                <i class="fa-solid fa-arrow-up-right-from-square"></i>
              </a>
              <div class="td_card_info">
                <div class="td_card_info_in">
                  <div class="td_mb_20">
                    <ul class="td_card_meta td_mp_0 td_medium td_heading_color">
                      <li>
                        <svg
                          class="td_accent_color"
                          width="22"
                          height="24"
                          viewBox="0 0 22 24"
                          fill="none"
                          xmlns="http://www.w3.org/2000/svg"
                        >
                          <path
                            d="M17.3308 11.7869H19.0049C19.3833 11.7869 19.6913 11.479 19.6913 11.1005V9.42642C19.6913 9.04795 19.3833 8.74003 19.0049 8.74003H17.3308C16.9523 8.74003 16.6444 9.04795 16.6444 9.42642V11.1005C16.6444 11.479 16.9523 11.7869 17.3308 11.7869ZM17.3475 9.44316H18.9881V11.0838H17.3475V9.44316ZM17.3308 16.24H19.0049C19.3833 16.24 19.6913 15.9321 19.6913 15.5536V13.8795C19.6913 13.5011 19.3833 13.1932 19.0049 13.1932H17.3308C16.9523 13.1932 16.6444 13.5011 16.6444 13.8795V15.5536C16.6444 15.9321 16.9523 16.24 17.3308 16.24ZM17.3475 13.8963H18.9881V15.5369H17.3475V13.8963ZM12.5535 11.7869H14.2276C14.606 11.7869 14.914 11.479 14.914 11.1005V9.42642C14.914 9.04795 14.606 8.74003 14.2276 8.74003H12.5535C12.175 8.74003 11.8671 9.04795 11.8671 9.42642V11.1005C11.8671 11.479 12.175 11.7869 12.5535 11.7869ZM12.5702 9.44316H14.2108V11.0838H12.5702V9.44316ZM4.67294 17.4375H2.99884C2.62037 17.4375 2.31245 17.7454 2.31245 18.1239V19.798C2.31245 20.1765 2.62037 20.4844 2.99884 20.4844H4.67294C5.05141 20.4844 5.35933 20.1765 5.35933 19.798V18.1239C5.35933 17.7454 5.05141 17.4375 4.67294 17.4375ZM4.6562 19.7812H3.01558V18.1406H4.6562V19.7812ZM4.67294 8.74003H2.99884C2.62037 8.74003 2.31245 9.04795 2.31245 9.42642V11.1005C2.31245 11.479 2.62037 11.7869 2.99884 11.7869H4.67294C5.05141 11.7869 5.35933 11.479 5.35933 11.1005V9.42642C5.35933 9.04791 5.05141 8.74003 4.67294 8.74003ZM4.6562 11.0838H3.01558V9.44316H4.6562V11.0838ZM12.5535 16.1356H14.2276C14.606 16.1356 14.914 15.8277 14.914 15.4493V13.7752C14.914 13.3967 14.606 13.0888 14.2276 13.0888H12.5535C12.175 13.0888 11.8671 13.3967 11.8671 13.7752V15.4493C11.8671 15.8277 12.175 16.1356 12.5535 16.1356ZM12.5702 13.7919H14.2108V15.4325H12.5702V13.7919ZM20.0404 1.60659H18.5373V1.06908C18.5373 0.479578 18.0578 0 17.4683 0H17.3068C16.7174 0 16.2378 0.479578 16.2378 1.06908V1.60659H5.76592V1.06908C5.76592 0.479578 5.28634 0 4.69684 0H4.53541C3.94591 0 3.46633 0.479578 3.46633 1.06908V1.60659H1.96328C0.992734 1.60659 0.203125 2.3962 0.203125 3.36675V22.2422C0.203125 23.2115 0.991656 24 1.96094 24H20.0429C21.0122 24 21.8007 23.2115 21.8007 22.2422V3.36675C21.8006 2.3962 21.011 1.60659 20.0404 1.60659ZM16.9409 1.06908C16.9409 0.867281 17.1051 0.703125 17.3069 0.703125H17.4683C17.6701 0.703125 17.8343 0.867281 17.8343 1.06908V1.60659H16.9409V1.06908ZM4.1695 1.06908C4.1695 0.867281 4.33366 0.703125 4.53545 0.703125H4.69689C4.89869 0.703125 5.06284 0.867281 5.06284 1.06908V1.60659H4.16955V1.06908H4.1695ZM21.0975 22.2422C21.0975 22.8238 20.6244 23.2969 20.0428 23.2969H1.96089C1.37931 23.2969 0.906203 22.8238 0.906203 22.2422V22.24C1.20077 22.4619 1.56691 22.5938 1.96328 22.5938H16.2172C16.6873 22.5938 17.1294 22.4107 17.4618 22.0782L21.0975 18.4425V22.2422ZM17.1031 21.4425C17.1306 21.3288 17.1456 21.2101 17.1456 21.088V18.7413C17.1456 18.2988 17.5057 17.9387 17.9482 17.9387H20.2949C20.417 17.9387 20.5357 17.9237 20.6494 17.8962L17.1031 21.4425ZM21.0975 6.63066H6.11748C5.92333 6.63066 5.76592 6.78806 5.76592 6.98222C5.76592 7.17637 5.92333 7.33378 6.11748 7.33378H21.0975V16.4331C21.0975 16.8756 20.7375 17.2357 20.2949 17.2357H17.9482C17.118 17.2357 16.4425 17.9111 16.4425 18.7413V21.0881C16.4425 21.5306 16.0825 21.8907 15.64 21.8907H1.96328C1.38044 21.8907 0.90625 21.4165 0.90625 20.8336V7.33378H4.71123C4.90539 7.33378 5.0628 7.17637 5.0628 6.98222C5.0628 6.78806 4.90539 6.63066 4.71123 6.63066H0.906203V3.36675C0.906203 2.78391 1.38039 2.30972 1.96323 2.30972H3.46633V3.34341C3.46633 3.93291 3.94591 4.41248 4.53541 4.41248C4.72956 4.41248 4.88697 4.25508 4.88697 4.06092C4.88697 3.86677 4.72956 3.70936 4.53541 3.70936C4.33361 3.70936 4.16945 3.5452 4.16945 3.34341V2.30972H16.2378V3.34341C16.2378 3.93291 16.7174 4.41248 17.3069 4.41248C17.501 4.41248 17.6584 4.25508 17.6584 4.06092C17.6584 3.86677 17.501 3.70936 17.3069 3.70936C17.1051 3.70936 16.9409 3.5452 16.9409 3.34341V2.30972H20.0405C20.6233 2.30972 21.0975 2.78391 21.0975 3.36675V6.63066ZM4.67294 13.0888H2.99884C2.62037 13.0888 2.31245 13.3967 2.31245 13.7752V15.4493C2.31245 15.8277 2.62037 16.1356 2.99884 16.1356H4.67294C5.05141 16.1356 5.35933 15.8277 5.35933 15.4493V13.7752C5.35933 13.3966 5.05141 13.0888 4.67294 13.0888ZM4.6562 15.4325H3.01558V13.7919H4.6562V15.4325ZM7.77616 11.7869H9.45025C9.82872 11.7869 10.1366 11.479 10.1366 11.1005V9.42642C10.1366 9.04795 9.82872 8.74003 9.45025 8.74003H7.77616C7.39769 8.74003 7.08977 9.04795 7.08977 9.42642V11.1005C7.08977 11.479 7.39769 11.7869 7.77616 11.7869ZM7.79289 9.44316H9.43352V11.0838H7.79289V9.44316ZM12.5698 19.7812C12.5611 19.5948 12.4072 19.4464 12.2186 19.4464C12.0244 19.4464 11.867 19.6038 11.867 19.798C11.867 20.1765 12.175 20.4844 12.5534 20.4844H14.2275C14.606 20.4844 14.9139 20.1765 14.9139 19.798V18.1239C14.9139 17.7454 14.606 17.4375 14.2275 17.4375H12.5534C12.175 17.4375 11.867 17.7454 11.867 18.1239V18.6067C11.867 18.8009 12.0244 18.9583 12.2186 18.9583C12.4127 18.9583 12.5702 18.8009 12.5702 18.6067V18.1406H14.2108V19.7812H12.5698ZM7.77616 16.1356H9.45025C9.82872 16.1356 10.1366 15.8277 10.1366 15.4493V13.7752C10.1366 13.3967 9.82872 13.0888 9.45025 13.0888H7.77616C7.39769 13.0888 7.08977 13.3967 7.08977 13.7752V15.4493C7.08977 15.8277 7.39769 16.1356 7.77616 16.1356ZM7.79289 13.7919H9.43352V15.4325H7.79289V13.7919ZM7.77616 20.4844H9.45025C9.82872 20.4844 10.1366 20.1765 10.1366 19.798V18.1239C10.1366 17.7454 9.82872 17.4375 9.45025 17.4375H7.77616C7.39769 17.4375 7.08977 17.7454 7.08977 18.1239V19.798C7.08977 20.1765 7.39769 20.4844 7.77616 20.4844ZM7.79289 18.1406H9.43352V19.7812H7.79289V18.1406Z"
                            fill="currentColor"
                          />
                        </svg>
                        <span>Jan 23 , 2024</span>
                      </li>
                      <li>
                        <svg
                          class="td_accent_color"
                          width="24"
                          height="24"
                          viewBox="0 0 24 24"
                          fill="none"
                          xmlns="http://www.w3.org/2000/svg"
                        >
                          <g>
                            <path
                              d="M12 24C18.616 24 24 18.616 24 12C24 5.38401 18.6161 0 12 0C5.38394 0 0 5.38401 0 12C0 18.616 5.38401 24 12 24ZM12 1.59997C17.736 1.59997 22.4 6.26396 22.4 12C22.4 17.736 17.736 22.4 12 22.4C6.26396 22.4 1.59997 17.736 1.59997 12C1.59997 6.26396 6.26402 1.59997 12 1.59997Z"
                              fill="currentColor"
                            />
                            <path
                              d="M15.4992 15.8209C15.6472 15.9408 15.8232 15.9969 15.9992 15.9969C16.2352 15.9969 16.4672 15.8929 16.6232 15.6969C16.8992 15.3529 16.8431 14.8489 16.4992 14.5729L12.7992 11.6129V5.59686C12.7992 5.15686 12.4392 4.79688 11.9992 4.79688C11.5592 4.79688 11.1992 5.15686 11.1992 5.59686V11.9969C11.1992 12.2409 11.3112 12.4689 11.4992 12.6209L15.4992 15.8209Z"
                              fill="currentColor"
                            />
                          </g>
                          <defs>
                            <clipPath>
                              <rect width="24" height="24" fill="white" />
                            </clipPath>
                          </defs>
                        </svg>
                        <span>10.00 am</span>
                      </li>
                    </ul>
                  </div>
                  <h2 class="td_card_title td_fs_20 td_semibold td_mb_20">
                    <a href="event-details.html"
                      >Education, Research and Innovation (ICERI 2024)</a
                    >
                  </h2>
                  <span class="td_card_location td_medium td_heading_color">
                    <svg
                      class="td_accent_color"
                      width="16"
                      height="22"
                      viewBox="0 0 16 22"
                      fill="none"
                      xmlns="http://www.w3.org/2000/svg"
                    >
                      <path
                        d="M8.0004 0.5C3.86669 0.5 0.554996 3.86526 0.500458 7.98242C0.48345 9.42271 0.942105 10.7046 1.56397 11.8232C2.76977 13.9928 4.04435 16.8182 5.32856 19.4639C5.9286 20.7002 6.89863 21.5052 8.0004 21.5C9.10217 21.4948 10.0665 20.6836 10.6575 19.4404C11.9197 16.7856 13.1685 13.9496 14.4223 11.835C15.1136 10.6691 15.4653 9.3606 15.4974 8.01758C15.5966 3.86772 12.1342 0.5 8.0004 0.5ZM8.0004 2.00586C11.3235 2.00586 14.0821 4.6775 14.0033 7.97363C13.9749 9.08002 13.6796 10.1416 13.1273 11.0732C11.7992 13.3133 10.5449 16.1706 9.2954 18.7988C8.85773 19.7191 8.35538 19.9924 7.98864 19.9941C7.62183 19.9959 7.12572 19.7246 6.68204 18.8105C5.41121 16.1923 4.12648 13.3534 2.87056 11.0938C2.32971 10.121 1.9798 9.11653 1.9946 8.00586C2.03995 4.67555 4.67723 2.00586 8.0004 2.00586ZM8.0004 4.25C5.94024 4.25 4.25034 5.94266 4.25034 8.00586C4.25034 10.0691 5.94024 11.75 8.0004 11.75C10.0605 11.75 11.7503 10.0691 11.7503 8.00586C11.7503 5.94266 10.0605 4.25 8.0004 4.25ZM8.0004 5.74414C9.25065 5.74414 10.2446 6.75372 10.2446 8.00586C10.2446 9.258 9.25065 10.2559 8.0004 10.2559C6.7501 10.2559 5.75331 9.258 5.75331 8.00586C5.75331 6.75372 6.7501 5.74414 8.0004 5.74414Z"
                        fill="currentColor"
                      />
                    </svg>
                    Tsc Center, Northern Asia
                  </span>
                </div>
              </div>
            </div>
            <div class="td_card td_style_1 td_type_1">
              <a href="event-details.html" class="td_card_thumb d-block">
                <img src="assets/img/home_1/event_thumb_3.jpg" alt="" />
                <i class="fa-solid fa-arrow-up-right-from-square"></i>
              </a>
              <div class="td_card_info">
                <div class="td_card_info_in">
                  <div class="td_mb_20">
                    <ul class="td_card_meta td_mp_0 td_medium td_heading_color">
                      <li>
                        <svg
                          class="td_accent_color"
                          width="22"
                          height="24"
                          viewBox="0 0 22 24"
                          fill="none"
                          xmlns="http://www.w3.org/2000/svg"
                        >
                          <path
                            d="M17.3308 11.7869H19.0049C19.3833 11.7869 19.6913 11.479 19.6913 11.1005V9.42642C19.6913 9.04795 19.3833 8.74003 19.0049 8.74003H17.3308C16.9523 8.74003 16.6444 9.04795 16.6444 9.42642V11.1005C16.6444 11.479 16.9523 11.7869 17.3308 11.7869ZM17.3475 9.44316H18.9881V11.0838H17.3475V9.44316ZM17.3308 16.24H19.0049C19.3833 16.24 19.6913 15.9321 19.6913 15.5536V13.8795C19.6913 13.5011 19.3833 13.1932 19.0049 13.1932H17.3308C16.9523 13.1932 16.6444 13.5011 16.6444 13.8795V15.5536C16.6444 15.9321 16.9523 16.24 17.3308 16.24ZM17.3475 13.8963H18.9881V15.5369H17.3475V13.8963ZM12.5535 11.7869H14.2276C14.606 11.7869 14.914 11.479 14.914 11.1005V9.42642C14.914 9.04795 14.606 8.74003 14.2276 8.74003H12.5535C12.175 8.74003 11.8671 9.04795 11.8671 9.42642V11.1005C11.8671 11.479 12.175 11.7869 12.5535 11.7869ZM12.5702 9.44316H14.2108V11.0838H12.5702V9.44316ZM4.67294 17.4375H2.99884C2.62037 17.4375 2.31245 17.7454 2.31245 18.1239V19.798C2.31245 20.1765 2.62037 20.4844 2.99884 20.4844H4.67294C5.05141 20.4844 5.35933 20.1765 5.35933 19.798V18.1239C5.35933 17.7454 5.05141 17.4375 4.67294 17.4375ZM4.6562 19.7812H3.01558V18.1406H4.6562V19.7812ZM4.67294 8.74003H2.99884C2.62037 8.74003 2.31245 9.04795 2.31245 9.42642V11.1005C2.31245 11.479 2.62037 11.7869 2.99884 11.7869H4.67294C5.05141 11.7869 5.35933 11.479 5.35933 11.1005V9.42642C5.35933 9.04791 5.05141 8.74003 4.67294 8.74003ZM4.6562 11.0838H3.01558V9.44316H4.6562V11.0838ZM12.5535 16.1356H14.2276C14.606 16.1356 14.914 15.8277 14.914 15.4493V13.7752C14.914 13.3967 14.606 13.0888 14.2276 13.0888H12.5535C12.175 13.0888 11.8671 13.3967 11.8671 13.7752V15.4493C11.8671 15.8277 12.175 16.1356 12.5535 16.1356ZM12.5702 13.7919H14.2108V15.4325H12.5702V13.7919ZM20.0404 1.60659H18.5373V1.06908C18.5373 0.479578 18.0578 0 17.4683 0H17.3068C16.7174 0 16.2378 0.479578 16.2378 1.06908V1.60659H5.76592V1.06908C5.76592 0.479578 5.28634 0 4.69684 0H4.53541C3.94591 0 3.46633 0.479578 3.46633 1.06908V1.60659H1.96328C0.992734 1.60659 0.203125 2.3962 0.203125 3.36675V22.2422C0.203125 23.2115 0.991656 24 1.96094 24H20.0429C21.0122 24 21.8007 23.2115 21.8007 22.2422V3.36675C21.8006 2.3962 21.011 1.60659 20.0404 1.60659ZM16.9409 1.06908C16.9409 0.867281 17.1051 0.703125 17.3069 0.703125H17.4683C17.6701 0.703125 17.8343 0.867281 17.8343 1.06908V1.60659H16.9409V1.06908ZM4.1695 1.06908C4.1695 0.867281 4.33366 0.703125 4.53545 0.703125H4.69689C4.89869 0.703125 5.06284 0.867281 5.06284 1.06908V1.60659H4.16955V1.06908H4.1695ZM21.0975 22.2422C21.0975 22.8238 20.6244 23.2969 20.0428 23.2969H1.96089C1.37931 23.2969 0.906203 22.8238 0.906203 22.2422V22.24C1.20077 22.4619 1.56691 22.5938 1.96328 22.5938H16.2172C16.6873 22.5938 17.1294 22.4107 17.4618 22.0782L21.0975 18.4425V22.2422ZM17.1031 21.4425C17.1306 21.3288 17.1456 21.2101 17.1456 21.088V18.7413C17.1456 18.2988 17.5057 17.9387 17.9482 17.9387H20.2949C20.417 17.9387 20.5357 17.9237 20.6494 17.8962L17.1031 21.4425ZM21.0975 6.63066H6.11748C5.92333 6.63066 5.76592 6.78806 5.76592 6.98222C5.76592 7.17637 5.92333 7.33378 6.11748 7.33378H21.0975V16.4331C21.0975 16.8756 20.7375 17.2357 20.2949 17.2357H17.9482C17.118 17.2357 16.4425 17.9111 16.4425 18.7413V21.0881C16.4425 21.5306 16.0825 21.8907 15.64 21.8907H1.96328C1.38044 21.8907 0.90625 21.4165 0.90625 20.8336V7.33378H4.71123C4.90539 7.33378 5.0628 7.17637 5.0628 6.98222C5.0628 6.78806 4.90539 6.63066 4.71123 6.63066H0.906203V3.36675C0.906203 2.78391 1.38039 2.30972 1.96323 2.30972H3.46633V3.34341C3.46633 3.93291 3.94591 4.41248 4.53541 4.41248C4.72956 4.41248 4.88697 4.25508 4.88697 4.06092C4.88697 3.86677 4.72956 3.70936 4.53541 3.70936C4.33361 3.70936 4.16945 3.5452 4.16945 3.34341V2.30972H16.2378V3.34341C16.2378 3.93291 16.7174 4.41248 17.3069 4.41248C17.501 4.41248 17.6584 4.25508 17.6584 4.06092C17.6584 3.86677 17.501 3.70936 17.3069 3.70936C17.1051 3.70936 16.9409 3.5452 16.9409 3.34341V2.30972H20.0405C20.6233 2.30972 21.0975 2.78391 21.0975 3.36675V6.63066ZM4.67294 13.0888H2.99884C2.62037 13.0888 2.31245 13.3967 2.31245 13.7752V15.4493C2.31245 15.8277 2.62037 16.1356 2.99884 16.1356H4.67294C5.05141 16.1356 5.35933 15.8277 5.35933 15.4493V13.7752C5.35933 13.3966 5.05141 13.0888 4.67294 13.0888ZM4.6562 15.4325H3.01558V13.7919H4.6562V15.4325ZM7.77616 11.7869H9.45025C9.82872 11.7869 10.1366 11.479 10.1366 11.1005V9.42642C10.1366 9.04795 9.82872 8.74003 9.45025 8.74003H7.77616C7.39769 8.74003 7.08977 9.04795 7.08977 9.42642V11.1005C7.08977 11.479 7.39769 11.7869 7.77616 11.7869ZM7.79289 9.44316H9.43352V11.0838H7.79289V9.44316ZM12.5698 19.7812C12.5611 19.5948 12.4072 19.4464 12.2186 19.4464C12.0244 19.4464 11.867 19.6038 11.867 19.798C11.867 20.1765 12.175 20.4844 12.5534 20.4844H14.2275C14.606 20.4844 14.9139 20.1765 14.9139 19.798V18.1239C14.9139 17.7454 14.606 17.4375 14.2275 17.4375H12.5534C12.175 17.4375 11.867 17.7454 11.867 18.1239V18.6067C11.867 18.8009 12.0244 18.9583 12.2186 18.9583C12.4127 18.9583 12.5702 18.8009 12.5702 18.6067V18.1406H14.2108V19.7812H12.5698ZM7.77616 16.1356H9.45025C9.82872 16.1356 10.1366 15.8277 10.1366 15.4493V13.7752C10.1366 13.3967 9.82872 13.0888 9.45025 13.0888H7.77616C7.39769 13.0888 7.08977 13.3967 7.08977 13.7752V15.4493C7.08977 15.8277 7.39769 16.1356 7.77616 16.1356ZM7.79289 13.7919H9.43352V15.4325H7.79289V13.7919ZM7.77616 20.4844H9.45025C9.82872 20.4844 10.1366 20.1765 10.1366 19.798V18.1239C10.1366 17.7454 9.82872 17.4375 9.45025 17.4375H7.77616C7.39769 17.4375 7.08977 17.7454 7.08977 18.1239V19.798C7.08977 20.1765 7.39769 20.4844 7.77616 20.4844ZM7.79289 18.1406H9.43352V19.7812H7.79289V18.1406Z"
                            fill="currentColor"
                          />
                        </svg>
                        <span>Jan 23 , 2024</span>
                      </li>
                      <li>
                        <svg
                          class="td_accent_color"
                          width="24"
                          height="24"
                          viewBox="0 0 24 24"
                          fill="none"
                          xmlns="http://www.w3.org/2000/svg"
                        >
                          <g>
                            <path
                              d="M12 24C18.616 24 24 18.616 24 12C24 5.38401 18.6161 0 12 0C5.38394 0 0 5.38401 0 12C0 18.616 5.38401 24 12 24ZM12 1.59997C17.736 1.59997 22.4 6.26396 22.4 12C22.4 17.736 17.736 22.4 12 22.4C6.26396 22.4 1.59997 17.736 1.59997 12C1.59997 6.26396 6.26402 1.59997 12 1.59997Z"
                              fill="currentColor"
                            />
                            <path
                              d="M15.4992 15.8209C15.6472 15.9408 15.8232 15.9969 15.9992 15.9969C16.2352 15.9969 16.4672 15.8929 16.6232 15.6969C16.8992 15.3529 16.8431 14.8489 16.4992 14.5729L12.7992 11.6129V5.59686C12.7992 5.15686 12.4392 4.79688 11.9992 4.79688C11.5592 4.79688 11.1992 5.15686 11.1992 5.59686V11.9969C11.1992 12.2409 11.3112 12.4689 11.4992 12.6209L15.4992 15.8209Z"
                              fill="currentColor"
                            />
                          </g>
                          <defs>
                            <clipPath>
                              <rect width="24" height="24" fill="white" />
                            </clipPath>
                          </defs>
                        </svg>
                        <span>10.00 am</span>
                      </li>
                    </ul>
                  </div>
                  <h2 class="td_card_title td_fs_20 td_semibold td_mb_20">
                    <a href="event-details.html"
                      >Innovation, Creativity and Emerging Research (ICERI
                      2024)</a
                    >
                  </h2>
                  <span class="td_card_location td_medium td_heading_color">
                    <svg
                      class="td_accent_color"
                      width="16"
                      height="22"
                      viewBox="0 0 16 22"
                      fill="none"
                      xmlns="http://www.w3.org/2000/svg"
                    >
                      <path
                        d="M8.0004 0.5C3.86669 0.5 0.554996 3.86526 0.500458 7.98242C0.48345 9.42271 0.942105 10.7046 1.56397 11.8232C2.76977 13.9928 4.04435 16.8182 5.32856 19.4639C5.9286 20.7002 6.89863 21.5052 8.0004 21.5C9.10217 21.4948 10.0665 20.6836 10.6575 19.4404C11.9197 16.7856 13.1685 13.9496 14.4223 11.835C15.1136 10.6691 15.4653 9.3606 15.4974 8.01758C15.5966 3.86772 12.1342 0.5 8.0004 0.5ZM8.0004 2.00586C11.3235 2.00586 14.0821 4.6775 14.0033 7.97363C13.9749 9.08002 13.6796 10.1416 13.1273 11.0732C11.7992 13.3133 10.5449 16.1706 9.2954 18.7988C8.85773 19.7191 8.35538 19.9924 7.98864 19.9941C7.62183 19.9959 7.12572 19.7246 6.68204 18.8105C5.41121 16.1923 4.12648 13.3534 2.87056 11.0938C2.32971 10.121 1.9798 9.11653 1.9946 8.00586C2.03995 4.67555 4.67723 2.00586 8.0004 2.00586ZM8.0004 4.25C5.94024 4.25 4.25034 5.94266 4.25034 8.00586C4.25034 10.0691 5.94024 11.75 8.0004 11.75C10.0605 11.75 11.7503 10.0691 11.7503 8.00586C11.7503 5.94266 10.0605 4.25 8.0004 4.25ZM8.0004 5.74414C9.25065 5.74414 10.2446 6.75372 10.2446 8.00586C10.2446 9.258 9.25065 10.2559 8.0004 10.2559C6.7501 10.2559 5.75331 9.258 5.75331 8.00586C5.75331 6.75372 6.7501 5.74414 8.0004 5.74414Z"
                        fill="currentColor"
                      />
                    </svg>
                    Tsc Center, Northern Asia
                  </span>
                </div>
              </div>
            </div>
            <div class="td_card td_style_1 td_type_1">
              <a href="event-details.html" class="td_card_thumb d-block">
                <img src="assets/img/home_1/event_thumb_4.jpg" alt="" />
                <i class="fa-solid fa-arrow-up-right-from-square"></i>
              </a>
              <div class="td_card_info">
                <div class="td_card_info_in">
                  <div class="td_mb_20">
                    <ul class="td_card_meta td_mp_0 td_medium td_heading_color">
                      <li>
                        <svg
                          class="td_accent_color"
                          width="22"
                          height="24"
                          viewBox="0 0 22 24"
                          fill="none"
                          xmlns="http://www.w3.org/2000/svg"
                        >
                          <path
                            d="M17.3308 11.7869H19.0049C19.3833 11.7869 19.6913 11.479 19.6913 11.1005V9.42642C19.6913 9.04795 19.3833 8.74003 19.0049 8.74003H17.3308C16.9523 8.74003 16.6444 9.04795 16.6444 9.42642V11.1005C16.6444 11.479 16.9523 11.7869 17.3308 11.7869ZM17.3475 9.44316H18.9881V11.0838H17.3475V9.44316ZM17.3308 16.24H19.0049C19.3833 16.24 19.6913 15.9321 19.6913 15.5536V13.8795C19.6913 13.5011 19.3833 13.1932 19.0049 13.1932H17.3308C16.9523 13.1932 16.6444 13.5011 16.6444 13.8795V15.5536C16.6444 15.9321 16.9523 16.24 17.3308 16.24ZM17.3475 13.8963H18.9881V15.5369H17.3475V13.8963ZM12.5535 11.7869H14.2276C14.606 11.7869 14.914 11.479 14.914 11.1005V9.42642C14.914 9.04795 14.606 8.74003 14.2276 8.74003H12.5535C12.175 8.74003 11.8671 9.04795 11.8671 9.42642V11.1005C11.8671 11.479 12.175 11.7869 12.5535 11.7869ZM12.5702 9.44316H14.2108V11.0838H12.5702V9.44316ZM4.67294 17.4375H2.99884C2.62037 17.4375 2.31245 17.7454 2.31245 18.1239V19.798C2.31245 20.1765 2.62037 20.4844 2.99884 20.4844H4.67294C5.05141 20.4844 5.35933 20.1765 5.35933 19.798V18.1239C5.35933 17.7454 5.05141 17.4375 4.67294 17.4375ZM4.6562 19.7812H3.01558V18.1406H4.6562V19.7812ZM4.67294 8.74003H2.99884C2.62037 8.74003 2.31245 9.04795 2.31245 9.42642V11.1005C2.31245 11.479 2.62037 11.7869 2.99884 11.7869H4.67294C5.05141 11.7869 5.35933 11.479 5.35933 11.1005V9.42642C5.35933 9.04791 5.05141 8.74003 4.67294 8.74003ZM4.6562 11.0838H3.01558V9.44316H4.6562V11.0838ZM12.5535 16.1356H14.2276C14.606 16.1356 14.914 15.8277 14.914 15.4493V13.7752C14.914 13.3967 14.606 13.0888 14.2276 13.0888H12.5535C12.175 13.0888 11.8671 13.3967 11.8671 13.7752V15.4493C11.8671 15.8277 12.175 16.1356 12.5535 16.1356ZM12.5702 13.7919H14.2108V15.4325H12.5702V13.7919ZM20.0404 1.60659H18.5373V1.06908C18.5373 0.479578 18.0578 0 17.4683 0H17.3068C16.7174 0 16.2378 0.479578 16.2378 1.06908V1.60659H5.76592V1.06908C5.76592 0.479578 5.28634 0 4.69684 0H4.53541C3.94591 0 3.46633 0.479578 3.46633 1.06908V1.60659H1.96328C0.992734 1.60659 0.203125 2.3962 0.203125 3.36675V22.2422C0.203125 23.2115 0.991656 24 1.96094 24H20.0429C21.0122 24 21.8007 23.2115 21.8007 22.2422V3.36675C21.8006 2.3962 21.011 1.60659 20.0404 1.60659ZM16.9409 1.06908C16.9409 0.867281 17.1051 0.703125 17.3069 0.703125H17.4683C17.6701 0.703125 17.8343 0.867281 17.8343 1.06908V1.60659H16.9409V1.06908ZM4.1695 1.06908C4.1695 0.867281 4.33366 0.703125 4.53545 0.703125H4.69689C4.89869 0.703125 5.06284 0.867281 5.06284 1.06908V1.60659H4.16955V1.06908H4.1695ZM21.0975 22.2422C21.0975 22.8238 20.6244 23.2969 20.0428 23.2969H1.96089C1.37931 23.2969 0.906203 22.8238 0.906203 22.2422V22.24C1.20077 22.4619 1.56691 22.5938 1.96328 22.5938H16.2172C16.6873 22.5938 17.1294 22.4107 17.4618 22.0782L21.0975 18.4425V22.2422ZM17.1031 21.4425C17.1306 21.3288 17.1456 21.2101 17.1456 21.088V18.7413C17.1456 18.2988 17.5057 17.9387 17.9482 17.9387H20.2949C20.417 17.9387 20.5357 17.9237 20.6494 17.8962L17.1031 21.4425ZM21.0975 6.63066H6.11748C5.92333 6.63066 5.76592 6.78806 5.76592 6.98222C5.76592 7.17637 5.92333 7.33378 6.11748 7.33378H21.0975V16.4331C21.0975 16.8756 20.7375 17.2357 20.2949 17.2357H17.9482C17.118 17.2357 16.4425 17.9111 16.4425 18.7413V21.0881C16.4425 21.5306 16.0825 21.8907 15.64 21.8907H1.96328C1.38044 21.8907 0.90625 21.4165 0.90625 20.8336V7.33378H4.71123C4.90539 7.33378 5.0628 7.17637 5.0628 6.98222C5.0628 6.78806 4.90539 6.63066 4.71123 6.63066H0.906203V3.36675C0.906203 2.78391 1.38039 2.30972 1.96323 2.30972H3.46633V3.34341C3.46633 3.93291 3.94591 4.41248 4.53541 4.41248C4.72956 4.41248 4.88697 4.25508 4.88697 4.06092C4.88697 3.86677 4.72956 3.70936 4.53541 3.70936C4.33361 3.70936 4.16945 3.5452 4.16945 3.34341V2.30972H16.2378V3.34341C16.2378 3.93291 16.7174 4.41248 17.3069 4.41248C17.501 4.41248 17.6584 4.25508 17.6584 4.06092C17.6584 3.86677 17.501 3.70936 17.3069 3.70936C17.1051 3.70936 16.9409 3.5452 16.9409 3.34341V2.30972H20.0405C20.6233 2.30972 21.0975 2.78391 21.0975 3.36675V6.63066ZM4.67294 13.0888H2.99884C2.62037 13.0888 2.31245 13.3967 2.31245 13.7752V15.4493C2.31245 15.8277 2.62037 16.1356 2.99884 16.1356H4.67294C5.05141 16.1356 5.35933 15.8277 5.35933 15.4493V13.7752C5.35933 13.3966 5.05141 13.0888 4.67294 13.0888ZM4.6562 15.4325H3.01558V13.7919H4.6562V15.4325ZM7.77616 11.7869H9.45025C9.82872 11.7869 10.1366 11.479 10.1366 11.1005V9.42642C10.1366 9.04795 9.82872 8.74003 9.45025 8.74003H7.77616C7.39769 8.74003 7.08977 9.04795 7.08977 9.42642V11.1005C7.08977 11.479 7.39769 11.7869 7.77616 11.7869ZM7.79289 9.44316H9.43352V11.0838H7.79289V9.44316ZM12.5698 19.7812C12.5611 19.5948 12.4072 19.4464 12.2186 19.4464C12.0244 19.4464 11.867 19.6038 11.867 19.798C11.867 20.1765 12.175 20.4844 12.5534 20.4844H14.2275C14.606 20.4844 14.9139 20.1765 14.9139 19.798V18.1239C14.9139 17.7454 14.606 17.4375 14.2275 17.4375H12.5534C12.175 17.4375 11.867 17.7454 11.867 18.1239V18.6067C11.867 18.8009 12.0244 18.9583 12.2186 18.9583C12.4127 18.9583 12.5702 18.8009 12.5702 18.6067V18.1406H14.2108V19.7812H12.5698ZM7.77616 16.1356H9.45025C9.82872 16.1356 10.1366 15.8277 10.1366 15.4493V13.7752C10.1366 13.3967 9.82872 13.0888 9.45025 13.0888H7.77616C7.39769 13.0888 7.08977 13.3967 7.08977 13.7752V15.4493C7.08977 15.8277 7.39769 16.1356 7.77616 16.1356ZM7.79289 13.7919H9.43352V15.4325H7.79289V13.7919ZM7.77616 20.4844H9.45025C9.82872 20.4844 10.1366 20.1765 10.1366 19.798V18.1239C10.1366 17.7454 9.82872 17.4375 9.45025 17.4375H7.77616C7.39769 17.4375 7.08977 17.7454 7.08977 18.1239V19.798C7.08977 20.1765 7.39769 20.4844 7.77616 20.4844ZM7.79289 18.1406H9.43352V19.7812H7.79289V18.1406Z"
                            fill="currentColor"
                          />
                        </svg>
                        <span>Jan 23 , 2024</span>
                      </li>
                      <li>
                        <svg
                          class="td_accent_color"
                          width="24"
                          height="24"
                          viewBox="0 0 24 24"
                          fill="none"
                          xmlns="http://www.w3.org/2000/svg"
                        >
                          <g>
                            <path
                              d="M12 24C18.616 24 24 18.616 24 12C24 5.38401 18.6161 0 12 0C5.38394 0 0 5.38401 0 12C0 18.616 5.38401 24 12 24ZM12 1.59997C17.736 1.59997 22.4 6.26396 22.4 12C22.4 17.736 17.736 22.4 12 22.4C6.26396 22.4 1.59997 17.736 1.59997 12C1.59997 6.26396 6.26402 1.59997 12 1.59997Z"
                              fill="currentColor"
                            />
                            <path
                              d="M15.4992 15.8209C15.6472 15.9408 15.8232 15.9969 15.9992 15.9969C16.2352 15.9969 16.4672 15.8929 16.6232 15.6969C16.8992 15.3529 16.8431 14.8489 16.4992 14.5729L12.7992 11.6129V5.59686C12.7992 5.15686 12.4392 4.79688 11.9992 4.79688C11.5592 4.79688 11.1992 5.15686 11.1992 5.59686V11.9969C11.1992 12.2409 11.3112 12.4689 11.4992 12.6209L15.4992 15.8209Z"
                              fill="currentColor"
                            />
                          </g>
                          <defs>
                            <clipPath>
                              <rect width="24" height="24" fill="white" />
                            </clipPath>
                          </defs>
                        </svg>
                        <span>10.00 am</span>
                      </li>
                    </ul>
                  </div>
                  <h2 class="td_card_title td_fs_20 td_semibold td_mb_20">
                    <a href="event-details.html"
                      >Intellectual Curiosity & Educational Reform (ICERI
                      2024)</a
                    >
                  </h2>
                  <span class="td_card_location td_medium td_heading_color">
                    <svg
                      class="td_accent_color"
                      width="16"
                      height="22"
                      viewBox="0 0 16 22"
                      fill="none"
                      xmlns="http://www.w3.org/2000/svg"
                    >
                      <path
                        d="M8.0004 0.5C3.86669 0.5 0.554996 3.86526 0.500458 7.98242C0.48345 9.42271 0.942105 10.7046 1.56397 11.8232C2.76977 13.9928 4.04435 16.8182 5.32856 19.4639C5.9286 20.7002 6.89863 21.5052 8.0004 21.5C9.10217 21.4948 10.0665 20.6836 10.6575 19.4404C11.9197 16.7856 13.1685 13.9496 14.4223 11.835C15.1136 10.6691 15.4653 9.3606 15.4974 8.01758C15.5966 3.86772 12.1342 0.5 8.0004 0.5ZM8.0004 2.00586C11.3235 2.00586 14.0821 4.6775 14.0033 7.97363C13.9749 9.08002 13.6796 10.1416 13.1273 11.0732C11.7992 13.3133 10.5449 16.1706 9.2954 18.7988C8.85773 19.7191 8.35538 19.9924 7.98864 19.9941C7.62183 19.9959 7.12572 19.7246 6.68204 18.8105C5.41121 16.1923 4.12648 13.3534 2.87056 11.0938C2.32971 10.121 1.9798 9.11653 1.9946 8.00586C2.03995 4.67555 4.67723 2.00586 8.0004 2.00586ZM8.0004 4.25C5.94024 4.25 4.25034 5.94266 4.25034 8.00586C4.25034 10.0691 5.94024 11.75 8.0004 11.75C10.0605 11.75 11.7503 10.0691 11.7503 8.00586C11.7503 5.94266 10.0605 4.25 8.0004 4.25ZM8.0004 5.74414C9.25065 5.74414 10.2446 6.75372 10.2446 8.00586C10.2446 9.258 9.25065 10.2559 8.0004 10.2559C6.7501 10.2559 5.75331 9.258 5.75331 8.00586C5.75331 6.75372 6.7501 5.74414 8.0004 5.74414Z"
                        fill="currentColor"
                      />
                    </svg>
                    Tsc Center, Northern Asia
                  </span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="td_height_100 td_height_lg_80"></div>
    </section> -->
     <!-- End Event Schedule Section -->
     <!-- Start Testimonial Section -->
     <!-- <section class="td_heading_bg td_hobble">
      <div class="td_height_112 td_height_lg_75"></div>
      <div class="container">
        <div
          class="td_section_heading td_style_1 text-center wow fadeInUp"
          data-wow-duration="1s"
          data-wow-delay="0.2s"
        >
          <h2 class="td_section_title td_fs_48 mb-0 td_white_color">
            Start your journey With Us
          </h2>
          <p
            class="td_section_subtitle td_fs_18 mb-0 td_white_color td_opacity_7"
          >
            Education is a dynamic and evolving field that plays a crucial
            <br />role in shaping individuals and societies. While significant
            <br />challenges,
          </p>
        </div>
        <div class="td_height_50 td_height_lg_50"></div>
        <div class="row align-items-center td_gap_y_40">
          <div
            class="col-lg-6 wow fadeInUp"
            data-wow-duration="1s"
            data-wow-delay="0.2s"
          >
            <div class="td_testimonial_img_wrap">
              <img
                src="assets/img/home_1/testimonial_img.png"
                alt=""
                class="td_testimonial_img"
              />
              <span class="td_testimonial_img_shape_1"><span></span></span>
              <span
                class="td_testimonial_img_shape_2 td_accent_color td_hover_layer_3"
              >
                <svg
                  width="145"
                  height="165"
                  viewBox="0 0 145 165"
                  fill="none"
                  xmlns="http://www.w3.org/2000/svg"
                >
                  <path
                    d="M145.003 25.9077L139.516 27.7024L143.814 31.5573L145.003 25.9077ZM69.5244 11.4999L69.2176 11.1051L69.5244 11.4999ZM69.5244 53.0379L69.3973 53.5215L69.5244 53.0379ZM141.65 28.8989C135.031 35.2997 125.943 38.4375 116.315 39.2654C106.688 40.0931 96.561 38.607 87.9207 35.8021C79.2649 32.9923 72.1739 28.8832 68.5572 24.5234C66.753 22.3484 65.8508 20.1579 65.9824 18.0635C66.1133 15.9807 67.2739 13.8818 69.8312 11.8948L69.2176 11.1051C66.5057 13.2123 65.1383 15.552 64.9844 18.0007C64.8313 20.4378 65.8877 22.8715 67.7876 25.1618C71.5792 29.7325 78.8783 33.9182 87.6119 36.7533C96.361 39.5934 106.622 41.1025 116.4 40.2617C126.177 39.4211 135.511 36.2268 142.346 29.6178L141.65 28.8989ZM69.8312 11.8948C76.1217 7.00714 81.1226 4.09865 85.0169 2.71442C88.9178 1.32781 91.6197 1.49918 93.4091 2.61867C95.1994 3.73872 96.231 5.90455 96.5629 8.8701C96.894 11.8276 96.5159 15.4895 95.5803 19.4474C93.7094 27.3612 89.6393 36.3356 84.7843 42.9886C82.3565 46.3156 79.7503 49.0371 77.1481 50.7594C74.545 52.4823 72.001 53.1717 69.6515 52.5543L69.3973 53.5215C72.1238 54.238 74.964 53.4042 77.7 51.5933C80.437 49.7818 83.1248 46.9592 85.5921 43.578C90.5275 36.8148 94.6527 27.7176 96.5534 19.6775C97.5035 15.6584 97.9053 11.8728 97.5567 8.75886C97.2091 5.65298 96.1014 3.12347 93.9395 1.77091C91.7766 0.417783 88.7131 0.33927 84.6819 1.77217C80.6441 3.20744 75.5463 6.18784 69.2176 11.1051L69.8312 11.8948ZM69.6515 52.5543C56.6241 49.1307 47.457 52.0938 41.14 58.6639C34.8623 65.1932 31.4678 75.2154 29.7777 85.7878C28.0854 96.3743 28.0905 107.589 28.673 116.58C28.9644 121.078 29.4007 125.024 29.843 128.065C30.2827 131.086 30.7341 133.255 31.0666 134.168L32.0062 133.825C31.7138 133.023 31.2736 130.952 30.8326 127.921C30.3942 124.908 29.9607 120.988 29.6709 116.516C29.0912 107.568 29.0886 96.4337 30.7652 85.9456C32.444 75.4434 35.7949 65.6661 41.8608 59.357C47.8875 53.0888 56.6625 50.1748 69.3973 53.5215L69.6515 52.5543Z"
                    fill="white"
                  />
                  <circle cx="34" cy="150" r="15" fill="currentColor" />
                  <circle cx="15" cy="137" r="15" fill="currentColor" />
                  <circle cx="24" cy="144" r="15" fill="white" />
                </svg>
              </span>
            </div>
          </div>
          <div
            class="col-lg-6 wow fadeInRight"
            data-wow-duration="1s"
            data-wow-delay="0.2s"
          >
            <div class="td_slider td_style_1">
              <div
                class="td_slider_container"
                data-autoplay="0"
                data-loop="1"
                data-speed="800"
                data-center="0"
                data-variable-width="0"
                data-slides-per-view="1"
              >
                <div class="td_slider_wrapper">
                  <div class="td_slide">
                    <div
                      class="td_testimonial td_style_1 td_white_bg td_radius_5"
                    >
                      <span class="td_quote_icon td_accent_color">
                        <svg
                          width="65"
                          height="46"
                          viewBox="0 0 65 46"
                          fill="none"
                          xmlns="http://www.w3.org/2000/svg"
                        >
                          <path
                            opacity="0.05"
                            d="M13.9286 26.6H1V1H26.8571V27.362L17.956 45H6.26764L14.8213 28.0505L15.5534 26.6H13.9286ZM51.0714 26.6H38.1429V1H64V27.362L55.0988 45H43.4105L51.9642 28.0505L52.6962 26.6H51.0714Z"
                            fill="currentColor"
                            stroke="currentColor"
                            stroke-width="2"
                          />
                        </svg>
                      </span>
                      <div class="td_testimonial_meta td_mb_24">
                        <img src="assets/img/home_1/avatar_1.png" alt="" />
                        <div class="td_testimonial_meta_right">
                          <h3 class="td_fs_24 td_semibold td_mb_2">
                            Marvin McKinney
                          </h3>
                          <p
                            class="td_fs_14 mb-0 td_heading_color td_opacity_7"
                          >
                            15th Batch Students
                          </p>
                        </div>
                      </div>
                      <blockquote
                        class="td_testimonial_text td_fs_20 td_medium td_heading_color td_mb_24 td_opacity_9"
                      >
                        The pandemic has accelerated the shift to online and
                        hybrid learning models. Platforms like Coursera, edX,
                        and university-specific online programs offer
                        flexibility and accessibility to a wider audience.
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
                  </div>
                  <div class="td_slide">
                    <div
                      class="td_testimonial td_style_1 td_white_bg td_radius_5"
                    >
                      <span class="td_quote_icon td_accent_color">
                        <svg
                          width="65"
                          height="46"
                          viewBox="0 0 65 46"
                          fill="none"
                          xmlns="http://www.w3.org/2000/svg"
                        >
                          <path
                            opacity="0.05"
                            d="M13.9286 26.6H1V1H26.8571V27.362L17.956 45H6.26764L14.8213 28.0505L15.5534 26.6H13.9286ZM51.0714 26.6H38.1429V1H64V27.362L55.0988 45H43.4105L51.9642 28.0505L52.6962 26.6H51.0714Z"
                            fill="currentColor"
                            stroke="currentColor"
                            stroke-width="2"
                          />
                        </svg>
                      </span>
                      <div class="td_testimonial_meta td_mb_24">
                        <img src="assets/img/home_2/avatar_2.png" alt="" />
                        <div class="td_testimonial_meta_right">
                          <h3 class="td_fs_24 td_semibold td_mb_2">
                            Marry Kristano
                          </h3>
                          <p
                            class="td_fs_14 mb-0 td_heading_color td_opacity_7"
                          >
                            13th Batch Students
                          </p>
                        </div>
                      </div>
                      <blockquote
                        class="td_testimonial_text td_fs_20 td_medium td_heading_color td_mb_24 td_opacity_9"
                      >
                        The pandemic has accelerated the shift to online and
                        hybrid learning models. Platforms like Coursera, edX,
                        and university-specific online programs offer
                        flexibility and accessibility to a wider audience.
                      </blockquote>
                      <div class="td_rating" data-rating="4.5">
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
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="td_height_100 td_height_lg_80"></div>
    </section> -->
     <!-- End Testimonial Section -->
     <!-- Start Blog Section -->
     <!-- <section>
      <div class="td_height_112 td_height_lg_75"></div>
      <div class="container">
        <div
          class="td_section_heading td_style_1 text-center wow fadeInUp"
          data-wow-duration="1s"
          data-wow-delay="0.2s"
        >
          <p
            class="td_section_subtitle_up td_fs_18 td_semibold td_spacing_1 td_mb_10 text-uppercase td_accent_color"
          >
            BLOG & ARTICLES
          </p>
          <h2 class="td_section_title td_fs_48 mb-0">
            Take A Look At The Latest <br />Articles
          </h2>
        </div>
        <div class="td_height_50 td_height_lg_50"></div>
        <div class="row td_gap_y_30">
          <div
            class="col-lg-4 wow fadeInUp"
            data-wow-duration="1s"
            data-wow-delay="0.2s">
            <div class="td_post td_style_1">
              <a href="blog-details.html" class="td_post_thumb d-block">
                <img src="assets/img/home_1/post_1.jpg" alt="" />
                <i class="fa-solid fa-link"></i>
              </a>
              <div class="td_post_info">
                <div class="td_post_meta td_fs_14 td_medium td_mb_20">
                  <span
                    ><img src="assets/img/icons/calendar.svg" alt="" />Jan 23 ,
                    2024</span
                  >
                  <span
                    ><img src="assets/img/icons/user.svg" alt="" />Jhon
                    Doe</span
                  >
                </div>
                <h2 class="td_post_title td_fs_24 td_medium td_mb_16">
                  <a href="blog-details.html"
                    >Comprehensive Student Guide for New Educations System</a
                  >
                </h2>
                <p
                  class="td_post_subtitle td_mb_24 td_heading_color td_opacity_7"
                >
                  Education is a dynamic and evolving field that plays a
                  crucial.
                </p>
                <a
                  href="blog-details.html"
                  class="td_btn td_style_1 td_type_3 td_radius_30 td_medium"
                >
                  <span class="td_btn_in td_accent_color">
                    <span>Read More</span>
                  </span>
                </a>
              </div>
            </div>
          </div>
          <div class="col-lg-4 wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.3s">
             <div class="td_post td_style_1">
              <a href="blog-details.html" class="td_post_thumb d-block">
                <img src="assets/img/home_1/post_2.jpg" alt="" />
                <i class="fa-solid fa-link"></i>
              </a>
              <div class="td_post_info">
                <div class="td_post_meta td_fs_14 td_medium td_mb_20">
                  <span
                    ><img src="assets/img/icons/calendar.svg" alt="" />Jan 20 ,
                    2024</span
                  >
                  <span
                    ><img src="assets/img/icons/user.svg" alt="" />Jhon
                    Doe</span
                  >
                </div>
                <h2 class="td_post_title td_fs_24 td_medium td_mb_16">
                  <a href="blog-details.html"
                    >Overview of the New Education System for Students</a
                  >
                </h2>
                <p
                  class="td_post_subtitle td_mb_24 td_heading_color td_opacity_7"
                >
                  Education is a dynamic and evolving field that plays a
                  crucial.
                </p>
                <a
                  href="blog-details.html"
                  class="td_btn td_style_1 td_type_3 td_radius_30 td_medium"
                >
                  <span class="td_btn_in td_accent_color">
                    <span>Read More</span>
                  </span>
                </a>
              </div>
            </div>
          </div>
          <div
            class="col-lg-4 wow fadeInUp"
            data-wow-duration="1s"
            data-wow-delay="0.4s"
          >
            <div class="td_post td_style_1">
              <a href="blog-details.html" class="td_post_thumb d-block">
                <img src="assets/img/home_1/post_3.jpg" alt="" />
                <i class="fa-solid fa-link"></i>
              </a>
              <div class="td_post_info">
                <div class="td_post_meta td_fs_14 td_medium td_mb_20">
                  <span
                    ><img src="assets/img/icons/calendar.svg" alt="" />Jan 18 ,
                    2024</span
                  >
                  <span
                    ><img src="assets/img/icons/user.svg" alt="" />Jhon
                    Doe</span
                  >
                </div>
                <h2 class="td_post_title td_fs_24 td_medium td_mb_16">
                  <a href="blog-details.html"
                    >Student Guide for the New Education System</a
                  >
                </h2>
                <p
                  class="td_post_subtitle td_mb_24 td_heading_color td_opacity_7"
                >
                  Education is a dynamic and evolving field that plays a
                  crucial.
                </p>
                <a
                  href="blog-details.html"
                  class="td_btn td_style_1 td_type_3 td_radius_30 td_medium"
                >
                  <span class="td_btn_in td_accent_color">
                    <span>Read More</span>
                  </span>
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="td_height_100 td_height_lg_80"></div>
    </section> -->
     <!-- End Blog Section -->
     <div style="width:100%; height:450px;">
         <iframe
             src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3429.6393699146447!2d76.75581317635954!3d30.72853717458636!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x14f6258053e76563%3A0xf262a6e0f4e3a740!2sInfomaths-%20Institute%20for%20MCA%20Entrance%2C%20NIMCET%2C%20CUET%2C%20MAHCET%2C%20PU%20coaching%2C%20Msc%20Ent.%2C%20Banking%20PO-SSC%20%2C%20CLAT%20%26%20all%20Govt%20Exams!5e0!3m2!1sen!2sin!4v1770899550517!5m2!1sen!2sin"
             width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"
             referrerpolicy="no-referrer-when-downgrade">
         </iframe>
     </div>





     <!-- Start Footer Section -->
     <footer class="td_footer td_style_1" style="background-color: #09226c;">
         <div class="container-fluid">
             <div class="td_footer_row">
                 <!-- Company Info Section -->
                 <div class="td_footer_col">
                     <div class="td_footer_widget">
                         <div class="td_footer_text_widget td_fs_18">
                             <img src="assets/img/infomath-white-logo.png" class="img-fluid mb-4" alt="Info Maths Logo"
                                 style="width: 248px" />
                             <p class="m-0">
                                 Infomaths – Where Concepts Become Confidence.
                                 Premier coaching for competitive exams with a legacy of success.
                                 <br>
                                 Trusted by Students Since 1999.
                             </p>
                             <!-- <p class="m-0 footercontact">
                                <br />Contact: <a href="tel:+919872124534" target="_blank"
                                    title="Contact">+9198721-24534</a>
                                <br />Admission Help No:<br />
                               
                                <a href="mailto:info@infomathsinstitute.com" target="_blank" title="Email">info@infomathsinstitute.com</a>
                            </p> -->
                         </div>
                         <div class="td_footer_social_btns td_fs_20 d-flex justify-content-start gap-2 mt-3">
                             <a href="https://www.facebook.com/infomathsindia" class="td_center"><i
                                     class="fa-brands fa-facebook"></i></a>
                             <a href="https://www.instagram.com/infomaths.coursedu/" class="td_center"><i
                                     class="fa-brands fa-instagram"></i></a>
                             <!-- <a href="https://x.com/i9872124534" class="td_center"><i
                                     class="fa-brands fa-twitter"></i></a> -->
                             <a rel="nofollow"
                                 href="https://www.linkedin.com/company/infomaths-studies-pvt-ltd/?originalSubdomain=in"
                                 target="_blank" class="td_center"><i class="fa-brands fa-linkedin-in"></i></a>
                             <a href="https://www.youtube.com/user/arpana2311" class="td_center"><i
                                     class="fa-brands fa-youtube"></i></a>
                         </div>
                     </div>
                 </div>
                 <!-- Programs Section -->
                 <div class="td_footer_col">
                     <div class="td_footer_widget">
                         <h2 class="td_footer_widget_title td_fs_32 td_white_color td_medium td_mb_30">
                             Our courses
                         </h2>
                         <ul class="td_footer_widget_menu">
                             <li>
                                 <a href="#">MCA Entrance</a>
                             </li>
                             <li>
                                 <a href="#">Bank PO SSC</a>
                             </li>
                             <li>
                                 <a href="#">IIT JAM Maths</a>
                             </li>
                             <li>
                                 <a href="#">CSIR NET JRF</a>
                             </li>
                             <li>
                                 <a href="#">BCA Subject Classes</a>
                             </li>
                             <li>
                                 <a href="#">BSC Subject Classes</a>
                             </li>
                             <li>
                                 <a href="#">Campus Placement</a>
                             </li>

                             <li>
                                 <a href="#">Internship Training</a>
                             </li>
                         </ul>
                     </div>
                 </div>
                 <div class="td_footer_col">
                     <div class="td_footer_widget">
                         <h2 class="td_footer_widget_title td_fs_32 td_white_color td_medium td_mb_30">
                             Quick Links
                         </h2>
                         <ul class="td_footer_widget_menu">
                             <li><a href="#">Home</a></li>
                             <li>
                                 <a href="#">About Us</a>
                             </li>

                             <li>
                                 <a href="#">Our Faculty</a>
                             </li>

                             <li><a href="#">Testimonial</a></li>
                             <li>
                                 <a href="#">Sitemap</a>
                             </li>
                             <li>
                                 <a href="#">Contact Us</a>
                             </li>
                             <li><a href="#">Privacy Policy</a></li>
                         </ul>
                     </div>
                 </div>
                 <!-- Useful Links Section -->
                 <div class="td_footer_col">
                     <div class="td_footer_widget">
                         <h2 class="td_footer_widget_title td_fs_32 td_white_color td_medium td_mb_30">
                             Get in Touch
                         </h2>

                         <div class="td_footer_text_widget td_fs_18">

                             <a href="https://maps.app.goo.gl/AoR8fQecc1aEBvTh7" class="m-0">
                                 <i class="fa-solid fa-location-dot" style="margin-right:8px;"></i>
                                 Quiet Office 10, Second Floor,<br>
                                 Sector 35 A, Chandigarh, 160035
                             </a>

                             <!-- <p class="m-0">
                                 <i class="fa-solid fa-map-location-dot" style="margin-right:8px;"></i>
                                 <a href="https://www.google.com/maps/place/Infomaths-Institute+for+MCA+Ent,M.Sc+Ent,PO-SSC,NDA,CLAT../@30.7285418,76.7558132,17z"
                                     target="_blank">
                                     Locate Us
                                 </a>
                             </p> -->

                             <p class="m-0">
                                 <i class="fa-solid fa-phone" style="margin-right:8px;"></i>
                                 <a href="tel:+919872124534">+91-98721-24534</a><br>

                                 <i class="fa-solid fa-phone" style="margin-right:8px;"></i>
                                 <a href="tel:+919878624534">+91 98786-24534</a>
                             </p>

                             <p class="m-0">
                                 <i class="fa-solid fa-envelope" style="margin-right:8px;"></i>
                                 <a href="mailto:info@Infomathsinstitute.com">
                                     info@Infomathsinstitute.com
                                 </a>
                             </p>

                         </div>
                     </div>
                 </div>
                 <!-- Quick Links Section -->

             </div>
             <div class="td_footer_bottom td_fs_18">
                 <div class="container">
                     <div class="td_footer_bottom_in" style="justify-content:center">
                         <p class="td_copyright mb-0">
                             InfoMaths © 2025 | All Rights Reserved.
                         </p>
                     </div>
                 </div>
             </div>
         </div>
     </footer>
     <!-- End Footer Section -->
     <!-- WhatsApp Floating Icon -->
     <a href="https://wa.me/919872124534" target="_blank" id="whatsapp-float"
         style="position:fixed;right:24px;bottom:24px;z-index:9999;background:#25d366;color:#fff;border-radius:50%;width:56px;height:56px;display:flex;align-items:center;justify-content:center;box-shadow:0 4px 16px rgba(0,0,0,0.18);text-decoration:none; animation: whatsapp-bounce 1.5s infinite;">
         <img src="assets/img/whatsapp.png" alt="WhatsApp" style="display:block;" />
     </a>
     <style>
     @keyframes whatsapp-bounce {

         0%,
         100% {
             transform: translateY(0);
         }

         20% {
             transform: translateY(-8px);
         }

         40% {
             transform: translateY(0);
         }

         60% {
             transform: translateY(-4px);
         }

         80% {
             transform: translateY(0);
         }
     }
     </style>
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
                     const videoId = $iframe.attr('src').match(/[?&]v=([^#\&\?]*)/)[1];

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

 </html>