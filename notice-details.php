<?php
// notice-details.php
require 'database.php';

$slug = isset($_GET['slug']) ? $_GET['slug'] : '';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$notice = null;

try {
    if (!empty($slug)) {
        // Check all notice tables in order
        $tables = ['bank_po_notices', 'bca_notices', 'iit_jam_notices', 'csir_net_notices', 'notices'];
        
        foreach ($tables as $table) {
            $stmt = $pdo->prepare("SELECT * FROM $table WHERE slug = ? AND is_active = 1");
            $stmt->execute([$slug]);
            $notice = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($notice) break;
        }
    } elseif ($id > 0) {
        // Check all notice tables by ID
        $tables = ['bank_po_notices', 'bca_notices', 'iit_jam_notices', 'csir_net_notices', 'notices'];
        
        foreach ($tables as $table) {
            $stmt = $pdo->prepare("SELECT * FROM $table WHERE id = ? AND is_active = 1");
            $stmt->execute([$id]);
            $notice = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($notice) break;
        }
    }
} catch (PDOException $e) {
    // Handle error quietly
}

if (!$notice) {
    // Redirect to main page or show 404
    header("HTTP/1.0 404 Not Found");
    // Optional: Redirect to bank PO page instead of 404
    // header("Location: best-coaching-for-bank-po-ssc.php");
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Notice Not Found - InfoMaths</title>
        <link rel="stylesheet" href="assets/css/bootstrap.min.css">
        <style>
            .error-container { text-align: center; padding: 100px 20px; }
            .error-heading { font-size: 72px; font-weight: bold; color: #1C56E1; }
            .error-sub { font-size: 24px; margin-bottom: 30px; }
        </style>
    </head>
    <body>
        <?php include 'includes/header-new.php'; ?>
        <div class="container error-container">
            <h1 class="error-heading">404</h1>
            <p class="error-sub">Notice not found or has been removed.</p>
            <a href="best-coaching-for-bank-po-ssc.php" class="btn btn-primary">Back to Bank PO Page</a>
        </div>
        <?php include 'includes/footer-new.php'; ?>
    </body>
    </html>
    <?php
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
    <title><?php echo !empty($notice['meta_title']) ? htmlspecialchars($notice['meta_title']) : htmlspecialchars($notice['page_title']) . ' - InfoMaths'; ?></title>
    <meta name="description" content="<?php echo !empty($notice['meta_description']) ? htmlspecialchars($notice['meta_description']) : 'Details for ' . htmlspecialchars($notice['page_title']); ?>">
    
    <!-- Favicon Icon -->
    <link rel="icon" href="/assets/img/favicon.png" />
    
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
    <link href="/assets/css/style.css?v=<?php echo time(); ?>" rel="stylesheet">
    <style>
        .banner-overlay {
            background: linear-gradient(105deg, rgba(0, 0, 27, 0.96) 0%, rgba(28, 86, 225, 0.8) 100%);
            padding: 140px 0 80px;
            color: #fff;
            position: relative;
        }
        .notice-content-wrapper {
            padding: 60px 0;
            background: #f8f9fa;
        }
        .notice-card {
            background: #fff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
            min-height: 400px;
        }
        .notice-card h2, .notice-card h3, .notice-card h4 {
            color: #000033;
            margin-top: 1.5rem;
        }
        .notice-card p {
            font-size: 16px;
            line-height: 1.7;
            color: #444;
            margin-bottom: 1.5rem;
        }
        .notice-card ul {
            padding-left: 20px;
            margin-bottom: 1.5rem;
        }
        .notice-card li {
            margin-bottom: 10px;
        }
        .back-link {
            display: inline-block;
            margin-bottom: 20px;
            color: #1C56E1;
            font-weight: 600;
            text-decoration: none;
        }
        .back-link:hover {
            color: #000033;
        }
    </style>
</head>
<body>
    <!-- Include Standard Header -->
    <?php include 'includes/header.php'; ?>
    
    <!-- Banner -->
    <section class="banner-overlay">
        <div class="container text-center">
            <h1 class="display-4 fw-bold mb-3 text-white"><?php echo htmlspecialchars($notice['page_title']); ?></h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb justify-content-center bg-transparent mb-0">
                    <li class="breadcrumb-item"><a href="index.php" class="text-white-50">Home</a></li>
                    <li class="breadcrumb-item"><a href="best-coaching-for-bank-po-ssc.php" class="text-white-50">Bank PO & SSC</a></li>
                    <li class="breadcrumb-item active text-white" aria-current="page">Notice Details</li>
                </ol>
            </nav>
        </div>
    </section>

    <!-- Main Content -->
    <section class="notice-content-wrapper">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <a href="javascript:void(0);" onclick="if(document.referrer) { window.history.back(); } else { window.location.href='index.php'; }" class="back-link">
                        <i class="fas fa-arrow-left me-2"></i> Back to Notices
                    </a>
                    
                    <div class="notice-card">
                        <?php if (!empty($notice['page_content'])): ?>
                            <!-- Display Rich Text Content -->
                            <?php echo $notice['page_content']; ?>
                        <?php else: ?>
                            <div class="alert alert-info text-center">
                                <h4>Detail Availabe Soon</h4>
                                <p>Detailed information for this notice is currently being updated.</p>
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
    <script src="/assets/js/main.js"></script>
    <script src="/assets/js/header-modals.js"></script>
</body>
</html>
