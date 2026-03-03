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

$toppers = []; // Initialize an empty array for the results.
$error_message = ''; // Initialize an empty error message.
$success_message = ''; // For success messages

// Check for success messages from redirect
if (isset($_GET['success']) && $_GET['success'] == 1) {
    $success_message = "Topper image uploaded successfully.";
}
if (isset($_GET['deleted']) && $_GET['deleted'] == 1) {
    $success_message = "Topper image deleted successfully.";
}

// Define the Category for this page
$PAGE_CATEGORY = 'Bank PO';

// Handle POST requests for upload
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['upload_image'])) {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $category = $PAGE_CATEGORY; // Hardcoded

    if ($name && isset($_FILES['image_file']) && $_FILES['image_file']['error'] == 0) {
        $file_name = $_FILES['image_file']['name'];
        $file_tmp = $_FILES['image_file']['tmp_name'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        $allowed_exts = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

        if (in_array($file_ext, $allowed_exts)) {
            $new_file_name = uniqid() . '.' . $file_ext;
            $upload_path = '../assets/toppers/' . $new_file_name;

            if (move_uploaded_file($file_tmp, $upload_path)) {
                try {
                    $stmt = $pdo->prepare("INSERT INTO toppers (name, description, category, image_path) VALUES (?, ?, ?, ?)");
                    $stmt->execute([$name, $description, $category, $new_file_name]);
                    // Redirect to prevent form resubmission
                    header("Location: bank-po-toppers-management.php?success=1");
                    exit;
                } catch (PDOException $e) {
                    $error_message = "Error saving to database: " . $e->getMessage();
                }
            } else {
                $error_message = "Error uploading file.";
            }
        } else {
            $error_message = "Invalid file type. Allowed: JPG, JPEG, PNG, GIF, WEBP.";
        }
    } else {
        $error_message = "Name and image file are required.";
    }
}

// Handle delete
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    try {
        // Check if the record exists and matches our category
        $stmt = $pdo->prepare("SELECT image_path, category FROM toppers WHERE id = ?");
        $stmt->execute([$id]);
        $record = $stmt->fetch();
        
        if ($record && $record['category'] == $PAGE_CATEGORY) {
            if (file_exists('../assets/toppers/' . $record['image_path'])) {
                unlink('../assets/toppers/' . $record['image_path']);
            }
            $stmt = $pdo->prepare("DELETE FROM toppers WHERE id = ?");
            $stmt->execute([$id]);
            // Redirect to clear query string
            header("Location: bank-po-toppers-management.php?deleted=1");
            exit;
        } else {
            $error_message = "Cannot delete this record (Invalid ID or Category Mismatch).";
        }
    } catch (PDOException $e) {
        $error_message = "Error deleting image: " . $e->getMessage();
    }
}

// Fetch toppers for this category
try {
    $stmt = $pdo->prepare("SELECT * FROM toppers WHERE category = ? ORDER BY uploaded_at DESC");
    $stmt->execute([$PAGE_CATEGORY]);
    $toppers = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Database Fetch Error: " . $e->getMessage());
    $error_message = "Could not retrieve toppers. Please try again later.";
}
?>

<!doctype html>
<html lang="en" dir="ltr">

<head>
    <!-- META DATA -->
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="Info Maths Online admin dashboard - Manage Bank PO Toppers.">
    
    <!-- FAVICON -->
    <link rel="shortcut icon" type="image/x-icon" href="../assets/img/favicon.png">

    <!-- TITLE -->
    <title>Info Maths Online - Bank PO Toppers Management</title>

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
</head>

