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

// Handle form submission for adding demo lectures
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_demo_lecture'])) {
        $video_type = $_POST['video_type'];
        $video_url = trim($_POST['video_url']);
        $display_order = (int)$_POST['display_order'];
        $is_active = isset($_POST['is_active']) ? 1 : 0;
        $page_name = $_POST['page_name']; // New field

        if (empty($video_type)) {
            $error_message = "Video type is required.";
        } elseif ($video_type === 'youtube' && empty($video_url)) {
            $error_message = "YouTube URL is required for YouTube videos.";
        } elseif ($video_type === 'upload' && empty($_FILES['video_file']['name'])) {
            $error_message = "Video file is required for uploaded videos.";
        } else {
            $video_file_path = '';
            $thumbnail_path = '';

            if ($video_type === 'upload') {
                // Handle video file upload
                $upload_dir = '../assets/videos/';
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0755, true);
                }

                $video_file = $_FILES['video_file'];
                $allowed_types = ['video/mp4', 'video/avi', 'video/mov', 'video/wmv'];
                $max_size = 100 * 1024 * 1024; // 100MB

                if (!in_array($video_file['type'], $allowed_types)) {
                    $error_message = "Invalid video file type. Only MP4, AVI, MOV, and WMV are allowed.";
                } elseif ($video_file['size'] > $max_size) {
                    $error_message = "Video file size too large. Maximum 100MB allowed.";
                } else {
                    $file_extension = pathinfo($video_file['name'], PATHINFO_EXTENSION);
                    $new_filename = 'demo_' . time() . '_' . uniqid() . '.' . $file_extension;
                    $video_file_path = 'assets/videos/' . $new_filename;

                    if (move_uploaded_file($video_file['tmp_name'], '../' . $video_file_path)) {
                        // Handle thumbnail upload
                        if (!empty($_FILES['thumbnail']['name'])) {
                            $thumbnail_dir = '../assets/img/';
                            if (!is_dir($thumbnail_dir)) {
                                mkdir($thumbnail_dir, 0755, true);
                            }

                            $thumbnail_file = $_FILES['thumbnail'];
                            $allowed_image_types = ['image/jpeg', 'image/png', 'image/gif'];
                            $max_image_size = 5 * 1024 * 1024; // 5MB

                            if (!in_array($thumbnail_file['type'], $allowed_image_types)) {
                                $error_message = "Invalid thumbnail file type. Only JPG, PNG, and GIF are allowed.";
                            } elseif ($thumbnail_file['size'] > $max_image_size) {
                                $error_message = "Thumbnail file size too large. Maximum 5MB allowed.";
                            } else {
                                $image_extension = pathinfo($thumbnail_file['name'], PATHINFO_EXTENSION);
                                $thumbnail_filename = 'thumb_' . time() . '_' . uniqid() . '.' . $image_extension;
                                $thumbnail_path = 'assets/img/' . $thumbnail_filename;

                                if (!move_uploaded_file($thumbnail_file['tmp_name'], '../' . $thumbnail_path)) {
                                    $error_message = "Failed to upload thumbnail file.";
                                }
                            }
                        } else {
                            $thumbnail_path = 'assets/img/demo_thumb.png'; // Default thumbnail
                        }
                    } else {
                        $error_message = "Failed to upload video file.";
                    }
                }
            } elseif ($video_type === 'youtube') {
                // Extract YouTube video ID and create embed URL
                $video_url = process_youtube_url($video_url);
                if (!$video_url) {
                    $error_message = "Invalid YouTube URL.";
                }
            }

            if (empty($error_message)) {
                try {
                    $stmt = $pdo->prepare("INSERT INTO demo_lectures (title, description, video_type, video_url, video_file, thumbnail, display_order, is_active, page_name) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
                    $stmt->execute(['', '', $video_type, $video_url, $video_file_path, $thumbnail_path, $display_order, $is_active, $page_name]);
                    $success_message = "Demo lecture added successfully.";
                } catch (PDOException $e) {
                    $error_message = "Error adding demo lecture: " . $e->getMessage();
                }
            }
        }
    } elseif (isset($_POST['edit_demo_lecture'])) {
        $id = (int)$_POST['edit_demo_lecture_id'];
        $video_type = $_POST['edit_video_type'];
        $video_url = trim($_POST['edit_video_url']);
        $display_order = (int)$_POST['edit_display_order'];
        $is_active = isset($_POST['edit_is_active']) ? 1 : 0;
        $page_name = $_POST['edit_page_name']; // New field

        // Fetch current values
        $stmt = $pdo->prepare("SELECT video_file, thumbnail FROM demo_lectures WHERE id = ?");
        $stmt->execute([$id]);
        $current = $stmt->fetch(PDO::FETCH_ASSOC);
        $video_file_path = $current['video_file'];
        $thumbnail_path = $current['thumbnail'];

        if (empty($video_type)) {
            $error_message = "Video type is required.";
        } elseif ($video_type === 'youtube' && empty($video_url)) {
            $error_message = "YouTube URL is required for YouTube videos.";
        } else {
            if ($video_type === 'upload' && !empty($_FILES['edit_video_file']['name'])) {
                // Handle video file upload for edit
                $upload_dir = '../assets/videos/';
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0755, true);
                }

                $video_file = $_FILES['edit_video_file'];
                $allowed_types = ['video/mp4', 'video/avi', 'video/mov', 'video/wmv'];
                $max_size = 100 * 1024 * 1024; // 100MB

                if (!in_array($video_file['type'], $allowed_types)) {
                    $error_message = "Invalid video file type. Only MP4, AVI, MOV, and WMV are allowed.";
                } elseif ($video_file['size'] > $max_size) {
                    $error_message = "Video file size too large. Maximum 100MB allowed.";
                } else {
                    $file_extension = pathinfo($video_file['name'], PATHINFO_EXTENSION);
                    $new_filename = 'demo_' . time() . '_' . uniqid() . '.' . $file_extension;
                    $video_file_path = 'assets/videos/' . $new_filename;

                    if (!move_uploaded_file($video_file['tmp_name'], '../' . $video_file_path)) {
                        $error_message = "Failed to upload video file.";
                    }
                }
            } elseif ($video_type === 'youtube') {
                $video_url = process_youtube_url($video_url);
                $video_file_path = ''; // Clear video file for YouTube
                if (!$video_url) {
                    $error_message = "Invalid YouTube URL.";
                }
            }

            // Handle thumbnail upload for edit
            if (!empty($_FILES['edit_thumbnail']['name'])) {
                $thumbnail_dir = '../assets/img/';
                if (!is_dir($thumbnail_dir)) {
                    mkdir($thumbnail_dir, 0755, true);
                }

                $thumbnail_file = $_FILES['edit_thumbnail'];
                $allowed_image_types = ['image/jpeg', 'image/png', 'image/gif'];
                $max_image_size = 5 * 1024 * 1024; // 5MB

                if (!in_array($thumbnail_file['type'], $allowed_image_types)) {
                    $error_message = "Invalid thumbnail file type. Only JPG, PNG, and GIF are allowed.";
                } elseif ($thumbnail_file['size'] > $max_image_size) {
                    $error_message = "Thumbnail file size too large. Maximum 5MB allowed.";
                } else {
                    $image_extension = pathinfo($thumbnail_file['name'], PATHINFO_EXTENSION);
                    $thumbnail_filename = 'thumb_' . time() . '_' . uniqid() . '.' . $image_extension;
                    $thumbnail_path = 'assets/img/' . $thumbnail_filename;

                    if (!move_uploaded_file($thumbnail_file['tmp_name'], '../' . $thumbnail_path)) {
                        $error_message = "Failed to upload thumbnail file.";
                    }
                }
            }

            if (empty($error_message)) {
                try {
                    $stmt = $pdo->prepare("UPDATE demo_lectures SET title=?, description=?, video_type=?, video_url=?, video_file=?, thumbnail=?, display_order=?, is_active=?, page_name=? WHERE id=?");
                    $stmt->execute(['', '', $video_type, $video_url, $video_file_path, $thumbnail_path, $display_order, $is_active, $page_name, $id]);
                    $success_message = "Demo lecture updated successfully.";
                } catch (PDOException $e) {
                    $error_message = "Error updating demo lecture: " . $e->getMessage();
                }
            }
        }
    }
}

