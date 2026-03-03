<?php
// Initialize the session
session_start();

// Check if the user is logged in.
if (!isset($_SESSION["admin_logged_in"]) || $_SESSION["admin_logged_in"] !== true) {
    header("location: login");
    exit;
}

require_once '../database.php';

if ($pdo === null) {
    die("Database connection failed.");
}

$reviews = [];
$error_message = '';
$success_message = '';

// Handle Image Upload
function uploadImage($file) {
    $target_dir = "../assets/img/im/"; // Specific path for alumni images
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    
    $file_extension = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
    $new_filename = uniqid() . '.' . $file_extension;
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
    if(!in_array($file_extension, ['jpg', 'png', 'jpeg', 'gif', 'webp'])) {
        return ['success' => false, 'message' => "Sorry, only JPG, JPEG, PNG, GIF & WEBP files are allowed."];
    }
    
    if (move_uploaded_file($file["tmp_name"], $target_file)) {
        return ['success' => true, 'filename' => $new_filename];
    } else {
        return ['success' => false, 'message' => "Sorry, there was an error uploading your file."];
    }
}

// Handle ADD
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_review'])) {
    $name = trim($_POST['name']);
    $role = trim($_POST['role']);
    $quote = trim($_POST['quote']);
    $display_order = (int)$_POST['display_order'];
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    $image_path = ''; // Default placeholder if needed, or enforce upload
    
    if (!empty($_FILES['image']['name'])) {
        $upload_result = uploadImage($_FILES['image']);
        if ($upload_result['success']) {
            $image_path = $upload_result['filename'];
        } else {
            $error_message = $upload_result['message'];
        }
    }
    
    if (empty($error_message)) {
        if (empty($name) || empty($quote)) {
            $error_message = "Name and Quote are required.";
        } else {
            try {
                $stmt = $pdo->prepare("INSERT INTO alumni_reviews (name, role, quote, image_path, display_order, is_active) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->execute([$name, $role, $quote, $image_path, $display_order, $is_active]);
                $success_message = "Review added successfully.";
            } catch (PDOException $e) {
                $error_message = "Error: " . $e->getMessage();
            }
        }
    }
}

// Handle EDIT
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_review'])) {
    $id = (int)$_POST['edit_id'];
    $name = trim($_POST['edit_name']);
    $role = trim($_POST['edit_role']);
    $quote = trim($_POST['edit_quote']);
    $display_order = (int)$_POST['edit_display_order'];
    $is_active = isset($_POST['edit_is_active']) ? 1 : 0;
    
    // Check if new image uploaded
    if (!empty($_FILES['edit_image']['name'])) {
        $upload_result = uploadImage($_FILES['edit_image']);
        if ($upload_result['success']) {
            $new_image_path = $upload_result['filename'];
            // Update with new image
            try {
                $stmt = $pdo->prepare("UPDATE alumni_reviews SET name=?, role=?, quote=?, image_path=?, display_order=?, is_active=? WHERE id=?");
                $stmt->execute([$name, $role, $quote, $new_image_path, $display_order, $is_active, $id]);
                $success_message = "Review updated successfully (Image Updated).";
            } catch (PDOException $e) {
                $error_message = "Error: " . $e->getMessage();
            }
        } else {
            $error_message = $upload_result['message'];
        }
    } else {
        // Update without changing image
        try {
            $stmt = $pdo->prepare("UPDATE alumni_reviews SET name=?, role=?, quote=?, display_order=?, is_active=? WHERE id=?");
            $stmt->execute([$name, $role, $quote, $display_order, $is_active, $id]);
            $success_message = "Review updated successfully.";
        } catch (PDOException $e) {
            $error_message = "Error: " . $e->getMessage();
        }
    }
}

// Handle DELETE
if (isset($_GET['delete_id'])) {
    $id = (int)$_GET['delete_id'];
    try {
        $stmt = $pdo->prepare("DELETE FROM alumni_reviews WHERE id = ?");
        $stmt->execute([$id]);
        $success_message = "Review deleted successfully.";
    } catch (PDOException $e) {
        $error_message = "Error deleting: " . $e->getMessage();
    }
}

// Fetch Reviews
try {
    $stmt = $pdo->query("SELECT * FROM alumni_reviews ORDER BY display_order ASC");
    $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error_message = "Error fetching data: " . $e->getMessage();
}
?>

<!doctype html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
    <title>Alumni Reviews Management</title>
    <link id="style" href="assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <link href="assets/css/plugins.css" rel="stylesheet">
    <link href="assets/css/icons.css" rel="stylesheet">
