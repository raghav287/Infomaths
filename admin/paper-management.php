<?php
session_start();
require_once '../database.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit();
}

// Handle Form Submission (Add/Edit Paper)
$success_message = '';
$error_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // ADD PAPER
    if (isset($_POST['add_paper'])) {
        $category_id = $_POST['category_id'];
        $title = $_POST['title'];
        $type = $_POST['type'];
        $display_order = $_POST['display_order'];

        // Handle Image Upload
        $image_path = '';
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $target_dir = "../assets/img/im/";
            if (!file_exists($target_dir)) mkdir($target_dir, 0777, true);
            $file_extension = pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);
            $new_filename = uniqid() . '.' . $file_extension;
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_dir . $new_filename)) {
                $image_path = "assets/img/im/" . $new_filename;
            }
        }

        // Handle PDF Upload
        $pdf_file = '';
        if (isset($_FILES['pdf_file']) && $_FILES['pdf_file']['error'] == 0) {
            $target_dir = "../assets/papers/";
            if (!file_exists($target_dir)) mkdir($target_dir, 0777, true);
            
            $file_extension = pathinfo($_FILES["pdf_file"]["name"], PATHINFO_EXTENSION);
            $new_filename = uniqid() . '_paper.' . $file_extension;
            if (move_uploaded_file($_FILES["pdf_file"]["tmp_name"], $target_dir . $new_filename)) {
                $pdf_file = "assets/papers/" . $new_filename;
            }
        }

        try {
            $stmt = $pdo->prepare("INSERT INTO university_papers (category_id, title, image_path, pdf_file, type, display_order) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$category_id, $title, $image_path, $pdf_file, $type, $display_order]);
            $success_message = "Paper added successfully!";
        } catch (PDOException $e) {
            $error_message = "Database Error: " . $e->getMessage();
        }
    }

    // EDIT PAPER
    if (isset($_POST['edit_paper'])) {
        $id = $_POST['edit_paper_id'];
        $category_id = $_POST['category_id'];
        $title = $_POST['title'];
        $type = $_POST['type'];
        $display_order = $_POST['display_order'];

        // Handle Image Update
        $image_sql_part = "";
        $params = [$category_id, $title, $type, $display_order];

        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $target_dir = "../assets/img/im/";
            if (!file_exists($target_dir)) mkdir($target_dir, 0777, true);
            $file_extension = pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);
            $new_filename = uniqid() . '.' . $file_extension;
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_dir . $new_filename)) {
                $image_path = "assets/img/im/" . $new_filename;
                $image_sql_part = ", image_path = ?";
                $params[] = $image_path;
            }
        }

        // Handle PDF Update
        $pdf_sql_part = "";
        if (isset($_FILES['pdf_file']) && $_FILES['pdf_file']['error'] == 0) {
            $target_dir = "../assets/papers/";
            if (!file_exists($target_dir)) mkdir($target_dir, 0777, true);
            
            $file_extension = pathinfo($_FILES["pdf_file"]["name"], PATHINFO_EXTENSION);
            $new_filename = uniqid() . '_paper.' . $file_extension;
            if (move_uploaded_file($_FILES["pdf_file"]["tmp_name"], $target_dir . $new_filename)) {
                $pdf_file = "assets/papers/" . $new_filename;
                $pdf_sql_part = ", pdf_file = ?";
                $params[] = $pdf_file;
            }
        }

        $params[] = $id; // For WHERE clause

        try {
            $sql = "UPDATE university_papers SET category_id = ?, title = ?, type = ?, display_order = ? $image_sql_part $pdf_sql_part WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            $success_message = "Paper updated successfully!";
        } catch (PDOException $e) {
            $error_message = "Database Error: " . $e->getMessage();
        }
    }

    // DELETE PAPER
    if (isset($_POST['delete_id'])) {
        $id = $_POST['delete_id'];
        try {
            $stmt = $pdo->prepare("DELETE FROM university_papers WHERE id = ?");
            $stmt->execute([$id]);
            $success_message = "Paper deleted.";
        } catch (PDOException $e) {
            $error_message = "Error: " . $e->getMessage();
        }
    }
}

