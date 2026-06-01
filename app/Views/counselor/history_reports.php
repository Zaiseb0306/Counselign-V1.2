<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <meta name="description" content="Counselign">
    <title>Report History - Counselign</title>
    <link rel="icon" href="<?= base_url('Photos/counselign.ico') ?>" sizes="16x16 32x32" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&family=JetBrains+Mono:wght@500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('css/counselor/history_reports.css?v=2') ?>">
    <link rel="stylesheet" href="<?= base_url('css/admin/admin_history_reports_theme.css?v=2') ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="<?= base_url('css/counselor/header.css') ?>">
    <link rel="stylesheet" href="<?= base_url('css/utils/sidebar.css') ?>">

    <link rel="stylesheet" href="<?= base_url('css/counselor/counselor_vibe_shared.css?v=3') ?>">
    <link rel="stylesheet" href="<?= base_url('css/student/student_notifications_dropdown.css?v=4') ?>">
    <link rel="stylesheet" href="<?= base_url('css/utils/vibe_topbar.css?v=3') ?>">
</head>

<body class="hr-page-body adm-hr-page-body">
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
                    <i class="fas fa-history me-2"></i>
                    Report History
                </h1>
            </div>

            <div class="top-bar-right">

                <button class="top-bar-btn" onclick="window.location.href='<?= base_url('counselor/appointments/view-all') ?>'" title="Current Reports">
                    <i class="fas fa-chart-line" aria-hidden="true"></i>
                    <span class="btn-label">Current Reports</span>
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

        <main class="appt-page hr-page">
            <div class="main-content">
            <div class="container-fluid px-4 hr-container report-container">
                
                <section class="rpt-panel filter-section">
                    <div class="row g-3 align-items-center">
                        <div class="col-md-4">
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                <input type="month" class="form-control" id="monthFilter" max="<?= date('Y-m') ?>">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-filter"></i></span>
                                <select class="form-select" id="reportTypeFilter">
                                    <option value="daily">Daily Reports</option>
                                    <option value="weekly">Weekly Reports</option>
                                    <option value="yearly">Yearly Reports</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <button class="btn btn-primary w-100" onclick="loadHistoricalReport()">
                                <i class="fas fa-search"></i> View Report
                            </button>
                        </div>
                    </div>
                </section>

                <section class="stats-summary hr-stats" aria-label="Historical statistics">
                    <article class="stat-card completed">
                        <div class="stat-icon"><i class="fas fa-check-circle text-primary"></i></div>
                        <div class="stat-details"><h3 id="completedCount">0</h3><p>Completed</p></div>
                    </article>
                    <article class="stat-card approved">
                        <div class="stat-icon"><i class="fas fa-thumbs-up text-success"></i></div>
                        <div class="stat-details"><h3 id="approvedCount">0</h3><p>Approved</p></div>
                    </article>
                    <article class="stat-card rescheduled">
                        <div class="stat-icon"><i class="fas fa-calendar-alt text-warning"></i></div>
                        <div class="stat-details"><h3 id="rescheduledCount">0</h3><p>Rescheduled</p></div>
                    </article>
                    <article class="stat-card pending">
                        <div class="stat-icon"><i class="fas fa-clock text-danger"></i></div>
                        <div class="stat-details"><h3 id="pendingCount">0</h3><p>Pending</p></div>
                    </article>
                    <article class="stat-card feedback-pending">
                        <div class="stat-icon"><i class="fas fa-star text-secondary"></i></div>
                        <div class="stat-details"><h3 id="feedbackPendingCount">0</h3><p>InProgress</p></div>
                    </article>
                </section>

                <section class="rpt-panel">
                <div class="row charts-section g-4">
                    <div class="col-md-8">
                        <div class="chart-container trend-chart rpt-chart-card">
                            <header class="rpt-chart-head">
                                <span class="rpt-chart-icon" aria-hidden="true"><i class="fas fa-chart-bar"></i></span>
                                <div class="rpt-chart-titles">
                                    <h4>Appointment Trends</h4>
                                    <p class="rpt-chart-subtitle" id="trendChartSubtitle">Loading trends…</p>
                                    <p class="rpt-chart-hint" id="trendChartHint">Each bar shows appointments by status for that time period.</p>
                                </div>
                            </header>
                            <p class="rpt-chart-empty" id="trendChartEmpty" style="display:none;"><i class="fas fa-chart-bar me-2"></i>No appointment data for this period.</p>
                            <div class="chart-wrapper">
                                <canvas id="appointmentTrendChart"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="chart-container pie-chart rpt-chart-card">
                            <header class="rpt-chart-head">
                                <span class="rpt-chart-icon rpt-chart-icon--pie" aria-hidden="true"><i class="fas fa-bars-staggered"></i></span>
                                <div class="rpt-chart-titles">
                                    <h4>Status Breakdown</h4>
                                    <p class="rpt-chart-subtitle">Count by appointment status</p>
                                </div>
                            </header>
                            <div class="chart-wrapper chart-wrapper--status">
                                <canvas id="statusPieChart"></canvas>
                            </div>
                            <div class="rpt-status-breakdown" id="statusChartBreakdown" aria-live="polite"></div>
                        </div>
                    </div>
                </div>
                </section>
            </div>
            </div>
        </main>
    </div>

    <?= view('counselor/partials/notifications_dropdown') ?>
    <script>
        window.BASE_URL = "<?= base_url() ?>";
    </script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
    <script src="<?= base_url('js/counselor/report_charts_theme.js') ?>?v=3"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= base_url('js/counselor/counselor_notifications_dropdown.js?v=3') ?>"></script>
    <script src="<?= base_url('js/counselor/history_reports.js') ?>?v=4"></script>
    <script src="<?= base_url('js/counselor/counselor_drawer.js') ?>"></script>
    <script src="<?= base_url('js/utils/secureLogger.js') ?>"></script>
    <script src="<?= base_url('js/counselor/logout.js') ?>"></script>
    <script src="<?= base_url('js/utils/sidebar.js') ?>"></script>
</body>

</html>