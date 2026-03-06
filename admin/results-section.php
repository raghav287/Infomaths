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

function getCategoryName($section_name) {
    $map = [
        'mca_results' => 'MCA Results',
        'bank_po_result' => 'Bank PO Results',
        'campus_placement' => 'Campus Placement',
        'college_results' => 'College Results',
        'iit_jam_maths' => 'IIT JAM Maths Results'
    ];
    return $map[$section_name] ?? $section_name;
}

$message = '';
$messageType = '';

// Handle display order update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_order'])) {
    if (isset($_POST['display_order']) && is_array($_POST['display_order'])) {
        try {
            $pdo->beginTransaction();
            foreach ($_POST['display_order'] as $image_id => $display_order) {
                $stmt = $pdo->prepare("UPDATE section_images SET display_order = ? WHERE id = ?");
                $stmt->execute([(int)$display_order, (int)$image_id]);
            }
            $pdo->commit();
            $message = 'Display order updated successfully!';
            $messageType = 'success';
        } catch (PDOException $e) {
            $pdo->rollBack();
            $message = 'Database error: ' . $e->getMessage();
            $messageType = 'error';
        }
    }
}

// Handle image upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload_image'])) {
    $section_name = trim($_POST['section_name']);
    $alt_text = trim($_POST['alt_text']);
    $display_order = (int)$_POST['display_order'];

    $valid_sections = ['mca_results', 'bank_po_result', 'campus_placement', 'college_results', 'iit_jam_maths'];

    if (empty($section_name) || !in_array($section_name, $valid_sections)) {
        $message = 'Please select a valid section.';
        $messageType = 'error';
    } elseif (!isset($_FILES['image_file']) || $_FILES['image_file']['error'] !== UPLOAD_ERR_OK) {
        $message = 'Please select an image file to upload.';
        $messageType = 'error';
    } else {
        $upload_dir = '../assets/img/';
        $file_name = basename($_FILES['image_file']['name']);
        $file_path = $upload_dir . $file_name;
        $db_path = 'assets/img/' . $file_name;

        // Check if file already exists
        if (file_exists($file_path)) {
            $message = 'An image with this name already exists. Please rename the file or choose a different one.';
            $messageType = 'error';
        } elseif (move_uploaded_file($_FILES['image_file']['tmp_name'], $file_path)) {
            try {
                $stmt = $pdo->prepare("INSERT INTO section_images (section_name, image_path, alt_text, display_order, uploaded_at) VALUES (?, ?, ?, ?, NOW())");
                $stmt->execute([$section_name, $db_path, $alt_text, $display_order]);
                $message = 'Image uploaded successfully!';
                $messageType = 'success';
            } catch (PDOException $e) {
                $message = 'Database error: ' . $e->getMessage();
                $messageType = 'error';
                // Remove uploaded file if database insert failed
                unlink($file_path);
            }
        } else {
            $message = 'Failed to upload image file.';
            $messageType = 'error';
        }
    }
}

// Handle image deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_image'])) {
    $image_id = (int)$_POST['image_id'];

    try {
        // Get image path before deleting
        $stmt = $pdo->prepare("SELECT image_path FROM section_images WHERE id = ?");
        $stmt->execute([$image_id]);
        $image = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($image) {
            // Delete from database
            $stmt = $pdo->prepare("DELETE FROM section_images WHERE id = ?");
            $stmt->execute([$image_id]);

            // Delete physical file
            $file_path = '../' . $image['image_path'];
            if (file_exists($file_path)) {
                unlink($file_path);
            }

            $message = 'Image deleted successfully!';
            $messageType = 'success';
        } else {
            $message = 'Image not found.';
            $messageType = 'error';
        }
    } catch (PDOException $e) {
        $message = 'Database error: ' . $e->getMessage();
        $messageType = 'error';
    }
}

// Handle image editing
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_image'])) {
    $image_id = (int)$_POST['image_id'];
    $alt_text = trim($_POST['edit_alt_text']);
    $display_order = (int)$_POST['edit_display_order'];

    // Get current image path
    $current_image_path = $_POST['current_image_path'];

    // Handle new image upload
    if (isset($_FILES['edit_image_file']) && $_FILES['edit_image_file']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = '../assets/img/';
        $file_name = basename($_FILES['edit_image_file']['name']);
        $file_path = $upload_dir . $file_name;
        $db_path = 'assets/img/' . $file_name;

        // Check if file already exists
        if (file_exists($file_path)) {
            $message = 'An image with this name already exists. Please rename the file or choose a different one.';
            $messageType = 'error';
        } elseif (move_uploaded_file($_FILES['edit_image_file']['tmp_name'], $file_path)) {
            // Delete old image if it exists
            if (!empty($current_image_path) && file_exists('../' . $current_image_path)) {
                unlink('../' . $current_image_path);
            }
            $current_image_path = $db_path;
        } else {
            $message = 'Failed to upload new image file.';
            $messageType = 'error';
        }
    }

    if (!isset($message) || $messageType !== 'error') {
        try {
            $stmt = $pdo->prepare("UPDATE section_images SET image_path = ?, alt_text = ?, display_order = ? WHERE id = ?");
            $stmt->execute([$current_image_path, $alt_text, $display_order, $image_id]);
            $message = 'Image updated successfully!';
            $messageType = 'success';
        } catch (PDOException $e) {
            $message = 'Database error: ' . $e->getMessage();
            $messageType = 'error';
        }
    }
}

