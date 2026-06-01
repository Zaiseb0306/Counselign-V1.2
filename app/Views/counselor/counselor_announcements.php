<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="University Guidance Counseling Services - Announcements and Events" />
    <meta name="keywords" content="counseling, guidance, university, support, mental health, counselor wellness" />
    <title>Announcements and Events - Counselign</title>
    <link rel="icon" href="<?= base_url('Photos/counselign.ico') ?>" sizes="16x16 32x32" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&family=JetBrains+Mono:wght@500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('css/counselor/counselor_announcements.css') ?>">
    <link rel="stylesheet" href="<?= base_url('css/counselor/header.css') ?>">
    <link rel="stylesheet" href="<?= base_url('css/utils/sidebar.css') ?>">

    <link rel="stylesheet" href="<?= base_url('css/counselor/counselor_vibe_shared.css?v=5') ?>">
    <link rel="stylesheet" href="<?= base_url('css/student/student_notifications_dropdown.css?v=4') ?>">
    <link rel="stylesheet" href="<?= base_url('css/utils/vibe_topbar.css?v=3') ?>">
</head>

<body class="ann-page-body">
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
                <a href="<?= base_url('counselor/announcements') ?>" class="sidebar-link active" title="Announcement">
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
                    <i class="fas fa-bullhorn me-2"></i>
                    Announcements and Events
                </h1>
            </div>

            <div class="top-bar-right">

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
        <main class="appt-page ann-page">
            <div class="container-fluid px-4 ann-container">


                <section class="stats-summary ann-stats" aria-label="Announcements statistics">
                    <article class="stat-card completed">
                        <div class="stat-icon"><i class="fas fa-bullhorn text-primary"></i></div>
                        <div class="stat-details">
                            <h3 id="statAnnouncementsCount">-</h3>
                            <p>Announcements</p>
                        </div>
                    </article>
                    <article class="stat-card approved">
                        <div class="stat-icon"><i class="fas fa-calendar-check text-success"></i></div>
                        <div class="stat-details">
                            <h3 id="statEventsCount">-</h3>
                            <p>Total Events</p>
                        </div>
                    </article>
                    <article class="stat-card pending">
                        <div class="stat-icon"><i class="fas fa-hourglass-half text-danger"></i></div>
                        <div class="stat-details">
                            <h3 id="statUpcomingCount">-</h3>
                            <p>Upcoming</p>
                        </div>
                    </article>
                    <article class="stat-card feedback-pending">
                        <div class="stat-icon"><i class="fas fa-calendar-day text-secondary"></i></div>
                        <div class="stat-details">
                            <h3 id="statMonthEventsCount">-</h3>
                            <p>This Month</p>
                        </div>
                    </article>
                </section>

                <div class="announcements-container ann-layout">
                    <section class="ann-panel announcements-section ann-announcements-panel">
                        <header class="ann-panel-header">
                            <h3 class="subsection-title mb-0">
                                <i class="fas fa-bullhorn me-2"></i>Announcements
                            </h3>
                        </header>
                        <div id="annAnnouncementsLoading" class="appt-state appt-loading">
                            <div class="appt-loader" role="status" aria-label="Loading announcements">
                                <span></span><span></span><span></span>
                            </div>
                            <p>Loading announcements...</p>
                        </div>
                        <div class="scrollable-container ann-scroll">
                            <div class="announcements-list" id="announcementsList"></div>
                        </div>
                        <div id="noAnnouncements" class="appt-state appt-empty ann-empty" style="display: none;">
                            <div class="appt-empty-icon"><i class="fas fa-bullhorn"></i></div>
                            <h4>No announcements</h4>
                            <p>There are no announcements available right now.</p>
                        </div>
                    </section>

                    <div class="ann-sidebar">
                        <section class="ann-panel calendar-section ann-calendar-panel">
                            <header class="ann-panel-header ann-panel-header-compact">
                                <h3 class="subsection-title mb-0">
                                    <i class="fas fa-calendar-alt me-2"></i>Calendar
                                </h3>
                            </header>
                            <div class="calendar-container">
                                <div class="calendar-header">
                                    <button id="prevMonth" class="calendar-nav-btn" type="button" aria-label="Previous month">
                                        <i class="fas fa-chevron-left"></i>
                                    </button>
                                    <h4 id="currentMonth" class="calendar-month"></h4>
                                    <button id="nextMonth" class="calendar-nav-btn" type="button" aria-label="Next month">
                                        <i class="fas fa-chevron-right"></i>
                                    </button>
                                </div>
                                <div class="calendar-grid" id="calendarGrid"></div>
                            </div>
                        </section>

                        <section class="ann-panel upcoming-events-section ann-events-panel">
                            <header class="ann-panel-header">
                                <h3 class="subsection-title mb-0">
                                    <i class="fas fa-calendar-week me-2"></i>Upcoming Events
                                </h3>
                                <p class="ann-panel-hint">Scheduled counseling events</p>
                            </header>
                            <div id="annEventsLoading" class="appt-state appt-loading">
                                <div class="appt-loader" role="status" aria-label="Loading events">
                                    <span></span><span></span><span></span>
                                </div>
                                <p>Loading events...</p>
                            </div>
                            <div class="scrollable-container ann-scroll">
                                <div class="events-list" id="eventsList"></div>
                            </div>
                            <div id="noEvents" class="appt-state appt-empty ann-empty" style="display: none;">
                                <div class="appt-empty-icon"><i class="fas fa-calendar-times"></i></div>
                                <h4>No events scheduled</h4>
                                <p>There are no upcoming events on the calendar.</p>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </main>
    </div>


    <?= view('counselor/partials/notifications_dropdown') ?>
    <script>
        window.BASE_URL = "<?= base_url() ?>";
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= base_url('js/counselor/counselor_notifications_dropdown.js?v=3') ?>"></script>
    <script src="<?= base_url('js/counselor/counselor_announcements.js') ?>" defer></script>
    <script src="<?= base_url('js/counselor/counselor_drawer.js') ?>"></script>
    <script src="<?= base_url('js/utils/secureLogger.js') ?>"></script>
    <script src="<?= base_url('js/counselor/logout.js') ?>"></script>
    <script src="<?= base_url('js/utils/sidebar.js') ?>"></script>
</body>

</html>