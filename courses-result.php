<?php
require_once 'database.php';

// Helper for readable section names
function getLabel($key) {
    $map = [
        'pu_results' => 'PU Results',
        'campus_placement' => 'Campus Placements',
        'college_results' => 'College Results',
        'iit_jam_gallery' => 'IIT JAM Gallery'
    ];
    return $map[$key] ?? ucwords(str_replace('_', ' ', $key));
}

// Fetch Other Results (Everything EXCEPT mca_results)
$grouped_results = [];
try {
    $stmt = $pdo->prepare("SELECT * FROM section_images WHERE section_name != 'mca_results' ORDER BY section_name, display_order ASC");
    $stmt->execute();
    $all_rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Group by section
    foreach ($all_rows as $row) {
        $grouped_results[$row['section_name']][] = $row;
    }
} catch (PDOException $e) {
    // Handle error quietly
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <title>Course Results | Infomaths</title>
    <meta name="description" content="View our results across various courses and placements">
    
    <!-- Favicon -->
    <link rel="icon" href="assets/img/favicon.png" />
    
    <!-- CSS -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/fontawesome.min.css" rel="stylesheet">
    <link href="assets/css/style.css?v=<?php echo time(); ?>" rel="stylesheet">
    
    <style>
        .page-hero {
            background: linear-gradient(135deg, #1C56E1 0%, #000033 100%);
            padding: 100px 0 60px;
            color: #fff;
            position: relative;
        }
        .page-hero::before {
             content: '';
             position: absolute;
             top: 0; left: 0; right: 0; bottom: 0;
             background: url('assets/img/hero-pattern.png') repeat;
             opacity: 0.1;
        }
        .section-separator {
            border-bottom: 2px solid #e9ecef;
            margin: 40px 0;
        }
        .category-title {
            color: #000033;
            border-left: 5px solid #1C56E1;
            padding-left: 15px;
            margin-bottom: 30px;
        }
        .result-card {
            background: #fff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            transition: transform 0.3s;
            margin-bottom: 30px;
            height: 100%;
        }
        .result-card:hover {
            transform: translateY(-5px);
        }
        .result-img-container {
            position: relative;
            padding-top: 75%; /* 4:3 Aspect Ratio */
            overflow: hidden;
            cursor: pointer;
        }
        .result-img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s;
        }
        .result-card:hover .result-img {
            transform: scale(1.03);
        }
        .result-title {
            padding: 15px;
            text-align: center;
            font-weight: 600;
            color: #333;
            border-top: 1px solid #eee;
        }
        /* Lightbox */
        .lightbox {
            display: none;
            position: fixed;
            z-index: 9999;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: rgba(0,0,0,0.9);
            justify-content: center;
            align-items: center;
        }
        .lightbox img {
            max-width: 90%;
            max-height: 90%;
            border: 5px solid #fff;
            border-radius: 5px;
        }
        .lightbox-close {
            position: absolute;
            top: 20px; right: 30px;
            color: #fff;
            font-size: 30px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <!-- Hero Section -->
    <section class="page-hero text-center">
        <div class="container position-relative z-1">
            <h1 class="display-4 fw-bold mb-3 text-white mt-5">Our Results</h1>
            <p class="lead text-white-50 mb-0">Proven track record of success in University Exams & Placements</p>
        </div>
    </section>
    
    <!-- Results Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <?php if (!empty($grouped_results)): ?>
                <?php foreach ($grouped_results as $section => $results): ?>
                    <div class="result-category mb-5">
                        <h2 class="category-title mb-4"><?php echo htmlspecialchars(getLabel($section)); ?></h2>
                        <div class="row">
                            <?php foreach ($results as $item): ?>
                                <div class="col-lg-4 col-md-6 mb-4">
                                    <div class="td_radius_10 d-flex align-items-center justify-content-center" onclick="openLightbox('<?php echo htmlspecialchars($item['image_path']); ?>')" style="cursor: pointer; overflow: hidden; border-radius: 10px;  height: 380px;">
                                        <img src="<?php echo htmlspecialchars($item['image_path']); ?>" 
                                             alt="<?php echo htmlspecialchars($item['alt_text']); ?>" 
                                             class="td_radius_10" 
                                             style="max-width: 100%; max-height: 100%; width: auto; height: auto; transition: transform 0.3s;">
                                    </div>
                                    <?php if (!empty($item['alt_text'])): ?>
                                        <div class="text-center mt-2 fw-bold text-muted">
                                            <?php echo htmlspecialchars($item['alt_text']); ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="section-separator"></div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="text-center py-5">
                    <i class="fas fa-certificate fa-4x text-muted mb-3"></i>
                    <h3>No Results Found</h3>
                    <p class="text-muted">Results will be updated soon.</p>
                </div>
            <?php endif; ?>
        </div>
    </section>
    
    <!-- Lightbox -->
    <div id="lightbox" class="lightbox" onclick="closeLightbox()">
        <span class="lightbox-close">&times;</span>
        <img id="lightbox-img" src="" alt="Full Screen Result">
    </div>

    <?php include 'includes/footer-new.php'; ?>
    
    <script src="assets/js/jquery-3.7.1.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/main.js"></script>
    <script src="assets/js/header-modals.js"></script>
    <script>
        function openLightbox(src) {
            document.getElementById('lightbox-img').src = src;
            document.getElementById('lightbox').style.display = 'flex';
        }
        function closeLightbox() {
            document.getElementById('lightbox').style.display = 'none';
        }
    </script>
</body>
</html>