// Fetch Categories for Dropdown
$categories = $pdo->query("SELECT * FROM university_categories ORDER BY name ASC")->fetchAll(PDO::FETCH_ASSOC);

// Fetch Existing Papers
$papers = $pdo->query("
    SELECT p.*, c.name as category_name 
    FROM university_papers p 
    JOIN university_categories c ON p.category_id = c.id 
    ORDER BY p.id DESC
")->fetchAll(PDO::FETCH_ASSOC);

?>
<!doctype html>
<html lang="en" dir="ltr">

<head>
    <!-- META DATA -->
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="Info Maths Online admin dashboard - Manage University Papers.">
    
    <!-- FAVICON -->
    <link rel="shortcut icon" type="image/x-icon" href="../assets/img/favicon.png">

    <!-- TITLE -->
    <title>Info Maths Online - Paper Management</title>

    <!-- BOOTSTRAP CSS -->
    <link id="style" href="assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- STYLE CSS -->
    <link href="assets/css/style.css" rel="stylesheet">

    <!-- Plugins CSS -->
    <link href="assets/css/plugins.css" rel="stylesheet">

    <!-- FONT-ICONS CSS -->
    <link href="assets/css/icons.css" rel="stylesheet">
    <link href="../assets/css/fontawesome.min.css" rel="stylesheet">
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
                            <h1 class="page-title">University Papers Management</h1>
                            <div>
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="panel">Home</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">University Papers</li>
                                </ol>
                            </div>
                        </div>

                        <!-- Row -->
                        <div class="row row-sm">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Manage Papers & Results</h3>
                                        <div class="card-options">
                                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPaperModal">
                                                <i class="fe fe-plus"></i> Add New Paper/Result
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
                                                        <th class="wd-15p border-bottom-0">Category</th>
                                                        <th class="wd-10p border-bottom-0">Thumbnail</th>
                                                        <th class="wd-25p border-bottom-0">Title</th>
                                                        <th class="wd-10p border-bottom-0">Type</th>
                                                        <th class="wd-10p border-bottom-0">PDF</th>
                                                        <th class="wd-15p border-bottom-0">Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($papers as $paper): ?>
                                                    <tr>
                                                        <td><span class="badge bg-info-transparent text-info rounded-pill"><?php echo htmlspecialchars($paper['category_name']); ?></span></td>
                                                        <td>
                                                            <?php if($paper['image_path']): ?>
                                                                <img src="../<?php echo htmlspecialchars($paper['image_path']); ?>" alt="img" style="height: 40px; border-radius: 4px;">
                                                            <?php else: ?>
                                                                <span class="text-muted">No Img</span>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td><?php echo htmlspecialchars($paper['title']); ?></td>
                                                        <td>
                                                            <?php if($paper['type'] == 'result'): ?>
                                                                <span class="badge bg-success">Result</span>
                                                            <?php else: ?>
                                                                <span class="badge bg-primary">Paper</span>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td>
                                                            <?php if($paper['pdf_file']): ?>
                                                                <a href="../<?php echo htmlspecialchars($paper['pdf_file']); ?>" target="_blank" class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-file-pdf"></i> View</a>
                                                            <?php else: ?>
                                                                <span class="text-muted">No PDF</span>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td>
                                                            <div class="btn-group">
                                                                <!-- Edit Button -->
                                                                <button type="button" class="btn btn-sm btn-warning edit-paper-btn"
                                                                    data-id="<?php echo $paper['id']; ?>"
                                                                    data-category="<?php echo $paper['category_id']; ?>"
                                                                    data-title="<?php echo htmlspecialchars($paper['title']); ?>"
                                                                    data-type="<?php echo $paper['type']; ?>"
                                                                    data-order="<?php echo $paper['display_order']; ?>"
                                                                >
                                                                    <i class="fe fe-edit"></i> Edit
                                                                </button>
                                                                
                                                                <form method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this item?');">
                                                                    <input type="hidden" name="delete_id" value="<?php echo $paper['id']; ?>">
                                                                    <button type="submit" class="btn btn-sm btn-danger"><i class="fe fe-trash"></i> Delete</button>
                                                                </form>
                                                            </div>
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
        </div>

        <!-- Add Paper Modal -->
        <div class="modal fade" id="addPaperModal" tabindex="-1">
            <div class="modal-dialog">
                <form method="POST" enctype="multipart/form-data">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add New Paper/Result</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="add_paper" value="1">
                        
                        <div class="mb-3">
                            <label class="form-label">University / Category</label>
                            <select name="category_id" class="form-select" required>
                                <?php foreach($categories as $cat): ?>
                                    <option value="<?php echo $cat['id']; ?>"><?php echo htmlspecialchars($cat['name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Title (e.g. 2024 Question Paper)</label>
                            <input type="text" name="title" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Type</label>
                            <select name="type" class="form-select">
                                <option value="paper">Previous Paper</option>
                                <option value="result">Result</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Thumbnail Image</label>
                            <input type="file" name="image" class="form-control" accept="image/*">
                            <small class="text-muted">Preview image for the card.</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">PDF File</label>
                            <input type="file" name="pdf_file" class="form-control" accept="application/pdf" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Display Order</label>
                            <input type="number" name="display_order" class="form-control" value="0">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Upload Paper</button>
                    </div>
                </div>
                </form>
            </div>
        </div>

        <!-- Edit Paper Modal -->
        <div class="modal fade" id="editPaperModal" tabindex="-1">
            <div class="modal-dialog">
                <form method="POST" enctype="multipart/form-data">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Paper/Result</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="edit_paper" value="1">
                        <input type="hidden" name="edit_paper_id" id="edit_paper_id">
                        
                        <div class="mb-3">
                            <label class="form-label">University / Category</label>
                            <select name="category_id" id="edit_category_id" class="form-select" required>
                                <?php foreach($categories as $cat): ?>
                                    <option value="<?php echo $cat['id']; ?>"><?php echo htmlspecialchars($cat['name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Title</label>
                            <input type="text" name="title" id="edit_title" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Type</label>
                            <select name="type" id="edit_type" class="form-select">
                                <option value="paper">Previous Paper</option>
                                <option value="result">Result</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Thumbnail Image</label>
                            <input type="file" name="image" class="form-control" accept="image/*">
                            <small class="text-muted">Leave empty to keep current image.</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">PDF File</label>
                            <input type="file" name="pdf_file" class="form-control" accept="application/pdf">
                            <small class="text-muted">Leave empty to keep current PDF.</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Display Order</label>
                            <input type="number" name="display_order" id="edit_display_order" class="form-control" value="0">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update Paper</button>
                    </div>
                </div>
                </form>
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

    <!-- Color Theme js -->
    <script src="assets/js/themeColors.js"></script>

    <!-- Sticky js -->
    <script src="assets/js/sticky.js"></script>

    <!-- CUSTOM JS -->
    <script src="assets/js/custom.js"></script>

    <script>
        $(document).ready(function() {
            // Edit Button Click Handler
            $('.edit-paper-btn').on('click', function() {
                var id = $(this).data('id');
                var category = $(this).data('category');
                var title = $(this).data('title');
                var type = $(this).data('type');
                var order = $(this).data('order');

                $('#edit_paper_id').val(id);
                $('#edit_category_id').val(category);
                $('#edit_title').val(title);
                $('#edit_type').val(type);
                $('#edit_display_order').val(order);

                $('#editPaperModal').modal('show');
            });
        });
    </script>
</body>
</html>
