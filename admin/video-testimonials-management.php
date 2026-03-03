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

$video_testimonials = []; // Initialize an empty array for the results.
$error_message = ''; // Initialize an empty error message.
$success_message = ''; // For success messages

// Handle POST requests for adding video testimonial
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_video_testimonial'])) {
    $name = trim($_POST['name']);
    $designation = trim($_POST['designation']);
    $video_type = $_POST['video_type'];

    if ($name && $video_type) {
        $video_file = null;
        $youtube_link = null;
        $video_id = null;

        if ($video_type === 'upload') {
            // Handle video file upload
            if (isset($_FILES['video_file']) && $_FILES['video_file']['error'] == 0) {
                $file_name = $_FILES['video_file']['name'];
                $file_tmp = $_FILES['video_file']['tmp_name'];
                $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

                $allowed_exts = ['mp4', 'avi', 'mov', 'wmv', 'flv', 'webm'];

                if (in_array($file_ext, $allowed_exts)) {
                    $new_file_name = uniqid() . '.' . $file_ext;
                    $upload_path = '../assets/videos/' . $new_file_name;

                    if (move_uploaded_file($file_tmp, $upload_path)) {
                        $video_file = $new_file_name;
                    } else {
                        $error_message = "Error uploading video file.";
                    }
                } else {
                    $error_message = "Invalid video file type. Allowed: MP4, AVI, MOV, WMV, FLV, WEBM.";
                }
            } else {
                $error_message = "Please select a video file to upload.";
            }
            // For upload type, set YouTube fields to NULL
            $youtube_link = null;
            $video_id = null;
        } elseif ($video_type === 'youtube') {
            // Handle YouTube link
            $youtube_link = trim($_POST['youtube_link']);
            if ($youtube_link) {
                // Extract video ID from YouTube link
                if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $youtube_link, $matches)) {
                    $video_id = $matches[1];
                } else {
                    $error_message = "Invalid YouTube link.";
                }
            } else {
                $error_message = "Please provide a YouTube link.";
            }
            // For YouTube type, set video_file to NULL
            $video_file = null;
        } else {
            $error_message = "Invalid video type selected.";
        }

        if (empty($error_message)) {
            try {
                $stmt = $pdo->prepare("INSERT INTO video_testimonials (name, designation, video_type, video_file, youtube_link, video_id) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->execute([$name, $designation, $video_type, $video_file, $youtube_link, $video_id]);
                $success_message = "Video testimonial added successfully.";
            } catch (PDOException $e) {
                $error_message = "Error saving to database: " . $e->getMessage();
            }
        }
    } else {
        $error_message = "Name and video type are required.";
    }
}

