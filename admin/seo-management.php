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

$seo_settings = []; // Initialize an empty array for the results.
$error_message = ''; // Initialize an empty error message.
$success_message = ''; // For success messages

// Handle POST requests for updating SEO settings
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_seo'])) {
    $page_name = trim($_POST['page_name']);
    $meta_title = trim($_POST['meta_title']);
    $meta_description = trim($_POST['meta_description']);
    $meta_keywords = trim($_POST['meta_keywords']);
    $og_title = trim($_POST['og_title']);
    $og_description = trim($_POST['og_description']);
    $og_image = trim($_POST['og_image']);
    $canonical_url = trim($_POST['canonical_url']);
    $robots_meta = trim($_POST['robots_meta']);

    if ($page_name) {
        try {
            $stmt = $pdo->prepare("INSERT INTO seo_settings (page_name, meta_title, meta_description, meta_keywords, og_title, og_description, og_image, canonical_url, robots_meta) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE meta_title = VALUES(meta_title), meta_description = VALUES(meta_description), meta_keywords = VALUES(meta_keywords), og_title = VALUES(og_title), og_description = VALUES(og_description), og_image = VALUES(og_image), canonical_url = VALUES(canonical_url), robots_meta = VALUES(robots_meta)");
            $stmt->execute([$page_name, $meta_title, $meta_description, $meta_keywords, $og_title, $og_description, $og_image, $canonical_url, $robots_meta]);
            $success_message = "SEO settings updated successfully for " . htmlspecialchars($page_name);
        } catch (PDOException $e) {
            $error_message = "Error saving SEO settings: " . $e->getMessage();
        }
    } else {
        $error_message = "Page name is required.";
    }
}

// Handle delete
if (isset($_GET['delete']) && !empty($_GET['delete'])) {
    $page_name = trim($_GET['delete']);
    try {
        $stmt = $pdo->prepare("DELETE FROM seo_settings WHERE page_name = ?");
        $stmt->execute([$page_name]);
        $success_message = "SEO settings deleted successfully for " . htmlspecialchars($page_name);
    } catch (PDOException $e) {
        $error_message = "Error deleting SEO settings: " . $e->getMessage();
    }
}

// Fetch all SEO settings
try {
    $sql = "SELECT * FROM seo_settings ORDER BY page_name ASC";
    $stmt = $pdo->query($sql);
    $seo_settings = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Database Fetch Error: " . $e->getMessage());
    $error_message = "Could not retrieve SEO settings. Please try again later.";
}

// Get specific page settings for editing
$edit_settings = null;
if (isset($_GET['edit']) && !empty($_GET['edit'])) {
    $edit_page = trim($_GET['edit']);
    foreach ($seo_settings as $setting) {
        if ($setting['page_name'] === $edit_page) {
            $edit_settings = $setting;
            break;
        }
    }
}
?>

<!doctype html>
<html lang="en" dir="ltr">

