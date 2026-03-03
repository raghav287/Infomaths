<?php
require_once 'database.php';

// Get Slug
$slug = isset($_GET['slug']) ? trim($_GET['slug']) : '';
if (empty($slug)) {
    header("Location: mca-entrance.php");
    exit();
}

// Fetch Category Details
$stmt = $pdo->prepare("SELECT * FROM university_categories WHERE slug = ? AND is_active = 1");
$stmt->execute([$slug]);
$category = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$category) {
    header("Location: mca-entrance.php");
    exit();
}

// Fetch Papers and Results
$stmt = $pdo->prepare("SELECT * FROM university_papers WHERE category_id = ? AND is_active = 1 ORDER BY display_order ASC");
$stmt->execute([$category['id']]);
$all_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

$papers = array_filter($all_items, function($item) { return $item['type'] === 'paper'; });
$results = array_filter($all_items, function($item) { return $item['type'] === 'result'; });
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Meta Tags -->
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Previous Papers & Results - <?php echo htmlspecialchars($category['name']); ?> | InfoMaths</title>
    
    <!-- Favicon -->
    <link href="assets/img/favicon.png" rel="shortcut icon" />
    
    <!-- CSS -->
    <!-- CSS -->
    <link rel="stylesheet" href="/assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="/assets/css/fontawesome.min.css" />
    <link rel="stylesheet" href="/assets/css/slick.min.css" />
    <link rel="stylesheet" href="/assets/css/slick-theme.min.css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/owl-carousel/1.3.3/owl.carousel.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/owl-carousel/1.3.3/owl.theme.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/odometer.css" />
    <link rel="stylesheet" href="/assets/css/animate.css" />
    <link rel="stylesheet" href="/assets/css/style.css" />

    <style>
        /* Pro Breadcrumb - Matching Blue Accent */
        .pro-breadcrumb {
            display: inline-flex;
            align-items: center;
            padding: 8px 20px;
            background: rgba(28, 86, 225, 0.15); /* Light blue tint */
            backdrop-filter: blur(4px);
            border-left: 3px solid #1C56E1; /* Main Blue */
            border-radius: 4px;
            margin-bottom: 25px;
            font-size: 14px;
            font-weight: 500;
        }
        .pro-breadcrumb-item {
            color: #d0d0d0; /* Light text for dark banner */
            text-decoration: none;
            transition: all 0.3s ease;
        }
        .pro-breadcrumb-item:hover {
            color: #fff;
        }
        .pro-breadcrumb-item.active {
            color: #fff; /* White for active page */
            font-weight: 600;
        }
        .pro-breadcrumb-separator {
            margin: 0 10px;
            color: rgba(255,255,255, 0.4);
            font-size: 10px;
        }

        .banner-overlay {
            background: linear-gradient(105deg, rgba(0, 0, 27, 0.95) 0%, rgba(28, 86, 225, 0.9) 100%);
            padding: 140px 0 80px;
            color: #fff;
            position: relative;
        }
        .banner-content-wrapper {
            position: relative;
            z-index: 2;
        }

        /* Tabs & Card Styles */
        .nav-tabs .nav-link {
            color: #495057;
            font-weight: 600;
            padding: 15px 30px;
            border-radius: 0;
            border: none;
            border-bottom: 3px solid transparent;
        }
        .nav-tabs .nav-link.active {
            color: #1C56E1; 
            background-color: transparent;
            border-bottom: 3px solid #1C56E1;
        }
        .paper-card {
            background: #fff;
            border: 1px solid #eef2f6;
            text-align: center;
            transition: all 0.3s ease;
            height: 100%;
            display: flex;
            flex-direction: column;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.03);
            position: relative;
        }
        .paper-card:hover {
            box-shadow: 0 15px 30px rgba(0,0,0,0.08);
            transform: translateY(-5px);
            border-color: #dbe4f3;
        }
        .paper-card-img {
            position: relative;
            width: 100%;
            height: 180px; /* Modern aspect ratio */
            overflow: hidden;
            background: #f8f9fa;
        }
        .paper-card-img a {
            display: block;
            width: 100%;
            height: 100%;
            position: absolute;
            inset: 0;
            z-index: 10;
        }
        .paper-card-img img {
            width: 100%;
            height: 100%;
            object-fit: cover; /* Ensures image fills area */
            object-position: top center;
            transition: transform 0.5s ease;
            position: relative; /* Ensure it stays below the anchor if needed, though anchor is z-10 */
            z-index: 1;
        }
        .paper-card:hover .paper-card-img img {
            transform: scale(1.05);
        }
        .paper-card-body {
            padding: 20px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative; /* Ensure z-index works if needed */
            z-index: 20; /* Keep body content clickable above any potential overlays */
        }
        .paper-title {
            font-size: 16px;
            font-weight: 700;
            margin-bottom: 15px;
            color: #333;
            line-height: 1.4;
        }
        .download-btn {
            background-color: transparent;
            color: #1C56E1;
            border: 2px solid #1C56E1;
            padding: 10px 20px;
            border-radius: 6px;
            /* text-transform: uppercase; */
            font-weight: 600;
            font-size: 14px;
            text-decoration: none;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        .download-btn:hover {
            background-color: #1C56E1;
            color: white;
            box-shadow: 0 5px 15px rgba(28, 86, 225, 0.2);
        }
        .paper-title {
            font-weight: 700;
            margin-bottom: 15px;
            font-size: 1.1rem;
            padding: 0 15px;
            color: #333;
        }
    </style>
</head>
<body>

    <!-- Include Standard Header -->
    <?php include 'includes/header.php'; ?>

    <!-- Banner -->
    <section class="banner-overlay">
        <div class="container text-center banner-content-wrapper">
            <!-- Professional Breadcrumb -->
            <div class="d-flex justify-content-center mb-4">
            <!-- Professional Breadcrumb Removed -->
            </div>

            <h1 class="display-4 fw-bold mb-3 text-white animate__animated animate__fadeInUp" style="animation-delay: 0.1s;"><?php echo htmlspecialchars($category['name']); ?> Previous Papers</h1>
            <p class="lead mb-0 text-white-50 animate__animated animate__fadeInUp" style="animation-delay: 0.2s;">Explore previous year question papers and result announcements.</p>
        </div>
    </section>

    <!-- Main Content -->
    <section class="section-padding" style="padding: 80px 0; background-color: #f8f9fa;">
        <div class="container">
             <div class="row mb-5">
                <div class="col-12 text-center">
                    <h2 class="fw-bold mb-3"><?php echo htmlspecialchars($category['name']); ?> Archive</h2>
                    <p class="text-muted">Explore previous year question papers and result announcements.</p>
                </div>
            </div>

            <!-- Tabs -->
            <ul class="nav nav-tabs mb-5 justify-content-center" id="paperTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="papers-tab" data-bs-toggle="tab" data-bs-target="#papers" type="button" role="tab" aria-selected="true">Previous Papers</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="results-tab" data-bs-toggle="tab" data-bs-target="#results" type="button" role="tab" aria-selected="false">Results</button>
                </li>
            </ul>

            <!-- Tab Content -->
            <div class="tab-content" id="paperTabsContent">
                
                <!-- Previous Papers Tab -->
                <div class="tab-pane fade show active" id="papers" role="tabpanel">
                    <?php if (count($papers) > 0): ?>
                        <div class="row g-4">
                            <?php foreach ($papers as $paper): ?>
                                <div class="col-lg-4 col-md-6">
                                    <div class="paper-card">
                                        <div class="paper-card-img">
                                            <a href="<?php echo htmlspecialchars($paper['pdf_file']); ?>" target="_blank" class="d-block w-100 h-100">
                                                <img src="/<?php echo !empty($paper['image_path']) ? htmlspecialchars($paper['image_path']) : 'assets/img/pdf-placeholder.png'; ?>" 
                                                     alt="<?php echo htmlspecialchars($paper['title']); ?>">
                                            </a>
                                        </div>
                                        <div class="paper-card-body">
                                            <h5 class="paper-title">
                                                <a href="/<?php echo htmlspecialchars($paper['pdf_file']); ?>" target="_blank" class="text-decoration-none text-dark">
                                                    <?php echo htmlspecialchars($paper['title']); ?>
                                                </a>
                                            </h5>
                                            <a href="/<?php echo htmlspecialchars($paper['pdf_file']); ?>" target="_blank" class="download-btn">
                                                <span>DOWNLOAD</span> <i class="fa-solid fa-download"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <div class="mb-3">
                                <i class="fa-regular fa-folder-open fa-3x text-muted"></i>
                            </div>
                            <h4 class="text-muted">No papers available yet.</h4>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Results Tab -->
                <div class="tab-pane fade" id="results" role="tabpanel">
                    <?php if (count($results) > 0): ?>
                        <div class="row g-4">
                            <?php foreach ($results as $result): ?>
                                <div class="col-lg-4 col-md-6">
                                    <div class="paper-card">
                                        <div class="paper-card-img">
                                            <a href="<?php echo htmlspecialchars($result['pdf_file']); ?>" target="_blank" class="d-block w-100 h-100">
                                                <img src="/<?php echo !empty($result['image_path']) ? htmlspecialchars($result['image_path']) : '/assets/img/pdf-placeholder.png'; ?>" 
                                                     alt="<?php echo htmlspecialchars($result['title']); ?>">
                                            </a>
                                        </div>
                                        <div class="paper-card-body">
                                            <h5 class="paper-title">
                                                <a href="/<?php echo htmlspecialchars($result['pdf_file']); ?>" target="_blank" class="text-decoration-none text-dark">
                                                    <?php echo htmlspecialchars($result['title']); ?>
                                                </a>
                                            </h5>
                                            <a href="/<?php echo htmlspecialchars($result['pdf_file']); ?>" target="_blank" class="download-btn">
                                                <span>DOWNLOAD RESULT</span> <i class="fa-solid fa-download"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <div class="mb-3">
                                <i class="fa-regular fa-folder-open fa-3x text-muted"></i>
                            </div>
                            <h4 class="text-muted">No results available yet.</h4>
                        </div>
                    <?php endif; ?>
                </div>

            </div>
        </div>
    </section>

    <!-- Include Standard Footer -->
    <?php include 'includes/footer-new.php'; ?>

    <!-- Scripts -->
    <script src="/assets/js/jquery-3.7.1.min.js"></script>
    <script src="/assets/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/js/jquery.slick.min.js"></script>
    <script src="/assets/js/odometer.js"></script>
    <script src="/assets/js/wow.min.js"></script>
    <script src="/assets/js/main.js"></script>
    <script src="/assets/js/header-modals.js"></script>

    <script>
        // Init WOW.js
        new WOW().init();
    </script>

</body>
</html>
