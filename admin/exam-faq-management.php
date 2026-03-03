<?php
// admin/exam-faq-management.php
session_start();

if (!isset($_SESSION["admin_logged_in"]) || $_SESSION["admin_logged_in"] !== true) {
    header("location: login");
    exit;
}

require_once '../database.php';

$exam_id = isset($_GET['exam_id']) ? (int)$_GET['exam_id'] : 0;
if ($exam_id == 0) {
    die("Invalid Exam ID.");
}

// Fetch Exam Name
$stmt = $pdo->prepare("SELECT exam_name FROM entrance_exams WHERE id = ?");
$stmt->execute([$exam_id]);
$exam = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$exam) {
    die("Exam not found.");
}
$exam_name = $exam['exam_name'];

$faqs = [];
$error_message = '';
$success_message = '';

// Handle ADD
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_faq'])) {
    $question = trim($_POST['question']);
    $answer = trim($_POST['answer']);
    $display_order = (int)$_POST['display_order'];
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    
    if (empty($question) || empty($answer)) {
        $error_message = "Question/Topic and Answer/Detail are required.";
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO entrance_exam_faqs (exam_id, question, answer, display_order, is_active) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$exam_id, $question, $answer, $display_order, $is_active]);
            $success_message = "Item added successfully.";
        } catch (PDOException $e) {
            $error_message = "Error: " . $e->getMessage();
        }
    }
}

// Handle EDIT
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_faq'])) {
    $id = (int)$_POST['edit_id'];
    $question = trim($_POST['edit_question']);
    $answer = trim($_POST['edit_answer']);
    $display_order = (int)$_POST['edit_display_order'];
    $is_active = isset($_POST['edit_is_active']) ? 1 : 0;
    
    try {
        $stmt = $pdo->prepare("UPDATE entrance_exam_faqs SET question=?, answer=?, display_order=?, is_active=? WHERE id=?");
        $stmt->execute([$question, $answer, $display_order, $is_active, $id]);
        $success_message = "Item updated successfully.";
    } catch (PDOException $e) {
        $error_message = "Error: " . $e->getMessage();
    }
}

// Handle DELETE
if (isset($_GET['delete_id'])) {
    $id = (int)$_GET['delete_id'];
    try {
        $stmt = $pdo->prepare("DELETE FROM entrance_exam_faqs WHERE id = ?");
        $stmt->execute([$id]);
        $success_message = "Item deleted successfully.";
    } catch (PDOException $e) {
        $error_message = "Error deleting: " . $e->getMessage();
    }
}

// Fetch FAQs
$stmt = $pdo->prepare("SELECT * FROM entrance_exam_faqs WHERE exam_id = ? ORDER BY display_order ASC");
$stmt->execute([$exam_id]);
$faqs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!doctype html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
    <title>Manage Syllabus Topics / FAQs - <?php echo htmlspecialchars($exam_name); ?></title>
    <link id="style" href="assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <link href="assets/css/plugins.css" rel="stylesheet">
    <link href="assets/css/icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
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
                            <h1 class="page-title">Manage Content for: <?php echo htmlspecialchars($exam_name); ?></h1>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="panel">Home</a></li>
                                <li class="breadcrumb-item"><a href="exam-management">Entrance Exams</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Manage Topics/FAQs</li>
                            </ol>
                        </div>
                        
                        <div class="row row-sm">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Syllabus Topics / FAQs</h3>
                                        <div class="card-options">
                                            <a href="exam-management" class="btn btn-secondary btn-sm me-2">Back to Exams</a>
                                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addModal">
                                                <i class="fe fe-plus"></i> Add New Topic
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
                                            <table class="table table-bordered text-nowrap border-bottom">
                                                <thead>
                                                    <tr>
                                                        <th>Order</th>
                                                        <th>Topic / Question</th>
                                                        <th>Status</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php if($faqs): ?>
                                                    <?php foreach ($faqs as $faq): ?>
                                                        <tr>
                                                            <td><?php echo $faq['display_order']; ?></td>
                                                            <td><?php echo htmlspecialchars($faq['question']); ?></td>
                                                            <td>
                                                                <?php if ($faq['is_active']): ?>
                                                                    <span class="badge bg-success">Active</span>
                                                                <?php else: ?>
                                                                    <span class="badge bg-danger">Inactive</span>
                                                                <?php endif; ?>
                                                            </td>
                                                            <td>
                                                                <button class="btn btn-warning btn-sm edit-btn"
                                                                    data-id="<?php echo $faq['id']; ?>"
                                                                    data-question="<?php echo htmlspecialchars($faq['question']); ?>"
                                                                    data-order="<?php echo $faq['display_order']; ?>"
                                                                    data-active="<?php echo $faq['is_active']; ?>"
                                                                    data-bs-toggle="modal" data-bs-target="#editModal">
                                                                    <i class="fe fe-edit"></i> Edit
                                                                </button>
                                                                <textarea id="ans_<?php echo $faq['id']; ?>" style="display:none;"><?php echo htmlspecialchars($faq['answer']); ?></textarea>
                                                                <a href="?exam_id=<?php echo $exam_id; ?>&delete_id=<?php echo $faq['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this item?')">
                                                                    <i class="fe fe-trash"></i> Delete
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                    <?php else: ?>
                                                        <tr><td colspan="4" class="text-center">No topics added yet.</td></tr>
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
    </div>

    <!-- Add Modal -->
    <div class="modal fade" id="addModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Topic / Question</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Topic Title / Question</label>
                            <input type="text" class="form-control" name="question" required placeholder="e.g. Mathematics Syllabus">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Detail / Answer</label>
                            <textarea class="form-control summernote" name="answer"></textarea>
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
                        <button type="submit" name="add_faq" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Topic / Question</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="edit_id" id="edit_id">
                        <div class="mb-3">
                            <label class="form-label">Topic Title / Question</label>
                            <input type="text" class="form-control" name="edit_question" id="edit_question" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Detail / Answer</label>
                            <textarea class="form-control summernote" name="edit_answer" id="edit_answer"></textarea>
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
                        <button type="submit" name="edit_faq" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/plugins/bootstrap/js/popper.min.js"></script>
    <script src="assets/plugins/bootstrap/js/bootstrap.min.js"></script>
    <!-- SIDE-MENU JS -->
    <script src="assets/plugins/sidemenu/sidemenu.js"></script>

     <!-- SIDEBAR JS -->
    <script src="assets/plugins/sidebar/sidebar.js"></script>

    <!-- CUSTOM JS -->
    <script src="assets/js/custom.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
    
    <!-- CUSTOM SUMMERNOTE BUTTONS -->
    <script src="assets/js/summernote-custom-buttons.js"></script>

    <script>
        $(document).ready(function() {
            // Initialize Summernote using shared config
            initializeCustomSummernote('.summernote');

            $('.edit-btn').on('click', function() {
                var id = $(this).data('id');
                $('#edit_id').val(id);
                $('#edit_question').val($(this).data('question'));
                $('#edit_display_order').val($(this).data('order'));
                $('#edit_is_active').prop('checked', $(this).data('active') == 1);
                
                var ans = $('#ans_' + id).val();
                $('#edit_answer').summernote('code', ans);
            });
        });
    </script>
</body>
</html>

