<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scheduled Appointments - Counselign</title>
    <link rel="icon" href="<?= base_url('Photos/counselign.ico') ?>" sizes="16x16 32x32" type="image/x-icon">
        <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&family=JetBrains+Mono:wght@500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('css/counselor/scheduled_appointments.css') ?>">
    <link rel="stylesheet" href="<?= base_url('css/counselor/header.css') ?>">
    <link rel="stylesheet" href="<?= base_url('css/utils/sidebar.css') ?>">

    <link rel="stylesheet" href="<?= base_url('css/counselor/counselor_vibe_shared.css?v=5') ?>">
    <link rel="stylesheet" href="<?= base_url('css/student/student_notifications_dropdown.css?v=4') ?>">
    <link rel="stylesheet" href="<?= base_url('css/utils/vibe_topbar.css?v=3') ?>">
</head>

<body class="csq-page-body">
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
                <a href="<?= base_url('counselor/dashboard') ?>" class="sidebar-link " title="Dashboard">
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

    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <div id="statusToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <strong class="me-auto" id="toastTitle">Notification</strong>
                <small id="toastTime">Just now</small>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body" id="toastMessage">Status updated successfully.</div>
        </div>
    </div>

    <div class="main-wrapper" id="mainWrapper">
        <!-- Interactive Profile Picture Section -->

        <!-- Top Bar -->
        <header class="top-bar">
            <div class="top-bar-left">
                <h1 class="page-title-header">
                    <i class="fas fa-calendar-alt me-2"></i>
                    Consultation Schedule Queries
                </h1>
            </div>

            <div class="top-bar-right">

                <div class="search-container">
                    <div class="input-group" style="max-width: 300px;">
                        <span class="input-group-text bg-white border-end-0">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                        <input type="text" class="form-control border-start-0" id="appointmentsSearchInput" placeholder="Search appointments..." aria-label="Search appointments">
                        <button class="btn btn-outline-secondary" type="button" id="clearSearchBtn" style="display: none;">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>

                <button class="top-bar-btn" onclick="window.location.href='<?= base_url('counselor/appointments') ?>'" title="Appointments">
                    <i class="fas fa-list-alt" aria-hidden="true"></i>
                    <span class="btn-label">Appointments</span>
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
        <main class="appt-page csq-page">
            <div class="container-fluid px-4 csq-container">
                

                <section class="stats-summary csq-stats" aria-label="Schedule statistics">
                    <article class="stat-card completed">
                        <div class="stat-icon"><i class="fas fa-calendar-check text-primary"></i></div>
                        <div class="stat-details">
                            <h3 id="statTotalCount">-</h3>
                            <p>Scheduled</p>
                        </div>
                    </article>
                    <article class="stat-card approved">
                        <div class="stat-icon"><i class="fas fa-check text-success"></i></div>
                        <div class="stat-details">
                            <h3 id="statApprovedCount">-</h3>
                            <p>Approved</p>
                        </div>
                    </article>
                    <article class="stat-card pending">
                        <div class="stat-icon"><i class="fas fa-sun text-danger"></i></div>
                        <div class="stat-details">
                            <h3 id="statTodayCount">-</h3>
                            <p>Today</p>
                        </div>
                    </article>
                    <article class="stat-card feedback-pending">
                        <div class="stat-icon"><i class="fas fa-check-double text-secondary"></i></div>
                        <div class="stat-details">
                            <h3 id="statCompletedCount">-</h3>
                            <p>Completed</p>
                        </div>
                    </article>
                </section>

                <div class="csq-layout">
                    <div class="csq-left">
                        <section class="csq-card csq-panel">
                            <header class="csq-panel-header">
                                <h5 class="mb-0"><i class="fas fa-table me-2"></i>Scheduled Appointments</h5>
                            </header>

                            <div id="loading-indicator" class="appt-state appt-loading d-none">
                                <div class="appt-loader" role="status" aria-label="Loading">
                                    <span></span><span></span><span></span>
                                </div>
                                <p>Loading appointments...</p>
                            </div>

                            <div id="empty-message" class="appt-state appt-empty d-none">
                                <div class="appt-empty-icon"><i class="fas fa-calendar-times"></i></div>
                                <h4>No scheduled appointments found</h4>
                                <p>Approved sessions will appear here once available.</p>
                            </div>

                            <div class="csq-table-wrap" id="appointments-table-container">
                                <table class="table csq-table mb-0" id="appointments-table">
                                    <thead>
                                        <tr>
                                            <th scope="col">Student ID</th>
                                            <th scope="col">Username</th>
                                            <th scope="col">Appointed Date</th>
                                            <th scope="col">Time</th>
                                            <th scope="col">Method</th>
                                            <th scope="col">Consultation Type</th>
                                            <th scope="col">Appointment Type</th>
                                            <th scope="col">Purpose</th>
                                            <th scope="col" class="text-center">Status</th>
                                            <th scope="col" class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="appointments-body"></tbody>
                                </table>
                            </div>
                        </section>
                    </div>

                    <aside class="csq-right">
                        <div class="csq-card csq-sidebar-container">
                            <div class="sidebar-card csq-side-card">
                                <h6 class="csq-side-title"><i class="fas fa-clock me-2"></i>Your Weekly Consultation Schedules</h6>
                                <div class="schedule-list" id="counselorScheduleList">
                                    <div class="schedule-row"><span>Monday</span><span>8:00am–11:00am</span></div>
                                    <div class="schedule-row"><span>Tuesday</span><span>2:00pm–4:00pm</span></div>
                                    <div class="schedule-row"><span>Thursday</span><span>8:00am–4:00pm</span></div>
                                </div>
                            </div>

                            <div class="sidebar-card mini-calendar-card csq-side-card">
                                <div class="mini-cal-header">
                                    <button type="button" class="mini-cal-btn" id="prevMonth" aria-label="Previous month"><i class="fas fa-chevron-left"></i></button>
                                    <div class="mini-cal-title" id="monthYear"></div>
                                    <button type="button" class="mini-cal-btn" id="nextMonth" aria-label="Next month"><i class="fas fa-chevron-right"></i></button>
                                </div>
                                <div class="mini-cal-week">
                                    <span>S</span><span>M</span><span>T</span><span>W</span><span>T</span><span>F</span><span>S</span>
                                </div>
                                <div class="mini-cal-days" id="calendarDays"></div>
                                <div class="mini-cal-legend">
                                    <div class="legend-item"><span class="legend-dot has-appointment"></span><span>Has Appointments</span></div>
                                    <div class="legend-item"><span class="legend-dot today"></span><span>Today</span></div>
                                </div>
                            </div>
                        </div>
                    </aside>
                </div>
            </div>
        </main>
    </div>

    <!-- Counselor Remarks Modal -->
    <div class="modal fade appt-vibe-modal" id="remarksModal" tabindex="-1" aria-labelledby="remarksModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content appointment-modal-content">
                <div class="modal-header appointment-modal-header success-modal-header">
                    <h5 class="modal-title" id="remarksModalLabel">
                        <i class="fas fa-check me-2"></i>Mark Appointment as Completed
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body appointment-modal-body">
                    <form id="remarksForm" style="display: flex; flex-direction: column;">
                        <div class="mb-3">
                            <label for="counselorRemarks" class="form-label fw-bold">Counselor Remarks:</label>
                            <textarea class="form-control" id="counselorRemarks" rows="4"
                                placeholder="Enter your remarks about this counseling session..." required></textarea>
                            <div class="form-text">These remarks will be saved with the appointment record.</div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer appointment-modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Cancel
                    </button>
                    <button type="button" class="btn btn-success" id="confirmCompleteBtn">
                        <i class="fas fa-check me-1"></i>Mark as Completed
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade appt-vibe-modal" id="rescheduleModal" tabindex="-1" aria-labelledby="rescheduleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content appointment-modal-content reschedule-modal-content">
                <div class="modal-header appointment-modal-header reschedule-modal-header">
                    <h5 class="modal-title" id="rescheduleModalLabel">
                        <i class="fas fa-calendar-alt me-2"></i>Re-schedule Appointment
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body appointment-modal-body">
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
                <div class="modal-footer appointment-modal-footer">
                    <button type="button" class="btn btn-warning" id="confirmRescheduleBtn">
                        <i class="fas fa-check me-1"></i>Confirm Re-schedule
                    </button>
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
    <script src="<?= base_url('js/utils/timeFormatter.js') ?>"></script>
    <script src="<?= base_url('js/counselor/scheduled_appointments.js?v=2') ?>"></script>
    <script src="<?= base_url('js/counselor/counselor_drawer.js') ?>"></script>
    <script src="<?= base_url('js/utils/secureLogger.js') ?>"></script>
    <script src="<?= base_url('js/counselor/logout.js') ?>"></script>
    <script src="<?= base_url('js/utils/sidebar.js') ?>"></script>
</body>

</html>