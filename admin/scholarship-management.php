<?php
// admin/scholarship-management.php
session_start();
if (!isset($_SESSION["admin_logged_in"]) || $_SESSION["admin_logged_in"] !== true) {
    header("location: login");
    exit;
}

require_once '../database.php';

// Handle Delete
if (isset($_GET['delete_id'])) {
    $id = (int)$_GET['delete_id'];
    try {
        $stmt = $pdo->prepare("DELETE FROM scholarship_registrations WHERE id = ?");
        $stmt->execute([$id]);
        $success_message = "Registration deleted successfully.";
    } catch (PDOException $e) {
        $error_message = "Error deleting registration: " . $e->getMessage();
    }
}

// Fetch Registrations
try {
    $stmt = $pdo->query("SELECT * FROM scholarship_registrations ORDER BY registration_date DESC");
    $registrations = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error_message = "Error fetching data: " . $e->getMessage();
}
?>

<!doctype html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Scholarship Registrations - Admin</title>
    <!-- BOOTSTRAP CSS -->
    <link id="style" href="assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!-- STYLE CSS -->
    <link href="assets/css/style.css" rel="stylesheet">
    <!-- Plugins CSS -->
    <link href="assets/css/plugins.css" rel="stylesheet">
    <!-- FONT-ICONS CSS -->
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
                            <h1 class="page-title">Scholarship Registrations</h1>
                            <div>
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="panel">Home</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Scholarships</li>
                                </ol>
                            </div>
                        </div>

                        <div class="row row-sm">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Applicants List</h3>
                                    </div>
                                    <div class="card-body">
                                        <?php if (!empty($error_message)): ?>
                                            <div class="alert alert-danger"><?php echo $error_message; ?></div>
                                        <?php endif; ?>
                                        <?php if (!empty($success_message)): ?>
                                            <div class="alert alert-success"><?php echo $success_message; ?></div>
                                        <?php endif; ?>

                                        <div class="table-responsive">
                                            <table class="table table-bordered text-nowrap border-bottom" id="basic-datatable">
                                                <thead>
                                                    <tr>
                                                        <th>ID</th>
                                                        <th>Date</th>
                                                        <th>Name</th>
                                                        <th>Contact</th>
                                                        <th>Qualification</th>
                                                        <th>Percentage</th>
                                                        <th>Course</th>
                                                        <th>Message</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php if (!empty($registrations)): ?>
                                                        <?php foreach ($registrations as $reg): ?>
                                                            <tr>
                                                                <td><?php echo $reg['id']; ?></td>
                                                                <td><?php echo date('d M Y, h:i A', strtotime($reg['registration_date'])); ?></td>
                                                                <td><?php echo htmlspecialchars($reg['name']); ?></td>
                                                                <td>
                                                                    <?php echo htmlspecialchars($reg['email']); ?><br>
                                                                    <?php echo htmlspecialchars($reg['mobile']); ?>
                                                                </td>
                                                                <td><?php echo htmlspecialchars($reg['qualification'] ?? '-'); ?></td>
                                                                <td><?php echo htmlspecialchars($reg['percentage'] ?? '-'); ?></td>
                                                                <td><?php echo htmlspecialchars($reg['course']); ?></td>
                                                                <td>
                                                                    <button type="button" class="btn btn-sm btn-info" data-bs-toggle="popover" title="Message" data-bs-content="<?php echo htmlspecialchars($reg['message']); ?>">View</button>
                                                                </td>
                                                                <td>
                                                                    <a href="?delete_id=<?php echo $reg['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">
                                                                        <i class="fe fe-trash"></i>
                                                                    </a>
                                                                </td>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                    <?php else: ?>
                                                        <tr><td colspan="7" class="text-center">No registrations found.</td></tr>
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
        </div>
        <?php include 'assets/footer.php'; ?>
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
    
    <script>
        $(document).ready(function(){
            $('[data-bs-toggle="popover"]').popover();
        });
    </script>
</body>
</html>
