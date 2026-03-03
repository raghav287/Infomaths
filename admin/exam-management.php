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

$exams = [];
$error_message = '';
$success_message = '';

// Handle Image Upload
function uploadImage($file) {
    $target_dir = "../assets/img/others/"; // Folder for exam icons
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    
    $file_extension = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
    $new_filename = uniqid('exam_') . '.' . $file_extension;
    $target_file = $target_dir . $new_filename;
    
    // Check if image file is actual image
    $check = getimagesize($file["tmp_name"]);
    if($check === false) {
        return ['success' => false, 'message' => "File is not an image."];
    }
    
    // Check file size (5MB limit)
    if ($file["size"] > 5000000) {
        return ['success' => false, 'message' => "Sorry, your file is too large."];
    }
    
    // Allow certain file formats
    if(!in_array($file_extension, ['jpg', 'png', 'jpeg', 'gif', 'webp', 'svg'])) {
        return ['success' => false, 'message' => "Sorry, only JPG, JPEG, PNG, GIF, WEBP & SVG files are allowed."];
    }
    
    if (move_uploaded_file($file["tmp_name"], $target_file)) {
        // Return relative path from root
        return ['success' => true, 'filename' => 'assets/img/others/' . $new_filename];
    } else {
        return ['success' => false, 'message' => "Sorry, there was an error uploading your file."];
    }
}

// Handle POST requests for adding exam
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_exam'])) {
    $exam_name = trim($_POST['exam_name']);
    $short_description = trim($_POST['short_description']);
    $full_description = $_POST['full_description'];
    $slug = trim($_POST['slug']);
    if (empty($slug)) {
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $exam_name)));
    }
    $meta_title = trim($_POST['meta_title']);
    $meta_keyword = trim($_POST['meta_keyword']);
    $meta_description = trim($_POST['meta_description']);
    $display_order = (int)$_POST['display_order'];
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    
    $icon_image = '';
    
    if (!empty($_FILES['icon_image']['name'])) {
        $upload_result = uploadImage($_FILES['icon_image']);
        if ($upload_result['success']) {
            $icon_image = $upload_result['filename'];
        } else {
            $error_message = $upload_result['message'];
        }
    }

    if (empty($error_message)) {
        if (empty($exam_name)) {
            $error_message = "Exam Name is required.";
        } else {
            try {
                $stmt = $pdo->prepare("INSERT INTO entrance_exams (exam_name, slug, icon_image, short_description, full_description, meta_title, meta_keyword, meta_description, display_order, is_active) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$exam_name, $slug, $icon_image, $short_description, $full_description, $meta_title, $meta_keyword, $meta_description, $display_order, $is_active]);
                $success_message = "Entrance Exam added successfully.";
            } catch (PDOException $e) {
                if ($e->getCode() == 23000) {
                    $error_message = "Error: Slug already exists. Please choose a unique slug.";
                } else {
                    $error_message = "Error saving to database: " . $e->getMessage();
                }
            }
        }
    }
}

// Handle POST requests for editing exam
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_exam'])) {
    $id = (int)$_POST['edit_exam_id'];
    $exam_name = trim($_POST['edit_exam_name']);
    $short_description = trim($_POST['edit_short_description']);
    $full_description = $_POST['edit_full_description'];
    $slug = trim($_POST['edit_slug']);
    if (empty($slug)) {
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $exam_name)));
    }
    $meta_title = trim($_POST['edit_meta_title']);
    $meta_keyword = trim($_POST['edit_meta_keyword']);
    $meta_description = trim($_POST['edit_meta_description']);
    $display_order = (int)$_POST['edit_display_order'];
    $is_active = isset($_POST['edit_is_active']) ? 1 : 0;
    
    // Image handling
    if (!empty($_FILES['edit_icon_image']['name'])) {
        $upload_result = uploadImage($_FILES['edit_icon_image']);
        if ($upload_result['success']) {
            $new_image = $upload_result['filename'];
            try {
                $stmt = $pdo->prepare("UPDATE entrance_exams SET exam_name = ?, slug = ?, icon_image = ?, short_description = ?, full_description = ?, meta_title = ?, meta_keyword = ?, meta_description = ?, display_order = ?, is_active = ? WHERE id = ?");
                $stmt->execute([$exam_name, $slug, $new_image, $short_description, $full_description, $meta_title, $meta_keyword, $meta_description, $display_order, $is_active, $id]);
                $success_message = "Exam updated successfully (Image Updated).";
            } catch (PDOException $e) {
                $error_message = "Error updating database: " . $e->getMessage();
            }
        } else {
            $error_message = $upload_result['message'];
        }
    } else {
        try {
            $stmt = $pdo->prepare("UPDATE entrance_exams SET exam_name = ?, slug = ?, short_description = ?, full_description = ?, meta_title = ?, meta_keyword = ?, meta_description = ?, display_order = ?, is_active = ? WHERE id = ?");
            $stmt->execute([$exam_name, $slug, $short_description, $full_description, $meta_title, $meta_keyword, $meta_description, $display_order, $is_active, $id]);
            $success_message = "Exam updated successfully.";
        } catch (PDOException $e) {
            $error_message = "Error updating database: " . $e->getMessage();
        }
    }
}