// Handle POST requests for editing video testimonial
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_video_testimonial'])) {
    $id = (int)$_POST['edit_id'];
    $name = trim($_POST['edit_name']);
    $designation = trim($_POST['edit_designation']);
    $video_type = $_POST['edit_video_type'];

    if ($name && $video_type && $id) {
        $video_file = null;
        $youtube_link = null;
        $video_id = null;

        // Get current testimonial data to handle file deletion
        $stmt = $pdo->prepare("SELECT video_file, video_type FROM video_testimonials WHERE id = ?");
        $stmt->execute([$id]);
        $current_testimonial = $stmt->fetch();

        if ($video_type === 'upload') {
            // Handle video file upload
            if (isset($_FILES['edit_video_file']) && $_FILES['edit_video_file']['error'] == 0) {
                $file_name = $_FILES['edit_video_file']['name'];
                $file_tmp = $_FILES['edit_video_file']['tmp_name'];
                $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

                $allowed_exts = ['mp4', 'avi', 'mov', 'wmv', 'flv', 'webm'];

                if (in_array($file_ext, $allowed_exts)) {
                    $new_file_name = uniqid() . '.' . $file_ext;
                    $upload_path = '../assets/videos/' . $new_file_name;

                    if (move_uploaded_file($file_tmp, $upload_path)) {
                        $video_file = $new_file_name;
                        // Delete old video file if it exists and is different
                        if ($current_testimonial && $current_testimonial['video_file'] && $current_testimonial['video_file'] !== $video_file) {
                            $old_file_path = '../assets/videos/' . $current_testimonial['video_file'];
                            if (file_exists($old_file_path)) {
                                unlink($old_file_path);
                            }
                        }
                    } else {
                        $error_message = "Error uploading video file.";
                    }
                } else {
                    $error_message = "Invalid video file type. Allowed: MP4, AVI, MOV, WMV, FLV, WEBM.";
                }
            } else {
                // Keep existing video file if no new file uploaded
                $video_file = $current_testimonial['video_file'];
            }
            // For upload type, set YouTube fields to NULL
            $youtube_link = null;
            $video_id = null;
        } elseif ($video_type === 'youtube') {
            // Handle YouTube link
            $youtube_link = trim($_POST['edit_youtube_link']);
            if ($youtube_link) {
                // Extract video ID from YouTube link
                if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $youtube_link, $matches)) {
                    $video_id = $matches[1];
                } else {
                    $error_message = "Invalid YouTube link.";
                }
            } else {
                $error_message = "Please provide a YouTube link.";
            }
            // For YouTube type, set video_file to NULL and delete old video file if switching from upload
            $video_file = null;
            if ($current_testimonial && $current_testimonial['video_type'] === 'upload' && $current_testimonial['video_file']) {
                $old_file_path = '../assets/videos/' . $current_testimonial['video_file'];
                if (file_exists($old_file_path)) {
                    unlink($old_file_path);
                }
            }
        } else {
            $error_message = "Invalid video type selected.";
        }

        if (empty($error_message)) {
            try {
                $stmt = $pdo->prepare("UPDATE video_testimonials SET name = ?, designation = ?, video_type = ?, video_file = ?, youtube_link = ?, video_id = ? WHERE id = ?");
                $stmt->execute([$name, $designation, $video_type, $video_file, $youtube_link, $video_id, $id]);
                $success_message = "Video testimonial updated successfully.";
            } catch (PDOException $e) {
                $error_message = "Error updating database: " . $e->getMessage();
            }
        }
    } else {
        $error_message = "Name, video type, and ID are required.";
    }
}

// Handle delete
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    try {
        // First get the testimonial to check if it has a video file
        $stmt = $pdo->prepare("SELECT video_file FROM video_testimonials WHERE id = ?");
        $stmt->execute([$id]);
        $testimonial = $stmt->fetch();

        // Delete the video file if it exists
        if ($testimonial && $testimonial['video_file']) {
            $file_path = '../assets/videos/' . $testimonial['video_file'];
            if (file_exists($file_path)) {
                unlink($file_path);
            }
        }

        // Delete from database
        $stmt = $pdo->prepare("DELETE FROM video_testimonials WHERE id = ?");
        $stmt->execute([$id]);
        $success_message = "Video testimonial deleted successfully.";
    } catch (PDOException $e) {
        $error_message = "Error deleting video testimonial: " . $e->getMessage();
    }
}

// Fetch all video testimonials
try {
    $sql = "SELECT * FROM video_testimonials ORDER BY created_at DESC";
    $stmt = $pdo->query($sql);
    $video_testimonials = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Database Fetch Error: " . $e->getMessage());
    $error_message = "Could not retrieve video testimonials. Please try again later.";
}
?>

<!doctype html>
<html lang="en" dir="ltr">

