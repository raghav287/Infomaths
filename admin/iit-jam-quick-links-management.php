<?php
// Initialize the session
session_start();

if (!isset($_SESSION["admin_logged_in"]) || $_SESSION["admin_logged_in"] !== true) {
    header("location: login");
    exit;
}

require_once '../database.php';

if ($pdo === null) {
    die("Database connection failed.");
}

$items = [];
$error_message = '';
$success_message = '';

// Handle Image Upload
function uploadImage($file) {
    $target_dir = "../assets/img/others/";
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    
    $file_extension = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
    $new_filename = uniqid('card_') . '.' . $file_extension;
    $target_file = $target_dir . $new_filename;
    
    $check = getimagesize($file["tmp_name"]);
    if($check === false) return ['success' => false, 'message' => "File is not an image."];
    
    if (move_uploaded_file($file["tmp_name"], $target_file)) {
        return ['success' => true, 'filename' => 'assets/img/others/' . $new_filename];
    } else {
        return ['success' => false, 'message' => "Error uploading file."];
    }
}

// Add Item
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_item'])) {
    $title = trim($_POST['title']);
    $custom_link = trim($_POST['custom_link']);
    $display_order = (int)$_POST['display_order'];
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    
    $icon_image = '';
    if (!empty($_FILES['icon_image']['name'])) {
        $res = uploadImage($_FILES['icon_image']);
        if ($res['success']) $icon_image = $res['filename'];
        else $error_message = $res['message'];
    }

    if (empty($error_message)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO iit_jam_quick_links (title, custom_link, icon_image, display_order, is_active) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$title, $custom_link, $icon_image, $display_order, $is_active]);
            $success_message = "Card added successfully.";
        } catch (PDOException $e) {
            $error_message = "Error: " . $e->getMessage();
        }
    }
}

// Edit Item
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_item'])) {
    $id = (int)$_POST['edit_id'];
    $title = trim($_POST['edit_title']);
    $custom_link = trim($_POST['edit_custom_link']);
    $display_order = (int)$_POST['edit_display_order'];
    $is_active = isset($_POST['edit_is_active']) ? 1 : 0;

    if (!empty($_FILES['edit_icon_image']['name'])) {
        $res = uploadImage($_FILES['edit_icon_image']);
        if ($res['success']) {
            $new_image = $res['filename'];
            $stmt = $pdo->prepare("UPDATE iit_jam_quick_links SET title=?, custom_link=?, icon_image=?, display_order=?, is_active=? WHERE id=?");
            $stmt->execute([$title, $custom_link, $new_image, $display_order, $is_active, $id]);
        }
    } else {
        $stmt = $pdo->prepare("UPDATE iit_jam_quick_links SET title=?, custom_link=?, display_order=?, is_active=? WHERE id=?");
        $stmt->execute([$title, $custom_link, $display_order, $is_active, $id]);
    }
    $success_message = "Card updated.";
}

// Delete Item
if (isset($_GET['delete_id'])) {
    $id = (int)$_GET['delete_id'];
    $pdo->prepare("DELETE FROM iit_jam_quick_links WHERE id = ?")->execute([$id]);
    $success_message = "Card deleted.";
}

// Fetch All
$stmt = $pdo->query("SELECT * FROM iit_jam_quick_links ORDER BY display_order ASC");
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!doctype html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
    <title>Manage Quick Links / Cards</title>
    <link href="assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
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
                            <h1 class="page-title">Manage Custom Cards</h1>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">All Cards</h3>
                                        <div class="card-options">
                                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal"><i class="fe fe-plus"></i> Add New Card</button>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <?php if($success_message) echo "<div class='alert alert-success'>$success_message</div>"; ?>
                                        <div class="table-responsive">
                                            <table class="table table-bordered border-bottom" id="basic-datatable">
                                                <thead>
                                                    <tr>
                                                        <th>Order</th>
                                                        <th>Image</th>
                                                        <th>Title</th>
                                                        <th>Link URL</th>
                                                        <th>Status</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($items as $item): ?>
                                                    <tr>
                                                        <td><?php echo $item['display_order']; ?></td>
                                                        <td><img src="../<?php echo $item['icon_image']; ?>" style="width:40px;"></td>
                                                        <td><?php echo htmlspecialchars($item['title']); ?></td>
                                                        <td><?php echo htmlspecialchars($item['custom_link']); ?></td>
                                                        <td><?php echo $item['is_active'] ? 'Active' : 'Inactive'; ?></td>
                                                        <td>
                                                            <button class="btn btn-warning btn-sm edit-btn" 
                                                                data-id="<?php echo $item['id']; ?>"
                                                                data-title="<?php echo htmlspecialchars($item['title']); ?>"
                                                                data-link="<?php echo htmlspecialchars($item['custom_link']); ?>"
                                                                data-order="<?php echo $item['display_order']; ?>"
                                                                data-active="<?php echo $item['is_active']; ?>"
                                                                data-bs-toggle="modal" data-bs-target="#editModal">Edit</button>
                                                            <a href="?delete_id=<?php echo $item['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete?')">Delete</a>
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

        <!-- Add Modal -->
        <div class="modal fade" id="addModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="POST" enctype="multipart/form-data">
                        <div class="modal-header"><h5 class="modal-title">Add Card</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                        <div class="modal-body">
                            <div class="mb-3"><label>Title</label><input type="text" name="title" class="form-control" required></div>
                            <div class="mb-3"><label>Link URL (Any URL)</label><input type="text" name="custom_link" class="form-control" placeholder="https://... or page.php"></div>
                            <div class="mb-3"><label>Image</label><input type="file" name="icon_image" class="form-control"></div>
                            <div class="mb-3"><label>Order</label><input type="number" name="display_order" class="form-control" value="0"></div>
                            <div class="form-check"><input type="checkbox" name="is_active" class="form-check-input" checked><label>Active</label></div>
                        </div>
                        <div class="modal-footer"><button type="submit" name="add_item" class="btn btn-primary">Save</button></div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Edit Modal -->
        <div class="modal fade" id="editModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="POST" enctype="multipart/form-data">
                        <div class="modal-header"><h5 class="modal-title">Edit Card</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                        <div class="modal-body">
                            <input type="hidden" name="edit_id" id="edit_id">
                            <div class="mb-3"><label>Title</label><input type="text" name="edit_title" id="edit_title" class="form-control" required></div>
                            <div class="mb-3"><label>Link URL</label><input type="text" name="edit_custom_link" id="edit_link" class="form-control"></div>
                            <div class="mb-3"><label>Change Image</label><input type="file" name="edit_icon_image" class="form-control"></div>
                            <div class="mb-3"><label>Order</label><input type="number" name="edit_display_order" id="edit_order" class="form-control"></div>
                            <div class="form-check"><input type="checkbox" name="edit_is_active" id="edit_active" class="form-check-input"><label>Active</label></div>
                        </div>
                        <div class="modal-footer"><button type="submit" name="edit_item" class="btn btn-primary">Update</button></div>
                    </form>
                </div>
            </div>
        </div>

    </div>
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/plugins/sidemenu/sidemenu.js"></script>
    <script src="assets/plugins/sidebar/sidebar.js"></script>
    <script src="assets/js/custom.js"></script>
    <script>
        $('.edit-btn').on('click', function() {
            $('#edit_id').val($(this).data('id'));
            $('#edit_title').val($(this).data('title'));
            $('#edit_link').val($(this).data('link'));
            $('#edit_order').val($(this).data('order'));
            $('#edit_active').prop('checked', $(this).data('active') == 1);
        });
    </script>
</body>
</html>
