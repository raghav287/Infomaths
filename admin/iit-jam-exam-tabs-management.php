<?php
// admin/iit-jam-exam-tabs-management.php
session_start();

if (!isset($_SESSION["admin_logged_in"]) || $_SESSION["admin_logged_in"] !== true) {
    header("location: login");
    exit;
}

require_once '../database.php';

if (!isset($_GET['exam_id'])) {
    header("location: iit-jam-entrance-exam-management.php");
    exit;
}

$exam_id = (int)$_GET['exam_id'];
$exam_name = '';

// Fetch Exam Name
try {
    $stmt = $pdo->prepare("SELECT exam_name FROM iit_jam_entrance_exams WHERE id = ?");
    $stmt->execute([$exam_id]);
    $exam = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($exam) {
        $exam_name = $exam['exam_name'];
    } else {
        header("location: iit-jam-entrance-exam-management.php"); // Exam not found
        exit;
    }
} catch (PDOException $e) {
    die("Error fetching exam: " . $e->getMessage());
}

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
            $stmt = $pdo->prepare("INSERT INTO iit_jam_exam_tabs (exam_id, tab_title, tab_content, display_order) VALUES (?, ?, ?, ?)");
            $stmt->execute([$exam_id, $title, $content, $order]);
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
            $stmt = $pdo->prepare("UPDATE iit_jam_exam_tabs SET tab_title = ?, tab_content = ?, display_order = ? WHERE id = ? AND exam_id = ?");
            $stmt->execute([$title, $content, $order, $id, $exam_id]);
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
        $stmt = $pdo->prepare("DELETE FROM iit_jam_exam_tabs WHERE id = ? AND exam_id = ?");
        $stmt->execute([$id, $exam_id]);
        $success_message = "Tab deleted successfully.";
    } catch (PDOException $e) {
        $error_message = "Error deleting tab: " . $e->getMessage();
    }
}