// Fetch results section images
try {
    $stmt = $pdo->prepare("SELECT * FROM section_images WHERE section_name IN ('mca_results', 'pu_results', 'campus_placement', 'college_results', 'iit_jam_maths') ORDER BY section_name, display_order");
    $stmt->execute();
    $section_images = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $section_images = [];
    $message = 'Database error loading images: ' . $e->getMessage();
    $messageType = 'error';
}
?>

<!doctype html>
<html lang="en" dir="ltr">

<head>
    <!-- META DATA -->
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description"
        content="Manage section images for different parts of the website on Info Maths Online admin panel.">
    <meta name="keywords" content="section images management, admin panel, image upload, display order">
    <!-- FAVICON -->
    <link rel="shortcut icon" type="image/x-icon" href="../assets/img/favicon.png">

    <!-- TITLE -->
    <title>Info Maths Online - Results Section Images</title>

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
                            <h1 class="page-title">Results Section Images</h1>
                            <div>
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Results Images</li>
                                </ol>
                            </div>
                        </div>
                        <!-- PAGE-HEADER END -->

                        <!-- Row -->
                        <div class="row row-sm">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Upload Results Section Image</h3>
                                    </div>
                                    <div class="card-body">

                                        <?php if ($message): ?>
                                        <div class="alert alert-<?php echo $messageType === 'success' ? 'success' : 'danger'; ?> alert-dismissible fade show"
                                            role="alert">
                                            <?php echo htmlspecialchars($message); ?>
                                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                        </div>
                                        <?php endif; ?>

                                        <form method="POST" enctype="multipart/form-data" class="mb-4">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <label for="section_name" class="form-label">Section *</label>
                                                    <select class="form-control" id="section_name" name="section_name"
                                                        required>
                                                        <option value="">Select Section</option>
                                                        <option value="mca_results">MCA Results</option>
                                                        <option value="bank_po_result">Bank PO Results</option>
                                                        <option value="campus_placement">Campus Placement</option>
                                                        <option value="college_results">College Results</option>
                                                        <option value="iit_jam_maths">IIT JAM Maths Results</option>
                                                    </select>
                                                </div>

                                                <div class="col-md-3">
                                                    <label for="image_file" class="form-label">Image File *</label>
                                                    <input type="file" class="form-control" id="image_file"
                                                        name="image_file" accept="image/*" required>
                                                    <div class="form-text">Supported formats: JPG, PNG, GIF, WebP</div>
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="alt_text" class="form-label">Alt Text</label>
                                                    <input type="text" class="form-control" id="alt_text"
                                                        name="alt_text" placeholder="Describe the image">
                                                </div>
                                                <div class="col-md-2">
                                                    <label for="display_order" class="form-label">Display Order</label>
                                                    <input type="number" class="form-control" id="display_order"
                                                        name="display_order" value="1" min="1">
                                                </div>

                                            </div>
                                            <div class="col-md-2 d-flex align-items-end">
                                                <button type="submit" name="upload_image" class="btn btn-success w-100">
                                                    <i class="fa fa-upload"></i> Upload
                                                </button>
                                            </div>
                                        </form>

                                        <?php if (!empty($section_images)): ?>
                                        <div class="section-header mb-3">
                                            <h4>Results Section Images</h4>
                                        </div>

                                        <form method="POST" class="mb-4">
                                            <div class="table-responsive">
                                                <table class="table table-bordered text-nowrap border-bottom"
                                                    id="basic-datatable">
                                                    <thead>
                                                        <tr>
                                                            <th class="wd-5p border-bottom-0">ID</th>
                                                            <th class="wd-10p border-bottom-0">Category</th>
                                                            <th class="wd-15p border-bottom-0">Image</th>
                                                            <th class="wd-20p border-bottom-0">Alt Text</th>
                                                            <th class="wd-10p border-bottom-0">Display Order</th>
                                                            <th class="wd-15p border-bottom-0">Uploaded At</th>
                                                            <th class="wd-10p border-bottom-0">Actions</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php foreach ($section_images as $image): ?>
                                                        <tr>
                                                            <td><?php echo htmlspecialchars($image['id']); ?></td>
                                                            <td><?php echo htmlspecialchars(getCategoryName($image['section_name'])); ?>
                                                            </td>
                                                            <td>
                                                                <img src="../<?php echo htmlspecialchars($image['image_path']); ?>"
                                                                    alt="<?php echo htmlspecialchars($image['alt_text']); ?>"
                                                                    style="width: 80px; height: 60px; object-fit: cover;">
                                                            </td>
                                                            <td><?php echo htmlspecialchars($image['alt_text'] ?: 'No alt text'); ?>
                                                            </td>
                                                            <td>
                                                                <input type="number"
                                                                    class="form-control form-control-sm"
                                                                    name="display_order[<?php echo $image['id']; ?>]"
                                                                    value="<?php echo $image['display_order']; ?>"
                                                                    min="1" style="width: 70px;">
                                                            </td>
                                                            <td><?php echo date('j M Y', strtotime($image['uploaded_at'])); ?>
                                                            </td>
                                                            <td>
                                                                <button type="button"
                                                                    class="btn btn-warning btn-sm me-1"
                                                                    onclick="editImage(<?php echo $image['id']; ?>, '<?php echo htmlspecialchars($image['alt_text']); ?>', <?php echo $image['display_order']; ?>, '<?php echo htmlspecialchars($image['image_path']); ?>')">
                                                                    <i class="fa fa-edit"></i> Edit
                                                                </button>
                                                                <form method="POST" style="display: inline;">
                                                                    <input type="hidden" name="image_id"
                                                                        value="<?php echo $image['id']; ?>">
                                                                    <button type="submit" name="delete_image"
                                                                        class="btn btn-danger btn-sm"
                                                                        onclick="return confirm('Are you sure you want to delete this image?')">
                                                                        <i class="fa fa-trash"></i> Delete
                                                                    </button>
                                                                </form>
                                                            </td>
                                                        </tr>
                                                        <?php endforeach; ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="text-end mt-3">
                                                <button type="submit" name="update_order" class="btn btn-primary">
                                                    <i class="fa fa-save"></i> Update Display Order
                                                </button>
                                            </div>
                                        </form>
                                        <?php else: ?>
                                        <div class="alert alert-info">
                                            <i class="fa fa-info-circle"></i> No Results section images found. Upload
                                            some images to get started.
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Edit Image Modal -->
                        <div class="modal fade" id="editImageModal" tabindex="-1" aria-labelledby="editImageModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editImageModalLabel">Edit Image Details</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <form method="POST" enctype="multipart/form-data">
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label for="edit_image_file" class="form-label">New Image File
                                                    (Optional)</label>
                                                <input type="file" class="form-control" id="edit_image_file"
                                                    name="edit_image_file" accept="image/*">
                                                <div class="form-text">Leave empty to keep current image. Supported
                                                    formats: JPG, PNG, GIF, WebP</div>
                                                <div id="current_image_preview" class="mt-2"></div>
                                            </div>
                                            <div class="mb-3">
                                                <label for="edit_alt_text" class="form-label">Alt Text</label>
                                                <input type="text" class="form-control" id="edit_alt_text"
                                                    name="edit_alt_text" placeholder="Describe the image">
                                            </div>
                                            <div class="mb-3">
                                                <label for="edit_display_order" class="form-label">Display Order</label>
                                                <input type="number" class="form-control" id="edit_display_order"
                                                    name="edit_display_order" min="1">
                                            </div>
                                            <input type="hidden" name="image_id" id="edit_image_id">
                                            <input type="hidden" name="current_image_path" id="current_image_path">
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" name="edit_image" class="btn btn-primary">Save
                                                Changes</button>
                                        </div>
                                    </form>
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

    <script>
    function editImage(id, altText, displayOrder, imagePath) {
        document.getElementById('edit_image_id').value = id;
        document.getElementById('edit_alt_text').value = altText;
        document.getElementById('edit_display_order').value = displayOrder;
        document.getElementById('current_image_path').value = imagePath;

        // Show current image preview
        const previewDiv = document.getElementById('current_image_preview');
        if (imagePath) {
            previewDiv.innerHTML = `
                    <small class="text-muted">Current Image:</small><br>
                    <img src="../${imagePath}" alt="Current image" style="max-width: 200px; max-height: 150px; object-fit: cover; border: 1px solid #ddd; padding: 5px; margin-top: 5px;">
                `;
        } else {
            previewDiv.innerHTML = '<small class="text-muted">No current image</small>';
        }

        // Show the modal
        var modal = new bootstrap.Modal(document.getElementById('editImageModal'));
        modal.show();
    }
    </script>

</body>

</html>