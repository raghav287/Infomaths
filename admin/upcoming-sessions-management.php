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

$sessions = []; // Initialize an empty array for the results.
$error_message = ''; // Initialize an empty error message.
$success_message = ''; // For success messages

// Create uploads directory if it doesn't exist
$upload_dir = '../assets/img/sessions/';
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0755, true);
}

// Function to handle file upload
function uploadImage($file) {
    global $upload_dir, $error_message;
    
    if ($file['error'] == UPLOAD_ERR_NO_FILE) {
        return null; // No file uploaded
    }
    
    if ($file['error'] !== UPLOAD_ERR_OK) {
        $error_message = "File upload error.";
        return false;
    }
    
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    if (!in_array($file['type'], $allowed_types)) {
        $error_message = "Only JPG, PNG, GIF, and WebP images are allowed.";
        return false;
    }
    
    if ($file['size'] > 5 * 1024 * 1024) { // 5MB limit
        $error_message = "File size must be less than 5MB.";
        return false;
    }
    
    $filename = uniqid() . '_' . basename($file['name']);
    $filepath = $upload_dir . $filename;
    
    if (move_uploaded_file($file['tmp_name'], $filepath)) {
        return 'assets/img/sessions/' . $filename;
    } else {
        $error_message = "Failed to save uploaded file.";
        return false;
    }
}

// Handle POST requests for adding session
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_session'])) {
    $category = trim($_POST['category']);
    $title = trim($_POST['title']);
    $session_date = trim($_POST['session_date']);
    $session_time = trim($_POST['session_time']);
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    
    // Handle image upload
    $image_path = uploadImage($_FILES['session_image'] ?? []);
    if ($image_path === false) {
        // Error already set in uploadImage function
    } elseif (empty($title) || empty($category) || empty($session_date) || empty($session_time)) {
        $error_message = "Title, category, date and time are required.";
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO upcoming_sessions (image_path, category, title, session_date, session_time, is_active) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$image_path, $category, $title, $session_date, $session_time, $is_active]);
            $success_message = "Session added successfully.";
        } catch (PDOException $e) {
            $error_message = "Error saving to database: " . $e->getMessage();
        }
    }
}

// Handle POST requests for editing session
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_session'])) {
    $id = (int)$_POST['edit_session_id'];
    $category = trim($_POST['edit_category']);
    $title = trim($_POST['edit_title']);
    $session_date = trim($_POST['edit_session_date']);
    $session_time = trim($_POST['edit_session_time']);
    $is_active = isset($_POST['edit_is_active']) ? 1 : 0;
    
    // Handle image upload (optional for edit)
    $image_path = uploadImage($_FILES['edit_session_image'] ?? []);
    if ($image_path === false) {
        // Error already set, don't proceed
    } elseif (empty($title) || empty($category) || empty($session_date) || empty($session_time) || !$id) {
        $error_message = "Title, category, date, time and valid ID are required.";
    } else {
        try {
            if ($image_path) {
                // Update with new image
                $stmt = $pdo->prepare("UPDATE upcoming_sessions SET image_path = ?, category = ?, title = ?, session_date = ?, session_time = ?, is_active = ? WHERE id = ?");
                $stmt->execute([$image_path, $category, $title, $session_date, $session_time, $is_active, $id]);
            } else {
                // Update without changing image
                $stmt = $pdo->prepare("UPDATE upcoming_sessions SET category = ?, title = ?, session_date = ?, session_time = ?, is_active = ? WHERE id = ?");
                $stmt->execute([$category, $title, $session_date, $session_time, $is_active, $id]);
            }
            $success_message = "Session updated successfully.";
        } catch (PDOException $e) {
            $error_message = "Error updating session: " . $e->getMessage();
        }
    }
}

// Handle delete
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    try {
        $stmt = $pdo->prepare("DELETE FROM upcoming_sessions WHERE id = ?");
        $stmt->execute([$id]);
        $success_message = "Session deleted successfully.";
    } catch (PDOException $e) {
        $error_message = "Error deleting session: " . $e->getMessage();
    }
}

// Fetch all sessions
try {
    $sql = "SELECT * FROM upcoming_sessions ORDER BY session_date ASC, session_time ASC";
    $stmt = $pdo->query($sql);
    $sessions = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Database Fetch Error: " . $e->getMessage());
    $error_message = "Could not retrieve sessions. Please try again later.";
}
?>

<!doctype html>
<html lang="en" dir="ltr">

