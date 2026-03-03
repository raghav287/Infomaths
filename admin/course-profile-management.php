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

// Handle POST requests for adding profile
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_profile'])) {
    $title = trim($_POST['title']);
    $subtitle = trim($_POST['subtitle']);
    $link = trim($_POST['link']);
    $description = $_POST['description']; // Allow HTML
    $slug = trim($_POST['slug']);
    if (empty($slug)) {
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));
    }
    $meta_title = trim($_POST['meta_title']);
    $meta_keyword = trim($_POST['meta_keyword']);
    $meta_description = trim($_POST['meta_description']);
    $display_order = (int)$_POST['display_order'];
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    
    if (empty($title) || empty($subtitle) || empty($link)) {
        $error_message = "Title, Subtitle, and Link are required.";
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO course_profiles (title, subtitle, link, description, slug, meta_title, meta_keyword, meta_description, display_order, is_active) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$title, $subtitle, $link, $description, $slug, $meta_title, $meta_keyword, $meta_description, $display_order, $is_active]);
            $success_message = "Course Profile added successfully.";
        } catch (PDOException $e) {
            $error_message = "Error saving to database: " . $e->getMessage();
        }
    }
}

// Handle POST requests for editing profile
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_profile'])) {
    $id = (int)$_POST['edit_profile_id'];
    $title = trim($_POST['edit_title']);
    $subtitle = trim($_POST['edit_subtitle']);
    $link = trim($_POST['edit_link']);
    $description = $_POST['edit_description']; // Allow HTML
    $slug = trim($_POST['edit_slug']);
    if (empty($slug)) {
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));
    }
    $meta_title = trim($_POST['edit_meta_title']);
    $meta_keyword = trim($_POST['edit_meta_keyword']);
    $meta_description = trim($_POST['edit_meta_description']);
    $display_order = (int)$_POST['edit_display_order'];
    $is_active = isset($_POST['edit_is_active']) ? 1 : 0;
    
    if (empty($title) || empty($subtitle) || empty($link)) {
        $error_message = "Title, Subtitle, and Link are required.";
    } else {
        try {
            $stmt = $pdo->prepare("UPDATE course_profiles SET title = ?, subtitle = ?, link = ?, description = ?, slug = ?, meta_title = ?, meta_keyword = ?, meta_description = ?, display_order = ?, is_active = ? WHERE id = ?");
            $stmt->execute([$title, $subtitle, $link, $description, $slug, $meta_title, $meta_keyword, $meta_description, $display_order, $is_active, $id]);
            $success_message = "Course Profile updated successfully.";
        } catch (PDOException $e) {
            $error_message = "Error updating database: " . $e->getMessage();
        }
    }
}

// Handle POST requests for deleting profile
if (isset($_GET['delete_id'])) {
    $id = (int)$_GET['delete_id'];
    try {
        $stmt = $pdo->prepare("DELETE FROM course_profiles WHERE id = ?");
        $stmt->execute([$id]);
        $success_message = "Course Profile deleted successfully.";
    } catch (PDOException $e) {
        $error_message = "Error deleting profile: " . $e->getMessage();
    }
}

