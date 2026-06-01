<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointments Management - Counselign</title>
    <link rel="icon" href="<?= base_url('Photos/counselign.ico') ?>" sizes="16x16 32x32"
        type="image/x-icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&family=JetBrains+Mono:wght@500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('css/shared/appointments_cards_theme.css?v=1') ?>">
    <link rel="stylesheet" href="<?= base_url('css/counselor/appointments.css?v=2') ?>">
    <link rel="stylesheet" href="<?= base_url('css/counselor/header.css') ?>">
    <link rel="stylesheet" href="<?= base_url('css/utils/sidebar.css') ?>">

    <link rel="stylesheet" href="<?= base_url('css/counselor/counselor_vibe_shared.css?v=3') ?>">
    <link rel="stylesheet" href="<?= base_url('css/student/student_notifications_dropdown.css?v=4') ?>">
    <link rel="stylesheet" href="<?= base_url('css/utils/vibe_topbar.css?v=3') ?>">
</head>

<body class="cam-page-body appt-page">
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
                <a href="<?= base_url('counselor/dashboard') ?>" class="sidebar-link" title="Dashboard">
                    <i class="fas fa-home"></i>
                    <span class="sidebar-text">Dashboard</span>
                </a>

                <a href="<?= base_url('counselor/appointments/scheduled') ?>" class="sidebar-link active" title="Scheduled Appointments">
                    <i class="fas fa-calendar-alt"></i>
                    <span class="sidebar-text">Scheduled Appointments</span>
                </a>
                <a href="<?= base_url('counselor/pending-feedback') ?>" class="sidebar-link" title="Pending Feedback">
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
                    Appointments Management
                </h1>
            </div>

            <div class="top-bar-right">

                <button class="top-bar-btn" onclick="window.location.href='<?= base_url('counselor/appointments/scheduled') ?>'" title="Scheduled Appointments">
                    <i class="fas fa-calendar-alt" aria-hidden="true"></i>
                    <span class="btn-label">Scheduled Appointments</span>
                </button>


                <?= view('counselor/partials/notification_bell') ?>

                <!-- Profile Dropdown -->
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

        <main class="appt-page">
            <div class="container-fluid px-4 appt-container">
                

                <section class="stats-summary" aria-label="Appointment statistics">
                    <article class="stat-card completed">
                        <div class="stat-icon"><i class="fas fa-check-double text-primary"></i></div>
                        <div class="stat-details">
                            <h3 id="completedCount">-</h3>
                            <p>Completed</p>
                        </div>
                    </article>
                    <article class="stat-card approved">
                        <div class="stat-icon"><i class="fas fa-check text-success"></i></div>
                        <div class="stat-details">
                            <h3 id="approvedCount">-</h3>
                            <p>Approved</p>
                        </div>
                    </article>
                    <article class="stat-card rescheduled">
                        <div class="stat-icon"><i class="fas fa-calendar-alt text-warning"></i></div>
                        <div class="stat-details">
                            <h3 id="rescheduledCount">-</h3>
                            <p>Rescheduled</p>
                        </div>
                    </article>
                    <article class="stat-card pending">
                        <div class="stat-icon"><i class="fas fa-clock text-danger"></i></div>
                        <div class="stat-details">
                            <h3 id="pendingCount">-</h3>
                            <p>Pending</p>
                        </div>
                    </article>
                    <article class="stat-card feedback-pending">
                        <div class="stat-icon"><i class="fas fa-spinner text-secondary"></i></div>
                        <div class="stat-details">
                            <h3 id="feedbackPendingCount">-</h3>
                            <p>InProgress</p>
                        </div>
                    </article>
                </section>

                <section class="appointments-container">
                    <header class="appointments-header">
                        <div class="appointments-header-title">
                            <h5 class="mb-0"><i class="fas fa-list-alt me-2"></i>Appointments List</h5>
                        </div>
                        <div class="filter-controls">
                            <div class="filter-group">
                                <label for="dateRangeFilter"><i class="fas fa-calendar-day"></i> Date</label>
                                <select class="form-select appt-select" id="dateRangeFilter">
                                    <option value="all">All Dates</option>
                                    <option value="today">Today</option>
                                    <option value="thisWeek">This Week</option>
                                    <option value="nextWeek">Next Week</option>
                                    <option value="nextMonth">Next Month</option>
                                    <option value="past">Past Appointments</option>
                                </select>
                            </div>
                            <div class="filter-group">
                                <label for="statusFilter"><i class="fas fa-filter"></i> Status</label>
                                <select id="statusFilter" class="form-select appt-select">
                                    <option value="all">All Statuses</option>
                                    <option value="pending">Pending</option>
                                    <option value="approved">Approved</option>
                                    <option value="rescheduled">Rescheduled</option>
                                    <option value="completed">Completed</option>
                                    <option value="feedback_pending">InProgress</option>
                                </select>
                            </div>
                        </div>
                    </header>

                    <div id="loadingIndicator" class="appt-state appt-loading">
                        <div class="appt-loader" role="status" aria-label="Loading">
                            <span></span><span></span><span></span>
                        </div>
                        <p>Loading appointments...</p>
                    </div>

                    <div id="noAppointmentsMessage" class="appt-state appt-empty d-none">
                        <div class="appt-empty-icon"><i class="fas fa-calendar-times"></i></div>
                        <p>No appointments found.</p>
                    </div>

                    <div id="appointmentsList" class="appointments-list d-none"></div>
                </section>
            </div>
        </main>
    </div>

    <div class="modal fade" id="appointmentDetailsModal" tabindex="-1" aria-labelledby="appointmentDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content appointment-modal-content">
                <div class="modal-header appointment-modal-header">
                    <h5 class="modal-title" id="appointmentDetailsModalLabel">
                        <i class="fas fa-calendar-check me-2"></i>Appointment Details
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body appointment-modal-body">
                    <div class="appointment-info-grid">
                        <div class="info-section">
                            <div class="info-item">
                                <i class="fas fa-user-circle info-icon"></i>
                                <div class="info-content">
                                    <span class="info-label">User ID</span>
                                    <span class="info-value" id="modalStudentId"></span>
                                </div>
                            </div>
                            <div class="info-item">
                                <i class="fas fa-user info-icon"></i>
                                <div class="info-content">
                                    <span class="info-label">Student Name</span>
                                    <span class="info-value" id="modalStudentName"></span>
                                </div>
                            </div>
                            <div class="info-item">
                                <i class="fas fa-envelope info-icon"></i>
                                <div class="info-content">
                                    <span class="info-label">Email</span>
                                    <span class="info-value" id="modalEmail"></span>
                                </div>
                            </div>
                        </div>
                        <div class="info-section">
                            <div class="info-item">
                                <i class="fas fa-calendar-alt info-icon"></i>
                                <div class="info-content">
                                    <span class="info-label">Date</span>
                                    <span class="info-value" id="modalDate"></span>
                                </div>
                            </div>
                            <div class="info-item">
                                <i class="fas fa-clock info-icon"></i>
                                <div class="info-content">
                                    <span class="info-label">Time</span>
                                    <span class="info-value" id="modalTime"></span>
                                </div>
                            </div>
                            <div class="info-item">
                                <i class="fas fa-check-circle info-icon"></i>
                                <div class="info-content">
                                    <span class="info-label">Status</span>
                                    <span id="modalStatus" class="badge"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="appointment-info-grid mt-3">
                        <div class="info-section">
                            <div class="info-item">
                                <i class="fas fa-users info-icon"></i>
                                <div class="info-content">
                                    <span class="info-label">Consultation Type</span>
                                    <span class="info-value" id="modalConsultationType"></span>
                                </div>
                            </div>
                            <div class="info-item">
                                <i class="fas fa-laptop info-icon"></i>
                                <div class="info-content">
                                    <span class="info-label">Method Type</span>
                                    <span class="info-value" id="modalMethodType"></span>
                                </div>
                            </div>
                        </div>
                        <div class="info-section">
                            <div class="info-item">
                                <i class="fas fa-bullseye info-icon"></i>
                                <div class="info-content">
                                    <span class="info-label">Purpose</span>
                                    <span class="info-value" id="modalPurpose"></span>
                                </div>
                            </div>
                            <div class="info-item" hidden>
                                <i class="fas fa-user-md info-icon"></i>
                                <div class="info-content">
                                    <span class="info-label">Counselor Preference</span>
                                    <span class="info-value" id="modalCounselorPreference"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="description-section mt-3">
                        <div class="description-header">
                            <i class="fas fa-file-alt me-2"></i>
                            <span>Description</span>
                        </div>
                        <div id="modalDescription" class="description-content"></div>
                    </div>
                    <div id="modalReasonContainer" class="description-section mt-3" style="display: none;">
                        <div class="description-header">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            <span>Reason</span>
                        </div>
                        <div id="modalReason" class="description-content"></div>
                    </div>
                    <div class="timestamp-info mt-3">
                        <small class="text-muted">
                            <i class="fas fa-clock me-1"></i>
                            Created: <span id="modalCreated"></span>
                        </small>
                        <span id="modalUpdated" style="display: none;"></span>
                    </div>
                    <input type="hidden" id="modalAppointmentId">
                </div>
                <div class="modal-footer appointment-modal-footer justify-content-between">
                    <div>
                        <button type="button" class="btn btn-warning btn-sm" id="rescheduleAppointmentBtn">
                            <i class="fas fa-calendar-alt me-1"></i> Reschedule
                        </button>
                    </div>
                    <div>
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary btn-sm" id="approveAppointmentBtn">
                            <i class="fas fa-check me-1"></i> Approve
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade appt-vibe-modal" id="rescheduleModal" tabindex="-1" aria-labelledby="rescheduleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content appointment-modal-content reschedule-modal-content">
                <div class="modal-header appointment-modal-header reschedule-modal-header">
                    <h5 class="modal-title" id="rescheduleModalLabel">
                        <i class="fas fa-calendar-alt me-2"></i>Reschedule Appointment
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="padding: 1rem;">
                    <form id="rescheduleForm" style="display: flex; flex-direction: column;">
                        <div class="mb-3">
                            <label for="rescheduleDate" class="form-label fw-bold">New Date:</label>
                            <input type="date" class="form-control" id="rescheduleDate" required>
                        </div>
                        <div class="mb-3">
                            <label for="rescheduleTime" class="form-label fw-bold">New Time:</label>
                            <select class="form-select" id="rescheduleTime" required>
                                <option value="">Select available time</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="rescheduleReason" class="form-label fw-bold">Reason for rescheduling:</label>
                            <textarea class="form-control" id="rescheduleReason" rows="3"
                                placeholder="Enter the reason for rescheduling..." required></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-warning" id="confirmRescheduleBtn">
                        <i class="fas fa-check me-1"></i>Confirm Re-schedule
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade appt-vibe-modal" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content appointment-modal-content">
                <div class="modal-header appointment-modal-header">
                    <h5 class="modal-title" id="confirmationModalTitle">Confirm Action</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="confirmationModalBody"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="confirmActionBtn">Confirm</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade appt-vibe-modal" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content appointment-modal-content">
                <div class="modal-header appointment-modal-header success-modal-header">
                    <h5 class="modal-title" id="successModalTitle">Success</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="successModalBody"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" data-bs-dismiss="modal">OK</button>
                </div>
            </div>
        </div>
    </div>

    <footer>
        <div class="footer-content">
            <div class="copyright">
                <b>© 2025 Counselign Team. All rights reserved.</b>
            </div>
        </div>
    </footer>

    <?= view('counselor/partials/notifications_dropdown') ?>
    <script>
        window.BASE_URL = "<?= base_url() ?>";
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= base_url('js/counselor/counselor_notifications_dropdown.js?v=3') ?>"></script>
    <script src="<?= base_url('js/counselor/appointments.js') ?>" defer></script>
    <script src="<?= base_url('js/counselor/counselor_drawer.js') ?>"></script>
    <script src="<?= base_url('js/utils/secureLogger.js') ?>"></script>
    <script src="<?= base_url('js/counselor/logout.js') ?>"></script>
    <script src="<?= base_url('js/utils/sidebar.js') ?>"></script>
</body>

</html>