// Fetch Tabs
try {
    $stmt = $pdo->prepare("SELECT * FROM iit_jam_exam_tabs WHERE exam_id = ? ORDER BY display_order ASC");
    $stmt->execute([$exam_id]);
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
    <title>Manage Exam Tabs - <?php echo htmlspecialchars($exam_name); ?></title>
    
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
                            <h1 class="page-title">Manage Tabs for: <span class="text-primary"><?php echo htmlspecialchars($exam_name); ?></span></h1>
                            <div>
                                <a href="iit-jam-entrance-exam-management.php" class="btn btn-secondary"><i class="fa fa-arrow-left"></i> Back to Exams</a>
                            </div>
                        </div>

                        <div class="row row-sm">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Exam Tabs</h3>
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
                                                                    
                                                                    <a href="?exam_id=<?php echo $exam_id; ?>&delete_id=<?php echo $tab['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this tab?')">
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
                                <input type="text" class="form-control" name="tab_title" required placeholder="e.g. Introduction">
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
    
    <!-- SUMMERNOTE JS -->
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>

    <script>
        $(document).ready(function() {
            // Check if Summernote is loaded
            if (typeof $.fn.summernote !== 'undefined') {
                
                // Define Custom Accordion Button
                var AccordionButton = function (context) {
                    var ui = $.summernote.ui;
                    var button = ui.button({
                        contents: '<i class="fa fa-list-ul"/> Accordion',
                        tooltip: 'Insert FAQ/Accordion',
                        click: function () {
                            var accordionId = 'accordion_' + new Date().getTime();
                            var html = `
                            <div class="accordion" id="${accordionId}">
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingOne_${accordionId}">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne_${accordionId}" aria-expanded="true" aria-controls="collapseOne_${accordionId}">
                                            <strong style="color: #1C56E1;">Topic Title 1</strong>
                                        </button>
                                    </h2>
                                    <div id="collapseOne_${accordionId}" class="accordion-collapse collapse show" aria-labelledby="headingOne_${accordionId}" data-bs-parent="#${accordionId}">
                                        <div class="accordion-body">
                                            <p>Enter your content, syllabus, or answer for this topic here.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingTwo_${accordionId}">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo_${accordionId}" aria-expanded="false" aria-controls="collapseTwo_${accordionId}">
                                            <strong style="color: #1C56E1;">Topic Title 2</strong>
                                        </button>
                                    </h2>
                                    <div id="collapseTwo_${accordionId}" class="accordion-collapse collapse" aria-labelledby="headingTwo_${accordionId}" data-bs-parent="#${accordionId}">
                                        <div class="accordion-body">
                                            <p>Enter your content, syllabus, or answer for this topic here.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <br>`;
                            context.invoke('editor.pasteHTML', html);
                        }
                    });
                    return button.render();
                }

                // Define Alert/Callout Button
                var CalloutButton = function (context) {
                    var ui = $.summernote.ui;
                    var button = ui.button({
                        contents: '<i class="fa fa-info-circle"/> Info Box',
                        tooltip: 'Insert Information Box',
                        click: function () {
                            var html = `
                            <div class="alert alert-success mt-4">
                                <strong>Important Note:</strong> <a href="#" class="alert-link">Click here</a> for more details or enter your text here.
                            </div>
                            <br>`;
                            context.invoke('editor.pasteHTML', html);
                        }
                    });
                    return button.render();
                }

                // Define 2-Column Layout Button
                var TwoColButton = function (context) {
                    var ui = $.summernote.ui;
                    var button = ui.button({
                        contents: '<span style="font-weight:bold;">2-Col</span>',
                        tooltip: 'Insert 2 Columns',
                        click: function () {
                            var html = `
                            <div class="mb-4">
                                <h4 style="color: #1C56E1; margin-bottom: 20px; font-weight: 700;">Sample Title Here</h4>
                                <div class="row">
                                    <div class="col-md-6">
                                        <ul style="list-style-type: disc; padding-left: 20px;">
                                            <li>Sample list item 1</li>
                                            <li>Sample list item 2</li>
                                            <li>Sample list item 3</li>
                                        </ul>
                                    </div>
                                    <div class="col-md-6">
                                        <ul style="list-style-type: disc; padding-left: 20px;">
                                            <li>Sample list item 1</li>
                                            <li>Sample list item 2</li>
                                            <li>Sample list item 3</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <br>`;
                            context.invoke('editor.pasteHTML', html);
                        }
                    });
                    return button.render();
                }

                // Define 3-Column Layout Button
                var ThreeColButton = function (context) {
                    var ui = $.summernote.ui;
                    var button = ui.button({
                        contents: '<span style="font-weight:bold;">3-Col</span>',
                        tooltip: 'Insert 3 Columns',
                        click: function () {
                            var html = `
                            <div class="mb-4">
                                <h4 style="color: #1C56E1; margin-bottom: 20px; font-weight: 700;">Sample Title Here</h4>
                                <div class="row">
                                    <div class="col-md-4">
                                        <ul style="list-style-type: disc; padding-left: 20px;">
                                            <li>Sample list item 1</li>
                                            <li>Sample list item 2</li>
                                            <li>Sample list item 3</li>
                                        </ul>
                                    </div>
                                    <div class="col-md-4">
                                        <ul style="list-style-type: disc; padding-left: 20px;">
                                            <li>Sample list item 1</li>
                                            <li>Sample list item 2</li>
                                            <li>Sample list item 3</li>
                                        </ul>
                                    </div>
                                    <div class="col-md-4">
                                        <ul style="list-style-type: disc; padding-left: 20px;">
                                            <li>Sample list item 1</li>
                                            <li>Sample list item 2</li>
                                            <li>Sample list item 3</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <br>`;
                            context.invoke('editor.pasteHTML', html);
                        }
                    });
                    return button.render();
                }

                // Define 4-Column Layout Button
                var FourColButton = function (context) {
                    var ui = $.summernote.ui;
                    var button = ui.button({
                        contents: '<span style="font-weight:bold;">4-Col</span>',
                        tooltip: 'Insert 4 Columns',
                        click: function () {
                            var html = `
                            <div class="mb-4">
                                <h4 style="color: #1C56E1; margin-bottom: 20px; font-weight: 700;">Sample Title Here</h4>
                                <div class="row">
                                    <div class="col-md-3">
                                        <ul style="list-style-type: disc; padding-left: 20px;">
                                            <li>Sample list item 1</li>
                                            <li>Sample list item 2</li>
                                        </ul>
                                    </div>
                                    <div class="col-md-3">
                                        <ul style="list-style-type: disc; padding-left: 20px;">
                                            <li>Sample list item 1</li>
                                            <li>Sample list item 2</li>
                                        </ul>
                                    </div>
                                    <div class="col-md-3">
                                        <ul style="list-style-type: disc; padding-left: 20px;">
                                            <li>Sample list item 1</li>
                                            <li>Sample list item 2</li>
                                        </ul>
                                    </div>
                                    <div class="col-md-3">
                                        <ul style="list-style-type: disc; padding-left: 20px;">
                                            <li>Sample list item 1</li>
                                            <li>Sample list item 2</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <br>`;
                            context.invoke('editor.pasteHTML', html);
                        }
                    });
                    return button.render();
                }

                // Initialize Summernote with custom buttons
                $('.summernote').summernote({
                    placeholder: 'Enter detailed content for this tab...',
                    tabsize: 2,
                    height: 300,
                    dialogsInBody: true,
                    tableClassName: 'table table-bordered table-striped text-center align-middle dynamic-styled-table',
                    toolbar: [
                      ['style', ['style']],
                      ['font', ['bold', 'underline', 'clear']],
                      ['fontsize', ['fontsize']],
                      ['color', ['color']],
                      ['para', ['ul', 'ol', 'paragraph']],
                      ['table', ['table']],
                      ['insert', ['link', 'picture', 'video']],
                      ['view', ['fullscreen', 'codeview', 'help']],
                      ['custom', ['accordion', 'callout']],
                      ['layout', ['twoCol', 'threeCol', 'fourCol']]
                    ],
                    buttons: {
                        accordion: AccordionButton,
                        callout: CalloutButton,
                        twoCol: TwoColButton,
                        threeCol: ThreeColButton,
                        fourCol: FourColButton
                    }
                });
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
