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

$courses = []; // Initialize an empty array for the results.
$error_message = ''; // Initialize an empty error message.
$success_message = ''; // For success messages

// Create uploads directory if it doesn't exist
$upload_dir = '../assets/img/courses/';
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
        return 'assets/img/courses/' . $filename;
    } else {
        $error_message = "Failed to save uploaded file.";
        return false;
    }
}

// Handle POST requests for adding course
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_course'])) {
    $tab = trim($_POST['tab']);
    $category = trim($_POST['category']);
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    
    // Handle image upload
    $image_path = uploadImage($_FILES['course_image'] ?? []);
    if ($image_path === false) {
        // Error already set in uploadImage function
    } elseif (empty($title) || empty($category) || empty($tab)) {
        $error_message = "Title, category, and tab are required.";
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO courses (tab, category, title, description, image_path, is_active) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$tab, $category, $title, $description, $image_path, $is_active]);
            $success_message = "Course added successfully.";
        } catch (PDOException $e) {
            $error_message = "Error saving to database: " . $e->getMessage();
        }
    }
}

// Handle POST requests for editing course
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_course'])) {
    $id = (int)$_POST['edit_course_id'];
    $tab = trim($_POST['edit_tab']);
    $category = trim($_POST['edit_category']);
    $title = trim($_POST['edit_title']);
    $description = trim($_POST['edit_description']);
    $is_active = isset($_POST['edit_is_active']) ? 1 : 0;
    
    // Handle image upload (optional for edit)
    $image_path = uploadImage($_FILES['edit_course_image'] ?? []);
    if ($image_path === false) {
        // Error already set, don't proceed
    } elseif (empty($title) || empty($category) || empty($tab) || !$id) {
        $error_message = "Title, category, tab and valid ID are required.";
    } else {
        try {
            if ($image_path) {
                // Update with new image
                $stmt = $pdo->prepare("UPDATE courses SET tab = ?, category = ?, title = ?, description = ?, image_path = ?, is_active = ? WHERE id = ?");
                $stmt->execute([$tab, $category, $title, $description, $image_path, $is_active, $id]);
            } else {
                // Update without changing image
                $stmt = $pdo->prepare("UPDATE courses SET tab = ?, category = ?, title = ?, description = ?, is_active = ? WHERE id = ?");
                $stmt->execute([$tab, $category, $title, $description, $is_active, $id]);
            }
            $success_message = "Course updated successfully.";
        } catch (PDOException $e) {
            $error_message = "Error updating course: " . $e->getMessage();
        }
    }
}

// Handle delete
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    try {
        $stmt = $pdo->prepare("DELETE FROM courses WHERE id = ?");
        $stmt->execute([$id]);
        $success_message = "Course deleted successfully.";
    } catch (PDOException $e) {
        $error_message = "Error deleting course: " . $e->getMessage();
    }
}

// Fetch all courses
try {
    $sql = "SELECT * FROM courses ORDER BY tab ASC, created_at DESC";
    $stmt = $pdo->query($sql);
    $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Database Fetch Error: " . $e->getMessage());
    $error_message = "Could not retrieve courses. Please try again later.";
}
?>

<!doctype html>
<html lang="en" dir="ltr">

