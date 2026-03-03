    <?php
// Database connection is assumed to be included by parent file since this is a header include
// But to be safe, we check if $pdo exists, if not we try to include it.
if (!isset($pdo)) {
    // Attempt to locate database.php relative to this file or root
    if (file_exists('database.php')) {
        require_once 'database.php';
    } elseif (file_exists('../database.php')) {
        require_once '../database.php';
    }
}

// Fetch Dynamic Menu Items for Bank PO & SSC
$menu_exams = [];
$exam_categories = [
    'SBI' => [],
    'RBI' => [],
    'IBPS' => [], // Will exclude RRB
    'RRBs' => [],
    'SSC' => [],
    'Other' => []
];

if (isset($pdo)) {
    try {
        $stmt_menu = $pdo->query("SELECT exam_name, slug FROM bank_po_entrance_exams WHERE is_active = 1 ORDER BY display_order ASC, exam_name ASC");
        $all_active_exams = $stmt_menu->fetchAll(PDO::FETCH_ASSOC);

        foreach ($all_active_exams as $menu_exam) {
            $name = strtoupper($menu_exam['exam_name']);
            if (strpos($name, 'SBI') !== false) {
                $exam_categories['SBI'][] = $menu_exam;
            } elseif (strpos($name, 'RBI') !== false) {
                $exam_categories['RBI'][] = $menu_exam;
            } elseif (strpos($name, 'RRB') !== false) {
                // Check RRB before IBPS to catch "IBPS RRB"
                $exam_categories['RRBs'][] = $menu_exam;
            } elseif (strpos($name, 'IBPS') !== false) {
                $exam_categories['IBPS'][] = $menu_exam;
            } elseif (strpos($name, 'SSC') !== false) {
                $exam_categories['SSC'][] = $menu_exam;
            } else {
                $exam_categories['Other'][] = $menu_exam;
            }
        }
    } catch (Exception $e) {
        // Silent failure for menu
    }
}
?>

    <head>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    </head>
    <!-- Start Header Section -->
    <!-- Top Bar -->

    <header class="td_site_header td_sticky_header   td_style_1 td_type_3 td_medium td_heading_color">
        <div class="td_top_bar" style="width: 100%;font-size: 15px">
            <div class="container" style="
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 32px;
            min-height: 32px;
          ">
                <div class="td_top_bar_center" style="flex: 1; text-align: center; overflow: hidden">

                    <div style="font-size: 15px; color: #222; display: flex; align-items: center; gap: 18px;">

                        <a href="mailto:info@infomathsonline.com"
                            style="color:#222; text-decoration:none; display:flex; align-items:center; gap:6px;">
                            <i class="fa-solid fa-envelope"></i>
                            hrinfomaths@gmail.com
                        </a> |

                        <a href="tel:+919872124534"
                            style="color:#222; text-decoration:none; display:flex; align-items:center; gap:6px;">
                            <i class="fa-solid fa-phone"></i>
                            +91 98721-24534
                        </a> |

                        <a href="tel:+919878624534"
                            style="color:#222; text-decoration:none; display:flex; align-items:center; gap:6px;">
                            <i class="fa-solid fa-phone"></i>
                            +91 98786 24534
                        </a>

                    </div>
                    <style>
                    .top-bar-menu a:hover {
                        opacity: 0.8;
                    }
                    </style>
                </div>
                <style>
                /* Notification Modal Styles */
                .notification-modal {
                    position: fixed;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    z-index: 10000;
                    display: none;
                    opacity: 0;
                    transition: opacity 0.3s ease-in-out;
                    backdrop-filter: blur(8px);
                }

                .notification-modal.active {
                    display: flex;
                    opacity: 1;
                    align-items: center;
                    justify-content: center;
                }

                .notification-modal-overlay {
                    position: absolute;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    background: rgba(0, 0, 0, 0.6);
                    backdrop-filter: blur(5px);
                }

                .notification-modal-content {
                    position: relative;
                    background: white;
                    border-radius: 20px;
                    width: 90%;
                    max-width: 1200px;
                    max-height: 90vh;
                    overflow: hidden;
                    box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
                    transform: scale(0.95);
                    transition: transform 0.3s ease-in-out;
                    z-index: 10001;
                }

                .notification-modal.active .notification-modal-content {
                    transform: scale(1);
                }

                .notification-modal-header {
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                    padding: 20px 24px;
                    border-bottom: 1px solid #e5e7eb;
                    background: #f8f9fa;
                }

                .notification-modal-header h3 {
                    margin: 0;
                    font-size: 20px;
                    font-weight: 600;
                    color: #1f2937;
                }

                .notification-modal-close {
                    background: none;
                    border: none;
                    color: #6b7280;
                    cursor: pointer;
                    padding: 8px;
                    border-radius: 50%;
                    transition: all 0.2s ease;
                }

                .notification-modal-close:hover {
                    background: #e5e7eb;
                    color: #374151;
                }

                .notification-modal-body {
                    padding: 0;
                    max-height: 60vh;
                    overflow-y: auto;
                }

                .notification-filters {
                    padding: 20px 24px 0;
                    border-bottom: 1px solid #e5e7eb;
                    background: white;
                    position: sticky;
                    top: 0;
                    z-index: 1;
                }

                .filter-tabs {
                    display: flex;
                    gap: 8px;
                    padding-bottom: 15px;
                    overflow-x: auto;
                }

                .filter-tab {
                    padding: 8px 16px;
                    border-radius: 20px;
                    border: 1px solid #e5e7eb;
                    background: white;
                    font-size: 14px;
                    font-weight: 500;
                    color: #4b5563;
                    cursor: pointer;
                    transition: all 0.2s;
                    white-space: nowrap;
                }

                .filter-tab:hover {
                    background: #f3f4f6;
                }

                .filter-tab.active {
                    background: #1C56E1;
                    color: white;
                    border-color: #1C56E1;
                }

                .notifications-container {
                    padding: 24px;
                    display: flex;
                    flex-direction: column;
                    gap: 16px;
                }

                .notification-item {
                    display: flex;
                    gap: 16px;
                    padding: 16px;
                    border-radius: 12px;
                    background: white;
                    border: 1px solid #e5e7eb;
                    transition: all 0.2s;
                    position: relative;
                }

                .notification-item:hover {
                    border-color: #1C56E1;
                    box-shadow: 0 4px 12px rgba(28, 86, 225, 0.05);
                }

                .notification-icon {
                    width: 40px;
                    height: 40px;
                    border-radius: 10px;
                    background: #eff6ff;
                    color: #1C56E1;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    flex-shrink: 0;
                }

                .notification-content h4 {
                    margin: 0 0 4px;
                    font-size: 16px;
                    font-weight: 600;
                    color: #111827;
                }

                .notification-content p {
                    margin: 0 0 8px;
                    font-size: 14px;
                    color: #4b5563;
                    line-height: 1.5;
                }

                .notification-time {
                    font-size: 12px;
                    color: #9ca3af;
                }

                .notification-link {
                    color: #1C56E1;
                    font-weight: 500;
                    text-decoration: none;
                    display: inline-flex;
                    align-items: center;
                    margin-right: 10px;
                    font-size: 14px;
                }

                .notification-link:hover {
                    text-decoration: underline;
                }

                @media (max-width: 768px) {
                    .notification-modal-content {
                        width: 95%;
                        max-height: 95vh;
                    }
                }

                /* Search Modal Styles */
                .search-modal {
                    position: fixed;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    z-index: 10000;
                    display: none;
                    opacity: 0;
                    transition: opacity 0.3s ease;
                    backdrop-filter: blur(5px);
                }

                .search-modal.active {
                    display: flex;
                    opacity: 1;
                }

                .search-modal-overlay {
                    position: absolute;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    background: rgba(0, 0, 0, 0.6);
                }

                .search-modal-content {
                    position: relative;
                    width: 90%;
                    max-width: 800px;
                    margin: 5% auto;
                    background: #ffffff;
                    border-radius: 16px;
                    box-shadow: 0 25px 50px rgba(0, 0, 0, 0.25);
                    animation: modalSlideIn 0.3s ease-out;
                    overflow: hidden;
                    display: flex;
                    flex-direction: column;
                }

                @keyframes modalSlideIn {
                    from {
                        transform: translateY(-50px);
                        opacity: 0;
                    }

                    to {
                        transform: translateY(0);
                        opacity: 1;
                    }
                }

                .search-modal-header {
                    display: flex;
                    justify-content: flex-end;
                    padding: 16px 20px 0;
                }

                .search-modal-close {
                    background: none;
                    border: none;
                    padding: 8px;
                    cursor: pointer;
                    border-radius: 8px;
                    color: #6b7280;
                    transition: all 0.2s ease;
                }

                .search-modal-close:hover {
                    background: #f3f4f6;
                    color: #374151;
                }

                .search-modal-body {
                    padding: 0 24px 24px;
                    flex: 1;
                    overflow-y: auto;
                }

                .search-input-container {
                    position: relative;
                    display: flex;
                    align-items: center;
                    background: #f9fafb;
                    border: 2px solid #e5e7eb;
                    border-radius: 12px;
                    padding: 0 16px;
                    transition: all 0.2s ease;
                    margin-bottom: 24px;
                }

                .search-input-container:focus-within {
                    border-color: #1e4e64;
                    background: #ffffff;
                    box-shadow: 0 0 0 3px rgba(30, 78, 100, 0.1);
                }

                .search-icon {
                    color: #9ca3af;
                    margin-right: 12px;
                    flex-shrink: 0;
                }

                .search-modal-input {
                    flex: 1;
                    border: none;
                    background: transparent;
                    outline: none;
                    padding: 16px 0;
                    font-size: 16px;
                    color: #111827;
                    font-family: inherit;
                }

                .search-modal-input::placeholder {
                    color: #9ca3af;
                }

                .search-submit-btn {
                    background: #1e4e64;
                    border: none;
                    border-radius: 8px;
                    padding: 8px;
                    color: white;
                    cursor: pointer;
                    transition: background 0.2s ease;
                    margin-left: 8px;
                    flex-shrink: 0;
                }

                .search-submit-btn:hover {
                    background: #0056b3;
                }

                .search-suggestions {
                    margin-bottom: 24px;
                }

                .suggestions-header {
                    font-size: 14px;
                    font-weight: 600;
                    color: #6b7280;
                    margin-bottom: 12px;
                }

                .suggestion-pills {
                    display: flex;
                    flex-wrap: wrap;
                    gap: 8px;
                }

                .suggestion-pill {
                    background: #f3f4f6;
                    border: 1px solid #e5e7eb;
                    border-radius: 20px;
                    padding: 8px 16px;
                    font-size: 14px;
                    color: #374151;
                    cursor: pointer;
                    transition: all 0.2s ease;
                    white-space: nowrap;
                }

                .suggestion-pill:hover {
                    background: #1e4e64;
                    color: white;
                    border-color: #1e4e64;
                }

                /* AI Summary Styles */
                .search-summary {
                    margin-bottom: 24px;
                    background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
                    border: 1px solid #0ea5e9;
                    border-radius: 12px;
                    padding: 20px;
                    position: relative;
                    overflow: hidden;
                }

                .search-summary::before {
                    content: '';
                    position: absolute;
                    top: 0;
                    left: 0;
                    right: 0;
                    height: 3px;
                    background: linear-gradient(90deg, #0ea5e9, #3b82f6, #8b5cf6);
                    animation: shimmer 2s linear infinite;
                }

                @keyframes shimmer {
                    0% {
                        transform: translateX(-100%);
                    }

                    100% {
                        transform: translateX(100%);
                    }
                }

                .summary-header {
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                    margin-bottom: 16px;
                }

                .summary-title {
                    display: flex;
                    align-items: center;
                    gap: 8px;
                    font-weight: 600;
                    color: #0f172a;
                    font-size: 16px;
                }

                .summary-title svg {
                    color: #0ea5e9;
                }

                .summary-regenerate {
                    background: #ffffff;
                    border: 1px solid #e2e8f0;
                    border-radius: 6px;
                    padding: 6px;
                    cursor: pointer;
                    transition: all 0.2s ease;
                    color: #64748b;
                }

                .summary-regenerate:hover {
                    background: #f1f5f9;
                    color: #334155;
                    border-color: #cbd5e1;
                }

                .summary-content {
                    font-size: 15px;
                    line-height: 1.6;
                    color: #334155;
                    margin-bottom: 16px;
                    min-height: 60px;
                }

                .summary-content.loading {
                    display: flex;
                    align-items: center;
                    gap: 12px;
                    color: #64748b;
                    font-style: italic;
                }

                .summary-content.loading::before {
                    content: '';
                    width: 16px;
                    height: 16px;
                    border: 2px solid #e2e8f0;
                    border-top: 2px solid #0ea5e9;
                    border-radius: 50%;
                    animation: spin 1s linear infinite;
                }

                .summary-sources {
                    border-top: 1px solid #e2e8f0;
                    padding-top: 12px;
                }

                .sources-label {
                    font-size: 12px;
                    font-weight: 600;
                    color: #64748b;
                    text-transform: uppercase;
                    letter-spacing: 0.5px;
                }

                .sources-list {
                    display: flex;
                    flex-wrap: wrap;
                    gap: 8px;
                    margin-top: 8px;
                }

                .source-link {
                    background: #ffffff;
                    border: 1px solid #e2e8f0;
                    border-radius: 16px;
                    padding: 4px 12px;
                    font-size: 12px;
                    color: #475569;
                    text-decoration: none;
                    transition: all 0.2s ease;
                    display: inline-flex;
                    align-items: center;
                    gap: 4px;
                    max-width: 200px;
                    overflow: hidden;
                    text-overflow: ellipsis;
                    white-space: nowrap;
                }

                .source-link:hover {
                    background: #f8fafc;
                    border-color: #cbd5e1;
                    color: #334155;
                }

                .source-link::before {
                    content: '';
                    width: 6px;
                    height: 6px;
                    background: #22c55e;
                    border-radius: 50%;
                    flex-shrink: 0;
                }

                .search-results {
                    max-height: 400px;
                    overflow-y: auto;
                }

                .results-header {
                    font-size: 14px;
                    font-weight: 600;
                    color: #6b7280;
                    margin-bottom: 16px;
                    padding-bottom: 8px;
                    border-bottom: 1px solid #e5e7eb;
                }

                .results-container {
                    display: flex;
                    flex-direction: column;
                    gap: 12px;
                }

                .search-result-item {
                    background: #ffffff;
                    border: 1px solid #e5e7eb;
                    border-radius: 12px;
                    padding: 16px;
                    cursor: pointer;
                    transition: all 0.2s ease;
                }

                .search-result-item:hover {
                    border-color: #1e4e64;
                    box-shadow: 0 4px 12px rgba(30, 78, 100, 0.1);
                }

                .result-title {
                    font-size: 16px;
                    font-weight: 600;
                    color: #1e4e64;
                    margin-bottom: 8px;
                    line-height: 1.4;
                }

                .result-snippet {
                    font-size: 14px;
                    color: #6b7280;
                    line-height: 1.5;
                    margin-bottom: 8px;
                }

                .result-url {
                    font-size: 12px;
                    color: #059669;
                    text-decoration: none;
                }

                .search-loading {
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                    justify-content: center;
                    padding: 40px;
                    color: #6b7280;
                }

                .loading-spinner {
                    width: 32px;
                    height: 32px;
                    border: 3px solid #e5e7eb;
                    border-top: 3px solid #1e4e64;
                    border-radius: 50%;
                    animation: spin 1s linear infinite;
                    margin-bottom: 12px;
                }

                @keyframes spin {
                    0% {
                        transform: rotate(0deg);
                    }

                    100% {
                        transform: rotate(360deg);
                    }
                }

                /* Responsive Design for Search Modal */
                @media (max-width: 768px) {
                    .search-modal-content {
                        width: 95%;
                        margin: 2% auto;
                        max-height: 95vh;
                    }

                    .search-modal-body {
                        padding: 0 16px 16px;
                    }

                    .search-input-container {
                        padding: 0 12px;
                    }

                    .search-modal-input {
                        padding: 14px 0;
                        font-size: 16px;
                    }

                    .suggestion-pills {
                        gap: 6px;
                    }

                    .suggestion-pill {
                        padding: 6px 12px;
                        font-size: 13px;
                    }
                }

                /* Notification Bell Styles */
                .td_notification_btn {
                    position: relative;
                }

                .notification-badge {
                    position: absolute;
                    top: -5px;
                    right: -5px;
                    background: #dc3545;
                    color: white;
                    border-radius: 50%;
                    width: 18px;
                    height: 18px;
                    font-size: 10px;
                    font-weight: bold;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    border: 2px solid white;
                }

                /* Notification Modal Styles */
                .notification-modal {
                    position: fixed;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    z-index: 10000;
                    display: none;
                    opacity: 0;
                    transition: opacity 0.3s ease-in-out;
                    backdrop-filter: blur(8px);
                }

                .notification-modal.active {
                    display: flex;
                    opacity: 1;
                    align-items: center;
                    justify-content: center;
                }

                .notification-modal-overlay {
                    position: absolute;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    background: rgba(0, 0, 0, 0.6);
                    backdrop-filter: blur(5px);
                }

                .notification-modal-content {
                    position: relative;
                    background: white;
                    border-radius: 20px;
                    width: 90%;
                    max-width: 1200px;
                    max-height: 90vh;
                    overflow: hidden;
                    box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
                    transform: scale(0.95);
                    transition: transform 0.3s ease-in-out;
                    z-index: 10001;
                }

                .notification-modal.active .notification-modal-content {
                    transform: scale(1);
                }

                .notification-modal-header {
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                    padding: 20px 24px;
                    border-bottom: 1px solid #e5e7eb;
                    background: #f8f9fa;
                }

                .notification-modal-header h3 {
                    margin: 0;
                    font-size: 20px;
                    font-weight: 600;
                    color: #1f2937;
                }

                .notification-modal-close {
                    background: none;
                    border: none;
                    color: #6b7280;
                    cursor: pointer;
                    padding: 8px;
                    border-radius: 50%;
                    transition: all 0.2s ease;
                }

                .notification-modal-close:hover {
                    background: #e5e7eb;
                    color: #374151;
                }

                .notification-modal-body {
                    padding: 0;
                    max-height: 60vh;
                    overflow-y: auto;
                }

                .notification-filters {
                    padding: 20px 24px 0;
                    border-bottom: 1px solid #e5e7eb;
                    background: white;
                    position: sticky;
                    top: 0;
                    z-index: 1;
                }

                .filter-tabs {
                    display: flex;
                    gap: 8px;
                    flex-wrap: wrap;
                    margin-bottom: 16px;
                }

                .filter-tab {
                    padding: 8px 16px;
                    border: 1px solid #d1d5db;
                    background: white;
                    color: #6b7280;
                    border-radius: 20px;
                    font-size: 14px;
                    cursor: pointer;
                    transition: all 0.2s ease;
                    white-space: nowrap;
                }

                .filter-tab:hover {
                    border-color: #1e4e64;
                    color: #1e4e64;
                }

                .filter-tab.active {
                    background: #1e4e64;
                    color: white;
                    border-color: #1e4e64;
                }

                .filter-badge {
                    position: absolute;
                    top: -2px;
                    right: -2px;
                    background: #dc2626;
                    color: white;
                    border-radius: 50%;
                    width: 16px;
                    height: 16px;
                    font-size: 10px;
                    font-weight: 600;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    border: 2px solid white;
                    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.12);
                }

                .filter-badge:empty {
                    display: none;
                }

                .filter-tab {
                    position: relative;
                }

                .notifications-container {
                    padding: 16px 0;
                }

                .notification-item {
                    display: flex;
                    align-items: flex-start;
                    gap: 16px;
                    padding: 16px 24px;
                    border-bottom: 1px solid #f3f4f6;
                    transition: background-color 0.2s ease;
                    position: relative;
                }

                .notification-item:hover {
                    background: #f8f9fa;
                }

                .notification-item:last-child {
                    border-bottom: none;
                }

                .notification-icon {
                    width: 40px;
                    height: 40px;
                    border-radius: 50%;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    flex-shrink: 0;
                }

                .notification-icon.engineering {
                    background: #e7f3ff;
                    color: #0066cc;
                }

                .notification-icon.pharmacy {
                    background: #f0fff4;
                    color: #16a34a;
                }

                .notification-icon.management {
                    background: #fef7e7;
                    color: #d97706;
                }

                .notification-icon.computer {
                    background: #f3e8ff;
                    color: #7c3aed;
                }

                .notification-icon.hotel {
                    background: #fef2f2;
                    color: #dc2626;
                }

                .notification-icon.dsw {
                    background: #e0f2fe;
                    color: #0277bd;
                }

                .notification-icon.rise {
                    background: #fff8e1;
                    color: #f57c00;
                }

                .notification-content {
                    flex: 1;
                }

                .notification-content h4 {
                    margin: 0 0 8px 0;
                    font-size: 16px;
                    font-weight: 600;
                    color: #1f2937;
                }

                .notification-content p {
                    margin: 0 0 8px 0;
                    font-size: 14px;
                    color: #6b7280;
                    line-height: 1.5;
                }

                .notification-time {
                    font-size: 12px;
                    color: #9ca3af;
                }

                .notification-status {
                    position: absolute;
                    top: 16px;
                    right: 16px;
                    width: 8px;
                    height: 8px;
                    border-radius: 50%;
                    flex-shrink: 0;
                }

                .notification-status.unread {
                    background: #dc3545;
                }

                .notification-status.read {
                    background: #e5e7eb;
                }

                /* Responsive Design for Notification Modal */
                @media (max-width: 768px) {
                    .notification-modal-content {
                        width: 95%;
                        margin: 2% auto;
                        max-height: 90vh;
                    }

                    .notification-modal-header {
                        padding: 16px 20px;
                    }

                    .notification-filters {
                        padding: 16px 20px 0;
                    }

                    .filter-tabs {
                        gap: 6px;
                    }

                    .filter-tab {
                        padding: 6px 12px;
                        font-size: 13px;
                    }

                    .notification-item {
                        padding: 12px 20px;
                        gap: 12px;
                    }

                    .notification-icon {
                        width: 36px;
                        height: 36px;
                    }

                    .notification-content h4 {
                        font-size: 15px;
                    }

                    .notification-content p {
                        font-size: 13px;
                    }
                }

                @media (max-width: 480px) {
                    .notification-modal-content {
                        width: 98%;
                        margin: 1% auto;
                    }

                    .filter-tabs {
                        justify-content: flex-start;
                    }

                    .filter-tab {
                        padding: 5px 10px;
                        font-size: 12px;
                    }
                }
                </style>
                <div class="td_top_bar_right" style="
              display: flex;
              align-items: center;
              gap: 20px;
              margin-right: 10px;
            ">


                    <a rel="nofollow" href="https://www.facebook.com/infomathsindia"><i
                            class="fa-brands fa-facebook-f"></i></a>
                    <!-- <a rel="nofollow" href="https://twitter.com/i9872124534"><i class="fa-brands fa-x-twitter"></i></a> -->
                    <a rel="nofollow" href="https://www.instagram.com/infomaths.coursedu/"><i
                            class="fa-brands fa-instagram"></i></a>

                    <a target="_blank" name="infomathsyoutube" rel="nofollow"
                        href="https://www.youtube.com/user/arpana2311"><i class="fa-brands fa-youtube"></i></a>
                    <a rel="nofollow"
                        href="https://www.linkedin.com/company/infomaths-studies-pvt-ltd/?originalSubdomain=in"><i
                            class="fa-brands fa-linkedin-in"></i></a>
                    <!-- Add more icons as needed -->
                </div>
            </div>
        </div>
        <div class="td_main_header py-2">
            <div class="container">
                <div class="td_main_header_in">

                    <a class="td_site_branding" href="/">
                        <img src="/assets/img/logo.png" alt="Infomaths" title="Infomaths" class="cgc-logo" />
                        <!-- <style>
                            @media (max-width: 600px) {
                                .cgc-logo {
                                    width: 250px !important;
                                }
                            }
                        </style> -->
                    </a>
                    <div class="r">
                        <nav class="td_nav">
                            <div class="td_nav_list_wrap">
                                <div class="td_nav_list_wrap_in">
                                    <ul class="td_nav_list">
                                        <li class="menu-item-has-children">
                                            <a href="javascript:void(0)">About</a>
                                            <ul class="hide">
                                                <li><a href="/about-infomaths.php">
                                                        <p>About Infomaths</p>
                                                    </a></li>
                                                <li><a href="/philosophy.php">
                                                        <p>Our Philosophy</p>
                                                    </a></li>
                                                <li><a href="/belief.php">
                                                        <p>Our Belief</p>
                                                    </a></li>
                                            </ul>
                                        </li>
                                        <li class="menu-item-has-children">
                                            <a href="javascript:void(0)">Courses</a>
                                            <ul class="hide">
                                                <li><a href="/mca-entrance.php">
                                                        <p>MCA Entrance</p>
                                                    </a></li>
                                                <li class="menu-item-has-children bclrgrnl"><a
                                                        href="/best-coaching-for-bank-po-ssc.php">
                                                        <p>Bank PO SSC </p>
                                                    </a>
                                                    <ul>
                                                        <!-- SBI SECTION -->
                                                        <?php if (!empty($exam_categories['SBI'])): ?>
                                                        <li class="menu-item-has-children"><a href="#">
                                                                <p>SBI</p>
                                                            </a>
                                                            <ul>
                                                                <?php foreach ($exam_categories['SBI'] as $menu_exam): ?>
                                                                <li><a
                                                                        href="/bank-po-exam/<?php echo htmlspecialchars($menu_exam['slug']); ?>">
                                                                        <p><?php echo htmlspecialchars($menu_exam['exam_name']); ?>
                                                                        </p>
                                                                    </a></li>
                                                                <?php endforeach; ?>
                                                            </ul>
                                                        </li>
                                                        <?php endif; ?>

                                                        <!-- RBI SECTION -->
                                                        <?php if (!empty($exam_categories['RBI'])): ?>
                                                        <li class="menu-item-has-children"><a href="#">
                                                                <p>RBI</p>
                                                            </a>
                                                            <ul>
                                                                <?php foreach ($exam_categories['RBI'] as $menu_exam): ?>
                                                                <li><a
                                                                        href="/bank-po-exam/<?php echo htmlspecialchars($menu_exam['slug']); ?>">
                                                                        <p><?php echo htmlspecialchars($menu_exam['exam_name']); ?>
                                                                        </p>
                                                                    </a></li>
                                                                <?php endforeach; ?>
                                                            </ul>
                                                        </li>
                                                        <?php endif; ?>

                                                        <!-- IBPS SECTION -->
                                                        <?php if (!empty($exam_categories['IBPS'])): ?>
                                                        <li class="menu-item-has-children"><a href="#">
                                                                <p>IBPS</p>
                                                            </a>
                                                            <ul>
                                                                <?php foreach ($exam_categories['IBPS'] as $menu_exam): ?>
                                                                <li><a
                                                                        href="/bank-po-exam/<?php echo htmlspecialchars($menu_exam['slug']); ?>">
                                                                        <p><?php echo htmlspecialchars($menu_exam['exam_name']); ?>
                                                                        </p>
                                                                    </a></li>
                                                                <?php endforeach; ?>
                                                            </ul>
                                                        </li>
                                                        <?php endif; ?>

                                                        <!-- RRBs SECTION -->
                                                        <?php if (!empty($exam_categories['RRBs'])): ?>
                                                        <li class="menu-item-has-children"><a href="#">
                                                                <p>RRBs</p>
                                                            </a>
                                                            <ul>
                                                                <?php foreach ($exam_categories['RRBs'] as $menu_exam): ?>
                                                                <li><a
                                                                        href="/bank-po-exam/<?php echo htmlspecialchars($menu_exam['slug']); ?>">
                                                                        <p><?php echo htmlspecialchars($menu_exam['exam_name']); ?>
                                                                        </p>
                                                                    </a></li>
                                                                <?php endforeach; ?>
                                                            </ul>
                                                        </li>
                                                        <?php endif; ?>

                                                        <!-- SSC SECTION -->
                                                        <?php if (!empty($exam_categories['SSC'])): ?>
                                                        <li class="menu-item-has-children"><a href="#">
                                                                <p>SSC</p>
                                                            </a>
                                                            <ul>
                                                                <?php foreach ($exam_categories['SSC'] as $menu_exam): ?>
                                                                <li><a
                                                                        href="/bank-po-exam/<?php echo htmlspecialchars($menu_exam['slug']); ?>">
                                                                        <p><?php echo htmlspecialchars($menu_exam['exam_name']); ?>
                                                                        </p>
                                                                    </a></li>
                                                                <?php endforeach; ?>
                                                            </ul>
                                                        </li>
                                                        <?php endif; ?>

                                                        <!-- OTHERS SECTION (If any exams don't fit above) -->
                                                        <?php if (!empty($exam_categories['Other'])): ?>
                                                        <li class="menu-item-has-children"><a href="#">
                                                                <p>Other Exams</p>
                                                            </a>
                                                            <ul>
                                                                <?php foreach ($exam_categories['Other'] as $menu_exam): ?>
                                                                <li><a
                                                                        href="/bank-po-exam/<?php echo htmlspecialchars($menu_exam['slug']); ?>">
                                                                        <p><?php echo htmlspecialchars($menu_exam['exam_name']); ?>
                                                                        </p>
                                                                    </a></li>
                                                                <?php endforeach; ?>
                                                            </ul>
                                                        </li>
                                                        <?php endif; ?>

                                                    </ul>
                                                </li>
                                                <li><a href="coaching-for-iit-jam-mathematics.php">
                                                        <p>IIT JAM Maths</p>
                                                    </a></li>
                                                <li><a href="/csir-net-jrf.php">
                                                        <p>CSIR NET JRF</p>
                                                    </a></li>

                                                <li><a href="/bca.php">
                                                        <p>BCA Subject Classes</p>
                                                    </a></li>
                                                <li><a href="/bsc.php">
                                                        <p>B.Sc Subject Classes</p>
                                                    </a></li>
                                                <li><a href="/bsc.php">
                                                        <p>Campus Placements</p>
                                                    </a></li>
                                                <li><a href="/bsc.php">
                                                        <p>Internships Training</p>
                                                    </a></li>

                                            </ul>
                                        </li>
                                        <li><a href="/faculty.php">Faculty</a></li>
                                        <li><a href="/testimonials.php">Testimonials</a></li>
                                        <li><a href="/blogs.php">Blog</a></li>
                                        <li class="menu-item-has-children"><a href="javascript:void(0)">Result</a>
                                            <ul class="hide">
                                                <li><a href="/mca-result.php">
                                                        <p>MCA Entrance Result</p>
                                                    </a></li>
                                                <li><a href="/courses-result.php">
                                                        <p>Other Courses Result</p>
                                                    </a></li>
                                            </ul>
                                        </li>
                                        <li><a href="/contact.php">Contact</a></li>
                                    </ul>
                                </div>
                            </div>
                        </nav>
                    </div>
                    <div class="td_main_header_right">


                        <div class="position-relative">
                            <!-- <button class="td_circle_btn td_center td_search_tobble_btn" type="button"
                                id="searchModalTrigger">
                                <img src="assets/img/icons/search_2.svg" alt="" />
                            </button> -->
                            <!-- <div class="td_header_search_wrap">
                  <form action="#" class="td_header_search">
                    <input
                      type="text"
                      class="td_header_search_input"
                      placeholder="Search For Anything"
                    />
                    <button class="td_header_search_btn td_center">
                      <img src="assets/img/icons/search_2.svg" alt="" />
                    </button>
                  </form>
                </div> -->
                        </div>
                        <div class="position-relative">
                            <button class="td_circle_btn td_center td_notification_btn" type="button"
                                id="notificationModalTrigger">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9" stroke="black"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M13.73 21A2 2 0 0 1 9.27 21" stroke="black" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <span class="notification-badge">5</span>
                            </button>
                        </div>
                        <button class="td_hamburger_btn"></button>
                    </div>
                    <!-- <div class="td_main_header_right">
              <div
                style="
                  display: flex;
                  align-items: center;
                  justify-content: flex-end;
                  height: 100%;
                "
              >
                <div
                  style="
                    background: #fff;
                    border-radius: 10px;
                    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
                    padding: 8px 12px;
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                  "
                >
                  <span
                    style="
                      color: #1565c0;
                      font-size: 16px;
                      font-weight: 600;
                      margin-bottom: 4px;
                    "
                    >Admission Helpline</span
                  >
                  <div style="display: flex; align-items: center">
                    <span
                      style="color: #1565c0; font-size: 18px; font-weight: 700"
                      >1800-200-3575</span
                    >
                  </div>
                </div>
              </div>
            </div> -->
                </div>
            </div>
        </div>
    </header>
    <div class="td_side_header">
        <button class="td_close"></button>
        <div class="td_side_header_overlay"></div>
        <div class="td_side_header_in">
            <div class="td_side_header_shape"></div>

            <div class="td_side_header_box">
                <h2 class="td_side_header_heading">

                    Connect with Infomaths.
                </h2>
            </div>
            <div class="td_side_header_box">
                <h3 class="td_side_header_title td_heading_color">Contact Us</h3>
                <ul class="td_side_header_contact_info td_mp_0">
                    <li>
                        <i class="fa-solid fa-phone"></i>
                        <span><a href="tel:+919872124534">+91-98721-24534</a></span>
                    </li>
                    <li>
                        <i class="fa-solid fa-envelope"></i>
                        <span><a href="mailto:info@infomathsinstitute.com">hrinfomaths@gmail.com</a></span>
                    </li>
                    <li>
                        <i class="fa-solid fa-location-dot"></i>
                        <span> <a href="https://maps.app.goo.gl/AoR8fQecc1aEBvTh7">Quiet Office 10, Second Floor,
                                <br />Sector 35 A, Chandigarh, 160035</a></span>
                    </li>
                </ul>
            </div>

            <div class="td_side_header_box">
                <h3 class="td_side_header_title td_heading_color">Follow Us</h3>
                <div class="td_social_btns td_style_1 td_heading_color">
                    <a href="https://www.facebook.com/infomathsindia" target="_blank" class="td_center">
                        <i class="fa-brands fa-facebook-f"></i>
                    </a>
                    <!-- <a href="https://twitter.com/i9872124534" target="_blank" class="td_center">
                        <i class="fa-brands fa-x-twitter"></i>
                    </a> -->
                    <a href="https://www.youtube.com/user/arpana2311" target="_blank" class="td_center">
                        <i class="fa-brands fa-youtube"></i>
                    </a>
                    <a href="https://www.instagram.com/infomaths.coursedu/" target="_blank" class="td_center">
                        <i class="fa-brands fa-instagram"></i>
                    </a>
                    <a rel="nofollow"
                        href="https://www.linkedin.com/company/infomaths-studies-pvt-ltd/?originalSubdomain=in"
                        target="_blank" class="td_center"><i class="fa-brands fa-linkedin-in"></i></a>
                </div>
            </div>
        </div>
    </div>
    <!-- End Header Section -->

    <!-- ChatGPT Style Search Modal -->
    <div id="searchModal" class="search-modal">
        <div class="search-modal-overlay"></div>
        <div class="search-modal-content">
            <div class="search-modal-header">
                <button class="search-modal-close" id="closeSearchModal">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M18 6L6 18" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" />
                        <path d="M6 6L18 18" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                </button>
            </div>
            <div class="search-modal-body">
                <div class="search-input-container">
                    <svg class="search-icon" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <circle cx="11" cy="11" r="8" stroke="currentColor" stroke-width="2" />
                        <path d="M21 21L16.65 16.65" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                    <input type="text" id="modalSearchInput" class="search-modal-input"
                        placeholder="Search CGC Landran..." autocomplete="off" />
                    <button class="search-submit-btn" id="submitSearch">AI Search
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M5 12h14" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path d="M12 5l7 7-7 7" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>
                    </button>
                </div>

                <div class="search-suggestions">
                    <div class="suggestions-header">
                        <span>Popular searches</span>
                    </div>
                    <div class="suggestion-pills">
                        <button class="suggestion-pill" data-query="cgc fees">CGC Fees</button>
                        <button class="suggestion-pill" data-query="cgc admission process">Admission Process</button>
                        <button class="suggestion-pill" data-query="cgc placement">Placement Records</button>
                        <button class="suggestion-pill" data-query="cgc courses">Courses Offered</button>
                        <button class="suggestion-pill" data-query="cgc campus tour">Campus Tour</button>
                        <button class="suggestion-pill" data-query="cgc scholarship">Scholarships</button>
                    </div>
                </div>

                <!-- AI Summary Section -->
                <div class="search-summary" id="searchSummary" style="display: none;">
                    <div class="summary-header">
                        <div class="summary-title">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M9.5 3A6.5 6.5 0 0 0 3 9.5v5A6.5 6.5 0 0 0 9.5 21v-7.5h11.5V9.5A6.5 6.5 0 0 0 14.5 3h-5z"
                                    stroke="currentColor" stroke-width="2" />
                            </svg>
                            <span>AI Summary</span>
                        </div>
                        <button class="summary-regenerate" id="regenerateSummary" title="Regenerate Summary">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M21 12c0 4.97-4.03 9-9 9s-9-4.03-9-9 4.03-9 9-9c2.03 0 3.89.67 5.39 1.8l-2.39 2.2"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>
                        </button>
                    </div>
                    <div class="summary-content" id="summaryContent">
                        <!-- AI generated summary will appear here -->
                    </div>
                    <div class="summary-sources">
                        <span class="sources-label">Sources:</span>
                        <div class="sources-list" id="sourcesList">
                            <!-- Source links will appear here -->
                        </div>
                    </div>
                </div>

                <div class="search-results" id="searchResults" style="display: none;">
                    <div class="results-header">
                        <span class="results-count" id="resultsCount">Search Results</span>
                    </div>
                    <div class="results-container" id="resultsContainer">
                        <!-- Search results will be populated here -->
                    </div>
                </div>

                <div class="search-loading" id="searchLoading" style="display: none;">
                    <div class="loading-spinner"></div>
                    <span>Searching...</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Notification Modal -->
    <div id="notificationModal" class="notification-modal">
        <div class="notification-modal-overlay"></div>
        <div class="notification-modal-content">
            <div class="notification-modal-header">
                <h3>Notifications</h3>
                <button class="notification-modal-close" id="closeNotificationModal">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M18 6L6 18" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" />
                        <path d="M6 6L18 18" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                </button>
            </div>
            <div class="notification-modal-body">
                <!-- Department/College Filter -->
                <div class="notification-filters">

                    <?php
                    // Fetch notifications first for filter tabs
                    try {
                        $stmt = $pdo->prepare("SELECT * FROM notifications WHERE is_active = 1 ORDER BY priority DESC, created_at DESC");
                        $stmt->execute();
                        $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    } catch (PDOException $e) {
                        $notifications = array(); // Empty array if database error
                    }

                    // Collect unique categories for filter tabs
                    $uniqueCategories = [];

                    if (!empty($notifications)) {
                        foreach ($notifications as $notif) {
                            $catName = trim(strtolower($notif['category']));
                            if (!in_array($catName, $uniqueCategories) && !empty($catName)) {
                                $uniqueCategories[] = $catName;
                            }
                        }
                        sort($uniqueCategories);
                    }
                    ?>
                    <div class="filter-tabs">
                        <button class="filter-tab active" data-category="all">
                            All Notifications
                            <span class="filter-badge" id="badge-all"></span>
                        </button>
                        <?php foreach ($uniqueCategories as $uniqueCat): ?>
                        <?php
                            $displayName = ucwords($uniqueCat);
                            $badgeId = 'badge-' . $uniqueCat;
                            ?>
                        <button class="filter-tab" data-category="<?php echo htmlspecialchars($uniqueCat); ?>">
                            <?php echo htmlspecialchars($displayName); ?>
                            <span class="filter-badge" id="<?php echo htmlspecialchars($badgeId); ?>"></span>
                        </button>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Notifications Container -->
                <div class="notifications-container" id="notificationsContainer">
                    <?php
                    try {
                        // Fetch active notifications from database
                        $stmt = $pdo->prepare("SELECT * FROM notifications WHERE is_active = 1 ORDER BY priority DESC, created_at DESC");
                        $stmt->execute();
                        $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        if (!empty($notifications)) {
                            foreach ($notifications as $notification) {
                                // Determine icon class based on category
                                $iconClass = 'general';
                                switch (strtolower($notification['category'])) {
                                    case 'engineering':
                                    case 'cec':
                                    case 'coe':
                                        $iconClass = 'engineering';
                                        break;
                                    case 'pharmacy':
                                    case 'ccp':
                                        $iconClass = 'pharmacy';
                                        break;
                                    case 'management':
                                    case 'cbsa':
                                        $iconClass = 'management';
                                        break;
                                    case 'computer':
                                    case 'cct':
                                        $iconClass = 'computer';
                                        break;
                                    case 'hotel':
                                    case 'cchmct':
                                        $iconClass = 'hotel';
                                        break;
                                    case 'dsw':
                                        $iconClass = 'dsw';
                                        break;
                                    case 'rise':
                                        $iconClass = 'rise';
                                        break;
                                }

                                // Build category data attribute for filtering
                                $catName = trim(strtolower($notification['category']));

                                // Calculate relative time
                                $createdTime = strtotime($notification['created_at']);
                                $now = time();
                                $diff = $now - $createdTime;

                                if ($diff < 60) {
                                    $timeAgo = 'Just now';
                                } elseif ($diff < 3600) {
                                    $minutes = floor($diff / 60);
                                    $timeAgo = $minutes . ' minute' . ($minutes > 1 ? 's' : '') . ' ago';
                                } elseif ($diff < 86400) {
                                    $hours = floor($diff / 3600);
                                    $timeAgo = $hours . ' hour' . ($hours > 1 ? 's' : '') . ' ago';
                                } elseif ($diff < 604800) {
                                    $days = floor($diff / 86400);
                                    $timeAgo = $days . ' day' . ($days > 1 ? 's' : '') . ' ago';
                                } else {
                                    $timeAgo = date('M j, Y', $createdTime);
                                }

                                echo '<div class="notification-item" data-category="' . htmlspecialchars($catName) . '" data-id="notification-' . $notification['id'] . '">';
                                echo '<div class="notification-icon ' . $iconClass . '">';

                                // Icon based on category
                                switch ($iconClass) {
                                    case 'engineering':
                                        echo '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M12 2L2 7v10c0 5.55 3.84 9.74 9 11 5.16-1.26 9-5.45 9-11V7l-10-5z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                              </svg>';
                                        break;
                                    case 'pharmacy':
                                        echo '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M4 4h16v16H4V4z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                <path d="M9 12h6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                <path d="M12 9v6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                              </svg>';
                                        break;
                                    case 'management':
                                        echo '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                <circle cx="8.5" cy="7" r="4" stroke="currentColor" stroke-width="2" />
                                                <path d="M20 8v6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                <path d="M23 11h-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                              </svg>';
                                        break;
                                    case 'computer':
                                        echo '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <rect x="2" y="3" width="20" height="14" rx="2" ry="2" stroke="currentColor" stroke-width="2" />
                                                <line x1="8" y1="21" x2="16" y2="21" stroke="currentColor" stroke-width="2" />
                                                <line x1="12" y1="17" x2="12" y2="21" stroke="currentColor" stroke-width="2" />
                                              </svg>';
                                        break;
                                    case 'hotel':
                                        echo '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M3 9v12h18V9" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                <path d="M21 7L12 2 3 7v2h18V7z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                <path d="M10 12v6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                <path d="M14 12v6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                              </svg>';
                                        break;
                                    case 'dsw':
                                        echo '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                <circle cx="9" cy="7" r="4" stroke="currentColor" stroke-width="2" />
                                                <path d="M23 21v-2a4 4 0 0 0-3-3.87" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                <path d="M16 3.13a4 4 0 0 1 0 7.75" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                              </svg>';
                                        break;
                                    case 'rise':
                                        echo '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M22 12h-4l-3 9L9 3l-3 9H2" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                              </svg>';
                                        break;
                                    default:
                                        echo '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="2" />
                                                <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z" stroke="currentColor" stroke-width="2" />
                                              </svg>';
                                }

                                echo '</div>';
                                echo '<div class="notification-content">';
                                echo '<h4>' . htmlspecialchars($notification['title']) . '</h4>';
                                echo '<p>' . htmlspecialchars($notification['message']) . '</p>';

                                // Add clickable link if provided
                                if (!empty($notification['link_url']) && !empty($notification['link_text'])) {
                                    echo '<a href="' . htmlspecialchars($notification['link_url']) . '" class="notification-link" target="_blank" rel="noopener noreferrer">';
                                    echo htmlspecialchars($notification['link_text']);
                                    echo ' <svg width="12" height="12" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="display: inline; margin-left: 4px;">';
                                    echo '<path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>';
                                    echo '<polyline points="15,3 21,3 21,9" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>';
                                    echo '<line x1="10" y1="14" x2="21" y2="3" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>';
                                    echo '</svg></a>';
                                }

                                echo '<span class="notification-time">' . htmlspecialchars($timeAgo) . '</span>';
                                echo '</div>';
                                echo '<span class="notification-status unread"></span>';
                                echo '</div>';
                            }
                        } else {
                            // No notifications found - show a default message
                            echo '<div class="notification-item" data-category="all" data-id="no-notifications">';
                            echo '<div class="notification-icon general">';
                            echo '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">';
                            echo '<circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="2" />';
                            echo '<path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z" stroke="currentColor" stroke-width="2" />';
                            echo '</svg>';
                            echo '</div>';
                            echo '<div class="notification-content">';
                            echo '<h4>Stay Updated</h4>';
                            echo '<p>No new notifications at the moment. Check back later for updates from CGC Landran.</p>';
                            echo '<span class="notification-time">Just now</span>';
                            echo '</div>';
                            echo '<span class="notification-status read"></span>';
                            echo '</div>';
                        }
                    } catch (PDOException $e) {
                        // Database error - show a fallback message
                        echo '<div class="notification-item" data-category="all" data-id="system-notification">';
                        echo '<div class="notification-icon general">';
                        echo '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">';
                        echo '<circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="2" />';
                        echo '<path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z" stroke="currentColor" stroke-width="2" />';
                        echo '</svg>';
                        echo '</div>';
                        echo '<div class="notification-content">';
                        echo '<h4>System Notification</h4>';
                        echo '<p>Unable to load notifications at this time. Please try again later.</p>';
                        echo '<span class="notification-time">Just now</span>';
                        echo '</div>';
                        echo '<span class="notification-status read"></span>';
                        echo '</div>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>