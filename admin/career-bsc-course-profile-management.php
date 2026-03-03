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

$profiles = []; // Initialize an empty array for the results.
$error_message = ''; // Initialize an empty error message.
$success_message = ''; // For success messages

// Directory for PDF uploads (Shared with Bank PO or can be separate)
$uploadDir = '../assets/uploads/syllabus/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

// Helper to handle file upload
function handlePdfUpload($file, $uploadDir) {
    if (isset($file) && $file['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $file['tmp_name'];
        $fileName = $file['name'];
        $fileSize = $file['size'];
        $fileType = $file['type'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        $allowedfileExtensions = array('pdf');
        if (in_array($fileExtension, $allowedfileExtensions)) {
            // Generate unique name
            $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
            $dest_path = $uploadDir . $newFileName;

            if(move_uploaded_file($fileTmpPath, $dest_path)) {
                return 'assets/uploads/syllabus/' . $newFileName; // Return relative path for DB
            }
        }
    }
    return false;
}

// Handle POST requests for adding syllabus
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_profile'])) {
    $title = trim($_POST['title']);
    $subtitle = trim($_POST['subtitle']);
    $link_type = $_POST['link_type'];
    
    $link = '#';
    $slug = '';
    $description = '';
    $meta_title = '';
    $meta_keyword = '';
    $meta_description = '';

    if ($link_type === 'external_url') {
        $link = trim($_POST['external_link']);
    } elseif ($link_type === 'pdf') {
        $uploadedPath = handlePdfUpload($_FILES['pdf_file'], $uploadDir);
        if ($uploadedPath) {
            $link = $uploadedPath;
        } else {
             $error_message = "Error uploading PDF. Please ensure it is a valid PDF file.";
        }
    } else {
        // CMS Page
        $description = $_POST['description']; // Allow HTML
        $slug = trim($_POST['slug']);
        if (empty($slug)) {
            $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));
        }
        $meta_title = trim($_POST['meta_title']);
        $meta_keyword = trim($_POST['meta_keyword']);
        $meta_description = trim($_POST['meta_description']);
        $link = '#'; // CMS pages use slug logic in frontend, store # as link
    }

    $display_order = (int)$_POST['display_order'];
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    
    if (empty($error_message)) {
        if (empty($title) || empty($subtitle)) {
            $error_message = "Title and Subtitle are required.";
        } else {
            try {
                $stmt = $pdo->prepare("INSERT INTO career_bca_course-profiles (title, subtitle, link, description, slug, meta_title, meta_keyword, meta_description, display_order, is_active) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$title, $subtitle, $link, $description, $slug, $meta_title, $meta_keyword, $meta_description, $display_order, $is_active]);
                $success_message = "Syllabus added successfully.";
            } catch (PDOException $e) {
                $error_message = "Error saving to database: " . $e->getMessage();
            }
        }
    }
}

// Handle POST requests for editing syllabus
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_profile'])) {
    $id = (int)$_POST['edit_profile_id'];
    $title = trim($_POST['edit_title']);
    $subtitle = trim($_POST['edit_subtitle']);
    $link_type = $_POST['edit_link_type'];
    
    // Default values if fields are hidden
    $link = trim($_POST['edit_link']); // Keep existing by default
    $slug = trim($_POST['edit_slug']);
    $description = $_POST['edit_description'];
    
    $meta_title = trim($_POST['edit_meta_title']);
    $meta_keyword = trim($_POST['edit_meta_keyword']);
    $meta_description = trim($_POST['edit_meta_description']);

    if ($link_type === 'external_url') {
        $link = trim($_POST['edit_external_link']);
        // Clear CMS fields
        $slug = '';
        $description = '';
        $meta_title = '';
        $meta_keyword = '';
        $meta_description = '';
    } elseif ($link_type === 'pdf') {
        if (isset($_FILES['edit_pdf_file']) && $_FILES['edit_pdf_file']['size'] > 0) {
            $uploadedPath = handlePdfUpload($_FILES['edit_pdf_file'], $uploadDir);
            if ($uploadedPath) {
                $link = $uploadedPath;
            } else {
                 $error_message = "Error uploading PDF.";
            }
        }
    } elseif ($link_type === 'cms_page') {
        $link = '#';
        if (empty($slug)) {
            $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));
        }
    }
    
    $display_order = (int)$_POST['edit_display_order'];
    $is_active = isset($_POST['edit_is_active']) ? 1 : 0;
    
    if (empty($error_message)) {
        if (empty($title) || empty($subtitle)) {
            $error_message = "Title and Subtitle are required.";
        } else {
            try {
                $stmt = $pdo->prepare("UPDATE career_bca_course-profiles SET title = ?, subtitle = ?, link = ?, description = ?, slug = ?, meta_title = ?, meta_keyword = ?, meta_description = ?, display_order = ?, is_active = ? WHERE id = ?");
                $stmt->execute([$title, $subtitle, $link, $description, $slug, $meta_title, $meta_keyword, $meta_description, $display_order, $is_active, $id]);
                $success_message = "Syllabus updated successfully.";
            } catch (PDOException $e) {
                $error_message = "Error updating database: " . $e->getMessage();
            }
        }
    }
}