// Handle delete
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    try {
        $stmt = $pdo->prepare("DELETE FROM demo_lectures WHERE id = ?");
        $stmt->execute([$id]);
        $success_message = "Demo lecture deleted successfully.";
    } catch (PDOException $e) {
        $error_message = "Error deleting demo lecture: " . $e->getMessage();
    }
}

// Fetch all demo lectures
try {
    $sql = "SELECT * FROM demo_lectures ORDER BY display_order ASC, created_at DESC";
    $stmt = $pdo->query($sql);
    $lectures = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Database Fetch Error: " . $e->getMessage());
    $error_message = "Could not retrieve demo lectures. Please try again later.";
}

function process_youtube_url($url) {
    // Extract YouTube video ID from various YouTube URL formats
    $patterns = [
        '/youtube\.com\/watch\?v=([a-zA-Z0-9_-]+)/',
        '/youtu\.be\/([a-zA-Z0-9_-]+)/',
        '/youtube\.com\/embed\/([a-zA-Z0-9_-]+)/'
    ];

    foreach ($patterns as $pattern) {
        if (preg_match($pattern, $url, $matches)) {
            return 'https://www.youtube.com/embed/' . $matches[1];
        }
    }

    return false;
}
?>

<!doctype html>
<html lang="en" dir="ltr">