// Handle POST requests for deleting exam
if (isset($_GET['delete_id'])) {
    $id = (int)$_GET['delete_id'];
    try {
        $stmt = $pdo->prepare("DELETE FROM entrance_exams WHERE id = ?");
        $stmt->execute([$id]);
        $success_message = "Exam deleted successfully.";
    } catch (PDOException $e) {
        $error_message = "Error deleting exam: " . $e->getMessage();
    }
}

// Fetch all exams
try {
    $stmt = $pdo->query("SELECT * FROM entrance_exams ORDER BY display_order ASC");
    $exams = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error_message = "Error fetching exams: " . $e->getMessage();
}
?>

<!doctype html>
<html lang="en" dir="ltr">

<head>
    <!-- META DATA -->
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="Info Maths Online admin dashboard - Manage Entrance Exams.">
    
    <!-- FAVICON -->
    <link rel="shortcut icon" type="image/x-icon" href="../assets/img/favicon.png">

    <!-- TITLE -->
    <title>Info Maths Online - Entrance Exam Management</title>

    <!-- BOOTSTRAP CSS -->
    <link id="style" href="assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- STYLE CSS -->
    <link href="assets/css/style.css" rel="stylesheet">

    <!-- Plugins CSS -->
    <link href="assets/css/plugins.css" rel="stylesheet">

    <!-- FONT-ICONS CSS -->
    <link href="assets/css/icons.css" rel="stylesheet">
    
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
                            <h1 class="page-title">Entrance Exam Management</h1>
                            <div>
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="panel">Home</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Entrance Exams</li>
                                </ol>
                            </div>
                        </div>

                        <!-- Row -->
                        <div class="row row-sm">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Manage Entrance Exams</h3>
                                        <div class="card-options">
                                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addExamModal">
                                                <i class="fe fe-plus"></i> Add New Exam
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
                                                        <th class="wd-10p border-bottom-0">Icon</th>
                                                        <th class="wd-20p border-bottom-0">Exam Name</th>
                                                        <th class="wd-20p border-bottom-0">Slug</th>
                                                        <th class="wd-10p border-bottom-0">Status</th>
                                                        <th class="wd-15p border-bottom-0">Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php if (!empty($exams)): ?>
                                                        <?php foreach ($exams as $exam): ?>
                                                            <tr>
                                                                <td><?php echo $exam['display_order']; ?></td>
                                                                <td>
                                                                    <?php if($exam['icon_image']): ?>
                                                                        <img src="../<?php echo htmlspecialchars($exam['icon_image']); ?>" style="width: 40px; height: 40px; object-fit: contain;">
                                                                    <?php else: ?>
                                                                        <span class="text-muted">No Icon</span>
                                                                    <?php endif; ?>
                                                                </td>
                                                                <td><?php echo htmlspecialchars($exam['exam_name']); ?></td>
                                                                <td><?php echo htmlspecialchars($exam['slug']); ?></td>
                                                                <td>
                                                                    <?php if ($exam['is_active']): ?>
                                                                        <span class="badge bg-success">Active</span>
                                                                    <?php else: ?>
                                                                        <span class="badge bg-danger">Inactive</span>
                                                                    <?php endif; ?>
                                                                </td>
                                                                <td>
                                                                    <div class="btn-group" role="group">
                                                                        <button type="button" class="btn btn-warning btn-sm edit-exam-btn" 
                                                                            data-id="<?php echo $exam['id']; ?>"
                                                                            data-exam-name="<?php echo htmlspecialchars($exam['exam_name']); ?>"
                                                                            data-slug="<?php echo htmlspecialchars($exam['slug']); ?>"
                                                                            data-short-description="<?php echo htmlspecialchars($exam['short_description']); ?>"
                                                                            data-meta-title="<?php echo htmlspecialchars($exam['meta_title']); ?>"
                                                                            data-meta-keyword="<?php echo htmlspecialchars($exam['meta_keyword']); ?>"
                                                                            data-meta-description="<?php echo htmlspecialchars($exam['meta_description']); ?>"
                                                                            data-order="<?php echo $exam['display_order']; ?>"
                                                                            data-active="<?php echo $exam['is_active']; ?>"
                                                                            data-bs-toggle="modal" data-bs-target="#editExamModal">
                                                                            <i class="fe fe-edit"></i> Edit
                                                                        </button>
                                                                        <!-- Hidden textarea for full description -->
                                                                        <textarea id="desc_<?php echo $exam['id']; ?>" style="display:none;"><?php echo htmlspecialchars($exam['full_description']); ?></textarea>
                                                                        <a href="?delete_id=<?php echo $exam['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this exam?')">
                                                                            <i class="fe fe-trash"></i> Delete
                                                                        </a>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                    <?php else: ?>
                                                        <tr>
                                                            <td colspan="6" class="text-center">No exams found.</td>
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

        <!-- Add Exam Modal -->
        <div class="modal fade" id="addExamModal" tabindex="-1" aria-labelledby="addExamModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form method="POST" action="" enctype="multipart/form-data">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addExamModalLabel">Add Entrance Exam</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="exam_name" class="form-label">Exam Name</label>
                                    <input type="text" class="form-control" id="exam_name" name="exam_name" required placeholder="e.g. NIMCET">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="slug" class="form-label">Slug (URL)</label>
                                    <input type="text" class="form-control" id="slug" name="slug" placeholder="e.g. nimcet-exam">
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="icon_image" class="form-label">Icon Image</label>
                                <input type="file" class="form-control" id="icon_image" name="icon_image" accept="image/*">
                            </div>

                            <div class="mb-3">
                                <label for="short_description" class="form-label">Short Description</label>
                                <textarea class="form-control" id="short_description" name="short_description" rows="2" placeholder="Brief summary for cards..."></textarea>
                            </div>
                            
                            <div class="mb-3">
                                <label for="full_description" class="form-label">Full Details (Syllabus, Pattern, etc.)</label>
                                <textarea class="form-control summernote" id="full_description" name="full_description"></textarea>
                            </div>
                            
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

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="display_order" class="form-label">Display Order</label>
                                    <input type="number" class="form-control" id="display_order" name="display_order" value="0">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="form-check mt-4">
                                        <input type="checkbox" class="form-check-input" id="is_active" name="is_active" checked>
                                        <label class="form-check-label" for="is_active">Active</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" name="add_exam" class="btn btn-primary">Save Exam</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Edit Exam Modal -->
        <div class="modal fade" id="editExamModal" tabindex="-1" aria-labelledby="editExamModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form method="POST" action="" enctype="multipart/form-data">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editExamModalLabel">Edit Entrance Exam</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" id="edit_exam_id" name="edit_exam_id">
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="edit_exam_name" class="form-label">Exam Name</label>
                                    <input type="text" class="form-control" id="edit_exam_name" name="edit_exam_name" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="edit_slug" class="form-label">Slug (URL)</label>
                                    <input type="text" class="form-control" id="edit_slug" name="edit_slug">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="edit_icon_image" class="form-label">Change Icon (Optional)</label>
                                <input type="file" class="form-control" id="edit_icon_image" name="edit_icon_image" accept="image/*">
                            </div>
                            
                            <div class="mb-3">
                                <label for="edit_short_description" class="form-label">Short Description</label>
                                <textarea class="form-control" id="edit_short_description" name="edit_short_description" rows="2"></textarea>
                            </div>
                            
                            <div class="mb-3">
                                <label for="edit_full_description" class="form-label">Full Details</label>
                                <textarea class="form-control summernote" id="edit_full_description" name="edit_full_description"></textarea>
                            </div>

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

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="edit_display_order" class="form-label">Display Order</label>
                                    <input type="number" class="form-control" id="edit_display_order" name="edit_display_order">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="form-check mt-4">
                                        <input type="checkbox" class="form-check-input" id="edit_is_active" name="edit_is_active">
                                        <label class="form-check-label" for="edit_is_active">Active</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" name="edit_exam" class="btn btn-primary">Update Exam</button>
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
        <!-- FOOTER -->
        <?php include 'assets/footer.php'; ?>
        <!-- FOOTER CLOSED -->
    </div>

    <!-- BACK-TO-TOP -->
    <a href="#top" id="back-to-top"><i class="fa fa-angle-up"></i></a>

    <!-- JQUERY JS -->
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

    <!-- Sticky js -->
    <script src="assets/js/sticky.js"></script>

    <!-- CUSTOM JS -->
    <script src="assets/js/custom.js"></script>
    
    <!-- SUMMERNOTE JS -->
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>

    <!-- CUSTOM SUMMERNOTE BUTTONS -->
    <script src="assets/js/summernote-custom-buttons.js"></script>

    <script>
        $(document).ready(function() {
            // Initialize Summernote with custom buttons
            initializeCustomSummernote('.summernote');

            $('.edit-exam-btn').on('click', function() {
                var id = $(this).data('id');
                var name = $(this).data('exam-name');
                var slug = $(this).data('slug');
                var shortDesc = $(this).data('short-description');
                
                var mTitle = $(this).data('meta-title');
                var mKey = $(this).data('meta-keyword');
                var mDesc = $(this).data('meta-description');
                
                var order = $(this).data('order');
                var active = $(this).data('active');
                
                // Retrieve full description from hidden textarea
                var fullDesc = $('#desc_' + id).val();

                $('#edit_exam_id').val(id);
                $('#edit_exam_name').val(name);
                $('#edit_slug').val(slug);
                $('#edit_short_description').val(shortDesc);
                
                $('#edit_meta_title').val(mTitle);
                $('#edit_meta_keyword').val(mKey);
                $('#edit_meta_description').val(mDesc);
                
                $('#edit_display_order').val(order);
                $('#edit_is_active').prop('checked', active == 1);
                
                // Set and update Summernote
                $('#edit_full_description').summernote('code', fullDesc);
            });
        });
    </script>
</body>
</html>
