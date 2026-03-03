<?php
require_once 'database.php';

// Fetch active testimonials
$testimonials = [];
try {
    $stmt = $pdo->query("SELECT * FROM testimonials ORDER BY created_at DESC");
    $testimonials = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $testimonials = [];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Student Testimonials | Infomaths</title>
    <meta name="description" content="Read what our students say about their experience with Infomaths coaching">

    <!-- Favicon -->
    <link rel="icon" href="assets/img/favicon.png" />

    <!-- CSS -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/fontawesome.min.css" rel="stylesheet">
    <link href="assets/css/style.css?v=<?php echo time(); ?>" rel="stylesheet">

    <style>
    .testimonials-hero {
        background: linear-gradient(135deg, #1C56E1 0%, #000033 100%);
        padding: 120px 0 80px;
        color: #fff;
        position: relative;
        overflow: hidden;
    }

    .testimonials-hero::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="rgba(255,255,255,0.05)" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,112C672,96,768,96,864,112C960,128,1056,160,1152,160C1248,160,1344,128,1392,112L1440,96L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>') no-repeat bottom;
        background-size: cover;
    }

    .testimonials-section {
        padding: 80px 0;
        background: #f8f9fa;
    }

    .testimonial-card {
        background: #fff;
        border-radius: 15px;
        padding: 30px;
        /* margin-bottom removed - handled by grid */
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
        transition: transform 0.3s, box-shadow 0.3s;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .testimonial-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(28, 86, 225, 0.15);
    }

    .testimonial-header {
        display: flex;
        align-items: center;
        margin-bottom: 20px;
    }

    .testimonial-image {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        object-fit: cover;
        margin-right: 15px;
        border: 3px solid #1C56E1;
    }

    .testimonial-image-placeholder {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        background: linear-gradient(135deg, #1C56E1, #000033);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 28px;
        font-weight: 700;
        margin-right: 15px;
    }

    .testimonial-info h4 {
        margin: 0;
        color: #000033;
        font-size: 18px;
        font-weight: 700;
    }

    .testimonial-info .course {
        color: #1C56E1;
        font-size: 14px;
        margin-top: 3px;
        /* height: 22rem; */
    }

    .testimonial-content {
        color: #555;
        line-height: 1.8;
        font-size: 15px;
        flex-grow: 1;
        position: relative;
        padding-left: 25px;
        /* Increased padding */
        margin-top: 15px;
        /* Added margin */

    }

    .testimonial-content::before {
        content: '"';
        position: absolute;
        left: -5px;
        /* Adjusted position */
        top: -20px;
        /* Adjusted top position */
        font-size: 60px;
        color: #1C56E1;
        opacity: 0.15;
        /* Slightly reduced opacity */
        font-family: Georgia, serif;
        line-height: 1;
    }

    .quote-icon {
        color: #1C56E1;
        font-size: 40px;
        opacity: 0.2;
        margin-bottom: 15px;
    }

    .no-testimonials {
        text-align: center;
        padding: 60px 20px;
    }

    .no-testimonials i {
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
    <section class="testimonials-hero">
        <div class="container text-center" style="position: relative; z-index: 2;">
            <h1 class="display-4 fw-bold mb-3 text-white mt-5">Student Testimonials</h1>
            <p class="lead mb-0 text-white">Hear from our successful students about their journey with Infomaths</p>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="testimonials-section">
        <div class="container">
            <?php if (!empty($testimonials)): ?>
            <div class="row">
                <?php foreach ($testimonials as $testimonial): ?>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="testimonial-card">
                        <div class="testimonial-header">
                            <?php if (!empty($testimonial['image_path'])): ?>
                            <img src="assets/testimonials/<?php echo htmlspecialchars($testimonial['image_path']); ?>"
                                alt="<?php echo htmlspecialchars($testimonial['name']); ?>" class="testimonial-image">
                            <?php else: ?>
                            <div class="testimonial-image-placeholder">
                                <?php echo strtoupper(substr($testimonial['name'], 0, 1)); ?>
                            </div>
                            <?php endif; ?>
                            <div class="testimonial-info">
                                <h4><?php echo htmlspecialchars($testimonial['name']); ?></h4>
                                <?php if (!empty($testimonial['designation'])): ?>
                                <div class="course"><?php echo htmlspecialchars($testimonial['designation']); ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="testimonial-content">
                            <?php echo nl2br(htmlspecialchars($testimonial['content'])); ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php else: ?>
            <div class="no-testimonials">
                <i class="fas fa-comments"></i>
                <h3>No Testimonials Yet</h3>
                <p class="text-muted">Check back soon to read what our students have to say!</p>
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