<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pending Feedback - Counselign</title>
    <link rel="icon" href="<?= base_url('Photos/counselign.ico') ?>" sizes="16x16 32x32"
        type="image/x-icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&family=JetBrains+Mono:wght@500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="<?= base_url('css/counselor/header.css') ?>">
    <link rel="stylesheet" href="<?= base_url('css/utils/sidebar.css') ?>">

    <link rel="stylesheet" href="<?= base_url('css/counselor/counselor_vibe_shared.css?v=5') ?>">
    <link rel="stylesheet" href="<?= base_url('css/student/student_notifications_dropdown.css?v=4') ?>">
    <link rel="stylesheet" href="<?= base_url('css/counselor/pending_feedback.css') ?>">
    <link rel="stylesheet" href="<?= base_url('css/utils/vibe_topbar.css?v=3') ?>">
</head>

<body class="pf-page-body">
    <!-- Sidebar -->
    <aside class="sidebar" id="uniSidebar">
        <div class="sidebar-content">
            <button class="sidebar-toggle-btn" id="sidebarToggle" title="Toggle Sidebar">
                <img src="<?= base_url('Photos/counselign_logo.png') ?>" alt="Logo" class="sidebar-logo">
                <span class="sidebar-brand-text">Counselign</span>
            </button>

            <nav class="sidebar-nav">
                <a href="<?= base_url('counselor/dashboard') ?>" class="sidebar-link" title="Dashboard">
                    <i class="fas fa-home"></i>
                    <span class="sidebar-text">Dashboard</span>
                </a>
                <a href="<?= base_url('counselor/appointments/scheduled') ?>" class="sidebar-link" title="Scheduled Appointments">
                    <i class="fas fa-calendar-alt"></i>
                    <span class="sidebar-text">Scheduled Appointments</span>
                </a>
                <a href="<?= base_url('counselor/pending-feedback') ?>" class="sidebar-link active" title="Pending Feedback">
                    <i class="fas fa-star"></i>
                    <span class="sidebar-text">Pending Feedback</span>
                </a>
                <a href="<?= base_url('counselor/pending-feedback/view-feedback') ?>" class="sidebar-link" title="View Feedback">
                    <i class="fas fa-comments"></i>
                    <span class="sidebar-text">View Feedback</span>
                </a>
                <a href="<?= base_url('counselor/follow-up') ?>" class="sidebar-link" title="Follow-up Sessions">
                    <i class="fas fa-clipboard-list"></i>
                    <span class="sidebar-text">Follow-up Sessions</span>
                </a>
                <a href="<?= base_url('counselor/announcements') ?>" class="sidebar-link" title="Announcement">
                    <i class="fas fa-bullhorn"></i>
                    <span class="sidebar-text">Announcement</span>
                </a>
            </nav>
        </div>
    </aside>

    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <button class="floating-sidebar-toggle" id="floatingSidebarToggle" title="Open Menu">
        <img src="<?= base_url('Photos/counselign_logo.png') ?>" alt="Menu">
    </button>

    <div class="main-wrapper" id="mainWrapper">
        <header class="top-bar">
            <div class="top-bar-left">
                <h1 class="page-title-header">
                    <i class="fas fa-star me-2"></i>
                    Pending Feedback
                </h1>
            </div>

            <div class="top-bar-right">
                <?= view('counselor/partials/notification_bell') ?>

                <div class="profile-dropdown">
                    <button class="top-bar-btn profile-btn" id="profileDropdownBtn">
                        <img id="profile-img-top" src="<?= base_url('Photos/profile.png') ?>" alt="Profile" class="profile-img-small">
                        <span class="btn-label" id="uniNameTop">Counselor</span>
                    </button>

                    <div class="profile-dropdown-menu" id="profileDropdownMenu">
                        <div class="profile-dropdown-header">
                            <img id="profile-img-dropdown" src="<?= base_url('Photos/profile.png') ?>" alt="Profile" class="profile-img-large">
                            <div class="profile-info">
                                <div class="profile-name" id="uniNameDropdown">Counselor</div>
                                <div class="profile-subtitle" id="lastLoginDropdown">Loading...</div>
                            </div>
                        </div>
                        <div class="profile-dropdown-divider"></div>
                        <a href="<?= base_url('counselor/profile') ?>" class="profile-dropdown-item">
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

        <main class="appt-page pf-page">
            <div class="container-fluid px-4 pf-container">
                <section class="stats-summary pf-stats" aria-label="Pending feedback count">
                    <article class="stat-card feedback-pending">
                        <div class="stat-icon"><i class="fas fa-star text-secondary"></i></div>
                        <div class="stat-details">
                            <h3 id="pendingFeedbackCount">-</h3>
                            <p>Inprogress</p>
                        </div>
                    </article>
                </section>

                <div id="loadingIndicator" class="appt-state appt-loading">
                    <div class="appt-loader" role="status" aria-label="Loading">
                        <span></span><span></span><span></span>
                    </div>
                    <p>Loading pending feedback appointments...</p>
                </div>

                <section id="appointmentsTableContainer" class="pf-panel d-none">
                    <header class="pf-panel-header">
                        <h2><i class="fas fa-table me-2"></i>Appointments Pending Student Feedback</h2>
                    </header>

                    <div id="pfTableWrap" class="pf-table-wrap">
                        <table class="table pf-table mb-0">
                            <thead>
                                <tr>
                                    <th>Student Name</th>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>Purpose</th>
                                    <th>Session Type</th>
                                    <th>Status</th>
                                    <th>Counselor Remarks</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="appointmentsTableBody"></tbody>
                        </table>
                    </div>

                    <div id="emptyState" class="appt-state appt-empty d-none">
                        <div class="appt-empty-icon"><i class="fas fa-inbox"></i></div>
                        <h4>No Pending Feedback Appointments</h4>
                        <p>All completed appointments have received student feedback.</p>
                    </div>
                </section>
            </div>
        </main>
    </div>

    <div class="modal fade appt-vibe-modal" id="viewRemarksModal" tabindex="-1" aria-labelledby="viewRemarksModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content appointment-modal-content">
                <div class="modal-header appointment-modal-header">
                    <h5 class="modal-title" id="viewRemarksModalLabel">
                        <i class="fas fa-comment-dots me-2"></i>Counselor Remarks
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body appointment-modal-body">
                    <div class="pf-modal-field">
                        <label>Student</label>
                        <p id="modalStudentName" class="pf-modal-value"></p>
                    </div>
                    <div class="pf-modal-field">
                        <label>Appointment Date &amp; Time</label>
                        <p id="modalDate" class="pf-modal-value"></p>
                    </div>
                    <div class="pf-modal-field mb-0">
                        <label>Remarks</label>
                        <div id="modalRemarks" class="pf-remarks-box"></div>
                    </div>
                </div>
                <div class="modal-footer appointment-modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade appt-vibe-modal" id="emailSentModal" tabindex="-1" aria-labelledby="emailSentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content appointment-modal-content">
                <div class="modal-header appointment-modal-header success-modal-header">
                    <h5 class="modal-title" id="emailSentModalLabel">
                        <i class="fas fa-check-circle me-2"></i>Email Sent Successfully
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body appointment-modal-body pf-success-body">
                    <div class="pf-success-icon">
                        <i class="fas fa-envelope-circle-check"></i>
                    </div>
                    <h4>Successfully sent the message via email</h4>
                    <p>The reminder email has been sent to the student. They will receive a notification with a link to complete their feedback.</p>
                </div>
                <div class="modal-footer appointment-modal-footer justify-content-center">
                    <button type="button" class="btn pf-btn-ok" data-bs-dismiss="modal">OK</button>
                </div>
            </div>
        </div>
    </div>

    <?= view('counselor/partials/notifications_dropdown') ?>
    <script>
        window.BASE_URL = "<?= base_url() ?>";
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= base_url('js/counselor/counselor_notifications_dropdown.js?v=3') ?>"></script>
    <script src="<?= base_url('js/utils/sidebar.js') ?>"></script>
    <script src="<?= base_url('js/utils/secureLogger.js') ?>"></script>
    <script src="<?= base_url('js/utils/timeFormatter.js') ?>"></script>
    <script src="<?= base_url('js/counselor/pending_feedback.js') ?>"></script>
    <script src="<?= base_url('js/counselor/logout.js') ?>"></script>
</body>

</html>
