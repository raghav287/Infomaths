<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <title>Our Philosophy | Infomaths</title>
    <meta name="description" content="Our Philosophy at Infomaths: Guiding principles focused on quality education, student interests, and delivering more than promised.">
    
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
        .philosophy-content p {
            font-size: 1.15rem;
            line-height: 1.8;
            color: #555;
            margin-bottom: 2rem;
            text-align: center;
        }
        .principles-list {
            list-style: none;
            padding: 0;
            margin-top: 30px;
        }
        .principles-list li {
            background: #fff;
            padding: 20px 25px;
            margin-bottom: 20px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            display: flex;
            align-items: center;
            font-size: 1.1rem;
            color: #333;
            border-left: 5px solid #1C56E1;
            transition: transform 0.3s ease;
        }
        .principles-list li:hover {
            transform: translateX(10px);
            background: #f8f9fa;
        }
        .principles-list li i {
            color: #1C56E1;
            font-size: 24px;
            margin-right: 20px;
            flex-shrink: 0;
        }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <!-- Hero Section -->
    <section class="page-hero text-center">
        <div class="container position-relative z-1">
            <h1 class="display-4 fw-bold mb-3 text-white mt-5">Our Philosophy</h1>
            <p class="lead text-white-50 mb-0">The Guiding Principles of INFOMATHS</p>
        </div>
    </section>
    
    <!-- Content Section -->
    <section class="py-5 bg-light">
        <div class="container py-4">
            <div class="row justify-content-center">
                <div class="col-lg-9 philosophy-content">
                    
                    <p class="mb-5">The following are the guiding principles for the working of all <strong>INFOMATHS</strong> centres. All of us at INFOMATHS consider these principles as supreme. We try to observe these principles in high spirit at all times.</p>

                    <ul class="principles-list">
                        <li>
                            <i class="fa-solid fa-graduation-cap"></i>
                            Provide highest quality education to students.
                        </li>
                        <li>
                            <i class="fa-solid fa-hand-holding-heart"></i>
                            Give students more than what we promise them.
                        </li>
                        <li>
                            <i class="fa-solid fa-tags"></i>
                            Charge very reasonable fees – without compromising on the quality of inputs that we give.
                        </li>
                        <li>
                            <i class="fa-solid fa-users"></i>
                            Act in the best interests of students at all times.
                        </li>
                    </ul>

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