<head>
    <!-- META DATA -->
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="Manage on-page SEO settings for all website pages on Info Maths Online admin panel.">
    <meta name="keywords" content="SEO management, meta tags, Open Graph, admin panel, Info Maths Online">
    <meta name="robots" content="noindex, nofollow">
    <!-- FAVICON -->
    <link rel="shortcut icon" type="image/x-icon" href="../assets/img/favicon.png">

    <!-- TITLE -->
    <title>Info Maths Online - SEO Management</title>

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
                            <h1 class="page-title">SEO Management</h1>
                            <div>
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">SEO Settings</li>
                                </ol>
                            </div>
                        </div>
                        <!-- PAGE-HEADER END -->

                        <!-- Row -->
                        <div class="row row-sm">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title"><?php echo $edit_settings ? 'Edit SEO Settings for "' . htmlspecialchars($edit_settings['page_name']) . '"' : 'Add/Update SEO Settings'; ?></h3>
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

                                        <form method="post" class="mb-4">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label>Page Name *</label>
                                                    <input type="text" name="page_name" class="form-control" value="<?php echo htmlspecialchars($edit_settings['page_name'] ?? ''); ?>" required placeholder="e.g., home, about, courses">
                                                    <small class="text-muted">Unique identifier for the page (lowercase, no spaces)</small>
                                                </div>
                                                <div class="col-md-6">
                                                    <label>Robots Meta</label>
                                                    <select name="robots_meta" class="form-control">
                                                        <option value="index,follow" <?php echo (isset($edit_settings['robots_meta']) && $edit_settings['robots_meta'] == 'index,follow') ? 'selected' : ''; ?>>Index, Follow</option>
                                                        <option value="noindex,follow" <?php echo (isset($edit_settings['robots_meta']) && $edit_settings['robots_meta'] == 'noindex,follow') ? 'selected' : ''; ?>>No Index, Follow</option>
                                                        <option value="index,nofollow" <?php echo (isset($edit_settings['robots_meta']) && $edit_settings['robots_meta'] == 'index,nofollow') ? 'selected' : ''; ?>>Index, No Follow</option>
                                                        <option value="noindex,nofollow" <?php echo (isset($edit_settings['robots_meta']) && $edit_settings['robots_meta'] == 'noindex,nofollow') ? 'selected' : ''; ?>>No Index, No Follow</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="row mt-3">
                                                <div class="col-md-12">
                                                    <label>Meta Title</label>
                                                    <input type="text" name="meta_title" class="form-control" value="<?php echo htmlspecialchars($edit_settings['meta_title'] ?? ''); ?>" maxlength="255">
                                                    <small class="text-muted">Recommended: 50-60 characters</small>
                                                </div>
                                            </div>

                                            <div class="row mt-3">
                                                <div class="col-md-12">
                                                    <label>Meta Description</label>
                                                    <textarea name="meta_description" class="form-control" rows="3" maxlength="320"><?php echo htmlspecialchars($edit_settings['meta_description'] ?? ''); ?></textarea>
                                                    <small class="text-muted">Recommended: 150-160 characters</small>
                                                </div>
                                            </div>

                                            <div class="row mt-3">
                                                <div class="col-md-12">
                                                    <label>Meta Keywords</label>
                                                    <textarea name="meta_keywords" class="form-control" rows="2" placeholder="keyword1, keyword2, keyword3"><?php echo htmlspecialchars($edit_settings['meta_keywords'] ?? ''); ?></textarea>
                                                    <small class="text-muted">Comma-separated keywords</small>
                                                </div>
                                            </div>

                                            <div class="row mt-3">
                                                <div class="col-md-6">
                                                    <label>Open Graph Title</label>
                                                    <input type="text" name="og_title" class="form-control" value="<?php echo htmlspecialchars($edit_settings['og_title'] ?? ''); ?>">
                                                </div>
                                                <div class="col-md-6">
                                                    <label>Open Graph Image URL</label>
                                                    <input type="url" name="og_image" class="form-control" value="<?php echo htmlspecialchars($edit_settings['og_image'] ?? ''); ?>" placeholder="https://example.com/image.jpg">
                                                </div>
                                            </div>

                                            <div class="row mt-3">
                                                <div class="col-md-6">
                                                    <label>Open Graph Description</label>
                                                    <textarea name="og_description" class="form-control" rows="2"><?php echo htmlspecialchars($edit_settings['og_description'] ?? ''); ?></textarea>
                                                </div>
                                                <div class="col-md-6">
                                                    <label>Canonical URL</label>
                                                    <input type="url" name="canonical_url" class="form-control" value="<?php echo htmlspecialchars($edit_settings['canonical_url'] ?? ''); ?>" placeholder="https://example.com/page">
                                                </div>
                                            </div>

                                            <div class="row mt-3">
                                                <div class="col-md-12">
                                                    <button type="submit" name="update_seo" class="btn btn-success"><?php echo $edit_settings ? 'Update SEO Settings' : 'Add SEO Settings'; ?></button>
                                                    <?php if ($edit_settings): ?>
                                                        <a href="seo-management" class="btn btn-secondary ms-2">Cancel Edit</a>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </form>

                                        <div class="table-responsive">
                                            <table class="table table-bordered text-nowrap border-bottom" id="basic-datatable">
                                                <thead>
                                                    <tr>
                                                        <th class="wd-15p border-bottom-0">Page Name</th>
                                                        <th class="wd-30p border-bottom-0">Meta Title</th>
                                                        <th class="wd-35p border-bottom-0">Meta Description</th>
                                                        <th class="wd-10p border-bottom-0">Robots</th>
                                                        <th class="wd-10p border-bottom-0">Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    if (!empty($seo_settings)):
                                                        foreach ($seo_settings as $setting):
                                                    ?>
                                                            <tr>
                                                                <td><?php echo htmlspecialchars($setting['page_name']); ?></td>
                                                                <td><?php echo htmlspecialchars(substr($setting['meta_title'], 0, 50)) . (strlen($setting['meta_title']) > 50 ? '...' : ''); ?></td>
                                                                <td><?php echo htmlspecialchars(substr($setting['meta_description'], 0, 80)) . (strlen($setting['meta_description']) > 80 ? '...' : ''); ?></td>
                                                                <td><?php echo htmlspecialchars($setting['robots_meta']); ?></td>
                                                                <td>
                                                                    <a href="?edit=<?php echo urlencode($setting['page_name']); ?>" class="btn btn-warning btn-sm">Edit</a>
                                                                    <a href="?delete=<?php echo urlencode($setting['page_name']); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete SEO settings for <?php echo htmlspecialchars($setting['page_name']); ?>?')">Delete</a>
                                                                </td>
                                                            </tr>
                                                        <?php
                                                        endforeach;
                                                    else:
                                                        ?>
                                                        <tr>
                                                            <td colspan="5" class="text-center">No SEO settings found. Add your first page settings above.</td>
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