<head>
    <!-- META DATA -->
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="Manage courses on Info Maths Online admin panel.">
    <meta name="keywords" content="courses management, courses, admin panel, Info Maths Online">
    <!-- FAVICON -->
    <link rel="shortcut icon" type="image/x-icon" href="../assets/img/favicon.png">

    <!-- TITLE -->
    <title>Info Maths Online - Courses Management</title>

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
                            <h1 class="page-title">Courses Management</h1>
                            <div>
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Courses</li>
                                </ol>
                            </div>
                        </div>
                        <!-- PAGE-HEADER END -->

                        <!-- Row -->
                        <div class="row row-sm">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Add New Course</h3>
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
                                                <div class="col-md-3">
                                                    <label>Course Image</label>
                                                    <input type="file" name="course_image" class="form-control" accept="image/*" required>
                                                    <small class="text-muted">Upload JPG, PNG, GIF, or WebP (max 5MB)</small>
                                                </div>
                                                <div class="col-md-3">
                                                    <label>Tab</label>
                                                    <input type="text" name="tab" class="form-control" placeholder="e.g., MCA Entrance" required>
                                                </div>
                                                <div class="col-md-3">
                                                    <label>Category</label>
                                                    <input type="text" name="category" class="form-control" placeholder="e.g., Data Analytics" required>
                                                </div>
                                                <div class="col-md-3">
                                                    <label>Title</label>
                                                    <input type="text" name="title" class="form-control" required>
                                                </div>
                                            </div>
                                            <div class="row mt-3">
                                                <div class="col-md-9">
                                                    <label>Description</label>
                                                    <textarea name="description" class="form-control" rows="3"></textarea>
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
                                                <div class="col-md-12">
                                                    <button type="submit" name="add_course" class="btn btn-success">Add Course</button>
                                                </div>
                                            </div>
                                        </form>

                                        <div class="table-responsive">
                                            <table class="table table-bordered text-nowrap border-bottom" id="basic-datatable">
                                                <thead>
                                                    <tr>
                                                        <th class="wd-5p border-bottom-0">ID</th>
                                                        <th class="wd-10p border-bottom-0">Image</th>
                                                        <th class="wd-10p border-bottom-0">Tab</th>
                                                        <th class="wd-15p border-bottom-0">Category</th>
                                                        <th class="wd-20p border-bottom-0">Title</th>
                                                        <th class="wd-25p border-bottom-0">Description</th>
                                                        <th class="wd-5p border-bottom-0">Status</th>
                                                        <th class="wd-10p border-bottom-0">Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    if (!empty($courses)):
                                                        foreach ($courses as $course):
                                                    ?>
                                                            <tr>
                                                                <td><?php echo htmlspecialchars($course['id']); ?></td>
                                                                <td>
                                                                    <img src="../<?php echo htmlspecialchars($course['image_path']); ?>" alt="<?php echo htmlspecialchars($course['title']); ?>" style="width: 60px; height: 45px; object-fit: cover; border-radius: 5px;">
                                                                </td>
                                                                <td><?php echo htmlspecialchars($course['tab']); ?></td>
                                                                <td><?php echo htmlspecialchars($course['category']); ?></td>
                                                                <td><?php echo htmlspecialchars($course['title']); ?></td>
                                                                <td><?php echo htmlspecialchars(substr($course['description'], 0, 50)) . (strlen($course['description']) > 50 ? '...' : ''); ?></td>
                                                                <td>
                                                                    <span class=" <?php echo $course['is_active'] ? 'badge-success' : 'badge-danger'; ?>">
                                                                        <?php echo $course['is_active'] ? 'Active' : 'Inactive'; ?>
                                                                    </span>
                                                                </td>
                                                                <td>
                                                                    <a href="#" class="btn btn-primary btn-sm me-1" data-bs-toggle="modal" data-bs-target="#editModal" onclick="editCourse(<?php echo $course['id']; ?>, '<?php echo addslashes(htmlspecialchars($course['image_path'])); ?>', '<?php echo addslashes(htmlspecialchars($course['tab'])); ?>', '<?php echo addslashes(htmlspecialchars($course['category'])); ?>', '<?php echo addslashes(htmlspecialchars($course['title'])); ?>', '<?php echo addslashes(htmlspecialchars($course['description'])); ?>', <?php echo $course['is_active']; ?>)">Edit</a>
                                                                    <a href="?delete=<?php echo $course['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</a>
                                                                </td>
                                                            </tr>
                                                        <?php
                                                        endforeach;
                                                    else:
                                                        ?>
                                                        <tr>
                                                            <td colspan="8" class="text-center">No courses found.</td>
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

        <!-- Edit Course Modal -->
        <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">Edit Course</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form method="post" enctype="multipart/form-data">
                        <div class="modal-body">
                            <input type="hidden" name="edit_course_id" id="edit_course_id">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="edit_course_image" class="form-label">Course Image (Optional - leave empty to keep current)</label>
                                    <input type="file" name="edit_course_image" id="edit_course_image" class="form-control" accept="image/*">
                                    <small class="text-muted">Upload JPG, PNG, GIF, or WebP (max 5MB)</small>
                                    <div id="current_image_preview" class="mt-2"></div>
                                </div>
                                <div class="col-md-6">
                                    <label for="edit_tab" class="form-label">Tab</label>
                                    <input type="text" name="edit_tab" id="edit_tab" class="form-control" placeholder="e.g., MCA Entrance" required>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <label for="edit_category" class="form-label">Category</label>
                                    <input type="text" name="edit_category" id="edit_category" class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="edit_title" class="form-label">Title</label>
                                    <input type="text" name="edit_title" id="edit_title" class="form-control" required>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-9">
                                    <label for="edit_description" class="form-label">Description</label>
                                    <textarea name="edit_description" id="edit_description" class="form-control" rows="3"></textarea>
                                </div>
                                <div class="col-md-3">
                                    <label for="edit_is_active" class="form-label">Active</label>
                                    <div class="form-check mt-4">
                                        <input type="checkbox" name="edit_is_active" id="edit_is_active" class="form-check-input">
                                        <label class="form-check-label" for="edit_is_active">Active</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" name="edit_course" class="btn btn-primary">Update Course</button>
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
        function editCourse(id, imagePath, tab, category, title, description, isActive) {
            document.getElementById('edit_course_id').value = id;
            document.getElementById('edit_tab').value = tab;
            document.getElementById('edit_category').value = category;
            document.getElementById('edit_title').value = title;
            document.getElementById('edit_description').value = description;
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