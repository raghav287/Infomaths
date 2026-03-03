<?php
// admin/bsc-page-tabs.php
session_start();

if (!isset($_SESSION["admin_logged_in"]) || $_SESSION["admin_logged_in"] !== true) {
    header("location: login");
    exit;
}

require_once '../database.php';

$tabs = [];
$error_message = '';
$success_message = '';

// Handle Add Tab
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_tab'])) {
    $title = trim($_POST['tab_title']);
    $content = $_POST['tab_content'];
    $order = (int)$_POST['display_order'];

    if (empty($title)) {
        $error_message = "Tab Title is required.";
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO bsc_page_tabs (tab_title, tab_content, display_order) VALUES (?, ?, ?)");
            $stmt->execute([$title, $content, $order]);
            $success_message = "Tab added successfully.";
        } catch (PDOException $e) {
            $error_message = "Error adding tab: " . $e->getMessage();
        }
    }
}

// Handle Edit Tab
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_tab'])) {
    $id = (int)$_POST['edit_tab_id'];
    $title = trim($_POST['edit_tab_title']);
    $content = $_POST['edit_tab_content'];
    $order = (int)$_POST['edit_display_order'];

    if (empty($title)) {
        $error_message = "Tab Title is required.";
    } else {
        try {
            $stmt = $pdo->prepare("UPDATE bsc_page_tabs SET tab_title = ?, tab_content = ?, display_order = ? WHERE id = ?");
            $stmt->execute([$title, $content, $order, $id]);
            $success_message = "Tab updated successfully.";
        } catch (PDOException $e) {
            $error_message = "Error updating tab: " . $e->getMessage();
        }
    }
}

// Handle Delete Tab
if (isset($_GET['delete_id'])) {
    $id = (int)$_GET['delete_id'];
    try {
        $stmt = $pdo->prepare("DELETE FROM bsc_page_tabs WHERE id = ?");
        $stmt->execute([$id]);
        $success_message = "Tab deleted successfully.";
    } catch (PDOException $e) {
        $error_message = "Error deleting tab: " . $e->getMessage();
    }
}

// Fetch Tabs
try {
    $stmt = $pdo->prepare("SELECT * FROM bsc_page_tabs ORDER BY display_order ASC");
    $stmt->execute();
    $tabs = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error_message = "Error fetching tabs: " . $e->getMessage();
}
?>
<!doctype html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Manage BSc Page Tabs</title>
    
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
    <!-- INTERNAL Switcher css -->
    <link href="assets/switcher/css/switcher.css" rel="stylesheet">
    <link href="assets/switcher/demo.css" rel="stylesheet">
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
                            <h1 class="page-title">Manage BSc <span class="text-primary">Page Tabs</span></h1>
                            <div>
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="panel">Home</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">BSc Tabs</li>
                                </ol>
                            </div>
                        </div>

                        <div class="row row-sm">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Main Page Tabs</h3>
                                        <div class="card-options">
                                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTabModal">
                                                <i class="fe fe-plus"></i> Add New Tab
                                            </button>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <?php if (!empty($error_message)): ?>
                                            <div class="alert alert-danger"><?php echo $error_message; ?></div>
                                        <?php endif; ?>
                                        <?php if (!empty($success_message)): ?>
                                            <div class="alert alert-success"><?php echo $success_message; ?></div>
                                        <?php endif; ?>

                                        <div class="table-responsive">
                                            <table class="table table-bordered text-nowrap border-bottom">
                                                <thead>
                                                    <tr>
                                                        <th>Order</th>
                                                        <th>Tab Title</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php if (!empty($tabs)): ?>
                                                        <?php foreach ($tabs as $tab): ?>
                                                            <tr>
                                                                <td><?php echo $tab['display_order']; ?></td>
                                                                <td><?php echo htmlspecialchars($tab['tab_title']); ?></td>
                                                                <td>
                                                                    <button class="btn btn-warning btn-sm edit-tab-btn"
                                                                        data-id="<?php echo $tab['id']; ?>"
                                                                        data-title="<?php echo htmlspecialchars($tab['tab_title']); ?>"
                                                                        data-order="<?php echo $tab['display_order']; ?>"
                                                                        data-bs-toggle="modal" data-bs-target="#editTabModal">
                                                                        <i class="fe fe-edit"></i> Edit
                                                                    </button>
                                                                    <!-- Hidden Content -->
                                                                    <textarea id="content_<?php echo $tab['id']; ?>" style="display:none;"><?php echo htmlspecialchars($tab['tab_content']); ?></textarea>
                                                                    
                                                                    <a href="?delete_id=<?php echo $tab['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this tab?')">
                                                                        <i class="fe fe-trash"></i> Delete
                                                                    </a>
                                                                </td>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                    <?php else: ?>
                                                        <tr><td colspan="3" class="text-center">No tabs found.</td></tr>
                                                    <?php endif; ?>
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

        <!-- Add Tab Modal -->
        <div class="modal fade" id="addTabModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form method="POST">
                        <div class="modal-header">
                            <h5 class="modal-title">Add New Tab</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Tab Title</label>
                                <input type="text" class="form-control" name="tab_title" required placeholder="e.g. Overview">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Content</label>
                                <textarea class="form-control summernote" name="tab_content"></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Display Order</label>
                                <input type="number" class="form-control" name="display_order" value="0">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" name="add_tab" class="btn btn-primary">Save Tab</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Edit Tab Modal -->
        <div class="modal fade" id="editTabModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form method="POST">
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Tab</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" id="edit_tab_id" name="edit_tab_id">
                            <div class="mb-3">
                                <label class="form-label">Tab Title</label>
                                <input type="text" class="form-control" id="edit_tab_title" name="edit_tab_title" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Content</label>
                                <textarea class="form-control summernote" id="edit_tab_content" name="edit_tab_content"></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Display Order</label>
                                <input type="number" class="form-control" id="edit_display_order" name="edit_display_order">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" name="edit_tab" class="btn btn-primary">Update Tab</button>
                        </div>
                    </form>
                </div>
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
    
    <!-- SUMMERNOTE JS -->
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
    
    <!-- CUSTOM SUMMERNOTE BUTTONS -->
    <script src="assets/js/summernote-custom-buttons.js"></script>

    <script>
        $(document).ready(function() {
             if (typeof $.fn.summernote !== 'undefined') {
                if (typeof initializeCustomSummernote === 'function') {
                    initializeCustomSummernote('.summernote');
                } else {
                     $('.summernote').summernote({ height: 300 });
                }
             }

            $('.edit-tab-btn').on('click', function() {
                var id = $(this).data('id');
                var title = $(this).data('title');
                var order = $(this).data('order');
                var content = $('#content_' + id).val();

                $('#edit_tab_id').val(id);
                $('#edit_tab_title').val(title);
                $('#edit_display_order').val(order);
                $('#edit_tab_content').summernote('code', content);
            });
        });
    </script>
</body>
</html>