// Handle POST requests for deleting syllabus
if (isset($_GET['delete_id'])) {
    $id = (int)$_GET['delete_id'];
    try {
        $stmt = $pdo->prepare("DELETE FROM career_bca_course-profiles WHERE id = ?");
        $stmt->execute([$id]);
        $success_message = "Syllabus deleted successfully.";
    } catch (PDOException $e) {
        $error_message = "Error deleting syllabus: " . $e->getMessage();
    }
}

// Fetch all profiles
try {
    $stmt = $pdo->query("SELECT * FROM career_bca_course-profiles ORDER BY display_order ASC");
    $profiles = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error_message = "Error fetching profiles: " . $e->getMessage();
}
?>

<!doctype html>
<html lang="en" dir="ltr">

<head>
    <!-- META DATA -->
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="Info Maths Online admin dashboard - Manage Career BSC Syllabus.">
    
    <!-- FAVICON -->
    <link rel="shortcut icon" type="image/x-icon" href="../assets/img/favicon.png">

    <!-- TITLE -->
    <title>Info Maths Online - Career BSC Syllabus Management</title>

    <!-- BOOTSTRAP CSS -->
    <link id="style" href="assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- STYLE CSS -->
    <link href="assets/css/style.css" rel="stylesheet">

    <!-- Plugins CSS -->
    <link href="assets/css/plugins.css" rel="stylesheet">

    <!-- FONT-ICONS CSS -->
    <link href="assets/css/icons.css" rel="stylesheet">
    <link href="../assets/css/fontawesome.min.css" rel="stylesheet">
    
    <!-- SUMMERNOTE CSS -->
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
    <!-- INTERNAL Switcher css -->
    <link href="assets/switcher/css/switcher.css" rel="stylesheet">
    <link href="assets/switcher/demo.css" rel="stylesheet">
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
                            <h1 class="page-title">Career BSC Syllabus Management</h1>
                            <div>
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="panel">Home</a></li>
                                    <li class="breadcrumb-item"><a href="javascript:void(0);">Career BSC Page</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Syllabus</li>
                                </ol>
                            </div>
                        </div>

                        <!-- Row -->
                        <div class="row row-sm">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Manage Career BSC Syllabus</h3>
                                        <div class="card-options">
                                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProfileModal">
                                                <i class="fe fe-plus"></i> Add New Syllabus
                                            </button>
                                        </div>
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

                                        <div class="table-responsive">
                                            <table class="table table-bordered text-nowrap border-bottom" id="basic-datatable">
                                                <thead>
                                                    <tr>
                                                        <th class="wd-5p border-bottom-0">Order</th>
                                                        <th class="wd-15p border-bottom-0">Title</th>
                                                        <th class="wd-25p border-bottom-0">Subtitle</th>
                                                        <th class="wd-25p border-bottom-0">Link</th>
                                                        <th class="wd-10p border-bottom-0">Status</th>
                                                        <th class="wd-15p border-bottom-0">Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php if (!empty($profiles)): ?>
                                                        <?php foreach ($profiles as $profile): ?>
                                                            <tr>
                                                                <td><?php echo $profile['display_order']; ?></td>
                                                                <td><?php echo htmlspecialchars($profile['title']); ?></td>
                                                                <td><?php echo htmlspecialchars($profile['subtitle']); ?></td>
                                                                <td>
                                                                    <?php if(strpos($profile['link'], '.pdf') !== false): ?>
                                                                        <span class="badge bg-info"><i class="fa fa-file-pdf"></i> PDF</span>
                                                                    <?php elseif($profile['link'] == '#' && !empty($profile['slug'])): ?>
                                                                        <span class="badge bg-primary">CMS Page</span>
                                                                    <?php else: ?>
                                                                        <a href="<?php echo htmlspecialchars($profile['link']); ?>" target="_blank">External Link <i class="fa fa-external-link"></i></a>
                                                                    <?php endif; ?>
                                                                </td>
                                                                <td>
                                                                    <?php if ($profile['is_active']): ?>
                                                                        <span class="badge bg-success">Active</span>
                                                                    <?php else: ?>
                                                                        <span class="badge bg-danger">Inactive</span>
                                                                    <?php endif; ?>
                                                                </td>
                                                                <td>
                                                                    <div class="btn-group" role="group">
                                                                        <button type="button" class="btn btn-warning btn-sm edit-profile-btn" 
                                                                            data-id="<?php echo $profile['id']; ?>"
                                                                            data-title="<?php echo htmlspecialchars($profile['title']); ?>"
                                                                            data-subtitle="<?php echo htmlspecialchars($profile['subtitle']); ?>"
                                                                            data-link="<?php echo htmlspecialchars($profile['link']); ?>"
                                                                            data-slug="<?php echo htmlspecialchars($profile['slug']); ?>"
                                                                            data-meta-title="<?php echo htmlspecialchars($profile['meta_title']); ?>"
                                                                            data-meta-keyword="<?php echo htmlspecialchars($profile['meta_keyword']); ?>"
                                                                            data-meta-description="<?php echo htmlspecialchars($profile['meta_description']); ?>"
                                                                            data-order="<?php echo $profile['display_order']; ?>"
                                                                            data-active="<?php echo $profile['is_active']; ?>"
                                                                            data-bs-toggle="modal" data-bs-target="#editProfileModal">
                                                                            <i class="fe fe-edit"></i> Edit
                                                                        </button>
                                                                        <!-- Hidden textarea for description to retrieve via JS -->
                                                                        <textarea id="desc_<?php echo $profile['id']; ?>" style="display:none;"><?php echo htmlspecialchars($profile['description']); ?></textarea>
                                                                        <a href="?delete_id=<?php echo $profile['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this syllabus?')">
                                                                            <i class="fe fe-trash"></i> Delete
                                                                        </a>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                    <?php else: ?>
                                                        <tr>
                                                            <td colspan="6" class="text-center">No syllabus found for Career BSC.</td>
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

        <!-- Add Profile Modal -->
        <div class="modal fade" id="addProfileModal" tabindex="-1" aria-labelledby="addProfileModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form method="POST" action="" enctype="multipart/form-data">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addProfileModalLabel">Add Syllabus</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="title" class="form-label">Title</label>
                                    <input type="text" class="form-control" id="title" name="title" required placeholder="e.g. Real Analysis">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="subtitle" class="form-label">Subtitle</label>
                                    <input type="text" class="form-control" id="subtitle" name="subtitle" required placeholder="e.g. Comprehensive Module">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label d-block">Link Type</label>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="link_type" id="link_type_cms" value="cms_page" checked>
                                    <label class="form-check-label" for="link_type_cms">CMS Page (Create New Page)</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="link_type" id="link_type_external" value="external_url">
                                    <label class="form-check-label" for="link_type_external">External/Internal URL</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="link_type" id="link_type_pdf" value="pdf">
                                    <label class="form-check-label" for="link_type_pdf">Upload PDF</label>
                                </div>
                            </div>

                            <!-- EXTERNAL LINK CONTAINER -->
                            <div id="container_link_external" class="mb-3" style="display:none;">
                                <label for="external_link" class="form-label">External/Internal URL</label>
                                <input type="text" class="form-control" id="external_link" name="external_link" placeholder="https://example.com or course-details.php">
                            </div>

                             <!-- PDF CONTAINER -->
                             <div id="container_pdf" class="mb-3" style="display:none;">
                                <label for="pdf_file" class="form-label">Upload PDF Document</label>
                                <input type="file" class="form-control" id="pdf_file" name="pdf_file" accept=".pdf">
                                <small class="text-muted">Will open in new tab (target="_blank")</small>
                            </div>

                            <!-- SEO SLUG CONTAINER -->
                            <div id="container_slug" class="mb-3">
                                <label for="slug" class="form-label">SEO Slug (URL Friendly)</label>
                                <input type="text" class="form-control" id="slug" name="slug" placeholder="e.g. real-analysis-course">
                            </div>

                            <!-- DESCRIPTION CONTAINER -->
                            <div id="container_description" class="mb-3">
                                <label for="description" class="form-label">Syllabus Description (Inside Page)</label>
                                <textarea class="form-control summernote" id="description" name="description"></textarea>
                            </div>
                            
                            <div id="container_seo">
                                <hr>
                                <h6>SEO Meta Tags</h6>
                                <div class="mb-3">
                                    <label class="form-label">Meta Title</label>
                                    <input type="text" class="form-control" name="meta_title">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Meta Keywords</label>
                                    <input type="text" class="form-control" name="meta_keyword">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Meta Description</label>
                                    <textarea class="form-control" name="meta_description" rows="2"></textarea>
                                </div>
                                <hr>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="display_order" class="form-label">Display Order</label>
                                    <input type="number" class="form-control" id="display_order" name="display_order" value="0">
                                </div>
                                <div class="col-md-6 mb-3 pt-4">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="is_active" name="is_active" checked>
                                        <label class="form-check-label" for="is_active">Active</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" name="add_profile" class="btn btn-primary">Save Syllabus</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Edit Profile Modal -->
        <div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form method="POST" action="" enctype="multipart/form-data">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editProfileModalLabel">Edit Syllabus</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" id="edit_profile_id" name="edit_profile_id">
                            <!-- Helper to store existing link -->
                            <input type="hidden" id="edit_link" name="edit_link"> 

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="edit_title" class="form-label">Title</label>
                                    <input type="text" class="form-control" id="edit_title" name="edit_title" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="edit_subtitle" class="form-label">Subtitle</label>
                                    <input type="text" class="form-control" id="edit_subtitle" name="edit_subtitle" required>
                                </div>
                            </div>

                             <div class="mb-3">
                                <label class="form-label d-block">Link Type</label>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="edit_link_type" id="edit_link_type_cms" value="cms_page">
                                    <label class="form-check-label" for="edit_link_type_cms">CMS Page</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="edit_link_type" id="edit_link_type_external" value="external_url">
                                    <label class="form-check-label" for="edit_link_type_external">External/Internal URL</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="edit_link_type" id="edit_link_type_pdf" value="pdf">
                                    <label class="form-check-label" for="edit_link_type_pdf">Upload PDF</label>
                                </div>
                            </div>

                             <!-- EXTERNAL LINK CONTAINER -->
                            <div id="edit_container_link_external" class="mb-3" style="display:none;">
                                <label for="edit_external_link" class="form-label">External/Internal URL</label>
                                <input type="text" class="form-control" id="edit_external_link" name="edit_external_link">
                            </div>

                             <!-- PDF CONTAINER -->
                             <div id="edit_container_pdf" class="mb-3" style="display:none;">
                                <label for="edit_pdf_file" class="form-label">Upload PDF Document</label>
                                <div id="edit_current_pdf_container" class="mb-2" style="display:none;">
                                    Current PDF: <a href="#" id="edit_current_pdf_link" target="_blank" class="text-primary">View</a>
                                </div>
                                <input type="file" class="form-control" id="edit_pdf_file" name="edit_pdf_file" accept=".pdf">
                            </div>

                             <!-- SEO SLUG CONTAINER -->
                            <div id="edit_container_slug" class="mb-3">
                                <label for="edit_slug" class="form-label">SEO Slug</label>
                                <input type="text" class="form-control" id="edit_slug" name="edit_slug">
                            </div>

                            <!-- DESCRIPTION CONTAINER -->
                            <div id="edit_container_description" class="mb-3">
                                <label for="edit_description" class="form-label">Syllabus Description</label>
                                <textarea class="form-control summernote" id="edit_description" name="edit_description"></textarea>
                            </div>

                            <div id="edit_container_seo">
                                <hr>
                                <h6>SEO Meta Tags</h6>
                                <div class="mb-3">
                                    <label class="form-label">Meta Title</label>
                                    <input type="text" class="form-control" id="edit_meta_title" name="edit_meta_title">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Meta Keywords</label>
                                    <input type="text" class="form-control" id="edit_meta_keyword" name="edit_meta_keyword">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Meta Description</label>
                                    <textarea class="form-control" id="edit_meta_description" name="edit_meta_description" rows="2"></textarea>
                                </div>
                                <hr>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="edit_display_order" class="form-label">Display Order</label>
                                    <input type="number" class="form-control" id="edit_display_order" name="edit_display_order">
                                </div>
                                <div class="col-md-6 mb-3 pt-4">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="edit_is_active" name="edit_is_active">
                                        <label class="form-check-label" for="edit_is_active">Active</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" name="edit_profile" class="btn btn-primary">Update Syllabus</button>
                        </div>
                    </form>
                </div>
            </div>
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

    <!-- SUMMERNOTE JS -->
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>

    <!-- CUSTOM SUMMERNOTE BUTTONS -->
    <script src="assets/js/summernote-custom-buttons.js"></script>

    <script>
        $(document).ready(function() {
            // Initialize Summernote
            initializeCustomSummernote('.summernote');

            // --- ADD MODAL LOGIC ---
            $('input[name="link_type"]').on('change', function() {
                var type = $(this).val();
                handleLinkTypeChange(type, 'add');
            });
            handleLinkTypeChange('cms_page', 'add'); // Default

            // --- EDIT MODAL LOGIC ---
            $('input[name="edit_link_type"]').on('change', function() {
                var type = $(this).val();
                handleLinkTypeChange(type, 'edit');
            });

            $('.edit-profile-btn').on('click', function() {
                var id = $(this).data('id');
                var title = $(this).data('title');
                var subtitle = $(this).data('subtitle');
                var link = $(this).data('link');
                var slug = $(this).data('slug');
                var mTitle = $(this).data('meta-title');
                var mKey = $(this).data('meta-keyword');
                var mDesc = $(this).data('meta-description');
                
                var order = $(this).data('order');
                var active = $(this).data('active');
                
                var linkType = 'cms_page'; // Default
                var linkValue = '';

                if ((link === '#' || link === '') && slug) {
                    linkType = 'cms_page';
                } else if (link.toLowerCase().endsWith('.pdf')) {
                    linkType = 'pdf';
                    $('#edit_current_pdf_link').attr('href', link).text(link);
                    $('#edit_current_pdf_container').show();
                } else {
                    linkType = 'external_url';
                    linkValue = link;
                }

                var description = $('#desc_' + id).val();

                $('#edit_profile_id').val(id);
                $('#edit_title').val(title);
                $('#edit_subtitle').val(subtitle);
                
                $('input[name="edit_link_type"][value="' + linkType + '"]').prop('checked', true);
                
                if (linkType === 'external_url') {
                     $('#edit_external_link').val(linkValue);
                }
                
                $('#edit_slug').val(slug);
                $('#edit_meta_title').val(mTitle);
                $('#edit_meta_keyword').val(mKey);
                $('#edit_meta_description').val(mDesc);
                
                $('#edit_display_order').val(order);
                $('#edit_is_active').prop('checked', active == 1);
                
                $('#edit_description').summernote('code', description);

                handleLinkTypeChange(linkType, 'edit');
            });

            function handleLinkTypeChange(type, mode) {
                var prefix = mode === 'edit' ? '#edit_' : '#';
                
                $(prefix + 'container_link_external').hide();
                $(prefix + 'container_slug').hide();
                $(prefix + 'container_description').hide();
                $(prefix + 'container_pdf').hide();
                $(prefix + 'container_seo').hide();

                if (type === 'cms_page') {
                    $(prefix + 'container_slug').show();
                    $(prefix + 'container_description').show();
                    $(prefix + 'container_seo').show();
                    if(mode === 'add') $(prefix + 'external_link').val('#'); 
                } 
                else if (type === 'external_url') {
                    $(prefix + 'container_link_external').show();
                } 
                else if (type === 'pdf') {
                    $(prefix + 'container_pdf').show();
                }
            }
        });
    </script>
</body>
</html>
