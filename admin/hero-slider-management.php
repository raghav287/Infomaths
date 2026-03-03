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

$messages = []; // Initialize an empty array for the results.
$error_message = ''; // Initialize an empty error message.
$success_message = ''; // For success messages

// Handle form submission for adding/editing slides
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_slide'])) {
        // Add new slide
        $title = trim($_POST['title']);
        $subtitle = trim($_POST['subtitle']);
        $button1_text = trim($_POST['button1_text']);
        $button1_link = trim($_POST['button1_link']);
        $status = $_POST['status'];
        $sort_order = (int)$_POST['sort_order'];

        if (empty($title) || empty($subtitle)) {
            $error_message = "Title and subtitle are required.";
        } else {
            // Handle background image upload
            $background_image = '';
            if (isset($_FILES['background_image']) && $_FILES['background_image']['error'] == 0) {
                $upload_dir = '../assets/img/bg/';
                $file_extension = pathinfo($_FILES['background_image']['name'], PATHINFO_EXTENSION);
                $file_name = 'hero_bg_' . time() . '.' . $file_extension;
                $target_file = $upload_dir . $file_name;

                if (move_uploaded_file($_FILES['background_image']['tmp_name'], $target_file)) {
                    $background_image = 'assets/img/bg/' . $file_name;
                }
            }

            try {
                $stmt = $pdo->prepare("INSERT INTO hero_slider (title, subtitle, background_image, button1_text, button1_link, status, sort_order) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$title, $subtitle, $background_image, $button1_text, $button1_link, $status, $sort_order]);
                $success_message = 'Hero slide added successfully!';
            } catch (PDOException $e) {
                $error_message = 'Error adding slide: ' . $e->getMessage();
            }
        }
    } elseif (isset($_POST['edit_slide'])) {
        // Edit existing slide
        $id = (int)$_POST['slide_id'];
        $title = trim($_POST['title']);
        $subtitle = trim($_POST['subtitle']);
        $button1_text = trim($_POST['button1_text']);
        $button1_link = trim($_POST['button1_link']);
        $status = $_POST['status'];
        $sort_order = (int)$_POST['sort_order'];

        if (empty($title) || empty($subtitle)) {
            $error_message = "Title and subtitle are required.";
        } else {
            // Handle background image upload
            $background_image = $_POST['current_background_image'];
            if (isset($_FILES['background_image']) && $_FILES['background_image']['error'] == 0) {
                $upload_dir = '../assets/img/bg/';
                $file_extension = pathinfo($_FILES['background_image']['name'], PATHINFO_EXTENSION);
                $file_name = 'hero_bg_' . time() . '.' . $file_extension;
                $target_file = $upload_dir . $file_name;

                if (move_uploaded_file($_FILES['background_image']['tmp_name'], $target_file)) {
                    $background_image = 'assets/img/bg/' . $file_name;
                    // Delete old image if it exists
                    if (!empty($_POST['current_background_image']) && file_exists('../' . $_POST['current_background_image'])) {
                        unlink('../' . $_POST['current_background_image']);
                    }
                }
            }

            try {
                $stmt = $pdo->prepare("UPDATE hero_slider SET title = ?, subtitle = ?, background_image = ?, button1_text = ?, button1_link = ?, status = ?, sort_order = ? WHERE id = ?");
                $stmt->execute([$title, $subtitle, $background_image, $button1_text, $button1_link, $status, $sort_order, $id]);
                $success_message = 'Hero slide updated successfully!';
            } catch (PDOException $e) {
                $error_message = 'Error updating slide: ' . $e->getMessage();
            }
        }
    }
}

// Handle delete
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    try {
        // Get slide data first to delete images
        $stmt = $pdo->prepare("SELECT background_image FROM hero_slider WHERE id = ?");
        $stmt->execute([$id]);
        $slide = $stmt->fetch(PDO::FETCH_ASSOC);

        // Delete images
        if (!empty($slide['background_image']) && file_exists('../' . $slide['background_image'])) {
            unlink('../' . $slide['background_image']);
        }

        // Delete from database
        $stmt = $pdo->prepare("DELETE FROM hero_slider WHERE id = ?");
        $stmt->execute([$id]);
        $success_message = 'Hero slide deleted successfully!';
    } catch (PDOException $e) {
        $error_message = 'Error deleting slide: ' . $e->getMessage();
    }
}