<head>
    <!-- META DATA -->
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="Manage upcoming sessions on Info Maths Online admin panel.">
    <meta name="keywords" content="upcoming sessions management, sessions, admin panel, Info Maths Online">
    <!-- FAVICON -->
    <link rel="shortcut icon" type="image/x-icon" href="../assets/img/favicon.png">

    <!-- TITLE -->
    <title>Info Maths Online - Upcoming Sessions Management</title>

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
                            <h1 class="page-title">Upcoming Sessions Management</h1>
                            <div>
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Upcoming Sessions</li>
                                </ol>
                            </div>
                        </div>
                        <!-- PAGE-HEADER END -->

                        <!-- Row -->
                        <div class="row row-sm">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Add New Session</h3>
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
                                                    <label>Session Image</label>
                                                    <input type="file" name="session_image" class="form-control" accept="image/*" required>
                                                    <small class="text-muted">Upload JPG, PNG, GIF, or WebP (max 5MB)</small>
                                                </div>
                                                <div class="col-md-4">
                                                    <label>Category</label>
                                                    <input type="text" name="category" class="form-control" placeholder="e.g., After XII, Study Abroad" required>
                                                </div>
                                                <div class="col-md-4">
                                                    <label>Title</label>
                                                    <input type="text" name="title" class="form-control" required>
                                                </div>
                                            </div>
                                            <div class="row mt-3">
                                                <div class="col-md-4">
                                                    <label>Date</label>
                                                    <input type="date" name="session_date" class="form-control" required>
                                                </div>
                                                <div class="col-md-4">
                                                    <label>Time</label>
                                                    <input type="time" name="session_time" class="form-control" required>
                                                </div>
                                                <div class="col-md-4">
                                                    <label>Status</label>
                                                    <div class="form-check mt-4">
                                                        <input type="checkbox" name="is_active" class="form-check-input" id="is_active" checked>
                                                        <label class="form-check-label" for="is_active">Active</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mt-3">
                                                <div class="col-md-12">
                                                    <button type="submit" name="add_session" class="btn btn-success">Add Session</button>
                                                </div>
                                            </div>
                                        </form>

                                        <div class="table-responsive">
                                            <table class="table table-bordered text-nowrap border-bottom" id="basic-datatable">
                                                <thead>
                                                    <tr>
                                                        <th class="wd-5p border-bottom-0">ID</th>
                                                        <th class="wd-15p border-bottom-0">Image</th>
                                                        <th class="wd-15p border-bottom-0">Category</th>
                                                        <th class="wd-20p border-bottom-0">Title</th>
                                                        <th class="wd-15p border-bottom-0">Date & Time</th>
                                                        <th class="wd-10p border-bottom-0">Status</th>
                                                        <th class="wd-15p border-bottom-0">Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    if (!empty($sessions)):
                                                        foreach ($sessions as $session):
                                                    ?>
                                                            <tr>
                                                                <td><?php echo htmlspecialchars($session['id']); ?></td>
                                                                <td>
                                                                    <img src="../<?php echo htmlspecialchars($session['image_path']); ?>" alt="<?php echo htmlspecialchars($session['title']); ?>" style="width: 80px; height: 60px; object-fit: cover; border-radius: 5px;">
                                                                </td>
                                                                <td><?php echo htmlspecialchars($session['category']); ?></td>
                                                                <td><?php echo htmlspecialchars($session['title']); ?></td>
                                                                <td><?php echo date('j M Y', strtotime($session['session_date'])) . '<br>' . date('h:i A', strtotime($session['session_time'])); ?></td>
                                                                <td>
                                                                    <span class=" <?php echo $session['is_active'] ? 'badge-success' : 'badge-danger'; ?>">
                                                                        <?php echo $session['is_active'] ? 'Active' : 'Inactive'; ?>
                                                                    </span>
                                                                </td>
                                                                <td>
                                                                    <a href="#" class="btn btn-primary btn-sm me-1" data-bs-toggle="modal" data-bs-target="#editModal" onclick="editSession(<?php echo $session['id']; ?>, '<?php echo addslashes(htmlspecialchars($session['image_path'])); ?>', '<?php echo addslashes(htmlspecialchars($session['category'])); ?>', '<?php echo addslashes(htmlspecialchars($session['title'])); ?>', '<?php echo htmlspecialchars($session['session_date']); ?>', '<?php echo htmlspecialchars($session['session_time']); ?>', <?php echo $session['is_active']; ?>)">Edit</a>
                                                                    <a href="?delete=<?php echo $session['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</a>
                                                                </td>
                                                            </tr>
                                                        <?php
                                                        endforeach;
                                                    else:
                                                        ?>
                                                        <tr>
                                                            <td colspan="7" class="text-center">No sessions found.</td>
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

        <!-- Edit Session Modal -->
        <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">Edit Session</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form method="post" enctype="multipart/form-data">
                        <div class="modal-body">
                            <input type="hidden" name="edit_session_id" id="edit_session_id">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="edit_session_image" class="form-label">Session Image (Optional - leave empty to keep current)</label>
                                    <input type="file" name="edit_session_image" id="edit_session_image" class="form-control" accept="image/*">
                                    <small class="text-muted">Upload JPG, PNG, GIF, or WebP (max 5MB)</small>
                                    <div id="current_image_preview" class="mt-2"></div>
                                </div>
                                <div class="col-md-6">
                                    <label for="edit_category" class="form-label">Category</label>
                                    <input type="text" name="edit_category" id="edit_category" class="form-control" required>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <label for="edit_title" class="form-label">Title</label>
                                    <input type="text" name="edit_title" id="edit_title" class="form-control" required>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <label for="edit_session_date" class="form-label">Date</label>
                                    <input type="date" name="edit_session_date" id="edit_session_date" class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="edit_session_time" class="form-label">Time</label>
                                    <input type="time" name="edit_session_time" id="edit_session_time" class="form-control" required>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <div class="form-check">
                                        <input type="checkbox" name="edit_is_active" id="edit_is_active" class="form-check-input">
                                        <label class="form-check-label" for="edit_is_active">Active</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" name="edit_session" class="btn btn-primary">Update Session</button>
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

    <script>
        function editSession(id, imagePath, category, title, sessionDate, sessionTime, isActive) {
            document.getElementById('edit_session_id').value = id;
            document.getElementById('edit_category').value = category;
            document.getElementById('edit_title').value = title;
            document.getElementById('edit_session_date').value = sessionDate;
            document.getElementById('edit_session_time').value = sessionTime;
            document.getElementById('edit_is_active').checked = isActive == 1;
            
            // Show current image preview
            const previewDiv = document.getElementById('current_image_preview');
            if (imagePath) {
                previewDiv.innerHTML = '<label class="form-label">Current Image:</label><br><img src="../' + imagePath + '" style="width: 100px; height: 75px; object-fit: cover; border-radius: 5px; border: 1px solid #ddd;">';
            } else {
                previewDiv.innerHTML = '';
            }
        }
    </script>

</body>

</html>