<?php
// exam-details.php
require 'database.php';

$slug = isset($_GET['exam']) ? $_GET['exam'] : (isset($_GET['slug']) ? $_GET['slug'] : '');
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$exam = null;

try {
    if (!empty($slug)) {
        $stmt = $pdo->prepare("SELECT * FROM entrance_exams WHERE slug = ?");
        $stmt->execute([$slug]);
        $exam = $stmt->fetch(PDO::FETCH_ASSOC);
    } elseif ($id > 0) {
        $stmt = $pdo->prepare("SELECT * FROM entrance_exams WHERE id = ?");
        $stmt->execute([$id]);
        $exam = $stmt->fetch(PDO::FETCH_ASSOC);
    }
} catch (PDOException $e) {
    // Handle error quietly
}

if (!$exam) {
    // Redirect or show 404
    header("HTTP/1.0 404 Not Found");
    echo "<h1>Exam not found</h1>";
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
    <title><?php echo !empty($exam['meta_title']) ? htmlspecialchars($exam['meta_title']) : htmlspecialchars($exam['exam_name']) . ' - InfoMaths'; ?></title>
    <meta name="description" content="<?php echo !empty($exam['meta_description']) ? htmlspecialchars($exam['meta_description']) : 'Exam details for ' . htmlspecialchars($exam['exam_name']); ?>">
    <meta name="keywords" content="<?php echo !empty($exam['meta_keyword']) ? htmlspecialchars($exam['meta_keyword']) : 'mca entrance, infomaths, ' . htmlspecialchars($exam['exam_name']); ?>">

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
    <?php include 'header-styles.php'; ?>
    
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

        .course-details-content {
            padding: 50px 0;
            font-size: 16px;
            line-height: 1.8;
            color: #333;
        }
        .course-details-content img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .course-details-content h1 { font-size: 2.5rem; font-weight: 700; margin-top: 35px; color: #1C56E1; }
        .course-details-content h2 { font-size: 2rem; font-weight: 600; margin-top: 30px; color: #1C56E1; }
        .course-details-content h3 { font-size: 1.75rem; font-weight: 600; margin-top: 25px; color: #1C56E1; }
        .course-details-content h4 { font-size: 1.5rem; font-weight: 600; margin-top: 20px; }
        .course-details-content h5 { font-size: 1.25rem; font-weight: 600; margin-top: 15px; }
        .course-details-content h6 { font-size: 1rem; font-weight: 600; margin-top: 10px; }
        .course-details-content p { margin-bottom: 15px; }
        .course-details-content ul, .course-details-content ol { margin-bottom: 15px; padding-left: 20px; }
        .course-details-content li { margin-bottom: 5px; }
        .course-details-content .accordion-header { margin-top: 0 !important; }

        .banner-overlay {
            background: linear-gradient(105deg, rgba(0, 0, 27, 0.95) 0%, rgba(28, 86, 225, 0.9) 100%);
            padding: 140px 0 80px;
            color: #fff;
            position: relative;
        }
        .exam-header-icon {
            width: 100px;
            height: 100px;
            object-fit: contain;
            background: white;
            border-radius: 50%;
            padding: 15px;
            margin-bottom: 25px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        .banner-content-wrapper {
            position: relative;
            z-index: 2;
        }
        
        /* Distinct Blue CTA Link */
        .dynamic-cta-link {
            color: #1C56E1 !important;
            font-weight: 700;
            text-decoration: underline;
            font-size: 1.1em;
            transition: color 0.3s ease;
        }
        .dynamic-cta-link:hover {
            color: #0d3aa9 !important;
        }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <!-- Banner -->
    <section class="banner-overlay">
        <div class="container text-center banner-content-wrapper">
            <!-- Professional Breadcrumb Removed -->
            <!-- <div class="d-flex justify-content-center mb-4">
               <div class="pro-breadcrumb">
                   <a href="index.php" class="pro-breadcrumb-item">Home</a>
                   <span class="pro-breadcrumb-separator"><i class="fa-solid fa-chevron-right"></i></span>
                   <a href="mca-entrance.php" class="pro-breadcrumb-item">MCA Entrance</a>
                   <span class="pro-breadcrumb-separator"><i class="fa-solid fa-chevron-right"></i></span>
                   <span class="pro-breadcrumb-item active"><?php echo htmlspecialchars($exam['exam_name']); ?></span>
               </div>
            </div> -->

            <?php if (!empty($exam['icon_image'])): ?>
                <img src="<?php echo htmlspecialchars($exam['icon_image']); ?>" alt="Icon" class="exam-header-icon animate__animated animate__fadeInUp">
            <?php endif; ?>
            <h1 class="font-weight-bold mb-3 text-white animate__animated animate__fadeInUp" style="animation-delay: 0.1s;"><?php echo htmlspecialchars($exam['exam_name']); ?></h1>
            <p class="lead mb-0 text-white-50 animate__animated animate__fadeInUp" style="animation-delay: 0.2s;"><?php echo htmlspecialchars($exam['short_description']); ?></p>
        </div>
    </section>

    <!-- Main Content -->
    <section class="course-details-content">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="bg-white p-5 rounded shadow-sm animate__animated animate__fadeInUp" style="animation-delay: 0.3s;">
                        <?php if (!empty($exam['full_description'])): ?>
                            <!-- Display Rich Text Content -->
                            <?php echo $exam['full_description']; ?>
                        <?php else: ?>
                            <div class="alert alert-info text-center">
                                <h4>Content Coming Soon</h4>
                                <p>We are currently updating the details for this exam. Please check back later or contact us for more info.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php include 'includes/footer-new.php'; ?>

    <script src="assets/js/jquery-3.7.1.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/slick.min.js"></script>
    <script src="assets/js/odometer.min.js"></script>
    <script src="assets/js/jquery-ui.min.js"></script>
    <script src="assets/js/main.js"></script>
    <script>
        $(document).ready(function() {
            // Find all links in the course description
            $('.course-details-content a').each(function() {
                var $link = $(this);
                var text = $link.text().trim().toLowerCase();
                
                // Check if link text contains keywords
                if (text.includes('join') || text.includes('register') || text.includes('apply') || text.includes('contact')) {
                    
                    $link.addClass('open-contact-modal dynamic-cta-link');
                    
                    // Directly bind click to ensure it works
                    $link.on('click', function(e) {
                        e.preventDefault();
                        // Try standard Bootstrap modal open
                        if (typeof $ !== 'undefined' && $.fn.modal) {
                            $('#contactPopupModal').modal('show');
                        } else {
                            // Fallback if jQuery modal not ready (unlikely given document.ready)
                            var myModal = new bootstrap.Modal(document.getElementById('contactPopupModal'));
                            myModal.show();
                        }
                    });
                }
            });
        });
    </script>
    </script>
    <script src="/assets/js/header-modals.js"></script>
</body>
</html>
