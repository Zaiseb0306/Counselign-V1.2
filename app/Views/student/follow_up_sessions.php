<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description"
        content="University Guidance Counseling Services - Your safe space for support and guidance" />
    <meta name="keywords" content="counseling, guidance, university, support, mental health, student wellness" />
    <meta name="csrf-token" content="<?= csrf_hash() ?>">
    <title>Follow-up Sessions - Student - Counselign</title>
    <link rel="icon" href="<?= base_url('Photos/counselign.ico') ?>" sizes="16x16 32x32" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&family=JetBrains+Mono:wght@500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="<?= base_url('css/counselor/follow_up.css?v=3') ?>">
    <link rel="stylesheet" href="<?= base_url('css/student/follow_up_sessions.css?v=5') ?>">
    <link rel="stylesheet" href="<?= base_url('css/student/student_vibe_shared.css?v=2') ?>">
    <link rel="stylesheet" href="<?= base_url('css/student/student_notifications_dropdown.css?v=4') ?>">
    <link rel="stylesheet" href="<?= base_url('css/student/header.css') ?>">
    <link rel="stylesheet" href="<?= base_url('css/utils/sidebar.css') ?>">
    <link rel="stylesheet" href="<?= base_url('css/utils/vibe_topbar.css?v=3') ?>">

</head>

<body class="fus-page-body">
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

                <a href="<?= base_url('student/my-appointments') ?>" class="sidebar-link" title="My Appointments">
                    <i class="fas fa-list-alt"></i>
                    <span class="sidebar-text">My Appointments</span>
                </a>

                <a href="<?= base_url('student/follow-up-sessions') ?>" class="sidebar-link active" title="Follow-up Sessions">
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
        <!-- Interactive Profile Picture Section -->

        <!-- Top Bar -->
        <header class="top-bar">
            <div class="top-bar-left">
                <h1 class="page-title-header">
                    <i class="fas fa-calendar-check me-2"></i>
                    For follow-up Session
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


        <main class="appt-page fu-page">
            <div class="container-fluid px-4 fu-container">

                <section class="stats-summary fu-stats" aria-label="Follow-up statistics">
                    <article class="stat-card completed">
                        <div class="stat-icon"><i class="fas fa-check-double text-primary"></i></div>
                        <div class="stat-details">
                            <h3 id="statCompletedCount">-</h3>
                            <p>Completed</p>
                        </div>
                    </article>
                    <article class="stat-card approved">
                        <div class="stat-icon"><i class="fas fa-calendar-plus text-success"></i></div>
                        <div class="stat-details">
                            <h3 id="statWithFollowUpCount">-</h3>
                            <p>With Follow-ups</p>
                        </div>
                    </article>
                    <article class="stat-card pending">
                        <div class="stat-icon"><i class="fas fa-exclamation-triangle text-danger"></i></div>
                        <div class="stat-details">
                            <h3 id="statPendingCount">-</h3>
                            <p>Pending</p>
                        </div>
                    </article>
                    <article class="stat-card feedback-pending">
                        <div class="stat-icon"><i class="fas fa-layer-group text-secondary"></i></div>
                        <div class="stat-details">
                            <h3 id="statTotalFollowUpCount">-</h3>
                            <p>Total Sessions</p>
                        </div>
                    </article>
                </section>

                <section class="fu-panel completed-appointments-section">
                    <header class="fu-panel-header section-header-bar">
                        <div class="section-title-wrapper">
                            <h3 class="subsection-title mb-0">
                                <i class="fas fa-check-circle me-2"></i>
                                Completed Appointments
                            </h3>
                        </div>
                        <div class="search-container">
                            <div class="input-group search-wrapper fu-search">
                                <span class="input-group-text"><i class="fas fa-search"></i></span>
                                <input type="text" class="form-control" id="searchInput" placeholder="Search appointments...">
                                <button class="btn fu-btn-clear" type="button" id="clearSearchBtn" style="display: none;" title="Clear search">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </header>

                    <div id="fuLoading" class="appt-state appt-loading">
                        <div class="appt-loader" role="status" aria-label="Loading">
                            <span></span><span></span><span></span>
                        </div>
                        <p>Loading completed appointments...</p>
                    </div>

                    <div id="completedAppointmentsContainer" class="appointments-grid"></div>

                    <div id="noCompletedAppointments" class="appt-state appt-empty no-data-message" style="display: none;">
                        <div class="appt-empty-icon"><i class="fas fa-calendar-check"></i></div>
                        <h4>No completed appointments</h4>
                        <p>Complete some appointments to view follow-up sessions.</p>
                    </div>
                    <div id="noSearchResults" class="appt-state appt-empty no-data-message" style="display: none;">
                        <div class="appt-empty-icon"><i class="fas fa-search"></i></div>
                        <h4>No matches found</h4>
                        <p>No appointments match your search criteria.</p>
                    </div>
                </section>
            </div>
        </main>
    </div>

    <?= view('student/partials/notifications_dropdown') ?>

    <!-- Follow-up Sessions Modal (Read-Only) -->
    <div class="modal fade appt-vibe-modal" id="followUpSessionsModal" tabindex="-1" aria-labelledby="followUpSessionsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content appointment-modal-content">
                <div class="modal-header appointment-modal-header">
                    <h5 class="modal-title" id="followUpSessionsModalLabel">
                        <i class="fas fa-calendar-alt me-2"></i>
                        Follow-up Sessions
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="followUpSessionsContainer"></div>
                    <div id="noFollowUpSessions" class="no-data-message" style="display: none;">
                        <i class="fas fa-info-circle"></i>
                        <p>No follow-up sessions found for this appointment.</p>
                    </div>
                </div>
                <div class="modal-footer appointment-modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Modal -->
    <div class="modal fade appt-vibe-modal" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content appointment-modal-content">
                <div class="modal-header appointment-modal-header success-modal-header">
                    <h5 class="modal-title" id="successModalTitle">
                        <i class="fas fa-check-circle me-2"></i>
                        Success
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="successModalBody"></div>
                <div class="modal-footer appointment-modal-footer">
                    <button type="button" class="btn btn-success" data-bs-dismiss="modal">OK</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Error Modal -->
    <div class="modal fade appt-vibe-modal" id="errorModal" tabindex="-1" aria-labelledby="errorModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content appointment-modal-content">
                <div class="modal-header appointment-modal-header danger-modal-header">
                    <h5 class="modal-title" id="errorModalTitle">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        Error
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="errorModalBody"></div>
                <div class="modal-footer appointment-modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">OK</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Must be defined before student scripts run
        window.BASE_URL = '<?= base_url() ?>';
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= base_url('js/student/follow_up_sessions.js') ?>?v=3" defer></script>
    <script src="<?= base_url('js/student/student_drawer.js') ?>"></script>
    <script src="<?= base_url('js/utils/secureLogger.js') ?>"></script>
    <script src="<?= base_url('js/student/student_header_drawer.js') ?>"></script>
    <script src="<?= base_url('js/student/logout.js') ?>"></script>
    <script src="<?= base_url('js/student/student_notifications_dropdown.js?v=3') ?>"></script>
    <script src="<?= base_url('js/utils/sidebar.js') ?>"></script>
</body>

</html>