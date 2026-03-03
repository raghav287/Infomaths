<?php
// Initialize the session
session_start();

// Check if the user is logged in. If not, redirect them to the login page.
if (!isset($_SESSION["admin_logged_in"]) || $_SESSION["admin_logged_in"] !== true) {
    header("location: login");
    exit;
}

// Include the database connection file.
require_once '../database.php';

if ($pdo === null) {
    die("Database connection failed.");
}

$messages = [];
$error_message = '';
$success_message = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_content'])) {
    $id = (int)$_POST['content_id'];
    $title = trim($_POST['title']);
    $content_value = trim($_POST['content_value']);

    try {
        $stmt = $pdo->prepare("UPDATE mca_entrance_content SET title = ?, content_value = ? WHERE id = ?");
        $stmt->execute([$title, $content_value, $id]);
        $success_message = "Content updated successfully.";
    } catch (PDOException $e) {
        $error_message = "Error updating content: " . $e->getMessage();
    }
}

// Fetch content
try {
    $stmt = $pdo->query("SELECT * FROM mca_entrance_content ORDER BY id ASC");
    $contents = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error_message = "Error fetching content.";
}
?>

<!doctype html>
<html lang="en" dir="ltr">

<head>
    <!-- META DATA -->
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="Info Maths Online admin dashboard - Manage MCA Entrance Content">
    
    <!-- FAVICON -->
    <link rel="shortcut icon" type="image/x-icon" href="../assets/img/favicon.png">

    <!-- TITLE -->
    <title>Manage MCA Entrance Content</title>

    <!-- BOOTSTRAP CSS -->
    <link id="style" href="assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- STYLE CSS -->
    <link href="assets/css/style.css" rel="stylesheet">

    <!-- Plugins CSS -->
    <link href="assets/css/plugins.css" rel="stylesheet">

    <!--- FONT-ICONS CSS -->
    <link href="assets/css/icons.css" rel="stylesheet">

    <!-- INTERNAL Switcher css -->
    <link href="assets/switcher/css/switcher.css" rel="stylesheet">
    <link href="assets/switcher/demo.css" rel="stylesheet">
    
    <!-- SUMMERNOTE CSS -->
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
</head>

<body class="app sidebar-mini ltr light-mode">

    <!-- GLOBAL-LOADER -->
    <div id="global-loader">
        <img src="assets/images/loader.svg" class="loader-img" alt="Loader">
    </div>

    <!-- PAGE -->
    <div class="page">
        <div class="page-main">
           <?php include 'assets/header.php'; ?>
           <?php include 'assets/sidebar.php'; ?>

            <!--app-content open-->
            <div class="main-content app-content mt-0">
                <div class="side-app">

                    <!-- CONTAINER -->
                    <div class="main-container container-fluid">

                        <!-- PAGE-HEADER -->
                        <div class="page-header">
                            <h1 class="page-title">Manage MCA Entrance Content</h1>
                            <div>
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                                    <li class="breadcrumb-item">MCA Entrance Page</li>
                                    <li class="breadcrumb-item active" aria-current="page">Update Paragraphs</li>
                                </ol>
                            </div>
                        </div>

                        <!-- Row -->
                        <div class="row row-sm">
                            <div class="col-lg-12">

                                <?php if (!empty($error_message)): ?>
                                    <div class="alert alert-danger" role="alert"><?php echo $error_message; ?></div>
                                <?php endif; ?>

                                <?php if (!empty($success_message)): ?>
                                    <div class="alert alert-success" role="alert"><?php echo $success_message; ?></div>
                                <?php endif; ?>

                                <?php if (!empty($contents)): ?>
                                    <?php foreach ($contents as $content): ?>
                                    <div class="card mb-4">
                                        <div class="card-header">
                                            <h3 class="card-title"><?php echo htmlspecialchars($content['title']); ?> (<?php echo htmlspecialchars($content['content_key']); ?>)</h3>
                                        </div>
                                        <div class="card-body">
                                            <form method="post" action="">
                                                <input type="hidden" name="content_id" value="<?php echo $content['id']; ?>">
                                                
                                                <div class="form-group">
                                                    <label class="form-label">Title</label>
                                                    <input type="text" name="title" class="form-control" value="<?php echo htmlspecialchars($content['title']); ?>">
                                                </div>

                                                <div class="form-group">
                                                    <label class="form-label">Content</label>
                                                    <textarea name="content_value" class="form-control summernote" rows="10"><?php echo htmlspecialchars($content['content_value']); ?></textarea>
                                                </div>

                                                <div class="mt-4">
                                                    <button type="submit" name="update_content" class="btn btn-primary"><i class="fe fe-save"></i> Save Changes</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="alert alert-warning">No content found. Please run setup script.</div>
                                <?php endif; ?>

                            </div>
                        </div>
                    </div>
                    <!-- CONTAINER CLOSED -->

                </div>
            </div>
        </div>

        <!-- FOOTER -->
       <?php include 'assets/footer.php'; ?>
    </div>

    <!-- BACK-TO-TOP -->
    <a href="#top" id="back-to-top"><i class="fa fa-angle-up"></i></a>

    <!-- JQUERY JS -->
    <script src="assets/js/jquery.min.js"></script>

    <!-- BOOTSTRAP JS -->
    <script src="assets/plugins/bootstrap/js/popper.min.js"></script>
    <script src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>

    <!-- Perfect SCROLLBAR JS-->
    <script src="assets/plugins/p-scroll/perfect-scrollbar.js"></script>
    <script src="assets/plugins/p-scroll/pscroll.js"></script>
    <script src="assets/plugins/p-scroll/pscroll-1.js"></script>

    <!-- SIDE-MENU JS -->
    <script src="assets/plugins/sidemenu/sidemenu.js"></script>

    <!-- SIDEBAR JS -->
    <script src="assets/plugins/sidebar/sidebar.js"></script>

    <!-- Color Theme js -->
    <script src="assets/js/themeColors.js"></script>

    <!-- Sticky js -->
    <script src="assets/js/sticky.js"></script>

    <!-- CUSTOM JS -->
    <script src="assets/js/custom.js"></script>

    <!-- Custom-switcher -->
    <script src="assets/js/custom-swicher.js"></script>

    <!-- Switcher js -->
    <script src="assets/switcher/js/switcher.js"></script>

    <!-- SUMMERNOTE JS -->
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>

    <script>
        $(document).ready(function() {
             $('.summernote').summernote({
                placeholder: 'Enter content...',
                tabsize: 2,
                height: 200,
                dialogsInBody: true
             });
        });
    </script>

</body>
</html>
