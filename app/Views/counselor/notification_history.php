<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notification History - Counselign</title>
    <link rel="icon" href="<?= base_url('Photos/counselign.ico') ?>" sizes="16x16 32x32" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&family=JetBrains+Mono:wght@500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="<?= base_url('css/counselor/notification_history.css') ?>">
    <link rel="stylesheet" href="<?= base_url('css/counselor/header.css') ?>">
    <link rel="stylesheet" href="<?= base_url('css/utils/sidebar.css') ?>">

    <link rel="stylesheet" href="<?= base_url('css/counselor/counselor_vibe_shared.css?v=3') ?>">
    <link rel="stylesheet" href="<?= base_url('css/student/student_notifications_dropdown.css?v=4') ?>">
    <link rel="stylesheet" href="<?= base_url('css/utils/vibe_topbar.css?v=3') ?>">
</head>

<body class="nh-page-body">
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

    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <button class="floating-sidebar-toggle" id="floatingSidebarToggle" title="Open Menu">
        <img src="<?= base_url('Photos/counselign_logo.png') ?>" alt="Menu">
    </button>

    <div class="main-wrapper" id="mainWrapper">
        <header class="top-bar">
            <div class="top-bar-left">
                <h1 class="page-title-header">
                    <i class="fas fa-history me-2"></i>
                    Notification History
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

        <main class="appt-page nh-page">
            <div class="container-fluid px-4 nh-container">

                <section class="stats-summary nh-stats" aria-label="Notification statistics">
                    <article class="stat-card completed">
                        <div class="stat-icon"><i class="fas fa-bell text-primary"></i></div>
                        <div class="stat-details">
                            <h3 id="statTotalCount">-</h3>
                            <p>Total</p>
                        </div>
                    </article>
                    <article class="stat-card approved">
                        <div class="stat-icon"><i class="fas fa-envelope text-success"></i></div>
                        <div class="stat-details">
                            <h3 id="statMessageCount">-</h3>
                            <p>Messages</p>
                        </div>
                    </article>
                    <article class="stat-card pending">
                        <div class="stat-icon"><i class="fas fa-calendar text-warning"></i></div>
                        <div class="stat-details">
                            <h3 id="statAppointmentCount">-</h3>
                            <p>Appointments</p>
                        </div>
                    </article>
                    <article class="stat-card feedback-pending">
                        <div class="stat-icon"><i class="fas fa-layer-group text-secondary"></i></div>
                        <div class="stat-details">
                            <h3 id="statOtherCount">-</h3>
                            <p>Other</p>
                        </div>
                    </article>
                </section>

                <div id="loadingIndicator" class="appt-state appt-loading">
                    <div class="appt-loader" role="status" aria-label="Loading notification history">
                        <span></span><span></span><span></span>
                    </div>
                    <p>Loading notification history...</p>
                </div>

                <section id="notificationsListContainer" class="nh-panel d-none" aria-label="Past notifications">
                    <header class="nh-panel-header">
                        <h2 class="nh-panel-title">Past Notifications</h2>
                    </header>
                    <div class="nh-panel-body">
                        <div id="notificationsList"></div>
                        <div id="emptyState" class="appt-state appt-empty d-none">
                            <div class="appt-empty-icon"><i class="fas fa-inbox"></i></div>
                            <h4>No Notifications Found</h4>
                            <p>You have no notification history.</p>
                        </div>
                    </div>
                </section>
            </div>
        </main>
    </div>

    <?= view('counselor/partials/notifications_dropdown') ?>
    <script>
        window.BASE_URL = "<?= base_url() ?>";
        window.SecureLogger = { info: function() {}, error: function() {}, warn: function() {}, debug: function() {} };
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= base_url('js/counselor/counselor_notifications_dropdown.js?v=3') ?>"></script>
    <script src="<?= base_url('js/counselor/notification_history.js') ?>"></script>
    <script src="<?= base_url('js/counselor/counselor_drawer.js') ?>"></script>
    <script src="<?= base_url('js/counselor/logout.js') ?>"></script>
    <script src="<?= base_url('js/utils/secureLogger.js') ?>"></script>
    <script src="<?= base_url('js/utils/sidebar.js') ?>"></script>
</body>

</html>
