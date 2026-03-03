<?php
// iit-jam-exam-details.php
require 'database.php';

$slug = isset($_GET['exam']) ? $_GET['exam'] : (isset($_GET['slug']) ? $_GET['slug'] : '');
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$exam = null;

try {
    if (!empty($slug)) {
        // CHANGED: Use iit_jam_entrance_exams table
        $stmt = $pdo->prepare("SELECT * FROM iit_jam_entrance_exams WHERE slug = ?");
        $stmt->execute([$slug]);
        $exam = $stmt->fetch(PDO::FETCH_ASSOC);
    } elseif ($id > 0) {
        // CHANGED: Use iit_jam_entrance_exams table
        $stmt = $pdo->prepare("SELECT * FROM iit_jam_entrance_exams WHERE id = ?");
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
    <meta name="keywords" content="<?php echo !empty($exam['meta_keyword']) ? htmlspecialchars($exam['meta_keyword']) : 'iit jam, tifr, nbhm, infomaths, ' . htmlspecialchars($exam['exam_name']); ?>">

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
        .course-details-content h1 { font-size: 2.5rem; font-weight: 700; margin-top: 35px; color: #1C56E1; }
        .course-details-content h2 { font-size: 2rem; font-weight: 600; margin-top: 30px; color: #1C56E1; }
        .course-details-content h3 { font-size: 1.75rem; font-weight: 600; margin-top: 25px; color: #1C56E1; }
        .course-details-content h4 { font-size: 1.5rem; font-weight: 600; margin-top: 20px; }
        .course-details-content h5 { font-size: 1.25rem; font-weight: 600; margin-top: 15px; }
        .course-details-content h6 { font-size: 1rem; font-weight: 600; margin-top: 10px; }
        .course-details-content p { margin-bottom: 15px; }
        .course-details-content ul, .course-details-content ol { margin-bottom: 15px; padding-left: 20px; }
        .course-details-content li { margin-bottom: 5px; }
        
        .course-card-shadow {
             background: #fff;
             padding: 40px; 
             border-radius: 12px; 
             box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        }

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

        /* Tabs CSS */
        .custom-tabs {
            border-bottom: 2px solid #e0e0e0;
            margin-bottom: 30px;
            background: #fff;
            padding: 10px 10px 0;
            border-radius: 12px 12px 0 0;
        }
        .custom-tabs .nav-link {
            color: #555;
            font-weight: 600;
            border: none;
            border-bottom: 3px solid transparent;
            padding: 15px 25px;
            font-size: 16px;
            transition: all 0.3s;
            margin-right: 5px;
            border-radius: 8px 8px 0 0;
        }
        .custom-tabs .nav-link:hover {
            color: #1C56E1;
            background: rgba(28, 86, 225, 0.05);
        }
        .custom-tabs .nav-link.active {
            background-color: #1C56E1;
            color: white;
            box-shadow: 0 -4px 10px rgba(28, 86, 225, 0.2);
        }
        .tab-content {
            background: #fff;
            padding: 40px;
            border: none;
            border-radius: 0 0 12px 12px;
        }
        
        .tab-pane h3 {
            color: #1C56E1;
            font-weight: 700;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f0f0f0;
            position: relative;
        }
        .tab-pane h3::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 60px;
            height: 2px;
            background: #1C56E1;
        }
        
        @media (max-width: 768px) {
            .custom-tabs {
                border-bottom: none;
                flex-direction: column;
                gap: 10px;
            }
            .custom-tabs .nav-link {
                width: 100%;
                border-radius: 8px !important;
                text-align: center;
                border: 1px solid #e0e0e0;
                margin-right: 0;
            }
            .custom-tabs .nav-link.active {
                background-color: #1C56E1;
                color: white;
                border: none;
            }
            .tab-content {
                padding: 20px;
                border-top: 1px solid #e0e0e0;
                border-radius: 12px;
            }
        }

        /* Tabs CSS */
        .custom-tabs {
            border-bottom: 2px solid #e0e0e0;
            margin-bottom: 30px;
            background: #fff;
            padding: 10px 10px 0;
            border-radius: 12px 12px 0 0;
        }
        .custom-tabs .nav-link {
            color: #555;
            font-weight: 600;
            border: none;
            border-bottom: 3px solid transparent;
            padding: 15px 25px;
            font-size: 16px;
            transition: all 0.3s;
            margin-right: 5px;
            border-radius: 8px 8px 0 0;
        }
        .custom-tabs .nav-link:hover {
            color: #1C56E1;
            background: rgba(28, 86, 225, 0.05);
        }
        .custom-tabs .nav-link.active {
            background-color: #1C56E1;
            color: white;
            box-shadow: 0 -4px 10px rgba(28, 86, 225, 0.2);
        }
        .tab-content {
            background: #fff;
            padding: 40px;
            border: none;
            border-radius: 0 0 12px 12px;
        }
        
        .tab-pane h3 {
            color: #1C56E1;
            font-weight: 700;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f0f0f0;
            position: relative;
        }
        .tab-pane h3::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 60px;
            height: 2px;
            background: #1C56E1;
        }
        
        @media (max-width: 768px) {
            .custom-tabs {
                border-bottom: none;
                flex-direction: column;
                gap: 10px;
            }
            .custom-tabs .nav-link {
                width: 100%;
                border-radius: 8px !important;
                text-align: center;
                border: 1px solid #e0e0e0;
                margin-right: 0;
            }
            .custom-tabs .nav-link.active {
                background-color: #1C56E1;
                color: white;
                border: none;
            }
            .tab-content {
                padding: 20px;
                border-top: 1px solid #e0e0e0;
                border-radius: 12px;
            }
        }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <!-- Banner -->
    <section class="banner-overlay">
        <div class="container text-center banner-content-wrapper">
            <?php if (!empty($exam['icon_image'])): ?>
                <!-- Prepend slash to ensure absolute path -->
                <img src="/<?php echo htmlspecialchars(ltrim($exam['icon_image'], '/')); ?>" alt="Icon" class="exam-header-icon animate__animated animate__fadeInUp">
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
                    <div class="course-card-shadow animate__animated animate__fadeInUp" style="animation-delay: 0.3s;">
                        
                        <?php
                        // Fetch Tabs
                        $tabs = [];
                        try {
                            $stmt_tabs = $pdo->prepare("SELECT * FROM iit_jam_exam_tabs WHERE exam_id = ? ORDER BY display_order ASC");
                            $stmt_tabs->execute([$exam['id']]);
                            $tabs = $stmt_tabs->fetchAll(PDO::FETCH_ASSOC);
                        } catch (PDOException $e) {
                            // Error handling
                        }
                        ?>

                        <?php if (!empty($tabs)): ?>
                            <!-- Dynamic Tabs -->
                            <ul class="nav nav-pills custom-tabs mb-0" id="examTabs" role="tablist">
                                <?php foreach ($tabs as $key => $tab): ?>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link <?php echo $key === 0 ? 'active' : ''; ?>" id="tab-btn-<?php echo $tab['id']; ?>" data-bs-toggle="tab" data-bs-target="#tab-content-<?php echo $tab['id']; ?>" type="button" role="tab" aria-controls="tab-content-<?php echo $tab['id']; ?>" aria-selected="<?php echo $key === 0 ? 'true' : 'false'; ?>"><?php echo htmlspecialchars($tab['tab_title']); ?></button>
                                    </li>
                                <?php endforeach; ?>
                            </ul>

                            <div class="tab-content" id="examTabsContent">
                                <!-- Dynamic Tab Contents -->
                                <?php foreach ($tabs as $key => $tab): ?>
                                    <div class="tab-pane fade <?php echo $key === 0 ? 'show active' : ''; ?>" id="tab-content-<?php echo $tab['id']; ?>" role="tabpanel" aria-labelledby="tab-btn-<?php echo $tab['id']; ?>">
                                        <div class="mt-2">
                                            <?php echo $tab['tab_content']; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>

                        <?php else: ?>
                            <!-- Fallback: Just display full description if no tabs exist -->
                            <?php if (!empty($exam['full_description'])): ?>
                                <?php echo $exam['full_description']; ?>
                            <?php else: ?>
                                <div class="alert alert-info text-center">
                                    <h4>Content Coming Soon</h4>
                                    <p>We are currently updating the details for this exam.</p>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>

                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php include 'includes/footer-new.php'; ?>

    <script src="/assets/js/jquery-3.7.1.min.js"></script>
    <script src="/assets/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/js/slick.min.js"></script>
    <script src="/assets/js/odometer.min.js"></script>
    <script src="/assets/js/jquery-ui.min.js"></script>
    <script src="/assets/js/main.js"></script>
    <script src="/assets/js/header-modals.js"></script>
</body>
</html>
