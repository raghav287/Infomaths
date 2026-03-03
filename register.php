<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register Now | Infomaths Courses</title>
    <meta name="description"
        content="Register for MCA Entrance, Bank PO SSC, IIT JAM Maths, CSIR NET JRF, BCA & B.Sc Subject Classes at Infomaths.">
    <link rel="icon" href="assets/img/favicon.png" />

    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/fontawesome.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">

    <style>
    .page-hero {
        background: linear-gradient(135deg, #1C56E1 0%, #000033 100%);
        padding: 100px 0 60px;
        color: #fff;
    }

    .register-wrapper {
        background: #fff;
        padding: 50px;
        border-radius: 15px;
        box-shadow: 0 5px 25px rgba(0, 0, 0, 0.05);
    }

    .highlight-box {
        background: rgba(28, 86, 225, 0.05);
        padding: 25px;
        border-radius: 12px;
        margin-bottom: 20px;
    }

    .course-list li {
        padding: 6px 0;
    }
    </style>
</head>

<body>

    <?php include 'includes/header.php'; ?>

    <!-- HERO -->
    <section class="page-hero text-center">
        <div class="container">
            <h1 class="display-4 fw-bold text-white mt-5">Register For Courses</h1>
            <p class="lead text-white-50">Start Your Preparation With Infomaths Today</p>
        </div>
    </section>

    <!-- REGISTER SECTION -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row g-4 align-items-center">

                <!-- LEFT CONTENT -->
                <div class="col-lg-6">

                    <div class="highlight-box">
                        <h3 class="fw-bold mb-3" style="color:#000033;">Available Courses</h3>
                        <ul class="list-unstyled course-list">
                            <li>✔ MCA Entrance</li>
                            <li>✔ Bank PO SSC</li>
                            <li>✔ IIT JAM Maths</li>
                            <li>✔ CSIR NET JRF</li>
                            <li>✔ BCA Subject Classes</li>
                            <li>✔ B.Sc Subject Classes</li>
                        </ul>
                    </div>

                    <div class="highlight-box">
                        <h5 class="fw-bold">Why Choose Infomaths?</h5>
                        <ul class="list-unstyled">
                            <li>✔ 10+ Years Experience</li>
                            <li>✔ Expert Faculty</li>
                            <li>✔ Small Batch Size</li>
                            <li>✔ Mock Tests & Performance Tracking</li>
                            <li>✔ Personal Mentorship</li>
                        </ul>
                    </div>

                </div>

                <!-- FORM -->
                <div class="col-lg-6">
                    <div class="register-wrapper">
                        <h2 class="mb-4 fw-bold" style="color:#000033;">Register Now</h2>

                        <form id="registerForm">

                            <div class="row g-3">

                                <div class="col-md-6">
                                    <input type="text" class="form-control p-3 bg-light border-0"
                                        placeholder="Full Name" required>
                                </div>

                                <div class="col-md-6">
                                    <input type="tel" class="form-control p-3 bg-light border-0"
                                        placeholder="Mobile Number" required>
                                </div>

                                <div class="col-12">
                                    <input type="email" class="form-control p-3 bg-light border-0"
                                        placeholder="Email Address" required>
                                </div>

                                <div class="col-12">
                                    <select class="form-select p-3 bg-light border-0" required>
                                        <option selected disabled>Select Course</option>
                                        <option>MCA Entrance</option>
                                        <option>Bank PO SSC</option>
                                        <option>IIT JAM Maths</option>
                                        <option>CSIR NET JRF</option>
                                        <option>BCA Subject Classes</option>
                                        <option>B.Sc Subject Classes</option>
                                    </select>
                                </div>

                                <div class="col-12">
                                    <textarea class="form-control p-3 bg-light border-0" rows="3"
                                        placeholder="Your Message (Optional)"></textarea>
                                </div>

                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary w-100 py-3 fw-bold"
                                        style="background-color:#1C56E1; border:none;">
                                        Submit Registration
                                    </button>
                                </div>

                            </div>

                            <div class="form-message mt-3"></div>

                        </form>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <?php include 'includes/footer-new.php'; ?>

    <script src="assets/js/jquery-3.7.1.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>

    <script>
    $(document).ready(function() {
        $('#registerForm').on('submit', function(e) {
            e.preventDefault();
            $('.form-message').html(
                '<div class="alert alert-success">Thank you! Our team will contact you soon.</div>'
            );
            this.reset();
        });
    });
    </script>

</body>

</html>