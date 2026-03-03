<?php
require_once 'database.php';

// Fetch all faculty members
$faculty_members = [];
try {
    $stmt = $pdo->query("SELECT * FROM faculty ORDER BY uploaded_at DESC");
    $faculty_members = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $faculty_members = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <title>Our Faculty | Infomaths</title>
    <meta name="description" content="Meet our experienced faculty members at Infomaths">
    
    <!-- Favicon -->
    <link rel="icon" href="assets/img/favicon.png" />
    
    <!-- CSS -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/fontawesome.min.css" rel="stylesheet">
    <link href="assets/css/style.css?v=<?php echo time(); ?>" rel="stylesheet">
    
    <style>
        .faculty-hero {
            background: linear-gradient(135deg, #1C56E1 0%, #000033 100%);
            padding: 120px 0 80px;
            color: #fff;
            position: relative;
            overflow: hidden;
        }
        .faculty-hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="rgba(255,255,255,0.05)" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,112C672,96,768,96,864,112C960,128,1056,160,1152,160C1248,160,1344,128,1392,112L1440,96L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>') no-repeat bottom;
            background-size: cover;
        }
        .faculty-section {
            padding: 80px 0;
            background: #f8f9fa;
        }
        .faculty-card {
            background: #fff;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            transition: transform 0.3s, box-shadow 0.3s;
            height: 100%;
            border: none;
        }
        .faculty-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(28, 86, 225, 0.15);
        }
        .faculty-image-container {
            height: 300px;
            overflow: hidden;
            position: relative;
        }
        .faculty-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s;
        }
        .faculty-card:hover .faculty-image {
            transform: scale(1.05);
        }
        .faculty-info {
            padding: 25px;
            text-align: center;
        }
        .faculty-name {
            color: #000033;
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 10px;
        }
        .faculty-description {
            color: #555;
            font-size: 15px;
            line-height: 1.6;
            margin-bottom: 0;
        }
        .no-faculty {
            text-align: center;
            padding: 60px 20px;
        }
        .no-faculty i {
            font-size: 80px;
            color: #ddd;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <?php include 'includes/header.php'; ?>
    
    <!-- Hero Section -->
    <section class="faculty-hero">
        <div class="container text-center" style="position: relative; z-index: 2;">
            <h1 class="display-4 fw-bold mb-3 text-white mt-5">Our Faculty</h1>
            <p class="lead mb-0 text-white">Meet the experts guiding your success at Infomaths</p>
        </div>
    </section>
    
    <!-- Faculty Section -->
    <section class="td_shape_section_1 td_gray_bg_3 pb-0" style="padding-top: 80px; padding-bottom: 80px; background-color: #f8f9fa;">
        <div class="container">
            <div class="td_section_heading td_style_1 text-center mb-5">
                <p class="td_section_subtitle_up td_fs_18 td_semibold td_spacing_1 td_mb_10 text-uppercase td_accent_color" style="color: #1C56E1; font-weight: 600; letter-spacing: 1px; margin-bottom: 10px;">
                    <i class="fas fa-users me-2"></i> The Team Behind Your Success <i class="fas fa-users ms-2"></i>
                </p>
                <h2 class="td_section_title td_fs_48 mb-0" style="font-size: 36px; font-weight: 700; color: #000033;">Meet Our Expert Members</h2>
            </div>
            
            <?php if (!empty($faculty_members)): ?>
                <div class="row">
                    <?php foreach ($faculty_members as $member): ?>
                        <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                            <div class="td_team_member text-center h-100" style="background: #fff; padding: 25px; border-radius: 12px; box-shadow: 0 5px 20px rgba(0,0,0,0.05); transition: transform 0.3s ease;">
                                <div class="td_member_img mb-3" style="position: relative; width: 120px; height: 120px; margin: 0 auto; overflow: hidden; border-radius: 50%; border: 3px solid #1C56E1;">
                                    <?php if (!empty($member['image_path'])): ?>
                                        <img src="assets/faculty/<?php echo htmlspecialchars($member['image_path']); ?>" 
                                             alt="<?php echo htmlspecialchars($member['name']); ?>" 
                                             style="width: 100%; height: 100%; object-fit: cover;">
                                    <?php else: ?>
                                        <div class="d-flex align-items-center justify-content-center h-100 bg-light text-secondary">
                                            <i class="fas fa-user fa-3x"></i>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <h4 class="td_member_name td_fs_20 td_mb_5" style="color: #1C56E1; font-weight: 700; font-size: 20px; margin-bottom: 5px;"><?php echo htmlspecialchars($member['name']); ?></h4>
                                <p class="td_member_designation td_fs_14 td_mb_0" style="color: #666; font-size: 14px; margin-bottom: 0; line-height: 1.5;"><?php echo nl2br(htmlspecialchars($member['description'])); ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="no-faculty text-center py-5">
                    <i class="fas fa-chalkboard-teacher fa-4x text-muted mb-3"></i>
                    <h3>No Faculty Members Found</h3>
                    <p class="text-muted">Faculty information will be updated soon.</p>
                </div>
            <?php endif; ?>
        </div>
    </section>
    
    <!-- Footer -->
    <?php include 'includes/footer-new.php'; ?>
    
    <!-- Scripts -->
    <script src="assets/js/jquery-3.7.1.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/main.js"></script>
    <script src="assets/js/header-modals.js"></script>
</body>
</html>