</head>
<body class="app sidebar-mini ltr light-mode">
    <div class="page">
        <div class="page-main">
            <?php include 'assets/header.php'; ?>
            <?php include 'assets/sidebar.php'; ?>
            
            <div class="main-content app-content mt-0">
                <div class="side-app">
                    <div class="main-container container-fluid">
                        <div class="page-header">
                            <h1 class="page-title">Alumni Reviews Management</h1>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="panel">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Alumni Reviews</li>
                            </ol>
                        </div>
                        
                        <div class="row row-sm">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Manage Reviews</h3>
                                        <div class="card-options">
                                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
                                                <i class="fe fe-plus"></i> Add New Review
                                            </button>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <?php if ($error_message): ?>
                                            <div class="alert alert-danger"><?php echo $error_message; ?></div>
                                        <?php endif; ?>
                                        <?php if ($success_message): ?>
                                            <div class="alert alert-success"><?php echo $success_message; ?></div>
                                        <?php endif; ?>
                                        
                                        <div class="table-responsive">
                                            <table class="table table-bordered text-nowrap border-bottom" id="basic-datatable">
                                                <thead>
                                                    <tr>
                                                        <th>Order</th>
                                                        <th>Image</th>
                                                        <th>Name</th>
                                                        <th>Role/Rank</th>
                                                        <th>Quote</th>
                                                        <th>Status</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($reviews as $review): ?>
                                                        <tr>
                                                            <td><?php echo $review['display_order']; ?></td>
                                                            <td>
                                                                <?php if($review['image_path']): ?>
                                                                    <img src="../assets/img/im/<?php echo htmlspecialchars($review['image_path']); ?>" style="width: 50px; height: 50px; object-fit: cover; border-radius: 50%;">
                                                                <?php else: ?>
                                                                    <span class="text-muted">No Image</span>
                                                                <?php endif; ?>
                                                            </td>
                                                            <td><?php echo htmlspecialchars($review['name']); ?></td>
                                                            <td><?php echo htmlspecialchars($review['role']); ?></td>
                                                            <td><?php echo substr(htmlspecialchars($review['quote']), 0, 50) . '...'; ?></td>
                                                            <td>
                                                                <?php if ($review['is_active']): ?>
                                                                    <span class="badge bg-success">Active</span>
                                                                <?php else: ?>
                                                                    <span class="badge bg-danger">Inactive</span>
                                                                <?php endif; ?>
                                                            </td>
                                                            <td>
                                                                <button class="btn btn-warning btn-sm edit-btn"
                                                                    data-id="<?php echo $review['id']; ?>"
                                                                    data-name="<?php echo htmlspecialchars($review['name']); ?>"
                                                                    data-role="<?php echo htmlspecialchars($review['role']); ?>"
                                                                    data-quote="<?php echo htmlspecialchars($review['quote']); ?>"
                                                                    data-order="<?php echo $review['display_order']; ?>"
                                                                    data-active="<?php echo $review['is_active']; ?>"
                                                                    data-bs-toggle="modal" data-bs-target="#editModal">
                                                                    <i class="fe fe-edit"></i> Edit
                                                                </button>
                                                                <a href="?delete_id=<?php echo $review['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this review?')">
                                                                    <i class="fe fe-trash"></i> Delete
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
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
            
            <?php include 'assets/footer.php'; ?>
        </div>
    </div>

    <!-- Add Modal -->
    <div class="modal fade" id="addModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Review</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Role / Rank</label>
                            <input type="text" class="form-control" name="role" placeholder="e.g. Rank 1 - NIMCET">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Quote</label>
                            <textarea class="form-control" name="quote" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Image</label>
                            <input type="file" class="form-control" name="image" accept="image/*">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Display Order</label>
                            <input type="number" class="form-control" name="display_order" value="0">
                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" name="is_active" checked>
                            <label class="form-check-label">Active</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" name="add_review" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Review</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="edit_id" id="edit_id">
                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" class="form-control" name="edit_name" id="edit_name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Role / Rank</label>
                            <input type="text" class="form-control" name="edit_role" id="edit_role">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Quote</label>
                            <textarea class="form-control" name="edit_quote" id="edit_quote" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Change Image (Optional)</label>
                            <input type="file" class="form-control" name="edit_image" accept="image/*">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Display Order</label>
                            <input type="number" class="form-control" name="edit_display_order" id="edit_display_order">
                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" name="edit_is_active" id="edit_is_active">
                            <label class="form-check-label">Active</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" name="edit_review" class="btn btn-primary">Update</button>
                    </div>
<head>
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
    <title>Alumni Reviews Management</title>
    <link id="style" href="assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <link href="assets/css/plugins.css" rel="stylesheet">
    <link href="assets/css/icons.css" rel="stylesheet">