<body class="app sidebar-mini ltr light-mode">

    <!-- GLOBAL-LOADER -->
    <div id="global-loader">
        <img src="assets/images/loader.svg" class="loader-img" alt="Loader">
    </div>
    <!-- /GLOBAL-LOADER -->

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
                            <h1 class="page-title">Bank PO Toppers Management</h1>
                            <div>
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="panel">Home</a></li>
                                    <li class="breadcrumb-item"><a href="javascript:void(0);">Bank PO & SSC</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Manage Toppers</li>
                                </ol>
                            </div>
                        </div>
                        <!-- PAGE-HEADER END -->

                        <!-- Row -->
                        <div class="row row-sm">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Upload New Topper Image</h3>
                                    </div>
                                    <div class="card-body">

                                        <?php if (!empty($error_message)): ?>
                                            <div class="alert alert-danger" role="alert">
                                                <?php echo $error_message; ?>
                                            </div>
                                        <?php endif; ?>

                                        <?php if (!empty($success_message)): ?>
                                            <div class="alert alert-success" role="alert">
                                                <?php echo $success_message; ?>
                                            </div>
                                        <?php endif; ?>

                                        <form method="post" enctype="multipart/form-data" class="mb-4">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <label>Name</label>
                                                    <input type="text" name="name" class="form-control" required placeholder="e.g. Student Name">
                                                </div>
                                                <div class="col-md-4">
                                                    <label>Description (Rank/Exam)</label>
                                                    <input type="text" name="description" class="form-control" placeholder="e.g. AIR 1 - SBI PO 2024">
                                                </div>
                                                <div class="col-md-4">
                                                    <label>Image File</label>
                                                    <input type="file" name="image_file" class="form-control" accept=".jpg,.jpeg,.png,.gif,.webp" required>
                                                    <small class="text-muted">Allowed: JPG, JPEG, PNG, GIF, WEBP</small>
                                                </div>
                                            </div>
                                            <div class="row mt-3">
                                                <div class="col-md-12">
                                                    <button type="submit" name="upload_image" class="btn btn-success">Upload Topper</button>
                                                </div>
                                            </div>
                                        </form>
                                        
                                        <hr>

                                        <div class="table-responsive">
                                            <table class="table table-bordered text-nowrap border-bottom" id="basic-datatable">
                                                <thead>
                                                    <tr>
                                                        <th class="wd-5p border-bottom-0">ID</th>
                                                        <th class="wd-15p border-bottom-0">Image</th>
                                                        <th class="wd-20p border-bottom-0">Name</th>
                                                        <th class="wd-20p border-bottom-0">Description</th>
                                                        <th class="wd-15p border-bottom-0">Category</th>
                                                        <th class="wd-10p border-bottom-0">Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    if (!empty($toppers)):
                                                        foreach ($toppers as $topper):
                                                    ?>
                                                            <tr>
                                                                <td><?php echo htmlspecialchars($topper['id']); ?></td>
                                                                <td>
                                                                    <img src="../assets/toppers/<?php echo htmlspecialchars($topper['image_path']); ?>" alt="<?php echo htmlspecialchars($topper['name']); ?>" style="width: 80px; height: 60px; object-fit: cover;">
                                                                </td>
                                                                <td><?php echo htmlspecialchars($topper['name']); ?></td>
                                                                <td><?php echo htmlspecialchars($topper['description']); ?></td>
                                                                <td><span class="badge bg-primary"><?php echo htmlspecialchars($topper['category']); ?></span></td>
                                                                <td>
                                                                    <a href="?delete=<?php echo $topper['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</a>
                                                                </td>
                                                            </tr>
                                                        <?php
                                                        endforeach;
                                                    else:
                                                        ?>
                                                        <tr>
                                                            <td colspan="6" class="text-center">No toppers found.</td>
                                                        </tr>
                                                    <?php endif; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- CONTAINER CLOSED -->

                </div>
            </div>
            <!--app-content closed-->
        </div>
        <!-- FOOTER -->
       <?php include 'assets/footer.php'; ?>
        <!-- FOOTER CLOSED -->
    </div>

    <!-- BACK-TO-TOP -->
    <a href="#top" id="back-to-top"><i class="fa fa-angle-up"></i></a>

    <!-- JQUERY JS -->
    <script src="assets/js/jquery.min.js"></script>

    <!-- BOOTSTRAP JS -->
    <script src="assets/plugins/bootstrap/js/popper.min.js"></script>
    <script src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>

    <!-- INPUT MASK JS-->
    <script src="assets/plugins/input-mask/jquery.mask.min.js"></script>

    <!-- TypeHead js -->
    <script src="assets/plugins/bootstrap5-typehead/autocomplete.js"></script>
    <script src="assets/js/typehead.js"></script>

    <!-- INTERNAL SELECT2 JS -->
    <script src="assets/plugins/select2/select2.full.min.js"></script>

    <!-- DATA TABLE JS-->
    <script src="assets/plugins/datatable/js/jquery.dataTables.min.js"></script>
    <script src="assets/plugins/datatable/js/dataTables.bootstrap5.js"></script>
    <script src="assets/plugins/datatable/js/buttons.bootstrap5.min.js"></script>
    <script src="assets/plugins/datatable/js/jszip.min.js"></script>
    <script src="assets/plugins/datatable/pdfmake/pdfmake.min.js"></script>
    <script src="assets/plugins/datatable/pdfmake/vfs_fonts.js"></script>
    <script src="assets/plugins/datatable/js/buttons.html5.min.js"></script>
    <script src="assets/plugins/datatable/js/buttons.print.min.js"></script>
    <script src="assets/plugins/datatable/js/buttons.colVis.min.js"></script>
    <script src="assets/plugins/datatable/responsive.bootstrap5.min.js"></script>
    <script src="assets/plugins/datatable/dataTables.responsive.min.js"></script>
    <script src="assets/js/table-data.js"></script>

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

</body>

</html>