// Fetch all profiles
try {
    $stmt = $pdo->query("SELECT * FROM course_profiles ORDER BY display_order ASC");
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
    <meta name="description" content="Info Maths Online admin dashboard - Manage Course Profiles.">
    
    <!-- FAVICON -->
    <link rel="shortcut icon" type="image/x-icon" href="../assets/img/favicon.png">

    <!-- TITLE -->
    <title>Info Maths Online - Course Profile Management</title>

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
                            <h1 class="page-title">Course Profile Management</h1>
                            <div>
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="panel">Home</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Course Profiles</li>
                                </ol>
                            </div>
                        </div>

                        <!-- Row -->
                        <div class="row row-sm">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Manage Course Profiles</h3>
                                        <div class="card-options">
                                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProfileModal">
                                                <i class="fe fe-plus"></i> Add New Profile
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
                                                                <td><?php echo htmlspecialchars($profile['link']); ?></td>
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
                                                                        <a href="?delete_id=<?php echo $profile['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this profile?')">
                                                                            <i class="fe fe-trash"></i> Delete
                                                                        </a>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                    <?php else: ?>
                                                        <tr>
                                                            <td colspan="6" class="text-center">No profiles found.</td>
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

        <!-- Add Profile Modal -->
        <div class="modal fade" id="addProfileModal" tabindex="-1" aria-labelledby="addProfileModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="POST" action="">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addProfileModalLabel">Add Course Profile</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="title" class="form-label">Title</label>
                                <input type="text" class="form-control" id="title" name="title" required placeholder="e.g. adWINtage">
                            </div>
                            <div class="mb-3">
                                <label for="subtitle" class="form-label">Subtitle</label>
                                <input type="text" class="form-control" id="subtitle" name="subtitle" required placeholder="e.g. 1 YR INTEGRATED BATCH">
                            </div>
                            <div class="mb-3">
                                <label for="link" class="form-label">Link (Internal URL)</label>
                                <input type="text" class="form-control" id="link" name="link" required placeholder="course-details.php?id=1" value="#">
                                <small class="text-muted">Will be auto-generated if using Slug logic</small>
                            </div>
                            <div class="mb-3">
                                <label for="slug" class="form-label">SEO Slug (URL Friendly)</label>
                                <input type="text" class="form-control" id="slug" name="slug" placeholder="e.g. adwintage-course">
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">Course Description (Inside Page)</label>
                                <textarea class="form-control summernote" id="description" name="description"></textarea>
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

                            <div class="mb-3">
                                <label for="display_order" class="form-label">Display Order</label>
                                <input type="number" class="form-control" id="display_order" name="display_order" value="0">
                            </div>
                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="is_active" name="is_active" checked>
                                <label class="form-check-label" for="is_active">Active</label>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" name="add_profile" class="btn btn-primary">Save Profile</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Edit Profile Modal -->
        <div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="POST" action="">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editProfileModalLabel">Edit Course Profile</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" id="edit_profile_id" name="edit_profile_id">
                            <div class="mb-3">
                                <label for="edit_title" class="form-label">Title</label>
                                <input type="text" class="form-control" id="edit_title" name="edit_title" required>
                            </div>
                            <div class="mb-3">
                                <label for="edit_subtitle" class="form-label">Subtitle</label>
                                <input type="text" class="form-control" id="edit_subtitle" name="edit_subtitle" required>
                            </div>
                            <div class="mb-3">
                                <label for="edit_link" class="form-label">Link (Internal URL)</label>
                                <input type="text" class="form-control" id="edit_link" name="edit_link" required>
                            </div>
                            <div class="mb-3">
                                <label for="edit_slug" class="form-label">SEO Slug</label>
                                <input type="text" class="form-control" id="edit_slug" name="edit_slug">
                            </div>
                            <div class="mb-3">
                                <label for="edit_description" class="form-label">Course Description</label>
                                <textarea class="form-control summernote" id="edit_description" name="edit_description"></textarea>
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

                            <div class="mb-3">
                                <label for="edit_display_order" class="form-label">Display Order</label>
                                <input type="number" class="form-control" id="edit_display_order" name="edit_display_order">
                            </div>
                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="edit_is_active" name="edit_is_active">
                                <label class="form-check-label" for="edit_is_active">Active</label>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" name="edit_profile" class="btn btn-primary">Update Profile</button>
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
            // Initialize Summernote
            initializeCustomSummernote('.summernote');

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
                
                // Retrieve description from hidden textarea
                var description = $('#desc_' + id).val();

                $('#edit_profile_id').val(id);
                $('#edit_title').val(title);
                $('#edit_subtitle').val(subtitle);
                $('#edit_link').val(link);
                $('#edit_slug').val(slug);
                $('#edit_meta_title').val(mTitle);
                $('#edit_meta_keyword').val(mKey);
                $('#edit_meta_description').val(mDesc);
                
                $('#edit_display_order').val(order);
                $('#edit_is_active').prop('checked', active == 1);
                
                // Set and update Summernote
                $('#edit_description').summernote('code', description);
            });
        });
    </script>
</body>
</html>