<head>
    <!-- META DATA -->
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="Info Maths Online admin dashboard - Manage demo lectures">
    <meta name="keywords" content="admin dashboard, demo lectures, video management">
    <meta name="robots" content="noindex, nofollow">
    <!-- FAVICON -->
    <link rel="shortcut icon" type="image/x-icon" href="../assets/img/favicon.png">

    <!-- TITLE -->
    <title>Info Maths Online - Demo Lectures Management</title>

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
                            <h1 class="page-title">Demo Lectures Management</h1>
                            <div>
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Demo Lectures</li>
                                </ol>
                            </div>
                        </div>
                        <!-- PAGE-HEADER END -->

                        <!-- Row -->
                        <div class="row row-sm">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Add New Demo Lecture</h3>
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
                                                    <label>Video Type</label>
                                                    <select name="video_type" id="video_type" class="form-control" required onchange="toggleVideoFields()">
                                                        <option value="">Select Type</option>
                                                        <option value="youtube">YouTube Link</option>
                                                        <option value="upload">Upload Video</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label">Page <span class="text-danger">*</span></label>
                                                    <select name="page_name" class="form-control form-select" required>
                                                        <option value="bank_po">Bank PO & SSC</option>
                                                        <option value="iit_jam">IIT JAM Mathematics</option>
                                                        <option value="bca">Career BCA</option>
                                                        <option value="bsc">Career BSC</option>
                                                        <option value="csir_net">CSIR NET / JRF</option>
                                                        <option value="mca">MCA Entrance</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-3">
                                                    <label>Display Order</label>
                                                    <input type="number" name="display_order" class="form-control" value="0">
                                                </div>
                                                <div class="col-md-3">
                                                    <label>Status</label>
                                                    <div class="form-check mt-4">
                                                        <input type="checkbox" name="is_active" class="form-check-input" id="is_active" checked>
                                                        <label class="form-check-label" for="is_active">Active</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mt-3">
                                                <div class="col-md-6">
                                                    <div id="youtube_field" style="display: none;">
                                                        <label>YouTube URL</label>
                                                        <input type="url" name="video_url" class="form-control" placeholder="https://youtube.com/watch?v=...">
                                                        <small class="text-muted">Enter full YouTube URL</small>
                                                    </div>
                                                    <div id="upload_field" style="display: none;">
                                                        <label>Video File</label>
                                                        <input type="file" name="video_file" class="form-control" accept="video/*">
                                                        <small class="text-muted">MP4, AVI, MOV, WMV (max 100MB)</small>
                                                        <label class="mt-2">Thumbnail Image</label>
                                                        <input type="file" name="thumbnail" class="form-control" accept="image/*">
                                                        <small class="text-muted">JPG, PNG, GIF (max 5MB)</small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mt-3">
                                                <div class="col-md-12">
                                                    <button type="submit" name="add_demo_lecture" class="btn btn-success">Add Demo Lecture</button>
                                                </div>
                                            </div>
                                        </form>

                                        <div class="table-responsive">
                                            <table class="table table-bordered text-nowrap border-bottom" id="basic-datatable">
                                                <thead>
                                                    <tr>
                                                        <th class="wd-5p border-bottom-0">ID</th>
                                                        <th class="wd-10p border-bottom-0">Page</th>
                                                        <th class="wd-10p border-bottom-0">Type</th>
                                                        <th class="wd-10p border-bottom-0">Order</th>
                                                        <th class="wd-10p border-bottom-0">Status</th>
                                                        <th class="wd-15p border-bottom-0">Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    if (!empty($lectures)):
                                                        foreach ($lectures as $lecture):
                                                    ?>
                                                            <tr>
                                                                <td><?php echo htmlspecialchars($lecture['id']); ?></td>
                                                                <td>
                                                                    <?php if($lecture['page_name'] == 'iit_jam'): ?>
                                                                        <span class="badge bg-warning">IIT JAM</span>
                                                                    <?php elseif($lecture['page_name'] == 'bca'): ?>
                                                                        <span class="badge bg-success">BCA</span>
                                                                    <?php elseif($lecture['page_name'] == 'bsc'): ?>
                                                                        <span class="badge bg-secondary">BSC</span>
                                                                    <?php elseif($lecture['page_name'] == 'csir_net'): ?>
                                                                        <span class="badge bg-danger">CSIR NET</span>
                                                                    <?php elseif($lecture['page_name'] == 'mca'): ?>
                                                                        <span class="badge bg-info">MCA</span>
                                                                    <?php else: ?>
                                                                        <span class="badge bg-primary">Bank PO</span>
                                                                    <?php endif; ?>
                                                                </td>
                                                                <td>
                                                                    <span class=" <?php echo $lecture['video_type'] === 'youtube' ? 'badge-danger' : 'badge-info'; ?>">
                                                                        <?php echo ucfirst($lecture['video_type']); ?>
                                                                    </span>
                                                                </td>
                                                                <td><?php echo htmlspecialchars($lecture['display_order']); ?></td>
                                                                <td>
                                                                    <span class=" <?php echo $lecture['is_active'] ? 'badge-success' : 'badge-danger'; ?>">
                                                                        <?php echo $lecture['is_active'] ? 'Active' : 'Inactive'; ?>
                                                                    </span>
                                                                </td>
                                                                <td>
                                                                    <a href="#" class="btn btn-primary btn-sm me-1" data-bs-toggle="modal" data-bs-target="#editModal" onclick="editLecture(<?php echo htmlspecialchars(json_encode($lecture)); ?>)">Edit</a>
                                                                    <a href="?delete=<?php echo $lecture['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</a>
                                                                </td>
                                                            </tr>
                                                        <?php
                                                        endforeach;
                                                    else:
                                                        ?>
                                                        <tr>
                                                            <td colspan="6" class="text-center">No demo lectures found.</td>
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

        <!-- Edit Demo Lecture Modal -->
        <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">Edit Demo Lecture</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form method="post" enctype="multipart/form-data">
                        <div class="modal-body">
                            <input type="hidden" name="edit_demo_lecture_id" id="edit_demo_lecture_id">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="edit_video_type" class="form-label">Video Type</label>
                                    <select name="edit_video_type" id="edit_video_type" class="form-control" required onchange="toggleEditVideoFields()">
                                        <option value="youtube">YouTube Link</option>
                                        <option value="upload">Upload Video</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Page <span class="text-danger">*</span></label>
                                    <select name="edit_page_name" id="edit_page_name" class="form-control form-select" required>
                                        <option value="bank_po">Bank PO & SSC</option>
                                        <option value="iit_jam">IIT JAM Mathematics</option>
                                        <option value="mca">MCA Entrance</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="edit_display_order" class="form-label">Display Order</label>
                                    <input type="number" name="edit_display_order" id="edit_display_order" class="form-control" value="0">
                                </div>
                                <div class="col-md-3">
                                    <label for="edit_is_active" class="form-label">Active</label>
                                    <div class="form-check mt-4">
                                        <input type="checkbox" name="edit_is_active" id="edit_is_active" class="form-check-input">
                                        <label class="form-check-label" for="edit_is_active">Active</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <div id="edit_youtube_field">
                                        <label for="edit_video_url" class="form-label">YouTube URL</label>
                                        <input type="url" name="edit_video_url" id="edit_video_url" class="form-control" placeholder="https://youtube.com/watch?v=...">
                                        <small class="text-muted">Enter full YouTube URL</small>
                                    </div>
                                    <div id="edit_upload_field" style="display: none;">
                                        <label for="edit_video_file" class="form-label">Video File (Optional)</label>
                                        <input type="file" name="edit_video_file" id="edit_video_file" class="form-control" accept="video/*">
                                        <small class="text-muted">MP4, AVI, MOV, WMV (max 100MB)</small>
                                        <label for="edit_thumbnail" class="form-label mt-2">Thumbnail Image (Optional)</label>
                                        <input type="file" name="edit_thumbnail" id="edit_thumbnail" class="form-control" accept="image/*">
                                        <small class="text-muted">JPG, PNG, GIF (max 5MB)</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" name="edit_demo_lecture" class="btn btn-primary">Update Demo Lecture</button>
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
        function toggleVideoFields() {
            const videoType = document.getElementById('video_type').value;
            const youtubeField = document.getElementById('youtube_field');
            const uploadField = document.getElementById('upload_field');

            if (videoType === 'youtube') {
                youtubeField.style.display = 'block';
                uploadField.style.display = 'none';
            } else if (videoType === 'upload') {
                youtubeField.style.display = 'none';
                uploadField.style.display = 'block';
            } else {
                youtubeField.style.display = 'none';
                uploadField.style.display = 'none';
            }
        }

        function toggleEditVideoFields() {
            const videoType = document.getElementById('edit_video_type').value;
            const youtubeField = document.getElementById('edit_youtube_field');
            const uploadField = document.getElementById('edit_upload_field');

            if (videoType === 'youtube') {
                youtubeField.style.display = 'block';
                uploadField.style.display = 'none';
            } else if (videoType === 'upload') {
                youtubeField.style.display = 'none';
                uploadField.style.display = 'block';
            }
        }

        function editLecture(lecture) {
            document.getElementById('edit_demo_lecture_id').value = lecture.id;
            document.getElementById('edit_video_type').value = lecture.video_type;
            document.getElementById('edit_display_order').value = lecture.display_order;
            document.getElementById('edit_is_active').checked = lecture.is_active == 1;
            document.getElementById('edit_page_name').value = lecture.page_name || 'bank_po';

            if (lecture.video_type === 'youtube') {
                document.getElementById('edit_video_url').value = lecture.video_url;
                document.getElementById('edit_youtube_field').style.display = 'block';
                document.getElementById('edit_upload_field').style.display = 'none';
            } else {
                document.getElementById('edit_youtube_field').style.display = 'none';
                document.getElementById('edit_upload_field').style.display = 'block';
            }
        }
    </script>

</body>

</html>