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

$testimonials = []; // Initialize an empty array for the results.
$error_message = ''; // Initialize an empty error message.
$success_message = ''; // For success messages

// Handle POST requests for adding testimonial
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_testimonial'])) {
    $name = trim($_POST['name']);
    $content = trim($_POST['content']);
    $designation = trim($_POST['designation']);

    $image_path = null;
    if (isset($_FILES['testimonial_image']) && $_FILES['testimonial_image']['error'] == 0) {
        $file_name = $_FILES['testimonial_image']['name'];
        $file_tmp = $_FILES['testimonial_image']['tmp_name'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        $allowed_exts = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

        if (in_array($file_ext, $allowed_exts)) {
            $new_file_name = uniqid() . '.' . $file_ext;
            $upload_path = '../assets/testimonials/' . $new_file_name;

            if (move_uploaded_file($file_tmp, $upload_path)) {
                $image_path = $new_file_name;
            } else {
                $error_message = "Error uploading image file.";
            }
        } else {
            $error_message = "Invalid image file type. Allowed: JPG, JPEG, PNG, GIF, WEBP.";
        }
    }

    if ($name && $content && empty($error_message)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO testimonials (name, content, designation, image_path) VALUES (?, ?, ?, ?)");
            $stmt->execute([$name, $content, $designation, $image_path]);
            $success_message = "Testimonial added successfully.";
        } catch (PDOException $e) {
            $error_message = "Error saving to database: " . $e->getMessage();
        }
    } else if (empty($error_message)) {
        $error_message = "Name and content are required.";
    }
}

// Handle POST requests for editing testimonial
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_testimonial'])) {
    $id = (int)$_POST['edit_testimonial_id'];
    $name = trim($_POST['edit_name']);
    $content = trim($_POST['edit_content']);
    $designation = trim($_POST['edit_designation']);

    if ($name && $content && $id) {
        $image_path = null;
        if (isset($_FILES['edit_testimonial_image']) && $_FILES['edit_testimonial_image']['error'] == 0) {
            $file_name = $_FILES['edit_testimonial_image']['name'];
            $file_tmp = $_FILES['edit_testimonial_image']['tmp_name'];
            $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

            $allowed_exts = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

            if (in_array($file_ext, $allowed_exts)) {
                $new_file_name = uniqid() . '.' . $file_ext;
                $upload_path = '../assets/testimonials/' . $new_file_name;

                if (move_uploaded_file($file_tmp, $upload_path)) {
                    // Delete old image if exists
                    $stmt = $pdo->prepare("SELECT image_path FROM testimonials WHERE id = ?");
                    $stmt->execute([$id]);
                    $old_image = $stmt->fetch();
                    if ($old_image && $old_image['image_path']) {
                        unlink('../assets/testimonials/' . $old_image['image_path']);
                    }
                    $image_path = $new_file_name;
                } else {
                    $error_message = "Error uploading image file.";
                }
            } else {
                $error_message = "Invalid image file type. Allowed: JPG, JPEG, PNG, GIF, WEBP.";
            }
        }

        if (empty($error_message)) {
            try {
                if ($image_path) {
                    $stmt = $pdo->prepare("UPDATE testimonials SET name = ?, content = ?, designation = ?, image_path = ? WHERE id = ?");
                    $stmt->execute([$name, $content, $designation, $image_path, $id]);
                } else {
                    $stmt = $pdo->prepare("UPDATE testimonials SET name = ?, content = ?, designation = ? WHERE id = ?");
                    $stmt->execute([$name, $content, $designation, $id]);
                }
                $success_message = "Testimonial updated successfully.";
            } catch (PDOException $e) {
                $error_message = "Error updating testimonial: " . $e->getMessage();
            }
        }
    } else {
        $error_message = "Name, content and valid ID are required.";
    }
}

// Handle delete
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    try {
        $stmt = $pdo->prepare("SELECT image_path FROM testimonials WHERE id = ?");
        $stmt->execute([$id]);
        $testimonial = $stmt->fetch();
        if ($testimonial && $testimonial['image_path']) {
            unlink('../assets/testimonials/' . $testimonial['image_path']);
        }
        $stmt = $pdo->prepare("DELETE FROM testimonials WHERE id = ?");
        $stmt->execute([$id]);
        $success_message = "Testimonial deleted successfully.";
    } catch (PDOException $e) {
        $error_message = "Error deleting testimonial: " . $e->getMessage();
    }
}

// Fetch all testimonials
try {
    $sql = "SELECT * FROM testimonials ORDER BY created_at DESC";
    $stmt = $pdo->query($sql);
    $testimonials = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Database Fetch Error: " . $e->getMessage());
    $error_message = "Could not retrieve testimonials. Please try again later.";
}
?>

<!doctype html>
<html lang="en" dir="ltr">