<head>
    <!-- META DATA -->
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="Manage video testimonials with YouTube links on Info Maths Online admin panel.">
    <meta name="keywords" content="video testimonials management, YouTube testimonials, admin panel, Info Maths Online">
    <!-- FAVICON -->
    <link rel="shortcut icon" type="image/x-icon" href="../assets/img/favicon.png">

    <!-- TITLE -->
    <title>Info Maths Online - Video Testimonials Management</title>

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
                            <h1 class="page-title">Video Testimonials Management</h1>
                            <div>
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Video Testimonials</li>
                                </ol>
                            </div>
                        </div>
                        <!-- PAGE-HEADER END -->

                        <!-- Row -->
                        <div class="row row-sm">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Add New Video Testimonial</h3>
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
                                                <div class="col-md-6">
                                                    <label>Name</label>
                                                    <input type="text" name="name" class="form-control" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label>Designation (Optional)</label>
                                                    <input type="text" name="designation" class="form-control" placeholder="e.g., Student, IIT Aspirant">
                                                </div>
                                            </div>
                                            <div class="row mt-3">
                                                <div class="col-md-12">
                                                    <label>Video Type</label>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="video_type" id="youtube_type" value="youtube" checked>
                                                        <label class="form-check-label" for="youtube_type">
                                                            YouTube Link
                                                        </label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="video_type" id="upload_type" value="upload">
                                                        <label class="form-check-label" for="upload_type">
                                                            Upload Video File
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mt-3" id="youtube_section">
                                                <div class="col-md-12">
                                                    <label>YouTube Link</label>
                                                    <input type="url" name="youtube_link" class="form-control" placeholder="https://www.youtube.com/watch?v=VIDEO_ID" id="youtube_input" required>
                                                </div>
                                            </div>
                                            <div class="row mt-3" id="upload_section" style="display: none;">
                                                <div class="col-md-12">
                                                    <label>Video File</label>
                                                    <input type="file" name="video_file" class="form-control" accept=".mp4,.avi,.mov,.wmv,.flv,.webm" id="video_input">
                                                    <small class="text-muted">Allowed formats: MP4, AVI, MOV, WMV, FLV, WEBM. Max file size depends on server settings.</small>
                                                </div>
                                            </div>
                                            <div class="row mt-3">
                                                <div class="col-md-12">
                                                    <button type="submit" name="add_video_testimonial" class="btn btn-success">Add Video Testimonial</button>
                                                </div>
                                            </div>
                                        </form>

                                        <!-- Edit Video Testimonial Modal -->
                                        <div class="modal fade" id="editVideoTestimonialModal" tabindex="-1" aria-labelledby="editVideoTestimonialModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="editVideoTestimonialModalLabel">Edit Video Testimonial</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form method="post" enctype="multipart/form-data" id="editVideoTestimonialForm">
                                                            <input type="hidden" name="edit_id" id="edit_id">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <label>Name</label>
                                                                    <input type="text" name="edit_name" id="edit_name" class="form-control" required>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label>Designation (Optional)</label>
                                                                    <input type="text" name="edit_designation" id="edit_designation" class="form-control" placeholder="e.g., Student, IIT Aspirant">
                                                                </div>
                                                            </div>
                                                            <div class="row mt-3">
                                                                <div class="col-md-12">
                                                                    <label>Video Type</label>
                                                                    <div class="form-check form-check-inline">
                                                                        <input class="form-check-input" type="radio" name="edit_video_type" id="edit_youtube_type" value="youtube">
                                                                        <label class="form-check-label" for="edit_youtube_type">
                                                                            YouTube Link
                                                                        </label>
                                                                    </div>
                                                                    <div class="form-check form-check-inline">
                                                                        <input class="form-check-input" type="radio" name="edit_video_type" id="edit_upload_type" value="upload">
                                                                        <label class="form-check-label" for="edit_upload_type">
                                                                            Upload Video File
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row mt-3" id="edit_youtube_section">
                                                                <div class="col-md-12">
                                                                    <label>YouTube Link</label>
                                                                    <input type="url" name="edit_youtube_link" id="edit_youtube_link" class="form-control" placeholder="https://www.youtube.com/watch?v=VIDEO_ID">
                                                                </div>
                                                            </div>
                                                            <div class="row mt-3" id="edit_upload_section" style="display: none;">
                                                                <div class="col-md-12">
                                                                    <label>Video File</label>
                                                                    <input type="file" name="edit_video_file" id="edit_video_file" class="form-control" accept=".mp4,.avi,.mov,.wmv,.flv,.webm">
                                                                    <small class="text-muted">Allowed formats: MP4, AVI, MOV, WMV, FLV, WEBM. Max file size depends on server settings.</small>
                                                                    <div id="current_video_info" class="mt-2"></div>
                                                                </div>
                                                            </div>
                                                            <div class="row mt-3">
                                                                <div class="col-md-12">
                                                                    <button type="submit" name="edit_video_testimonial" class="btn btn-success">Update Video Testimonial</button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="table-responsive">
                                            <table class="table table-bordered text-nowrap border-bottom" id="basic-datatable">
                                                <thead>
                                                    <tr>
                                                        <th class="wd-5p border-bottom-0">ID</th>
                                                        <th class="wd-15p border-bottom-0">Preview</th>
                                                        <th class="wd-15p border-bottom-0">Name</th>
                                                        <th class="wd-10p border-bottom-0">Designation</th>
                                                        <th class="wd-10p border-bottom-0">Type</th>
                                                        <th class="wd-10p border-bottom-0">Created At</th>
                                                        <th class="wd-10p border-bottom-0">Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    if (!empty($video_testimonials)):
                                                        foreach ($video_testimonials as $testimonial):
                                                    ?>
                                                            <tr>
                                                                <td><?php echo htmlspecialchars($testimonial['id']); ?></td>
                                                                <td>
                                                                    <?php if ($testimonial['video_type'] === 'youtube' && $testimonial['video_id']): ?>
                                                                        <img src="https://img.youtube.com/vi/<?php echo htmlspecialchars($testimonial['video_id']); ?>/0.jpg" alt="YouTube Thumbnail" style="width: 120px; height: 90px; object-fit: cover;">
                                                                    <?php elseif ($testimonial['video_type'] === 'upload' && $testimonial['video_file']): ?>
                                                                        <div style="width: 120px; height: 90px; background: #f8f9fa; display: flex; align-items: center; justify-content: center; border: 1px solid #dee2e6;">
                                                                            <i class="fas fa-video" style="font-size: 24px; color: #6c757d;"></i>
                                                                        </div>
                                                                    <?php else: ?>
                                                                        <div style="width: 120px; height: 90px; background: #f8f9fa; display: flex; align-items: center; justify-content: center; border: 1px solid #dee2e6;">
                                                                            <i class="fas fa-question-circle" style="font-size: 24px; color: #6c757d;"></i>
                                                                        </div>
                                                                    <?php endif; ?>
                                                                </td>
                                                                <td><?php echo htmlspecialchars($testimonial['name']); ?></td>
                                                                <td><?php echo htmlspecialchars($testimonial['designation']); ?></td>
                                                                <td>
                                                                    <span class="badge bg-<?php echo $testimonial['video_type'] === 'youtube' ? 'danger' : 'success'; ?>">
                                                                        <?php echo ucfirst($testimonial['video_type']); ?>
                                                                    </span>
                                                                </td>
                                                                <td><?php echo date('j M Y', strtotime($testimonial['created_at'])); ?></td>
                                                                <td>
                                                                    <?php if ($testimonial['video_type'] === 'youtube' && $testimonial['youtube_link']): ?>
                                                                        <a href="<?php echo htmlspecialchars($testimonial['youtube_link']); ?>" target="_blank" class="btn btn-info btn-sm me-1">View</a>
                                                                    <?php elseif ($testimonial['video_type'] === 'upload' && $testimonial['video_file']): ?>
                                                                        <button class="btn btn-info btn-sm me-1" onclick="playVideo('../assets/videos/<?php echo htmlspecialchars($testimonial['video_file']); ?>')">Play</button>
                                                                    <?php endif; ?>
                                                                    <button class="btn btn-warning btn-sm me-1" onclick="editVideoTestimonial(<?php echo $testimonial['id']; ?>, '<?php echo htmlspecialchars($testimonial['name']); ?>', '<?php echo htmlspecialchars($testimonial['designation']); ?>', '<?php echo htmlspecialchars($testimonial['video_type']); ?>', '<?php echo htmlspecialchars($testimonial['youtube_link'] ?? ''); ?>', '<?php echo htmlspecialchars($testimonial['video_file'] ?? ''); ?>')">Edit</button>
                                                                    <a href="?delete=<?php echo $testimonial['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</a>
                                                                </td>
                                                            </tr>
                                                        <?php
                                                        endforeach;
                                                    else:
                                                        ?>
                                                        <tr>
                                                            <td colspan="7" class="text-center">No video testimonials found.</td>
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

    <script>
        // Handle video type selection
        function updateFormValidation() {
            const youtubeSection = document.getElementById('youtube_section');
            const uploadSection = document.getElementById('upload_section');
            const youtubeInput = document.getElementById('youtube_input');
            const videoInput = document.getElementById('video_input');
            const selectedType = document.querySelector('input[name="video_type"]:checked').value;

            if (selectedType === 'youtube') {
                youtubeSection.style.display = 'block';
                uploadSection.style.display = 'none';
                youtubeInput.required = true;
                videoInput.required = false;
                videoInput.value = ''; // Clear video input
            } else {
                youtubeSection.style.display = 'none';
                uploadSection.style.display = 'block';
                youtubeInput.required = false;
                videoInput.required = true;
                youtubeInput.value = ''; // Clear youtube input
            }
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            updateFormValidation();
        });

        // Handle video type selection
        document.querySelectorAll('input[name="video_type"]').forEach(function(radio) {
            radio.addEventListener('change', updateFormValidation);
        });

        // Function to play uploaded videos
        function playVideo(videoPath) {
            // Create a modal to play the video
            const modalHtml = `
                <div class="modal fade" id="videoModal" tabindex="-1" aria-labelledby="videoModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="videoModalLabel">Video Testimonial</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <video controls style="width: 100%; max-height: 500px;">
                                    <source src="${videoPath}" type="video/mp4">
                                    <source src="${videoPath}" type="video/avi">
                                    <source src="${videoPath}" type="video/mov">
                                    <source src="${videoPath}" type="video/wmv">
                                    <source src="${videoPath}" type="video/flv">
                                    <source src="${videoPath}" type="video/webm">
                                    Your browser does not support the video tag.
                                </video>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            // Remove existing modal if present
            const existingModal = document.getElementById('videoModal');
            if (existingModal) {
                existingModal.remove();
            }

            // Add modal to body
            document.body.insertAdjacentHTML('beforeend', modalHtml);

            // Show modal
            const modal = new bootstrap.Modal(document.getElementById('videoModal'));
            modal.show();

            // Remove modal from DOM when hidden
            document.getElementById('videoModal').addEventListener('hidden.bs.modal', function() {
                this.remove();
            });
        }

        // Function to edit video testimonial
        function editVideoTestimonial(id, name, designation, videoType, youtubeLink, videoFile) {
            // Populate the edit modal
            document.getElementById('edit_id').value = id;
            document.getElementById('edit_name').value = name;
            document.getElementById('edit_designation').value = designation;

            // Set video type radio button
            if (videoType === 'youtube') {
                document.getElementById('edit_youtube_type').checked = true;
                document.getElementById('edit_upload_type').checked = false;
            } else {
                document.getElementById('edit_youtube_type').checked = false;
                document.getElementById('edit_upload_type').checked = true;
            }

            // Set form values based on video type
            if (videoType === 'youtube') {
                document.getElementById('edit_youtube_link').value = youtubeLink || '';
                document.getElementById('edit_video_file').value = '';
                document.getElementById('current_video_info').innerHTML = '';
            } else {
                document.getElementById('edit_youtube_link').value = '';
                document.getElementById('edit_video_file').value = '';
                document.getElementById('current_video_info').innerHTML = videoFile ? '<small class="text-info">Current file: ' + videoFile + ' (will be replaced if you upload a new file)</small>' : '';
            }

            // Update form validation for edit modal
            updateEditFormValidation();

            // Show the modal
            const modal = new bootstrap.Modal(document.getElementById('editVideoTestimonialModal'));
            modal.show();
        }

        // Handle edit video type selection
        function updateEditFormValidation() {
            const youtubeSection = document.getElementById('edit_youtube_section');
            const uploadSection = document.getElementById('edit_upload_section');
            const youtubeInput = document.getElementById('edit_youtube_link');
            const videoInput = document.getElementById('edit_video_file');
            const selectedType = document.querySelector('input[name="edit_video_type"]:checked').value;

            if (selectedType === 'youtube') {
                youtubeSection.style.display = 'block';
                uploadSection.style.display = 'none';
                youtubeInput.required = true;
                videoInput.required = false;
                videoInput.value = ''; // Clear video input
            } else {
                youtubeSection.style.display = 'none';
                uploadSection.style.display = 'block';
                youtubeInput.required = false;
                videoInput.required = false; // Not required for edit since we might keep existing file
                youtubeInput.value = ''; // Clear youtube input
            }
        }

        // Initialize edit form validation on page load
        document.addEventListener('DOMContentLoaded', function() {
            updateFormValidation();

            // Handle edit video type selection
            document.querySelectorAll('input[name="edit_video_type"]').forEach(function(radio) {
                radio.addEventListener('change', updateEditFormValidation);
            });
        });
    </script>

</body>

</html>
