<?php
session_start();
require_once '../database.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit();
}

$success_message = '';
$error_message = '';

// Helper function to generate slug
function generateSlug($text) {
    // Replace non-letter or digits by -
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);
    // Transliterate
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
    // Remove unwanted characters
    $text = preg_replace('~[^-\w]+~', '', $text);
    // Trim
    $text = trim($text, '-');
    // Remove duplicate -
    $text = preg_replace('~-+~', '-', $text);
    // Lowercase
    $text = strtolower($text);

    if (empty($text)) {
        return 'n-a';
    }

    return $text;
}

// Handle Form Submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // ADD NOTICE
    if (isset($_POST['add_notice'])) {
        $content = trim($_POST['content']);
        $display_order = (int)$_POST['display_order'];
        $is_active = isset($_POST['is_active']) ? 1 : 0;
        
        $link_type = $_POST['link_type']; // 'external' or 'page'
        $link = '';
        $page_title = '';
        $page_content = '';
        $meta_title = '';
        $meta_description = '';
        $slug = '';

        if ($link_type == 'page') {
            $page_title = trim($_POST['page_title']);
            $page_content = isset($_POST['page_content']) ? $_POST['page_content'] : ''; // Allow HTML
            $meta_title = trim($_POST['meta_title']);
            $meta_description = trim($_POST['meta_description']);
            
            // Generate Slug
            $base_slug = generateSlug($page_title);
            $slug = $base_slug;
            // Ensure unique slug
            $counter = 1;
            while(true) {
                $check = $pdo->prepare("SELECT id FROM csir_net_notices WHERE slug = ?");
                $check->execute([$slug]);
                if($check->rowCount() == 0) break;
                $slug = $base_slug . '-' . $counter;
                $counter++;
            }
            // Link is internal slug
            $link = 'notice/' . $slug;

        } else {
            $link = trim($_POST['link']);
        }

        if (!empty($content)) {
            try {
                $stmt = $pdo->prepare("INSERT INTO csir_net_notices (content, link, display_order, is_active, slug, page_title, page_content, meta_title, meta_description) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$content, $link, $display_order, $is_active, $slug, $page_title, $page_content, $meta_title, $meta_description]);
                $success_message = "Notice added successfully!";
            } catch (PDOException $e) {
                $error_message = "DB Error: " . $e->getMessage();
            }
        } else {
            $error_message = "Notice Content is required.";
        }
    }

    // EDIT NOTICE
    if (isset($_POST['edit_notice'])) {
        $id = (int)$_POST['notice_id'];
        $content = trim($_POST['content']);
        $display_order = (int)$_POST['display_order'];
        $is_active = isset($_POST['is_active']) ? 1 : 0;

        $link_type = $_POST['link_type']; // 'external' or 'page'
        
        // Fetch existing data
        $existing = $pdo->prepare("SELECT * FROM csir_net_notices WHERE id = ?");
        $existing->execute([$id]);
        $row = $existing->fetch(PDO::FETCH_ASSOC);

        $link = trim($_POST['link']);
        $page_title = $row['page_title']; 
        $page_content = $row['page_content'];
        $meta_title = $row['meta_title'];
        $meta_description = $row['meta_description'];
        $slug = $row['slug']; // Default to existing

        if ($link_type == 'page') {
             $page_title = trim($_POST['page_title']);
             $page_content = isset($_POST['page_content']) ? $_POST['page_content'] : '';
             $meta_title = trim($_POST['meta_title']);
             $meta_description = trim($_POST['meta_description']);
             
             // Allow Manual Slug Update
             $new_slug = isset($_POST['slug']) ? trim($_POST['slug']) : '';
             
             if (!empty($new_slug)) {
                 $slug = generateSlug($new_slug);
             } elseif (empty($slug)) {
                 // Generate from title if both empty
                 $slug = generateSlug($page_title);
             }

             // Ensure unique slug (excluding current ID)
             $base_slug_check = $slug;
             $counter = 1;
             while(true) {
                 $check = $pdo->prepare("SELECT id FROM csir_net_notices WHERE slug = ? AND id != ?");
                 $check->execute([$slug, $id]);
                 if($check->rowCount() == 0) break;
                 $slug = $base_slug_check . '-' . $counter;
                 $counter++;
             }

             $link = 'notice/' . $slug;
        }

        if (!empty($content)) {
            try {
                $stmt = $pdo->prepare("UPDATE csir_net_notices SET content = ?, link = ?, display_order = ?, is_active = ?, slug = ?, page_title = ?, page_content = ?, meta_title = ?, meta_description = ? WHERE id = ?");
                $stmt->execute([$content, $link, $display_order, $is_active, $slug, $page_title, $page_content, $meta_title, $meta_description, $id]);
                $success_message = "Notice details updated successfully!";
            } catch (PDOException $e) {
                $error_message = "DB Error: " . $e->getMessage();
            }
        }
    }

    // DELETE NOTICE
    if (isset($_POST['delete_id'])) {
        $id = (int)$_POST['delete_id'];
        try {
            $stmt = $pdo->prepare("DELETE FROM csir_net_notices WHERE id = ?");
            $stmt->execute([$id]);
            $success_message = "Notice deleted.";
        } catch (PDOException $e) {
            $error_message = "Error: " . $e->getMessage();
        }
    }
}

