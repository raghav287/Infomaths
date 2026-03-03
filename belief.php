<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <title>Our Belief | Infomaths</title>
    <meta name="description" content="Our Belief at Infomaths: Ethics, Quality Teaching, Core Values, and Personal Attention for every student's success.">
    
    <!-- Favicon -->
    <link rel="icon" href="assets/img/favicon.png" />
    
    <!-- CSS -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/fontawesome.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    
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
        .belief-card {
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
            margin-bottom: 25px;
            border-top: 4px solid #1C56E1;
            transition: transform 0.3s;
            height: 100%;
        }
        .belief-card:hover {
            transform: translateY(-5px);
        }
        .belief-title {
            color: #000033;
            font-weight: 700;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
        }
        .belief-title i {
            color: #1C56E1;
            margin-right: 12px;
            font-size: 1.25rem;
        }
        .belief-text {
            color: #555;
            line-height: 1.7;
            font-size: 1.05rem;
            margin-bottom: 0;
            text-align: justify;
        }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <!-- Hero Section -->
    <section class="page-hero text-center">
        <div class="container position-relative z-1">
            <h1 class="display-4 fw-bold mb-3 text-white mt-5">Our Belief</h1>
            <p class="lead text-white-50 mb-0">Core principles that drive our commitment to excellence</p>
        </div>
    </section>
    
    <!-- Content Section -->
    <section class="py-5 bg-light">
        <div class="container py-4">
            <div class="row g-4 justify-content-center">
                
                <!-- Ethics -->
                <div class="col-lg-6">
                    <div class="belief-card">
                        <h3 class="belief-title">
                            <i class="fa-solid fa-scale-balanced"></i>
                            Ethics
                        </h3>
                        <p class="belief-text">
                            Our philosophy is based on sheer ethics and thus our entire team of faculties, counsellors etc., are monitored and ensured that our students receive the right kind of training and studying atmosphere.
                        </p>
                    </div>
                </div>

                <!-- Quality Teaching -->
                <div class="col-lg-6">
                    <div class="belief-card">
                        <h3 class="belief-title">
                            <i class="fa-solid fa-chalkboard-user"></i>
                            Quality Teaching
                        </h3>
                        <p class="belief-text">
                            We believe in imparting quality teaching and course materials along with result-oriented methodologies. We strive to provide the best resources to help students excel in their exams.
                        </p>
                    </div>
                </div>

                <!-- Values -->
                <div class="col-lg-6">
                    <div class="belief-card">
                        <h3 class="belief-title">
                            <i class="fa-solid fa-star"></i>
                            Values
                        </h3>
                        <p class="belief-text">
                            We strongly believe in values like discipline, integrity, commitments, professionalism and simplicity in our actions which are definitely going to lead our students to the path of success.
                        </p>
                    </div>
                </div>

                <!-- Personal Attention -->
                <div class="col-lg-6">
                    <div class="belief-card">
                        <h3 class="belief-title">
                            <i class="fa-solid fa-user-check"></i>
                            Personal Attention
                        </h3>
                        <p class="belief-text">
                            We believe in providing personal attention to all our students so that they are able bring out their work to their maximum potential as well as achieve maximum score in the competitive examinations.
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <?php include 'includes/footer-new.php'; ?>
    
    <script src="assets/js/jquery-3.7.1.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/main.js"></script>
    <script src="assets/js/header-modals.js"></script>
</body>
</html>