<head>
    <!-- META DATA -->
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="Manage text testimonials on Info Maths Online admin panel.">
    <meta name="keywords" content="testimonials management, student testimonials, admin panel, Info Maths Online">
    <!-- FAVICON -->
    <link rel="shortcut icon" type="image/x-icon" href="../assets/img/favicon.png">

    <!-- TITLE -->
    <title>Info Maths Online - Testimonials Management</title>

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
                            <h1 class="page-title">Testimonials Management</h1>
                            <div>
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Testimonials</li>
                                </ol>
                            </div>
                        </div>
                        <!-- PAGE-HEADER END -->

                        <!-- Row -->
                        <div class="row row-sm">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Add New Testimonial</h3>
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
                                                    <label>Name</label>
                                                    <input type="text" name="name" class="form-control" required>
                                                </div>
                                                <div class="col-md-3">
                                                    <label>Designation (Optional)</label>
                                                    <input type="text" name="designation" class="form-control" placeholder="e.g., Student, IIT Aspirant">
                                                </div>
                                                <div class="col-md-3">
                                                    <label>Profile Image</label>
                                                    <input type="file" name="testimonial_image" class="form-control" accept=".jpg,.jpeg,.png,.gif,.webp">
                                                    <small class="text-muted">Optional: JPG, JPEG, PNG, GIF, WEBP</small>
                                                </div>
                                            </div>
                                            <div class="row mt-3">
                                                <div class="col-md-12">
                                                    <label>Content</label>
                                                    <textarea name="content" class="form-control" rows="4" required></textarea>
                                                </div>
                                            </div>
                                            <div class="row mt-3">
                                                <div class="col-md-12">
                                                    <button type="submit" name="add_testimonial" class="btn btn-success">Add Testimonial</button>
                                                </div>
                                            </div>
                                        </form>

                                        <div class="table-responsive">
                                            <table class="table table-bordered text-nowrap border-bottom" id="basic-datatable">
                                                <thead>
                                                    <tr>
                                                        <th class="wd-5p border-bottom-0">ID</th>
                                                        <th class="wd-10p border-bottom-0">Image</th>
                                                        <th class="wd-15p border-bottom-0">Name</th>
                                                        <th class="wd-10p border-bottom-0">Designation</th>
                                                        <th class="wd-35p border-bottom-0">Content</th>
                                                        <th class="wd-10p border-bottom-0">Created At</th>
                                                        <th class="wd-10p border-bottom-0">Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    if (!empty($testimonials)):
                                                        foreach ($testimonials as $testimonial):
                                                    ?>
                                                            <tr>
                                                                <td><?php echo htmlspecialchars($testimonial['id']); ?></td>
                                                                <td>
                                                                    <?php if ($testimonial['image_path']): ?>
                                                                        <img src="../assets/testimonials/<?php echo htmlspecialchars($testimonial['image_path']); ?>" alt="<?php echo htmlspecialchars($testimonial['name']); ?>" style="width: 60px; height: 60px; object-fit: cover; border-radius: 50%;">
                                                                    <?php else: ?>
                                                                        <img src="https://www.cgc.edu.in/assets/images/testimonials/default-avatar.webp" alt="Default Avatar" style="width: 60px; height: 60px; object-fit: cover; border-radius: 50%;">
                                                                    <?php endif; ?>
                                                                </td>
                                                                <td><?php echo htmlspecialchars($testimonial['name']); ?></td>
                                                                <td><?php echo htmlspecialchars($testimonial['designation']); ?></td>
                                                                <td><?php echo htmlspecialchars(substr($testimonial['content'], 0, 80)) . (strlen($testimonial['content']) > 80 ? '...' : ''); ?></td>
                                                                <td><?php echo date('j M Y', strtotime($testimonial['created_at'])); ?></td>
                                                                <td>
                                                                    <a href="#" class="btn btn-primary btn-sm me-1" data-bs-toggle="modal" data-bs-target="#editModal" onclick="editTestimonial(<?php echo $testimonial['id']; ?>, '<?php echo addslashes(htmlspecialchars($testimonial['name'])); ?>', '<?php echo addslashes(htmlspecialchars($testimonial['designation'])); ?>', '<?php echo addslashes(htmlspecialchars($testimonial['content'])); ?>', '<?php echo htmlspecialchars($testimonial['image_path']); ?>')">Edit</a>
                                                                    <a href="?delete=<?php echo $testimonial['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</a>
                                                                </td>
                                                            </tr>
                                                        <?php
                                                        endforeach;
                                                    else:
                                                        ?>
                                                        <tr>
                                                            <td colspan="7" class="text-center">No testimonials found.</td>
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

        <!-- Edit Testimonial Modal -->
        <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">Edit Testimonial</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form method="post" enctype="multipart/form-data">
                        <div class="modal-body">
                            <input type="hidden" name="edit_testimonial_id" id="edit_testimonial_id">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="edit_name" class="form-label">Name</label>
                                    <input type="text" name="edit_name" id="edit_name" class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="edit_designation" class="form-label">Designation (Optional)</label>
                                    <input type="text" name="edit_designation" id="edit_designation" class="form-control" placeholder="e.g., Student, IIT Aspirant">
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <label for="edit_image" class="form-label">Profile Image (Optional)</label>
                                    <input type="file" name="edit_testimonial_image" id="edit_image" class="form-control" accept=".jpg,.jpeg,.png,.gif,.webp">
                                    <small class="text-muted">Leave empty to keep current image. JPG, JPEG, PNG, GIF, WEBP</small>
                                    <div id="current_image_container" class="mt-2">
                                        <img id="current_image" src="" alt="Current Image" style="width: 60px; height: 60px; object-fit: cover; border-radius: 50%; display: none;">
                                        <small id="current_image_text" class="text-muted"></small>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <label for="edit_content" class="form-label">Content</label>
                                    <textarea name="edit_content" id="edit_content" class="form-control" rows="4" required></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" name="edit_testimonial" class="btn btn-primary">Update Testimonial</button>
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
        function editTestimonial(id, name, designation, content, imagePath) {
            document.getElementById('edit_testimonial_id').value = id;
            document.getElementById('edit_name').value = name;
            document.getElementById('edit_designation').value = designation;
            document.getElementById('edit_content').value = content;

            const currentImage = document.getElementById('current_image');
            const currentImageText = document.getElementById('current_image_text');

            if (imagePath) {
                currentImage.src = '../assets/testimonials/' + imagePath;
                currentImage.style.display = 'inline-block';
                currentImageText.textContent = 'Current image will be replaced if you upload a new one.';
            } else {
                currentImage.style.display = 'none';
                currentImageText.textContent = 'No image uploaded yet.';
            }
        }
    </script>

</body>

</html>
