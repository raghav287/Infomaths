<?php
// Function to verify if user is on a mobile device
function isMobile() {
    return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <title>Contact Us | Infomaths</title>
    <meta name="description" content="Get in touch with Infomaths Institute. Call us at +91 9878624534 or visit our center in Chandigarh.">
    
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
        .contact-info-card {
            background: #fff;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 5px 25px rgba(0,0,0,0.05);
            height: 100%;
            text-align: center;
            transition: transform 0.3s;
        }
        .contact-info-card:hover {
            transform: translateY(-5px);
        }
        .contact-icon {
            width: 80px;
            height: 80px;
            line-height: 80px;
            border-radius: 50%;
            background: rgba(28, 86, 225, 0.1);
            color: #1C56E1;
            font-size: 32px;
            margin: 0 auto 25px;
        }
        .contact-label {
            font-weight: 700;
            margin-bottom: 15px;
            font-size: 22px;
            color: #000033;
        }
        .contact-text {
            color: #666;
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 5px;
        }
        .contact-text a {
            color: #666;
            text-decoration: none;
            transition: color 0.2s;
        }
        .contact-text a:hover {
            color: #1C56E1;
        }
        .map-section {
            height: 500px;
            width: 100%;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 25px rgba(0,0,0,0.05);
        }
        .contact-form-wrapper {
            background: #fff;
            padding: 50px;
            border-radius: 15px;
            box-shadow: 0 5px 25px rgba(0,0,0,0.05);
        }
        .social-links {
            margin-top: 20px;
        }
        .social-btn {
            display: inline-flex;
            width: 45px;
            height: 45px;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: #f8f9fa;
            color: #1C56E1;
            margin: 0 5px;
            font-size: 18px;
            transition: all 0.3s;
        }
        .social-btn:hover {
            background: #1C56E1;
            color: #fff;
            transform: translateY(-3px);
        }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <!-- Hero Section -->
    <section class="page-hero text-center">
        <div class="container position-relative z-1">
            <h1 class="display-4 fw-bold mb-3 text-white mt-5">Contact Us</h1>
            <p class="lead text-white-50 mb-0">We'd love to hear from you. Get in touch with us!</p>
        </div>
    </section>
    
    <!-- Contact Info Section -->
    <section class="py-5">
        <div class="container py-4">
            <div class="row g-4 justify-content-center">
                
                <!-- Call Us -->
                <div class="col-lg-4 col-md-6">
                    <div class="contact-info-card">
                        <div class="contact-icon">
                            <i class="fa-solid fa-phone-volume"></i>
                        </div>
                        <h3 class="contact-label">Call Us</h3>
                        <p class="contact-text">
                            <a href="tel:+919878624534">+91 98786 24534</a>
                        </p>
                        <p class="contact-text">
                            <a href="tel:+919872124534">+91 98721 24534</a>
                        </p>
                    </div>
                </div>

                <!-- Address -->
                <div class="col-lg-4 col-md-6">
                    <div class="contact-info-card">
                        <div class="contact-icon">
                            <i class="fa-solid fa-location-dot"></i>
                        </div>
                        <h3 class="contact-label">Address</h3>
                        <p class="contact-text">
                            Quiet Office 10, Second Floor,<br>
                            Sector 35 A, Chandigarh, 160035
                        </p>
                    </div>
                </div>

                <!-- Email Us -->
                <div class="col-lg-4 col-md-6">
                    <div class="contact-info-card">
                        <div class="contact-icon">
                            <i class="fa-solid fa-envelope"></i>
                        </div>
                        <h3 class="contact-label">Email Us</h3>
                        <p class="contact-text">
                            <a href="mailto:info@Infomathsinstitute.com">info@Infomathsinstitute.com</a>
                        </p>
                        <p class="contact-text">
                            <a href="mailto:career@Infomathsinstitute.com">career@Infomathsinstitute.com</a>
                        </p>
                        
                        <div class="social-links">
                            <p class="contact-text mb-2 fw-bold small text-uppercase" style="letter-spacing:1px; color:#999; font-size:12px;">Connect With Us</p>
                            <a href="https://www.facebook.com/infomathsindia" target="_blank" class="social-btn"><i class="fa-brands fa-facebook-f"></i></a>
                            <a href="https://www.instagram.com/infomaths.coursedu/" target="_blank" class="social-btn"><i class="fa-brands fa-instagram"></i></a>
                            <a href="https://x.com/i9872124534" target="_blank" class="social-btn"><i class="fa-brands fa-twitter"></i></a>
                            <a href="https://www.youtube.com/user/arpana2311" target="_blank" class="social-btn"><i class="fa-brands fa-youtube"></i></a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Map & Form Section -->
    <section class="py-5 bg-light">
        <div class="container py-4">
            <div class="row g-4 align-items-center">
                <!-- Map -->
                <div class="col-lg-6">
                   <div class="map-section">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d3511953.1149751768!2d76.75828!3d30.7284713!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x14f6258053e76563%3A0xf262a6e0f4e3a740!2sInfomaths-%20Institute%20for%20MCA%20Entrance%2C%20NIMCET%2C%20CUET%2C%20MAHCET%2C%20PU%20coaching%2C%20Msc%20Ent.%2C%20Banking%20PO-SSC%20%2C%20CLAT%20%26%20all%20Govt%20Exams!5e0!3m2!1sen!2sin!4v1768213198115!5m2!1sen!2sin" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                   </div>
                </div>
                
                <!-- Helper for Form ID -->
                <?php $index = 99; // Unique index for contact page ?>
                
                <!-- Form -->
                <div class="col-lg-6">
                    <div class="contact-form-wrapper">
                        <h2 class="mb-4 fw-bold" style="color:#000033;">Send Us A Message</h2>
                        <form action="submit_form.php" method="POST" class="unified-contact-form" id="contactPageForm">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="text" class="form-control p-3 bg-light border-0" name="student_name" placeholder="Your Name" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="tel" class="form-control p-3 bg-light border-0" name="student_mobile" placeholder="Phone Number" required>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <input type="email" class="form-control p-3 bg-light border-0" name="student_email" placeholder="Email Address" required>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <input type="text" class="form-control p-3 bg-light border-0" name="course_interest" placeholder="Course Interested In">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <textarea class="form-control p-3 bg-light border-0" name="enquiry" rows="4" placeholder="Your Message" required></textarea>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary w-100 py-3 fw-bold" style="background-color: #1C56E1; border: none;">Send Message</button>
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
    <script src="assets/js/main.js"></script>
    <script src="assets/js/header-modals.js"></script>

    <script>
    $(document).ready(function() {
        $('#contactPageForm').on('submit', function(e) {
            e.preventDefault();
            
            var form = $(this);
            var submitBtn = form.find('button[type="submit"]');
            var originalBtnText = submitBtn.text();
            var messageDiv = form.find('.form-message');
            
            // Disable button and show loading state
            submitBtn.prop('disabled', true).text('Sending...');
            messageDiv.html('').removeClass('text-success text-danger');
            
            $.ajax({
                url: form.attr('action'),
                type: 'POST',
                data: form.serialize(),
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        messageDiv.html('<div class="alert alert-success mt-3">' + response.message + '</div>');
                        form[0].reset();
                    } else {
                        messageDiv.html('<div class="alert alert-danger mt-3">' + response.message + '</div>');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Submission failed:', error);
                    messageDiv.html('<div class="alert alert-danger mt-3">Something went wrong. Please try again later.</div>');
                },
                complete: function() {
                    submitBtn.prop('disabled', false).text(originalBtnText);
                }
            });
        });
    });
    </script>
</body>
</html>
