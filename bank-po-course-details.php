<?php
// bank-po-course-details.php
require 'database.php';

$slug = isset($_GET['slug']) ? $_GET['slug'] : '';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$course = null;

try {
    if (!empty($slug)) {
        // CHANGED: Use bank_po_course_profiles table
        $stmt = $pdo->prepare("SELECT * FROM bank_po_course_profiles WHERE slug = ?");
        $stmt->execute([$slug]);
        $course = $stmt->fetch(PDO::FETCH_ASSOC);
    } elseif ($id > 0) {
        // CHANGED: Use bank_po_course_profiles table
        $stmt = $pdo->prepare("SELECT * FROM bank_po_course_profiles WHERE id = ?");
        $stmt->execute([$id]);
        $course = $stmt->fetch(PDO::FETCH_ASSOC);
    }
} catch (PDOException $e) {
    // Handle error quietly
}

if (!$course) {
    // Redirect or show 404
    header("HTTP/1.0 404 Not Found");
    echo "<h1>Course not found</h1>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- Dynamic SEO Tags -->
    <title><?php echo !empty($course['meta_title']) ? htmlspecialchars($course['meta_title']) : htmlspecialchars($course['title']) . ' - InfoMaths'; ?></title>
    <meta name="description" content="<?php echo !empty($course['meta_description']) ? htmlspecialchars($course['meta_description']) : 'Course details for ' . htmlspecialchars($course['title']); ?>">
    <meta name="keywords" content="<?php echo !empty($course['meta_keyword']) ? htmlspecialchars($course['meta_keyword']) : 'bank po, ssc, infomaths, ' . htmlspecialchars($course['title']); ?>">

    <!-- CSS -->
    <link href="/assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="/assets/css/fontawesome.min.css" rel="stylesheet">
    <link href="/assets/css/slick.min.css" rel="stylesheet">
    <link href="/assets/css/slick-theme.min.css" rel="stylesheet">
    <link href="/assets/css/odometer.css" rel="stylesheet">
    <link href="/assets/css/animate.css" rel="stylesheet">
    <link href="/assets/css/jquery-ui.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/owl-carousel/1.3.3/owl.carousel.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/owl-carousel/1.3.3/owl.theme.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css" />
    <link href="/assets/css/style.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="/assets/css/slick-theme.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/owl-carousel/1.3.3/owl.carousel.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/owl-carousel/1.3.3/owl.theme.min.css" />
    <link rel="stylesheet" href="/assets/css/style.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css" />
    
    <style>
        /* Pro Breadcrumb - Matching Bank PO Page Theme (Dark/Navy) */
        .banner-overlay {
            /* Deep Corporate Navy Gradient - similar to Bank PO main page */
            background: linear-gradient(105deg, rgba(0, 0, 27, 0.96) 0%, rgba(28, 86, 225, 0.8) 100%);
            padding: 140px 0 80px;
            color: #fff;
            position: relative;
        }
        .banner-content-wrapper {
            position: relative;
            z-index: 2;
        }

        .course-details-content {
            padding: 50px 0;
            font-size: 16px;
            line-height: 1.8;
            color: #333;
            background: #f8f9fa;
        }
        .course-details-content img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .course-details-content h2, .course-details-content h3 {
            color: #1C56E1;
            margin-top: 30px;
            margin-bottom: 15px;
        }
        .course-card-shadow {
             background: #fff;
             padding: 40px; 
             border-radius: 12px; 
             box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        }
    </style>
</head>
<body>
    <!-- Include Standard Header -->
    <?php include 'includes/header.php'; ?>
    
    <!-- Banner -->
    <section class="banner-overlay">
        <div class="container text-center banner-content-wrapper">
            <h1 class="display-4 fw-bold mb-3 text-white animate__animated animate__fadeInUp" style="animation-delay: 0.1s;"><?php echo htmlspecialchars($course['title']); ?></h1>
            <p class="lead mb-0 text-white-50 animate__animated animate__fadeInUp" style="animation-delay: 0.2s;"><?php echo htmlspecialchars($course['subtitle']); ?></p>
        </div>
    </section>

    <!-- Main Content -->
    <section class="course-details-content">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="course-card-shadow">
                        <?php if (!empty($course['description'])): ?>
                            <!-- Display Rich Text Content -->
                            <?php echo $course['description']; ?>
                        <?php else: ?>
                            <div class="alert alert-info text-center">
                                <h4>Content Coming Soon</h4>
                                <p>We are currently updating the details for this course. Please check back later or contact us for more info.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Include Standard Footer -->
    <?php include 'includes/footer-new.php'; ?>
    <script src="/assets/js/jquery-3.7.1.min.js"></script>
    <script src="/assets/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/js/jquery.slick.min.js"></script>
    <script src="/assets/js/odometer.js"></script>
    <script src="/assets/js/wow.min.js"></script>
    <script src="/assets/js/main.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"></script>
    <script src="/assets/js/header-modals.js"></script>
</body>
</html>
