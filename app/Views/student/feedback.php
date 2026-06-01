<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="Student Feedback - Counselign" />
    <meta name="keywords" content="counseling, feedback, student, university, support" />
    <title>Appointment Feedback - Counselign</title>
    <link rel="icon" href="<?= base_url('Photos/counselign.ico') ?>" sizes="16x16 32x32" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="<?= base_url('css/student/student_feedback.css?v=3') ?>">
    <link rel="stylesheet" href="<?= base_url('css/student/student_vibe_shared.css?v=1') ?>">
    <link rel="stylesheet" href="<?= base_url('css/student/student_notifications_dropdown.css?v=4') ?>">
    <link rel="stylesheet" href="<?= base_url('css/student/header.css') ?>">
    <link rel="stylesheet" href="<?= base_url('css/utils/sidebar.css') ?>">
    <link rel="stylesheet" href="<?= base_url('css/utils/vibe_topbar.css?v=3') ?>">

</head>

<body class="fb-page-body">
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
                <a href="<?= base_url('student/dashboard') ?>" class="sidebar-link" title="Dashboard">
                    <i class="fas fa-home"></i>
                    <span class="sidebar-text">Dashboard</span>
                </a>

                <a href="<?= base_url('student/schedule-appointment') ?>" class="sidebar-link" title="Schedule an Appointment">
                    <i class="fas fa-plus-circle"></i>
                    <span class="sidebar-text">Schedule an Appointment</span>
                </a>

                <a href="<?= base_url('student/my-appointments') ?>" class="sidebar-link active" title="My Appointments">
                    <i class="fas fa-list-alt"></i>
                    <span class="sidebar-text">My Appointments</span>
                </a>

                <a href="<?= base_url('student/follow-up-sessions') ?>" class="sidebar-link" title="Follow-up Sessions">
                    <i class="fas fa-clipboard-list"></i>
                    <span class="sidebar-text">Follow-up Sessions</span>
                </a>
                <a href="<?= base_url('student/announcements') ?>" class="sidebar-link" title="Announcement">
                    <i class="fas fa-bullhorn"></i>
                    <span class="sidebar-text">Announcement</span>
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

    <div class="main-wrapper" id="mainWrapper">
        <!-- Top Bar -->
        <header class="top-bar">
            <div class="top-bar-left">
                <h1 class="page-title-header">
                    <i class="fas fa-star"></i>
                    Appointment Feedback
                </h1>
            </div>

            <div class="top-bar-right">

                <?= view('student/partials/header_actions') ?>

                <!-- Profile Dropdown -->
                <div class="profile-dropdown">
                    <button class="top-bar-btn profile-btn" id="profileDropdownBtn">
                        <img id="profile-img-top" src="<?= base_url('Photos/profile.png') ?>" alt="Profile" class="profile-img-small">
                        <span class="btn-label" id="uniNameTop">Student</span>
                    </button>

                    <div class="profile-dropdown-menu" id="profileDropdownMenu">
                        <div class="profile-dropdown-header">
                            <img id="profile-img-dropdown" src="<?= base_url('Photos/profile.png') ?>" alt="Profile" class="profile-img-large">
                            <div class="profile-info">
                                <div class="profile-name" id="uniNameDropdown">Student</div>
                                <div class="profile-subtitle" id="lastLoginDropdown">Loading...</div>
                            </div>
                        </div>
                        <div class="profile-dropdown-divider"></div>
                        <a href="<?= base_url('student/profile') ?>" class="profile-dropdown-item">
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

        <main class="bg-light p-4">
            <div class="container-fluid px-4">
                <div class="feedback-container">
                    <div class="feedback-header">
                        <h3 class="text-center mb-4">
                            <i class="fas fa-comments text-primary me-2"></i>
                            Help Us Improve Our Counseling Services
                        </h3>
                        <p class="text-muted text-center mb-4">
                            Your feedback is valuable to us. Please take a few minutes to rate your experience with our counseling system.
                            All responses are anonymous and confidential.
                        </p>
                    </div>

                    <div class="feedback-card">
                        <form id="feedbackForm">
                            <input type="hidden" name="appointment_id" value="<?= $appointmentId ?>">

                            <!-- Likert Scale Legend -->
                            <div class="likert-legend mb-4">
                                <div class="legend-item">
                                    <span class="legend-label">1 = Strongly Disagree</span>
                                    <span class="legend-label">2 = Disagree</span>
                                    <span class="legend-label">3 = Neutral</span>
                                    <span class="legend-label">4 = Agree</span>
                                    <span class="legend-label">5 = Strongly Agree</span>
                                </div>
                            </div>

                            <?php if (!empty($questions)): ?>
                                <?php foreach ($questions as $question): ?>
                                    <!-- Question <?= $question['question_number'] ?> -->
                                    <div class="question-item mb-4">
                                        <label class="question-label">
                                            <?= $question['question_number'] ?>. <?= htmlspecialchars($question['question_text']) ?>
                                            <span class="text-danger">*</span>
                                        </label>
                                        <div class="likert-scale">
                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                <div class="scale-option">
                                                    <input type="radio" 
                                                           id="<?= $question['field_name'] ?>_<?= $i ?>" 
                                                           name="<?= $question['field_name'] ?>" 
                                                           value="<?= $i ?>" 
                                                           required>
                                                    <label for="<?= $question['field_name'] ?>_<?= $i ?>"><?= $i ?></label>
                                                </div>
                                            <?php endfor; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="alert alert-warning">
                                    No feedback questions are currently available. Please contact the administrator.
                                </div>
                            <?php endif; ?>

                            <!-- Additional Comments -->
                            <div class="question-item mb-4">
                                <label for="additionalComments" class="question-label">
                                    Additional Comments (Optional)
                                </label>
                                <textarea id="additionalComments" name="additional_comments" class="form-control"
                                    rows="4" placeholder="Please share any additional feedback or suggestions..."></textarea>
                            </div>

                            <!-- Submit Button -->
                            <div class="text-center mt-4">
                                <button type="submit" class="btn btn-primary btn-lg" id="submitFeedbackBtn">
                                    <i class="fas fa-paper-plane me-2"></i>
                                    Submit Feedback
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <?= view('student/partials/notifications_dropdown') ?>

    <?php echo view('modals/student_dashboard_modals'); ?>
    <script src="<?= base_url('js/modals/student_dashboard_modals.js') ?>"></script>

    <!-- Toast for notifications -->
    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <div id="feedbackToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <strong class="me-auto" id="toastTitle">Notification</strong>
                <small id="toastTime">Just now</small>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body" id="toastMessage"></div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        window.BASE_URL = "<?= base_url() ?>";
    </script>
    <script src="<?= base_url('js/utils/timeFormatter.js') ?>"></script>
    <script src="<?= base_url('js/student/student_feedback.js') ?>"></script>
    <script src="<?= base_url('js/student/logout.js') ?>"></script>
    <script src="<?= base_url('js/student/student_notifications_dropdown.js?v=3') ?>"></script>
    <script src="<?= base_url('js/utils/secureLogger.js') ?>"></script>
    <script src="<?= base_url('js/utils/sidebar.js') ?>"></script>
</body>

</html>