// Fetch Notices
$notices = $pdo->query("SELECT * FROM csir_net_notices ORDER BY display_order ASC, created_at DESC")->fetchAll(PDO::FETCH_ASSOC);

// Fetch Internal Pages for "CMS Linker"
$internal_pages = [];
// 1. Entrance Exams
try {
    $exams = $pdo->query("SELECT exam_name, slug FROM entrance_exams ORDER BY exam_name ASC")->fetchAll(PDO::FETCH_ASSOC);
    foreach($exams as $ex) $internal_pages[] = ['name' => 'Exam: ' . $ex['exam_name'], 'url' => 'exam-details.php?slug=' . $ex['slug']];
} catch(Exception $e) {}

// 2. Course Profiles
try {
    $courses = $pdo->query("SELECT course_name, slug FROM course_profiles ORDER BY course_name ASC")->fetchAll(PDO::FETCH_ASSOC);
    foreach($courses as $co) $internal_pages[] = ['name' => 'Course: ' . $co['course_name'], 'url' => 'course-details.php?slug=' . $co['slug']];
} catch(Exception $e) {}

// 3. Papers
try {
    $internal_pages[] = ['name' => 'University Papers (Main)', 'url' => 'papers.php'];
} catch(Exception $e) {}

?>
<!doctype html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Info Maths Online - CSIR NET Notice Management</title>
    <link rel="shortcut icon" type="image/x-icon" href="../assets/img/favicon.png">
    <link id="style" href="assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <link href="assets/css/plugins.css" rel="stylesheet">
    <link href="assets/css/icons.css" rel="stylesheet">
    <link href="../assets/css/fontawesome.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
    <!-- INTERNAL Switcher css -->
    <link href="assets/switcher/css/switcher.css" rel="stylesheet">
    <link href="assets/switcher/demo.css" rel="stylesheet">