</head>
<body class="app sidebar-mini ltr light-mode">
    <div class="page">
        <div class="page-main">
            <?php include 'assets/header.php'; ?>
            <?php include 'assets/sidebar.php'; ?>
            
            <div class="main-content app-content mt-0">
                <div class="side-app">
                    <div class="main-container container-fluid">
                        <div class="page-header">
                            <h1 class="page-title">Alumni Reviews Management</h1>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="panel">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Alumni Reviews</li>
                            </ol>
                        </div>
                        
                        <div class="row row-sm">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Manage Reviews</h3>
                                        <div class="card-options">
                                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
                                                <i class="fe fe-plus"></i> Add New Review
                                            </button>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <?php if ($error_message): ?>
                                            <div class="alert alert-danger"><?php echo $error_message; ?></div>
                                        <?php endif; ?>
                                        <?php if ($success_message): ?>
                                            <div class="alert alert-success"><?php echo $success_message; ?></div>
                                        <?php endif; ?>
                                        
                                        <div class="table-responsive">
                                            <table class="table table-bordered text-nowrap border-bottom" id="basic-datatable">
                                                <thead>
                                                    <tr>
                                                        <th>Order</th>
                                                        <th>Image</th>
                                                        <th>Name</th>
                                                        <th>Role/Rank</th>
                                                        <th>Quote</th>
                                                        <th>Status</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($reviews as $review): ?>
                                                        <tr>
                                                            <td><?php echo $review['display_order']; ?></td>
                                                            <td>
                                                                <?php if($review['image_path']): ?>
                                                                    <img src="../assets/img/im/<?php echo htmlspecialchars($review['image_path']); ?>" style="width: 50px; height: 50px; object-fit: cover; border-radius: 50%;">
                                                                <?php else: ?>
                                                                    <span class="text-muted">No Image</span>
                                                                <?php endif; ?>
                                                            </td>
                                                            <td><?php echo htmlspecialchars($review['name']); ?></td>
                                                            <td><?php echo htmlspecialchars($review['role']); ?></td>
                                                            <td><?php echo substr(htmlspecialchars($review['quote']), 0, 50) . '...'; ?></td>
                                                            <td>
                                                                <?php if ($review['is_active']): ?>
                                                                    <span class="badge bg-success">Active</span>
                                                                <?php else: ?>
                                                                    <span class="badge bg-danger">Inactive</span>
                                                                <?php endif; ?>
                                                            </td>
                                                            <td>
                                                                <button class="btn btn-warning btn-sm edit-btn"
                                                                    data-id="<?php echo $review['id']; ?>"
                                                                    data-name="<?php echo htmlspecialchars($review['name']); ?>"
                                                                    data-role="<?php echo htmlspecialchars($review['role']); ?>"
                                                                    data-quote="<?php echo htmlspecialchars($review['quote']); ?>"
                                                                    data-order="<?php echo $review['display_order']; ?>"
                                                                    data-active="<?php echo $review['is_active']; ?>"
                                                                    data-bs-toggle="modal" data-bs-target="#editModal">
                                                                    <i class="fe fe-edit"></i> Edit
                                                                </button>
                                                                <a href="?delete_id=<?php echo $review['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this review?')">
                                                                    <i class="fe fe-trash"></i> Delete
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
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
            
            <?php include 'assets/footer.php'; ?>
        </div>
    </div>

    <!-- Add Modal -->
    <div class="modal fade" id="addModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Review</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Role / Rank</label>
                            <input type="text" class="form-control" name="role" placeholder="e.g. Rank 1 - NIMCET">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Quote</label>
                            <textarea class="form-control" name="quote" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Image</label>
                            <input type="file" class="form-control" name="image" accept="image/*">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Display Order</label>
                            <input type="number" class="form-control" name="display_order" value="0">
                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" name="is_active" checked>
                            <label class="form-check-label">Active</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" name="add_review" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Review</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="edit_id" id="edit_id">
                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" class="form-control" name="edit_name" id="edit_name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Role / Rank</label>
                            <input type="text" class="form-control" name="edit_role" id="edit_role">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Quote</label>
                            <textarea class="form-control" name="edit_quote" id="edit_quote" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Change Image (Optional)</label>
                            <input type="file" class="form-control" name="edit_image" accept="image/*">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Display Order</label>
                            <input type="number" class="form-control" name="edit_display_order" id="edit_display_order">
                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" name="edit_is_active" id="edit_is_active">
                            <label class="form-check-label">Active</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" name="edit_review" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

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
                $('#edit_id').val($(this).data('id'));
                $('#edit_name').val($(this).data('name'));
                $('#edit_role').val($(this).data('role'));
                $('#edit_quote').val($(this).data('quote'));
                $('#edit_display_order').val($(this).data('order'));
                $('#edit_is_active').prop('checked', $(this).data('active') == 1);
            });
        });
    </script>
</body>
</html>

