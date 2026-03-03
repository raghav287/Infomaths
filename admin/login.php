<?php
// Start the session. This must be at the very top of the file.
session_start();

// If the user is already logged in, redirect them to the panel
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header("Location: panel");
    exit;
}

// Include the database connection
require_once '../database.php';

// Define a variable to hold error messages
$error_message = '';

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Check if email and password are set and not empty
    if (empty(trim($_POST['email'])) || empty(trim($_POST['password']))) {
        $error_message = "Please enter both email and password.";
    } else {
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);

        // Prepare a statement to select the user by email
        $sql = "SELECT id, email, password FROM admin_credentials WHERE email = ?";
        
        try {
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$email]);
            
            // Check if a user with that email exists
            if ($stmt->rowCount() == 1) {
                $user = $stmt->fetch();
                $database_password = $user['password'];

                // Verify the submitted password against the password in the database
                if ($password == $database_password) {
                    // Password is correct! Start a new session.
                    session_regenerate_id(); // Prevent session fixation attacks
                    $_SESSION['admin_logged_in'] = true;
                    $_SESSION['admin_id'] = $user['id'];
                    $_SESSION['admin_email'] = $user['email'];

                    // Redirect to the admin panel
                    header("Location: panel");
                    exit;
                } else {
                    // Password is not valid
                    $error_message = "Invalid password.";
                }
            } else {
                // No user found with that email
                $error_message = "No account found with that email address.";
            }
        } catch (PDOException $e) {
            error_log("Login Error: " . $e->getMessage());
            $error_message = "An error occurred. Please try again later.";
        }
    }
}
?>

<!doctype html>
<html lang="en" dir="ltr">

<head>

    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="Admin login portal for Info Maths Online management system. Secure access to toppers gallery, testimonials, demo videos, and maths notices management.">
    <meta name="keywords" content="admin login, Info Maths Online, toppers management, testimonials, demo videos, maths notices, secure login">
    <meta name="robots" content="noindex, nofollow">

    <link rel="shortcut icon" type="image/x-icon" href="../assets/img/favicon.png">

    <title>Admin Login - Info Maths Online</title>

    <link id="style" href="assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <link href="assets/css/style.css" rel="stylesheet">

    <link href="assets/css/plugins.css" rel="stylesheet">

    <link href="assets/css/icons.css" rel="stylesheet">

    <link href="assets/switcher/css/switcher.css" rel="stylesheet">
    <link href="assets/switcher/demo.css" rel="stylesheet">

</head>

<body class="app sidebar-mini ltr login-img">

    <div class="">

        <div id="global-loader">
            <img src="assets/images/loader.svg" class="loader-img" alt="Loader">
        </div>
        <div class="page">
            <div class="">
                <div class="col col-login mx-auto mt-7">
                    <div class="text-center">
                        <a href="/"><img src="assets/images/brand/new-logo-info.png"  style="max-width: 150px;"class="header-brand-img" alt="Info Maths Online Admin"></a>
                    </div>
                </div>

                <div class="container-login100">
                    <div class="wrap-login100 p-6">
                        <form class="login100-form validate-form" method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                            <span class="login100-form-title pb-5">
                              Admin Login
                            </span>

                            <?php if (!empty($error_message)): ?>
                                <div class="alert alert-danger text-center" role="alert">
                                    <?php echo $error_message; ?>
                                </div>
                            <?php endif; ?>

                            <div class="panel panel-primary">
                              
                                <div class="panel-body tabs-menu-body p-0 pt-5">
                                    <div class="tab-content">
                                        <div class="tab-pane active" id="tab5">
                                            <div class="wrap-input100 validate-input input-group" data-bs-validate="Valid email is required: ex@abc.xyz">
                                                <a href="javascript:void(0)" class="input-group-text bg-white text-muted">
                                                    <i class="zmdi zmdi-email text-muted" aria-hidden="true"></i>
                                                </a>
                                                <input class="input100 border-start-0 form-control ms-0" type="email" name="email" placeholder="Email" required>
                                            </div>
                                            <div class="wrap-input100 validate-input input-group" id="Password-toggle">
                                                <a href="javascript:void(0)" class="input-group-text bg-white text-muted">
                                                    <i class="zmdi zmdi-eye text-muted" aria-hidden="true"></i>
                                                </a>
                                                <input class="input100 border-start-0 form-control ms-0" type="password" name="password" placeholder="Password" required>
                                            </div>
                                            <div class="container-login100-form-btn">
                                                <button type="submit" class="login100-form-btn btn-primary">
                                                    Login
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
                </div>
        </div>
        </div>
    <script src="assets/js/jquery.min.js"></script>

    <script src="assets/plugins/bootstrap/js/popper.min.js"></script>
    <script src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>

    <script src="assets/js/show-password.min.js"></script>

    <script src="assets/js/generate-otp.js"></script>

    <script src="assets/plugins/p-scroll/perfect-scrollbar.js"></script>

    <script src="assets/js/themeColors.js"></script>

    <script src="assets/js/custom.js"></script>

    <script src="assets/js/custom-swicher.js"></script>

    <script src="assets/switcher/js/switcher.js"></script>

</body>

</html>