// Get all slides
try {
    $stmt = $pdo->query("SELECT * FROM hero_slider ORDER BY sort_order ASC, created_at DESC");
    $slides = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Database Fetch Error: " . $e->getMessage());
    $error_message = "Could not retrieve slides. Please try again later.";
    $slides = [];
}

// Get slide for editing
$edit_slide = null;
if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
    $edit_id = (int)$_GET['edit'];
    foreach ($slides as $slide) {
        if ($slide['id'] == $edit_id) {
            $edit_slide = $slide;
            break;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Info Maths Online admin dashboard - Manage hero slider content for the homepage.">
    <meta name="keywords" content="admin dashboard, hero slider, homepage management, Info Maths Online">
    <meta name="robots" content="noindex, nofollow">
    <!-- FAVICON -->
    <link rel="shortcut icon" type="image/x-icon" href="../assets/img/favicon.png">

    <!-- TITLE -->
    <title>Info Maths Online - Hero Slider Management</title>

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
                            <h1 class="page-title">Hero Slider Management</h1>
                            <div>
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Hero Slider Management</li>
                                </ol>
                            </div>
                        </div>
                        <!-- PAGE-HEADER END -->

                        <!-- Row -->
                        <div class="row row-sm">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title" style="margin-right:4px;">
                                            <?php echo $edit_slide ? 'Edit Hero Slide' : 'Add New Hero Slide'; ?></h3>
                                        <?php if (!$edit_slide): ?>
                                        <a href="?add=1" class="btn btn-primary btn-sm">Add New Slide</a>
                                        <?php endif; ?>
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

                                        <form method="POST" action="" enctype="multipart/form-data">
                                            <?php if ($edit_slide): ?>
                                            <input type="hidden" name="slide_id"
                                                value="<?php echo $edit_slide['id']; ?>">
                                            <input type="hidden" name="current_background_image"
                                                value="<?php echo htmlspecialchars($edit_slide['background_image']); ?>">
                                            <?php endif; ?>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="title">Title *</label>
                                                        <input type="text" class="form-control" id="title" name="title"
                                                            required
                                                            value="<?php echo $edit_slide ? htmlspecialchars($edit_slide['title']) : ''; ?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="subtitle">Subtitle *</label>
                                                        <textarea class="form-control" name="subtitle" rows="5"
                                                            required>
<?php echo $edit_slide ? htmlspecialchars($edit_slide['subtitle']) : ''; ?>
</textarea>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="background_image">Background Image</label>
                                                        <input type="file" class="form-control" id="background_image"
                                                            name="background_image" accept=".jpg,.jpeg,.png,.gif,.webp">
                                                        <small class="form-text text-muted">Recommended size:
                                                            1520x720px. Leave empty to keep current image.</small>
                                                        <?php if ($edit_slide && !empty($edit_slide['background_image'])): ?>
                                                        <div class="mt-2">
                                                            <small class="text-muted">Current:
                                                                <?php echo htmlspecialchars($edit_slide['background_image']); ?></small>
                                                        </div>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="button1_text">Button Text</label>
                                                        <input type="text" class="form-control" id="button1_text"
                                                            name="button1_text"
                                                            value="<?php echo $edit_slide ? htmlspecialchars($edit_slide['button1_text']) : 'Explore Programs'; ?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="button1_link">Button Link</label>
                                                        <input type="text" class="form-control" id="button1_link"
                                                            name="button1_link"
                                                            value="<?php echo $edit_slide ? htmlspecialchars($edit_slide['button1_link']) : 'courses.html'; ?>">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="status">Status</label>
                                                        <select class="form-control" id="status" name="status" required>
                                                            <option value="active"
                                                                <?php echo ($edit_slide && $edit_slide['status'] == 'active') ? 'selected' : ''; ?>>
                                                                Active</option>
                                                            <option value="inactive"
                                                                <?php echo ($edit_slide && $edit_slide['status'] == 'inactive') ? 'selected' : ''; ?>>
                                                                Inactive</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="sort_order">Sort Order</label>
                                                        <input type="number" class="form-control" id="sort_order"
                                                            name="sort_order"
                                                            value="<?php echo $edit_slide ? htmlspecialchars($edit_slide['sort_order']) : '0'; ?>"
                                                            min="0">
                                                        <small class="form-text text-muted">Lower numbers appear
                                                            first</small>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <button type="submit"
                                                    name="<?php echo $edit_slide ? 'edit_slide' : 'add_slide'; ?>"
                                                    class="btn btn-primary">
                                                    <?php echo $edit_slide ? 'Update Slide' : 'Add Slide'; ?>
                                                </button>
                                                <?php if ($edit_slide): ?>
                                                <a href="?" class="btn btn-secondary ml-2">Cancel</a>
                                                <?php endif; ?>
                                            </div>
                                        </form>

                                        <!-- Slides List -->
                                        <div class="row row-sm">
                                            <div class="col-lg-12">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <h3 class="card-title" style="margin-right:4px;">All Hero Slides
                                                        </h3>
                                                        <a href="?add=1" class="btn btn-primary btn-sm">Add New
                                                            Slide</a>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="table-responsive">
                                                            <table
                                                                class="table table-bordered text-nowrap border-bottom"
                                                                id="slides-datatable">
                                                                <thead>
                                                                    <tr>
                                                                        <th class="wd-5p border-bottom-0">ID</th>
                                                                        <th class="wd-20p border-bottom-0">Title</th>
                                                                        <th class="wd-15p border-bottom-0">Subtitle</th>
                                                                        <th class="wd-15p border-bottom-0">Background
                                                                        </th>
                                                                        <th class="wd-10p border-bottom-0">Status</th>
                                                                        <th class="wd-5p border-bottom-0">Order</th>
                                                                        <th class="wd-15p border-bottom-0">Created</th>
                                                                        <th class="wd-15p border-bottom-0">Actions</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php if (!empty($slides)): ?>
                                                                    <?php foreach ($slides as $slide): ?>
                                                                    <tr>
                                                                        <td><?php echo htmlspecialchars($slide['id']); ?>
                                                                        </td>
                                                                        <td><?php echo htmlspecialchars($slide['title']); ?>
                                                                        </td>
                                                                        <td><?php echo htmlspecialchars($slide['subtitle']); ?>
                                                                        </td>
                                                                        <td>
                                                                            <?php if ($slide['background_image']): ?>
                                                                            <img src="../<?php echo htmlspecialchars($slide['background_image']); ?>"
                                                                                alt="Background"
                                                                                style="width: 60px; height: 40px; object-fit: cover;">
                                                                            <?php else: ?>
                                                                            No Image
                                                                            <?php endif; ?>
                                                                        </td>
                                                                        <td>
                                                                            <span
                                                                                class="<?php echo $slide['status'] == 'active' ? 'badge-success' : 'badge-danger'; ?>">
                                                                                <?php echo ucfirst($slide['status']); ?>
                                                                            </span>
                                                                        </td>
                                                                        <td><?php echo htmlspecialchars($slide['sort_order']); ?>
                                                                        </td>
                                                                        <td><?php echo date('M j, Y', strtotime($slide['created_at'])); ?>
                                                                        </td>
                                                                        <td>
                                                                            <div class="btn-group" role="group">
                                                                                <a href="?edit=<?php echo $slide['id']; ?>"
                                                                                    class="btn btn-warning btn-sm"
                                                                                    title="Edit">
                                                                                    <i class="fa fa-edit"></i> Edit
                                                                                </a>
                                                                                <a href="?delete=<?php echo $slide['id']; ?>"
                                                                                    class="btn btn-danger btn-sm"
                                                                                    onclick="return confirm('Are you sure you want to delete this slide?')"
                                                                                    title="Delete">
                                                                                    <i class="fa fa-trash"></i> Delete
                                                                                </a>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                    <?php endforeach; ?>
                                                                    <?php else: ?>
                                                                    <tr>
                                                                        <td colspan="8" class="text-center">No hero
                                                                            slides found.</td>
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