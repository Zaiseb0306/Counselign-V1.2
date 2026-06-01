<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <meta name="description" content="Counselign">
    <title>Counselign</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="<?= base_url('Photos/counselign.ico') ?>" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&family=JetBrains+Mono:wght@500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('css/counselor/view_all_appointments.css?v=9') ?>">
    <link rel="stylesheet" href="<?= base_url('css/admin/admin_reports_counselor_theme.css?v=2') ?>">
    <?= view('admin/partials/vibe_styles') ?>
</head>

<body class="adm-dash-page-body va-page-body">
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
                <a href="<?= base_url('admin/dashboard') ?>" class="sidebar-link active" title="Dashboard">
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
                <a href="<?= base_url('admin/feedback-questions') ?>" class="sidebar-link" title="Feedback Questions">
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

    <!-- Main Content Area -->
    <div class="main-wrapper" id="mainWrapper">
        <!-- Top Bar -->
        <header class="top-bar">
            <div class="top-bar-left">
                <h1 class="page-title-header">
                    <i class="fas fa-chart-line me-2"></i>
                    Appointment Reports
                </h1>
            </div>

            <div class="top-bar-right">
                <!-- Quote Modal Button -->
                <button class="top-bar-btn" id="openQuotesModalBtn" title="Manage Quotes">
                    <i class="fas fa-quote-right"></i>
                    <span class="btn-label">Quotes</span>
                </button>

                <!-- Profile Dropdown -->
                <div class="profile-dropdown">
                    <button class="top-bar-btn profile-btn" id="profileDropdownBtn">
                        <img id="profile-img-top" src="<?= base_url('Photos/UGC-Logo.png') ?>" alt="Profile" class="profile-img-small">
                        <span class="btn-label" id="uniNameTop">Admin</span>
                    </button>

                    <div class="profile-dropdown-menu" id="profileDropdownMenu">
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

        <main class="appt-page va-page">
            <div class="main-content">
            <div class="container-fluid px-4 va-container report-container">

                <div class="adm-reports-legacy-header" aria-hidden="true">
                    <span id="adminName">Loading...</span>
                </div>

                <section class="rpt-panel filter-section">
                    <div class="row g-3 align-items-center">
                        <div class="col-md-8">
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                <select class="form-select" id="timeRange">
                                    <option value="daily">Daily Report</option>
                                    <option value="weekly" selected>Weekly Report</option>
                                    <option value="monthly">Monthly Report</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <a href="<?= base_url('admin/history-reports') ?>" class="btn btn-secondary w-100">
                                <i class="fas fa-history"></i> View Past Reports
                            </a>
                        </div>
                    </div>
                </section>

                <div class="modal fade appt-vibe-modal" id="historyModal" tabindex="-1" aria-labelledby="historyModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content appointment-modal-content">
                            <div class="modal-header appointment-modal-header">
                                <h5 class="modal-title" id="historyModalLabel">Report History</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Date Generated</th>
                                                <th>Report Type</th>
                                                <th>Total Appointments</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody id="historyTableBody"></tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>

                <section class="stats-summary va-stats" aria-label="Appointment statistics">
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

                <!-- Charts -->
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

                <section class="appointment-container rpt-panel va-appointments-panel">
                    <header class="va-panel-header rpt-panel-header">
                        <div class="va-panel-header-text">
                            <h2 class="rpt-section-title mb-0">
                                <i class="fas fa-table-list me-2"></i>
                                List of All Your Appointments
                            </h2>
                            <p class="va-panel-sub mb-0">Filter by status, search students, and export reports</p>
                        </div>
                    </header>

                        <!-- Navigation Tabs -->
                        <ul class="nav nav-tabs rpt-appt-tabs mb-3" id="appointmentTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="all-tab" data-bs-toggle="tab" data-bs-target="#all" type="button">
                                    <i class="fas fa-list-alt"></i>
                                    <span class="tab-text">All Appointments</span>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="pending-tab" data-bs-toggle="tab" data-bs-target="#pending" type="button">
                                    <i class="fas fa-clock"></i>
                                    <span class="tab-text">Pending</span>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="followup-tab" data-bs-toggle="tab" data-bs-target="#followup" type="button">
                                    <i class="fas fa-calendar-plus"></i>
                                    <span class="tab-text">Follow-up</span>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="approved-tab" data-bs-toggle="tab" data-bs-target="#approved" type="button">
                                    <i class="fas fa-check-circle"></i>
                                    <span class="tab-text">Approved</span>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="rescheduled-tab" data-bs-toggle="tab" data-bs-target="#rescheduled" type="button">
                                    <i class="fas fa-calendar-alt"></i>
                                    <span class="tab-text">Rescheduled</span>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="completed-tab" data-bs-toggle="tab" data-bs-target="#completed" type="button">
                                    <i class="fas fa-check-double"></i>
                                    <span class="tab-text">Completed</span>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="feedback-pending-tab" data-bs-toggle="tab" data-bs-target="#feedback-pending" type="button">
                                    <i class="fas fa-star"></i>
                                    <span class="tab-text">InProgress</span>
                                </button>
                            </li>
                        </ul>

                    <div class="rpt-toolbar va-toolbar">
                            <div class="rpt-toolbar-field">
                                <label class="rpt-toolbar-label" for="searchInput">Search</label>
                                <div class="input-group rpt-input-group">
                                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                                    <input type="text" class="form-control" id="searchInput" placeholder="Search appointments...">
                                </div>
                            </div>
                            <div class="rpt-toolbar-field">
                                <label class="rpt-toolbar-label" for="dateFilter">Filter by month</label>
                                <div class="input-group rpt-input-group">
                                    <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                    <input type="month" class="form-control" id="dateFilter">
                                </div>
                            </div>
                            <div class="rpt-toolbar-actions">
                                <button type="button" class="rpt-btn-export rpt-btn-pdf" id="exportPDF">
                                    <i class="fas fa-file-pdf"></i>
                                    <span>Export PDF</span>
                                </button>
                                <button type="button" class="rpt-btn-export rpt-btn-excel" id="exportExcel">
                                    <i class="fas fa-file-excel"></i>
                                    <span>Export Excel</span>
                                </button>
                            </div>
                        </div>

                    <div class="modal fade appt-vibe-modal" id="exportFiltersModal" tabindex="-1" aria-labelledby="exportFiltersModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-scrollable">
                            <div class="modal-content appointment-modal-content">
                                <div class="modal-header appointment-modal-header">
                                    <h5 class="modal-title" id="exportFiltersModalLabel"><i class="fas fa-filter me-2"></i>Export Filters</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                    <div class="modal-body">
                                        <!-- Date Range Filters -->
                                        <div class="row g-3 mb-4">
                                            <div class="col-md-3">
                                                <label for="exportStartDate" class="form-label">Start Date</label>
                                                <input type="date" class="form-control" id="exportStartDate">
                                            </div>
                                            <div class="col-md-3">
                                                <label for="exportEndDate" class="form-label">End Date</label>
                                                <input type="date" class="form-control" id="exportEndDate">
                                            </div>
                                            <div class="col-md-6 d-flex align-items-end">
                                                <small class="text-muted">
                                                    <i class="fas fa-info-circle me-1"></i>
                                                    Leave dates empty to export all appointments from the selected status tab.
                                                </small>
                                            </div>
                                        </div>

                                        <!-- Additional Filters -->
                                        <div class="row g-3 mb-2">
                                            <div class="col-md-6">
                                                <label for="exportCounselorFilter" class="form-label">Counselor</label>
                                                <select class="form-select" id="exportCounselorFilter">
                                                    <option value="">All Counselors</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="exportStudentFilter" class="form-label">Student</label>
                                                <select class="form-select" id="exportStudentFilter">
                                                    <option value="">All Students</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label for="exportCourseFilter" class="form-label">Course</label>
                                                <select class="form-select" id="exportCourseFilter">
                                                    <option value="">All Courses</option>
                                                    <option value="BSIT">BSIT</option>
                                                    <option value="BSABE">BSABE</option>
                                                    <option value="BSEnE">BSEnE</option>
                                                    <option value="BSHM">BSHM</option>
                                                    <option value="BFPT">BFPT</option>
                                                    <option value="BSA">BSA</option>
                                                    <option value="BTHM">BTHM</option>
                                                    <option value="BSSW">BSSW</option>
                                                    <option value="BSAF">BSAF</option>
                                                    <option value="BTLED">BTLED</option>
                                                    <option value="DAT-BAT">DAT-BAT</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="exportYearLevelFilter" class="form-label">Year Level</label>
                                                <select class="form-select" id="exportYearLevelFilter">
                                                    <option value="">All Year Levels</option>
                                                    <option value="I">I</option>
                                                    <option value="II">II</option>
                                                    <option value="III">III</option>
                                                    <option value="IV">IV</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button class="btn btn-outline-secondary" id="clearAllFilters">
                                            <i class="fas fa-times me-1"></i>Clear All
                                        </button>
                                        <button class="btn btn-outline-primary" id="clearDateRange">
                                            <i class="fas fa-calendar-times me-1"></i>Clear Dates
                                        </button>
                                        <button class="btn btn-primary" id="applyFilters">
                                            <i class="fas fa-check me-1"></i>Apply Filters & Export
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                    <div class="tab-content" id="appointmentTabContent">
                        <div class="loading-spinner appt-state appt-loading" style="display: none;">
                            <div class="appt-loader" role="status" aria-label="Loading appointments">
                                <span></span><span></span><span></span>
                            </div>
                            <p>Loading appointments...</p>
                        </div>

                        <div class="empty-state appt-empty alert alert-info text-center" style="display: none;">
                            <i class="fas fa-info-circle me-2"></i>
                            No appointments found.
                        </div>

                             <!-- All Appointments Tab -->
                             <div class="tab-pane fade show active" id="all" role="tabpanel">
                                 <div class="rpt-table-shell">
                                 <div class="rpt-table-scroll">
                                     <table class="table table-hover mb-0 rpt-table va-appt-table rpt-has-reason">
                                         <thead class="rpt-table-head">
                                             <tr>
                                                 <th>User ID</th>
                                                 <th>Full Name</th>
                                                 <th>Date</th>
                                                 <th>Time</th>
                                                 <th>Method Type</th>
                                                 <th>Consultation Type</th>
                                                 <th>Session Type</th>
                                                 <th>Purpose</th>
                                                 <th>Student Concern</th>
                                                 <th>Counselor Remarks</th>
                                                 <th>Counselor</th>
                                                 <th>Student Feedbacks</th>
                                                 <th>Mean</th>
                                                 <th>Interpretation</th>
                                                 <th>Status</th>
                                                 <th class="rpt-col-reason">Reason for Status</th>
                                             </tr>
                                         </thead>
                                         <tbody id="allAppointmentsTable"></tbody>
                                     </table>
                                 </div>
                                 </div>
                              </div>

                              <!-- Pending Appointments Tab -->
                              <div class="tab-pane fade" id="pending" role="tabpanel">
                                  <div class="rpt-table-shell">
                                  <div class="rpt-table-scroll">
                                      <table class="table table-hover mb-0 rpt-table va-appt-table rpt-has-reason">
                                          <thead class="rpt-table-head">
                                              <tr>
                                                  <th>User ID</th>
                                                  <th>Full Name</th>
                                                  <th>Date</th>
                                                  <th>Time</th>
                                                  <th>Method Type</th>
                                                  <th>Consultation Type</th>
                                                  <th>Session Type</th>
                                                  <th>Purpose</th>
                                                  <th>Student Concern</th>
                                                  <th>Counselor Remarks</th>
                                                  <th>Counselor</th>
                                                  <th>Student Feedbacks</th>
                                                  <th>Mean</th>
                                                  <th>Interpretation</th>
                                                  <th>Status</th>
                                                  <th class="rpt-col-reason">Reason for Status</th>
                                              </tr>
                                          </thead>
                                          <tbody id="pendingAppointmentsTable"></tbody>
                                      </table>
                                  </div>
                                  </div>
                              </div>

                              <!-- Approved Appointments Tab -->
                              <div class="tab-pane fade" id="approved" role="tabpanel">
                                 <div class="rpt-table-shell">
                                 <div class="rpt-table-scroll">
                                     <table class="table table-hover mb-0 rpt-table va-appt-table">
                                         <thead class="rpt-table-head">
                                             <tr>
                                                 <th>User ID</th>
                                                 <th>Full Name</th>
                                                 <th>Date</th>
                                                 <th>Time</th>
                                                 <th>Method Type</th>
                                                 <th>Consultation Type</th>
                                                 <th>Session Type</th>
                                                 <th>Purpose</th>
                                                 <th>Student Concern</th>
                                                 <th>Counselor Remarks</th>
                                                 <th>Counselor</th>
                                                 <th>Student Feedbacks</th>
                                                 <th>Mean</th>
                                                 <th>Interpretation</th>
                                                 <th>Status</th>
                                             </tr>
                                         </thead>
                                         <tbody id="approvedAppointmentsTable"></tbody>
                                     </table>
                                 </div>
                                 </div>
                             </div>

                             <!-- Rescheduled Appointments Tab -->
                             <div class="tab-pane fade" id="rescheduled" role="tabpanel">
                                 <div class="rpt-table-shell">
                                 <div class="rpt-table-scroll">
                                     <table class="table table-hover mb-0 rpt-table va-appt-table rpt-has-reason">
                                         <thead class="rpt-table-head">
                                             <tr>
                                                 <th>User ID</th>
                                                 <th>Full Name</th>
                                                 <th>Date</th>
                                                 <th>Time</th>
                                                 <th>Method Type</th>
                                                 <th>Consultation Type</th>
                                                 <th>Session Type</th>
                                                 <th>Purpose</th>
                                                 <th>Student Concern</th>
                                                 <th>Counselor Remarks</th>
                                                 <th>Counselor</th>
                                                 <th>Student Feedbacks</th>
                                                 <th>Mean</th>
                                                 <th>Interpretation</th>
                                                 <th>Status</th>
                                                 <th class="rpt-col-reason">Reason for Status</th>
                                             </tr>
                                         </thead>
                                         <tbody id="rescheduledAppointmentsTable"></tbody>
                                     </table>
                                 </div>
                                 </div>
                             </div>

                             <!-- Completed Appointments Tab -->
                             <div class="tab-pane fade" id="completed" role="tabpanel">
                                 <div class="rpt-table-shell">
                                 <div class="rpt-table-scroll">
                                     <table class="table table-hover mb-0 rpt-table va-appt-table">
                                         <thead class="rpt-table-head">
                                             <tr>
                                                 <th>User ID</th>
                                                 <th>Full Name</th>
                                                 <th>Date</th>
                                                 <th>Time</th>
                                                 <th>Method Type</th>
                                                 <th>Consultation Type</th>
                                                 <th>Session Type</th>
                                                 <th>Purpose</th>
                                                 <th>Student Concern</th>
                                                 <th>Counselor Remarks</th>
                                                 <th>Counselor</th>
                                                 <th>Student Feedbacks</th>
                                                 <th>Mean</th>
                                                 <th>Interpretation</th>
                                                 <th>Status</th>
                                             </tr>
                                         </thead>
                                         <tbody id="completedAppointmentsTable"></tbody>
                                     </table>
                                 </div>
                                 </div>
                             </div>

                             <!-- Feedback Pending Appointments Tab -->
                             <div class="tab-pane fade" id="feedback-pending" role="tabpanel">
                                 <div class="rpt-table-shell">
                                 <div class="rpt-table-scroll">
                                     <table class="table table-hover mb-0 rpt-table va-appt-table">
                                         <thead class="rpt-table-head">
                                             <tr>
                                                 <th>User ID</th>
                                                 <th>Full Name</th>
                                                 <th>Date</th>
                                                 <th>Time</th>
                                                 <th>Method Type</th>
                                                 <th>Consultation Type</th>
                                                 <th>Session Type</th>
                                                 <th>Purpose</th>
                                                 <th>Student Concern</th>
                                                 <th>Counselor Remarks</th>
                                                 <th>Counselor</th>
                                                 <th>Student Feedbacks</th>
                                                 <th>Mean</th>
                                                 <th>Interpretation</th>
                                                 <th>Status</th>
                                             </tr>
                                         </thead>
                                         <tbody id="feedbackPendingAppointmentsTable"></tbody>
                                     </table>
                                 </div>
                                 </div>
                             </div>
                        <div class="tab-pane fade" id="followup" role="tabpanel">
                            <div class="rpt-table-shell">
                            <div class="rpt-table-scroll">
                                <table class="table table-hover mb-0 rpt-table va-appt-table">
                                    <thead class="rpt-table-head">
                                        <tr>
                                            <th>User ID</th>
                                            <th>Full Name</th>
                                            <th>Date</th>
                                            <th>Time</th>
                                            <th>Method Type</th>
                                            <th>Consultation Type</th>
                                            <th>Session Type</th>
                                            <th>Purpose</th>
                                            <th>Student Concern</th>
                                            <th>Counselor Remarks</th>
                                            <th>Counselor</th>
                                            <th>Student Feedbacks</th>
                                            <th>Mean</th>
                                            <th>Interpretation</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody id="followUpAppointmentsTable"></tbody>
                                </table>
                            </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
            </div>
        </main>
    </div>

    <!-- Quotes Management Modal -->
    <div class="modal fade" id="quotesManagementModal" tabindex="-1" aria-labelledby="quotesManagementModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header" style="background: linear-gradient(135deg, #060E57, #0A1875); color: white;">
                    <h5 class="modal-title" id="quotesManagementModalLabel">
                        <i class="fas fa-quote-left me-2"></i>Manage Quotes
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Navigation Tabs -->
                    <ul class="nav nav-tabs mb-4" id="quoteTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="pending-tab" data-bs-toggle="tab" data-bs-target="#pending-quotes" type="button" role="tab">
                                <i class="fas fa-clock me-1"></i>Pending
                                <span class="badge bg-warning ms-2" id="pending-count">0</span>
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="approved-tab" data-bs-toggle="tab" data-bs-target="#approved-quotes" type="button" role="tab">
                                <i class="fas fa-check-circle me-1"></i>Approved
                                <span class="badge bg-success ms-2" id="approved-count">0</span>
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="rejected-tab" data-bs-toggle="tab" data-bs-target="#rejected-quotes" type="button" role="tab">
                                <i class="fas fa-times-circle me-1"></i>Rejected
                                <span class="badge bg-danger ms-2" id="rejected-count">0</span>
                            </button>
                        </li>
                    </ul>

                    <!-- Tab Content -->
                    <div class="tab-content" id="quoteTabContent">
                        <!-- Pending Quotes Tab -->
                        <div class="tab-pane fade show active" id="pending-quotes" role="tabpanel">
                            <div id="pendingQuotesList" class="d-flex flex-column gap-3">
                                <div class="text-center py-4">
                                    <i class="fas fa-spinner fa-spin fa-2x text-primary"></i>
                                    <p class="mt-2 text-muted">Loading quotes...</p>
                                </div>
                            </div>
                        </div>

                        <!-- Approved Quotes Tab -->
                        <div class="tab-pane fade" id="approved-quotes" role="tabpanel">
                            <div id="approvedQuotesList" class="d-flex flex-column gap-3">
                                <div class="text-center py-4">
                                    <i class="fas fa-spinner fa-spin fa-2x text-primary"></i>
                                    <p class="mt-2 text-muted">Loading quotes...</p>
                                </div>
                            </div>
                        </div>

                        <!-- Rejected Quotes Tab -->
                        <div class="tab-pane fade" id="rejected-quotes" role="tabpanel">
                            <div id="rejectedQuotesList" class="d-flex flex-column gap-3">
                                <div class="text-center py-4">
                                    <i class="fas fa-spinner fa-spin fa-2x text-primary"></i>
                                    <p class="mt-2 text-muted">Loading quotes...</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Rejection Reason Modal -->
    <div class="modal fade" id="rejectionReasonModal" tabindex="-1" aria-labelledby="rejectionReasonModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="rejectionReasonModalLabel">
                        <i class="fas fa-times-circle me-2 text-danger"></i>Reject Quote
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted mb-3">Please provide a reason for rejecting this quote:</p>
                    <form id="rejectionReasonForm">
                        <div class="mb-3">
                            <label for="rejectionReason" class="form-label">Rejection Reason <span class="text-danger">*</span></label>
                            <textarea
                                class="form-control"
                                id="rejectionReason"
                                name="rejection_reason"
                                rows="4"
                                maxlength="500"
                                placeholder="Enter the reason for rejection..."
                                required></textarea>
                            <div class="form-text">This reason will be visible to the counselor who submitted the quote.</div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmRejectionBtn">
                        <i class="fas fa-times-circle me-2"></i>Reject Quote
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Shared Confirmation Modal -->
    <div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmationModalLabel">Confirm Action</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="confirmationMessageContent">Are you sure?</div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="confirmationConfirmBtn">Confirm</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Shared Alert Modal -->
    <div class="modal fade" id="alertModal" tabindex="-1" aria-labelledby="alertModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="alertModalLabel">Information</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body d-flex align-items-start gap-2">
                    <span id="alertIcon"><i class="fas fa-info-circle text-primary"></i></span>
                    <span id="alertMessageContent">Message</span>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">OK</button>
                </div>
            </div>
        </div>
    </div>

    <!-- View Details Modal -->
    <div class="modal fade" id="viewDetailsModal" tabindex="-1" aria-labelledby="viewDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 12px; border: none; box-shadow: 0 10px 40px rgba(0,0,0,0.15);">
                <div class="modal-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 12px 12px 0 0; border: none;">
                    <h5 class="modal-title" id="viewDetailsModalLabel" style="font-weight: 600;">
                        <i class="fas fa-info-circle me-2"></i>Details
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="padding: 2rem;">
                    <div class="mb-4">
                        <label class="form-label fw-bold" style="color: #667eea; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.5px;">Student Name</label>
                        <p id="modalStudentName" class="form-control-plaintext" style="font-size: 1.1rem; color: #333; font-weight: 500;"></p>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-bold" style="color: #667eea; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.5px;">Date & Time</label>
                        <p id="modalDateTime" class="form-control-plaintext" style="font-size: 1.1rem; color: #333; font-weight: 500;"></p>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-bold" style="color: #667eea; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.5px;">Details</label>
                        <div id="modalDetails" class="form-control-plaintext bg-light p-4 rounded" style="font-size: 1rem; color: #555; line-height: 1.6; background-color: #f8f9fa; border-left: 4px solid #667eea;"></div>
                    </div>
                </div>
                <div class="modal-footer" style="border-top: 1px solid #e9ecef; padding: 1.5rem;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="padding: 0.6rem 1.5rem; border-radius: 8px; font-weight: 500;">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>
    <script src="<?= base_url('js/shared/appointment_pdf_dashboard.js') ?>?v=4"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        window.BASE_URL = "<?= base_url() ?>";
        window.FLASK_MIDDLEWARE_URL = "http://localhost:5000";
    </script>
    <script src="<?= base_url('js/utils/secureLogger.js') ?>"></script>
    <script src="<?= base_url('js/admin/admin_dashboard.js') ?>"></script>
    <script src="<?= base_url('js/admin/profile_sync.js') ?>"></script>
    <script src="<?= base_url('js/counselor/report_charts_theme.js') ?>?v=3"></script>
    <script src="<?= base_url('js/shared/report_status_utils.js') ?>?v=1"></script>
    <script src="<?= base_url('js/admin/view_all_appointments.js') ?>?v=8"></script>
    <script src="<?= base_url('js/modals/student_dashboard_modals.js') ?>"></script>
    <script src="<?= base_url('js/admin/quotes_management.js') ?>"></script>
    <script src="<?= base_url('js/admin/logout.js') ?>" defer></script>
    <script src="<?= base_url('js/utils/sidebar.js') ?>"></script>
</body>

</html>

