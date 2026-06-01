<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback Questions Management - Counselign</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="<?= base_url('Photos/counselign.ico') ?>" type="image/x-icon">
    <link rel="stylesheet" href="<?= base_url('css/admin/feedback_questions.css') ?>">
    <style>
        .profile-dropdown-menu.show {
            opacity: 1 !important;
            visibility: visible !important;
            transform: translateY(0) !important;
        }
    </style>
    <?= view('admin/partials/vibe_styles') ?>
</head>

<body class="adm-fq-page-body">
    <!-- Sidebar -->
    <aside class="sidebar" id="uniSidebar">
        <div class="sidebar-content">
            <!-- Logo/Toggle Button -->
            <button class="sidebar-toggle-btn" id="sidebarToggle" title="Toggle Sidebar">
                <img src="<?= base_url('Photos/counselign_logo.png') ?>" alt="Logo" class="sidebar-logo">
                <span class="sidebar-brand-text">Counselign</span>
            </button>

            <!-- Navigation Links -->
            <nav class="sidebar-nav">
                <a href="<?= base_url('admin/dashboard') ?>" class="sidebar-link" title="Dashboard">
                    <i class="fas fa-home"></i>
                    <span class="sidebar-text">Dashboard</span>
                </a>
                <a href="<?= base_url('admin/admins-management') ?>" class="sidebar-link" title="Management">
                    <i class="fas fa-users-cog"></i>
                    <span class="sidebar-text">Management</span>
                </a>
                <a href="<?= base_url('admin/appointments') ?>" class="sidebar-link" title="Recent Appointments">
                    <i class="fas fa-calendar-check"></i>
                    <span class="sidebar-text">Recent Appointments</span>
                </a>
                <a href="<?= base_url('admin/feedback-questions') ?>" class="sidebar-link active" title="Feedback Questions">
                    <i class="fas fa-question-circle"></i>
                    <span class="sidebar-text">Feedback Questions</span>
                </a>
                <a href="<?= base_url('admin/feedback-analytics/view-feedback') ?>" class="sidebar-link" title="View Feedback">
                    <i class="fas fa-comments"></i>
                    <span class="sidebar-text">View Feedback</span>
                </a>
                <a href="<?= base_url('admin/follow-up-sessions') ?>" class="sidebar-link" title="Follow-up Sessions">
                    <i class="fas fa-calendar-days"></i>
                    <span class="sidebar-text">Follow-up Sessions</span>
                </a>
                    <i class="fas fa-chart-bar"></i>
                    <span class="sidebar-text">Data Analytics</span>
                </a>
                <a href="<?= base_url('admin/resources') ?>" class="sidebar-link" title="Resources">
                    <i class="fas fa-folder-open"></i>
                    <span class="sidebar-text">Resources</span>
                </a>
                <a href="<?= base_url('admin/announcements') ?>" class="sidebar-link" title="Announcements">
                    <i class="fa-solid fa-bullhorn"></i>
                    <span class="sidebar-text">Announcements</span>
                </a>
            </nav>
        </div>
    </aside>

    <!-- Sidebar Overlay for Mobile -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Floating Sidebar Toggle for Mobile (shows when sidebar is hidden) -->
    <button class="floating-sidebar-toggle" id="floatingSidebarToggle" title="Open Menu">
        <img src="<?= base_url('Photos/counselign_logo.png') ?>" alt="Menu">
    </button>

    <!-- Toast container -->
    <div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 1050;">
        <div id="statusToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <strong class="me-auto" id="toastTitle">Notification</strong>
                <small id="toastTime">Just now</small>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body" id="toastMessage">
                Operation completed successfully.
            </div>
        </div>
    </div>

    <!-- Main Content Section -->
    <div class="main-wrapper" id="mainWrapper">
        <header class="top-bar">
            <div class="top-bar-left">
                <h1 class="page-title-header">
                    <i class="fas fa-question-circle me-2"></i>
                    Feedback Questions Management
                </h1>
            </div>

            <div class="top-bar-right">
                <!-- Add Question Button -->
                <button class="top-bar-btn" id="addQuestionBtn" title="Add New Question">
                    <i class="fas fa-plus"></i>
                    <span class="btn-label">Add Question</span>
                </button>

                <!-- Profile Dropdown -->
                <div class="profile-dropdown" style="z-index: 1050;">
                    <button class="top-bar-btn profile-btn" id="profileDropdownBtn">
                        <img id="profile-img-top" src="<?= base_url('Photos/UGC-Logo.png') ?>" alt="Profile" class="profile-img-small">
                        <span class="btn-label" id="uniNameTop">Admin</span>
                    </button>

                    <div class="profile-dropdown-menu" id="profileDropdownMenu" style="z-index: 1051; position: absolute; top: calc(100% + 8px); right: 0; background-color: #ffffff; border-radius: 12px; box-shadow: 0 4px 16px rgba(0, 0, 0, 0.15); min-width: 320px; opacity: 0; visibility: hidden; transform: translateY(-10px); transition: all 0.3s ease;">
                        <div class="profile-dropdown-header">
                            <img id="profile-img-dropdown" src="<?= base_url('Photos/UGC-Logo.png') ?>" alt="Profile" class="profile-img-large">
                            <div class="profile-info">
                                <div class="profile-name" id="uniNameDropdown">Admin</div>
                                <div class="profile-subtitle" id="lastLoginDropdown">Loading...</div>
                            </div>
                        </div>
                        <div class="profile-dropdown-divider"></div>
                        <a href="<?= base_url('admin/account-settings') ?>" class="profile-dropdown-item">
                            <i class="fas fa-user-cog"></i>
                            <span>Profile</span>
                        </a>
                        <div class="profile-dropdown-divider"></div>
                        <button class="profile-dropdown-item" onclick="confirmLogout()">
                            <i class="fas fa-sign-out-alt"></i>
                            <span>Log Out</span>
                        </button>
                    </div>
                </div>
            </div>
        </header>

        <main class="main-content">
            <div class="container py-5">
                <!-- Questions List Container -->
                <div class="questions-container bg-white rounded shadow-sm">
                    <div class="questions-header">
                        <h5 class="mb-0"><i class="fas fa-list-alt me-2"></i>Student Feedback Questions</h5>
                        <div class="header-info">
                            <small class="text-muted">Drag and drop to reorder questions</small>
                        </div>
                    </div>

                    <!-- Loading indicator -->
                    <div id="loadingIndicator" class="text-center py-5">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2">Loading questions...</p>
                    </div>

                    <!-- No questions message -->
                    <div id="noQuestionsMessage" class="text-center py-5 d-none">
                        <i class="fas fa-question-circle fa-3x text-muted mb-3"></i>
                        <p class="mb-3">No feedback questions yet. Add your own or load the default set.</p>
                        <button type="button" class="btn btn-outline-primary me-2" id="seedDefaultsBtn">
                            <i class="fas fa-download me-1"></i>Load Default Questions
                        </button>
                        <button type="button" class="btn btn-primary" id="addFirstQuestionBtn">
                            <i class="fas fa-plus me-1"></i>Add Question
                        </button>
                    </div>

                    <!-- Questions List -->
                    <div id="questionsList" class="questions-list d-none">
                        <!-- Questions will be dynamically added here -->
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Add/Edit Question Modal -->
    <div class="modal fade" id="questionModal" tabindex="-1" aria-labelledby="questionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="questionModalLabel">
                        <i class="fas fa-question-circle me-2"></i><span id="modalTitleText">Add Question</span>
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="questionForm">
                        <input type="hidden" id="questionId">
                        <div class="mb-3">
                            <label for="questionText" class="form-label fw-bold">Question Text *</label>
                            <textarea class="form-control" id="questionText" rows="3" required
                                placeholder="Enter the feedback question..."></textarea>
                        </div>
                        <input type="hidden" id="fieldName" value="">
                        <div class="mb-3">
                            <label for="sortOrder" class="form-label fw-bold">Sort Order</label>
                            <input type="number" class="form-control" id="sortOrder" min="1"
                                placeholder="Enter sort order...">
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="isActive" checked>
                            <label class="form-check-label" for="isActive">
                                Active (visible to students)
                            </label>
                        </div>
                    </form>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="saveQuestionBtn">
                        <i class="fas fa-save me-1"></i>Save
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Confirmation Modal -->
    <div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title" id="confirmationModalTitle">Confirm Action</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="confirmationModalBody">
                    <!-- Content will be dynamically inserted -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmActionBtn">Confirm</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script>
        window.BASE_URL = "<?= base_url() ?>";
        window.AUTH_URL = "<?= base_url('auth') ?>";
        window.INITIAL_QUESTIONS = <?= json_encode($questions ?? [], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>;
    </script>
    <script src="<?= base_url('js/utils/secureLogger.js') ?>"></script>
    <script src="<?= base_url('js/utils/sidebar.js') ?>"></script>
    <script src="<?= base_url('js/admin/feedback_questions.js?v=2') ?>"></script>
    <script src="<?= base_url('js/admin/logout.js') ?>"></script>
</body>

</html>