</head>
<body class="app sidebar-mini ltr light-mode">
    <div id="global-loader"><img src="assets/images/loader.svg" class="loader-img" alt="Loader"></div>
    <div class="page">
        <div class="page-main">
            <?php include 'assets/header.php'; ?>
            <?php include 'assets/sidebar.php'; ?>
            
            <div class="main-content app-content mt-0">
                <div class="side-app">
                    <div class="main-container container-fluid">
                        <div class="page-header">
                            <h1 class="page-title">CSIR NET Notice Management</h1>
                            <div>
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="panel">Home</a></li>
                                    <li class="breadcrumb-item"><a href="javascript:void(0);">CSIR NET Page</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Manage Notices</li>
                                </ol>
                            </div>
                        </div>

                        <div class="row row-sm">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Manage CSIR NET Notices</h3>
                                        <div class="card-options">
                                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addNoticeModal">
                                                <i class="fe fe-plus"></i> Add New Notice
                                            </button>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <?php if ($success_message): ?><div class="alert alert-success"><?php echo $success_message; ?></div><?php endif; ?>
                                        <?php if ($error_message): ?><div class="alert alert-danger"><?php echo $error_message; ?></div><?php endif; ?>

                                        <div class="table-responsive">
                                            <table class="table table-bordered text-nowrap border-bottom" id="basic-datatable">
                                                <thead>
                                                    <tr>
                                                        <th class="wd-5p">Order</th>
                                                        <th class="wd-40p">Content</th>
                                                        <th class="wd-25p">Target</th>
                                                        <th class="wd-10p">Status</th>
                                                        <th class="wd-20p">Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($notices as $notice): ?>
                                                    <tr>
                                                        <td><?php echo $notice['display_order']; ?></td>
                                                        <td style="white-space:normal;"><?php echo htmlspecialchars($notice['content']); ?></td>
                                                        <td>
                                                            <?php if(!empty($notice['slug'])): ?>
                                                                <span class="badge bg-purple">CMS Page</span><br>
                                                                <small><a href="../notice/<?php echo htmlspecialchars($notice['slug']); ?>" target="_blank" class="text-primary">View Page</a></small>
                                                            <?php else: ?>
                                                                <span class="badge bg-secondary">Ext Link</span><br>
                                                                <small><a href="../<?php echo htmlspecialchars($notice['link']); ?>" target="_blank" class="text-muted"><?php echo substr($notice['link'],0,20); ?>...</a></small>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td>
                                                            <?php if ($notice['is_active']): ?>
                                                                <span class="badge bg-success">Active</span>
                                                            <?php else: ?>
                                                                <span class="badge bg-danger">Inactive</span>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td>
                                                            <button class="btn btn-sm btn-warning edit-btn" 
                                                                    data-id="<?php echo $notice['id']; ?>"
                                                                    data-content="<?php echo htmlspecialchars($notice['content']); ?>"
                                                                    data-link="<?php echo htmlspecialchars($notice['link']); ?>"
                                                                    data-order="<?php echo $notice['display_order']; ?>"
                                                                    data-active="<?php echo $notice['is_active']; ?>"
                                                                    data-pagetitle="<?php echo htmlspecialchars($notice['page_title']); ?>"
                                                                    data-metatitle="<?php echo htmlspecialchars($notice['meta_title']); ?>"
                                                                    data-metadesc="<?php echo htmlspecialchars($notice['meta_description']); ?>"
                                                                    data-slug="<?php echo htmlspecialchars($notice['slug']); ?>">
                                                                <i class="fe fe-edit"></i> Edit
                                                            </button>
                                                            <!-- Hidden textarea to store HTML content safely -->
                                                            <textarea id="content_html_<?php echo $notice['id']; ?>" style="display:none;"><?php echo htmlspecialchars($notice['page_content']); ?></textarea>
                                                            
                                                            <form method="POST" style="display:inline;" onsubmit="return confirm('Delete this notice?');">
                                                                <input type="hidden" name="delete_id" value="<?php echo $notice['id']; ?>">
                                                                <button type="submit" class="btn btn-sm btn-danger"><i class="fe fe-trash"></i> Delete</button>
                                                            </form>
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
        <div class="modal fade" id="addNoticeModal" tabindex="-1" data-bs-backdrop="static">
            <div class="modal-dialog modal-lg"> <!-- Large modal for CMS -->
                <div class="modal-content">
                    <form method="POST">
                        <div class="modal-header">
                            <h5 class="modal-title">Add Notice</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="add_notice" value="1">
                            
                            <div class="mb-3">
                                <label class="form-label">Notice Text (Shown in Marquee/List) <span class="text-danger">*</span></label>
                                <textarea name="content" class="form-control" rows="2" required placeholder="Enter short notice text..."></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Link Destination</label>
                                <select name="link_type" id="add_link_type" class="form-select" onchange="toggleLinkType('add')">
                                    <option value="external">External / Internal URL</option>
                                    <option value="page">Create New CMS Page</option>
                                </select>
                            </div>

                            <!-- EXTERNAL LINK SECTION -->
                            <div id="add_external_section">
                                <div class="mb-3">
                                    <label class="form-label">Link URL</label>
                                    <div class="input-group">
                                        <input type="text" name="link" id="add_link" class="form-control" placeholder="http:// or internal page">
                                        <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">Select</button>
                                        <ul class="dropdown-menu dropdown-menu-end" style="max-height: 200px; overflow-y: auto;">
                                            <li><a class="dropdown-item" href="#" onclick="selectLink(this, 'add_link', '#')">No Link (#)</a></li>
                                            <li><hr class="dropdown-divider"></li>
                                            <?php foreach($internal_pages as $page): ?>
                                                <li><a class="dropdown-item" href="#" onclick="selectLink(this, 'add_link', '<?php echo $page['url']; ?>')"><?php echo htmlspecialchars($page['name']); ?></a></li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- CMS PAGE SECTION -->
                            <div id="add_page_section" style="display:none; border: 1px solid #eee; padding: 15px; border-radius: 5px; background: #fafafa;">
                                <h6 class="text-primary mb-3">Page Content</h6>
                                <div class="mb-3">
                                    <label class="form-label">Page Title</label>
                                    <input type="text" name="page_title" class="form-control" placeholder="Full Page Title">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Content (Detailed Information)</label>
                                    <textarea name="page_content" id="summernote_add" class="form-control"></textarea>
                                </div>
                                <div class="row">
                                    <div class="col-6 mb-3">
                                        <label class="form-label">SEO Meta Title</label>
                                        <input type="text" name="meta_title" class="form-control">
                                    </div>
                                    <div class="col-6 mb-3">
                                        <label class="form-label">SEO Meta Description</label>
                                        <input type="text" name="meta_description" class="form-control">
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-6 mb-3">
                                    <label class="form-label">Display Order</label>
                                    <input type="number" name="display_order" class="form-control" value="0">
                                </div>
                                <div class="col-6 mb-3 pt-4">
                                    <div class="form-check">
                                        <input type="checkbox" name="is_active" class="form-check-input" checked>
                                        <label class="form-check-label">Active</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save Notice</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Edit Modal -->
        <div class="modal fade" id="editNoticeModal" tabindex="-1" data-bs-backdrop="static">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form method="POST">
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Notice</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="edit_notice" value="1">
                            <input type="hidden" name="notice_id" id="edit_id">
                            
                            <div class="mb-3">
                                <label class="form-label">Notice Text <span class="text-danger">*</span></label>
                                <textarea name="content" id="edit_content" class="form-control" rows="2" required></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Link Destination</label>
                                <select name="link_type" id="edit_link_type" class="form-select" onchange="toggleLinkType('edit')">
                                    <option value="external">External / Internal URL</option>
                                    <option value="page">CMS Page</option>
                                </select>
                            </div>

                            <!-- EXTERNAL LINK SECTION -->
                            <div id="edit_external_section">
                                <div class="mb-3">
                                    <label class="form-label">Link URL</label>
                                    <div class="input-group">
                                        <input type="text" name="link" id="edit_link" class="form-control">
                                        <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">Select</button>
                                        <ul class="dropdown-menu dropdown-menu-end" style="max-height: 200px; overflow-y: auto;">
                                            <li><a class="dropdown-item" href="#" onclick="selectLink(this, 'edit_link', '#')">No Link (#)</a></li>
                                            <li><hr class="dropdown-divider"></li>
                                            <?php foreach($internal_pages as $page): ?>
                                                <li><a class="dropdown-item" href="#" onclick="selectLink(this, 'edit_link', '<?php echo $page['url']; ?>')"><?php echo htmlspecialchars($page['name']); ?></a></li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- CMS PAGE SECTION -->
                            <div id="edit_page_section" style="display:none; border: 1px solid #eee; padding: 15px; border-radius: 5px; background: #fafafa;">
                                <h6 class="text-primary mb-3">Page Content</h6>
                                <div class="mb-3">
                                    <label class="form-label">Page Title</label>
                                    <input type="text" name="page_title" id="edit_page_title" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Content (Detailed Information)</label>
                                    <textarea name="page_content" id="summernote_edit" class="form-control"></textarea>
                                </div>
                                <div class="row">
                                    <div class="col-6 mb-3">
                                        <label class="form-label">SEO Meta Title</label>
                                        <input type="text" name="meta_title" id="edit_meta_title" class="form-control">
                                    </div>
                                    <div class="col-6 mb-3">
                                        <label class="form-label">SEO Meta Description</label>
                                        <input type="text" name="meta_description" id="edit_meta_desc" class="form-control">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">URL Slug (leave empty to auto-generate)</label>
                                    <input type="text" name="slug" id="edit_slug" class="form-control" placeholder="e.g. my-notice-title">
                                    <small class="text-muted">Current Slug: <span id="edit_slug_display"></span></small>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-6 mb-3">
                                    <label class="form-label">Order</label>
                                    <input type="number" name="display_order" id="edit_order" class="form-control">
                                </div>
                                <div class="col-6 mb-3 pt-4">
                                    <div class="form-check">
                                        <input type="checkbox" name="is_active" id="edit_active" class="form-check-input">
                                        <label class="form-check-label">Active</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Update Notice</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <?php include 'assets/footer.php'; ?>
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
    
    <!-- SUMMERNOTE JS -->
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
    
    <!-- CUSTOM SUMMERNOTE BUTTONS -->
    <script src="assets/js/summernote-custom-buttons.js"></script>

    <script>
        function selectLink(element, inputId, url) {
            event.preventDefault(); 
            document.getElementById(inputId).value = url;
        }

        function toggleLinkType(mode) {
            var type = document.getElementById(mode + '_link_type').value;
            if (type === 'page') {
                document.getElementById(mode + '_external_section').style.display = 'none';
                document.getElementById(mode + '_page_section').style.display = 'block';
            } else {
                document.getElementById(mode + '_external_section').style.display = 'block';
                document.getElementById(mode + '_page_section').style.display = 'none';
            }
        }

        $(document).ready(function() {
            // Initialize Summernote using shared config
            initializeCustomSummernote('#summernote_add');
            initializeCustomSummernote('#summernote_edit');

            $('.edit-btn').click(function() {
                var id = $(this).data('id');
                var content = $(this).data('content');
                var link = $(this).data('link');
                var order = $(this).data('order');
                var active = $(this).data('active');
                
                var slug = $(this).data('slug');
                var pageTitle = $(this).data('pagetitle');
                var metaTitle = $(this).data('metatitle');
                var metaDesc = $(this).data('metadesc');
                
                // Get HTML content from hidden content textarea
                var pageContent = $('#content_html_' + id).val();

                $('#edit_id').val(id);
                $('#edit_content').val(content);
                $('#edit_link').val(link);
                $('#edit_order').val(order);
                $('#edit_active').prop('checked', active == 1);
                
                $('#edit_page_title').val(pageTitle);
                
                // Set logic for Page vs Link
                if (slug && slug !== '') {
                    $('#edit_link_type').val('page');
                    $('#summernote_edit').summernote('code', pageContent);
                    $('#edit_meta_title').val(metaTitle);
                    $('#edit_meta_desc').val(metaDesc);
                    $('#edit_slug_display').text(slug);
                $('#edit_slug').val(slug);
                    toggleLinkType('edit');
                } else {
                    $('#edit_link_type').val('external');
                    $('#summernote_edit').summernote('code', ''); // Clear to be safe
                    toggleLinkType('edit');
                }

                $('#editNoticeModal').modal('show');
            });
        });
    </script>
</body>
</html>
