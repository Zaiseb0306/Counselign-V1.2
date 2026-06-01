<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description"
        content="University Guidance Counseling Services - Your safe space for support and guidance" />
    <meta name="keywords" content="counseling, guidance, university, support, mental health, student wellness" />
    <title>Counselign</title>
    <link rel="icon" href="<?= base_url('Photos/counselign.ico') ?>" sizes="16x16 32x32" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&family=JetBrains+Mono:wght@500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="<?= base_url('css/counselor/counselor_dashboard.css') ?>">
    <link rel="stylesheet" href="<?= base_url('css/counselor/header.css') ?>">
    <link rel="stylesheet" href="<?= base_url('css/utils/resources.css') ?>">
    <link rel="stylesheet" href="<?= base_url('css/utils/sidebar.css') ?>">

    <link rel="stylesheet" href="<?= base_url('css/counselor/counselor_vibe_shared.css?v=5') ?>">
    <link rel="stylesheet" href="<?= base_url('css/student/student_notifications_dropdown.css?v=4') ?>">
    <link rel="stylesheet" href="<?= base_url('css/utils/vibe_topbar.css?v=3') ?>">


</head>

<body class="dash-page-body">
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
                <a href="<?= base_url('counselor/dashboard') ?>" class="sidebar-link active" title="Dashboard">
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
                    <i class="fas fa-user-tie me-2"></i>
                    Counselor Dashboard
                </h1>
            </div>

            <div class="top-bar-right">

                <button class="top-bar-btn" id="openQuoteModalBtn" title="Quotes">
                    <i class="fas fa-quote-right" aria-hidden="true"></i>
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

        <main class="appt-page dash-page">
            <div class="container-fluid px-4 dash-container">

                <section class="stats-summary dash-stats" aria-label="Dashboard overview">
                    <article class="stat-card pending">
                        <div class="stat-icon"><i class="fas fa-clock text-danger"></i></div>
                        <div class="stat-details">
                            <h3 id="statPendingCount">-</h3>
                            <p>Pending</p>
                        </div>
                    </article>
                    <article class="stat-card completed">
                        <div class="stat-icon"><i class="fas fa-envelope text-primary"></i></div>
                        <div class="stat-details">
                            <h3 id="statMessagesCount">-</h3>
                            <p>Messages</p>
                        </div>
                    </article>
                    <article class="stat-card approved">
                        <div class="stat-icon"><i class="fas fa-bell text-success"></i></div>
                        <div class="stat-details">
                            <h3 id="statNotificationsCount">-</h3>
                            <p>Notifications</p>
                        </div>
                    </article>
                    <article class="stat-card feedback-pending">
                        <div class="stat-icon"><i class="fas fa-folder-open text-secondary"></i></div>
                        <div class="stat-details">
                            <h3 id="statResourcesCount">-</h3>
                            <p>Resources</p>
                        </div>
                    </article>
                </section>

                <section class="dash-workspace">
                    <!-- <p class="dash-welcome">Welcome to Your Workspace, Counselor!</p> -->
                    <div class="row g-4 dash-cards-row">
                        <div class="col-lg-6">
                            <section class="dashboard-card dash-panel" id="messagesCard" style="cursor: pointer;">
                                <header class="dash-panel-header">
                                    <h3 class="dash-panel-title title-color mb-0">
                                        <i class="fas fa-envelope me-2"></i> Messages
                                        <span id="messagesBadge" class="badge bg-danger messages-badge" style="display:none;" aria-label="New messages">0</span>
                                    </h3>
                                </header>
                                <div id="messagesLoading" class="appt-state appt-loading dash-loading">
                                    <div class="appt-loader" role="status" aria-label="Loading messages">
                                        <span></span><span></span><span></span>
                                    </div>
                                    <p>Loading messages...</p>
                                </div>
                                <div class="dash-panel-body d-flex flex-column gap-3"></div>
                            </section>
                        </div>
                        <div class="col-lg-6">
                            <section class="dashboard-card dash-panel" id="appointments-container">
                                <header class="dash-panel-header">
                                    <h3 class="dash-panel-title title-color mb-0"><i class="fas fa-list-alt me-2"></i> Appointments</h3>
                                </header>
                                <div id="appointmentsLoading" class="appt-state appt-loading dash-loading">
                                    <div class="appt-loader" role="status" aria-label="Loading appointments">
                                        <span></span><span></span><span></span>
                                    </div>
                                    <p>Loading appointments...</p>
                                </div>
                                <div class="dash-panel-body d-flex flex-column gap-3"></div>
                                <footer class="dash-panel-footer d-flex justify-content-start gap-2">
                                    <a href="<?= base_url('counselor/appointments/view-all') ?>" class="btn btn-dash btn-dash-primary">
                                        <i class="fas fa-chart-line me-1"></i> Reports
                                    </a>
                                    <a href="<?= base_url('counselor/appointments') ?>" class="btn btn-dash btn-dash-success">
                                        <i class="fas fa-list-alt me-1"></i> Manage
                                    </a>
                                </footer>
                            </section>
                        </div>
                    </div>
                </section>
            </div>
        </main>

        <!-- Resources Accordion Section -->
        <section class="resources-section dash-resources">
            <div class="container-fluid px-4 dash-container">
                <div class="dash-panel dash-resources-panel">
                    <div class="accordion" id="resourcesParentAccordion">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="resourcesParentHeading">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#resourcesParentCollapse" aria-expanded="true" aria-controls="resourcesParentCollapse">
                                <i class="fas fa-folder-open me-2"></i>
                                <span class="fw-bold">Resources</span>
                            </button>
                        </h2>
                        <div id="resourcesParentCollapse" class="accordion-collapse collapse show" aria-labelledby="resourcesParentHeading" data-bs-parent="#resourcesParentAccordion">
                            <div class="accordion-body">
                                <div class="accordion" id="resourcesAccordion">
                                    <div id="resourcesAccordionContent">
                                        <div class="text-center py-4">
                                            <div class="appt-loader" role="status">
                                                <span></span><span></span><span></span>
                                            </div>
                                            <p class="mt-2 text-muted">Loading resources...</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Chat Popup -->
    <div id="chatPopup" class="chat-popup">
        <div class="chat-header">
            <div class="font-bold">Send A Message to the Admin</div>
            <button id="closeChat" class="text-white">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="chat-body" id="chatMessages">
            <div class="text-center text-gray-500 text-sm mb-4">
                Your conversation is private and confidential
            </div>
            <div id="messagesContainer">
                <!-- Messages will be loaded here -->
            </div>
        </div>
        <div class="chat-footer">
            <form id="messageForm" class="message-form">
                <div class="message-input-wrapper">
                    <textarea
                        id="messageInput"
                        name="messageInput"
                        class="message-input"
                        placeholder="Type your message here..."
                        rows="2"
                        required>
                    </textarea>
                </div>
                <button type="submit" class="send-button" id="sendMessage">
                    <i class="fas fa-paper-plane"></i>
                </button>
            </form>
        </div>
    </div>

    <!-- Appointment Details Modal -->
    <div class="modal fade appt-vibe-modal" id="appointmentDetailsModal" tabindex="-1" aria-labelledby="appointmentDetailsLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content appointment-modal-content">
                <div class="modal-header appointment-modal-header">
                    <h5 class="modal-title" id="appointmentDetailsLabel">Appointment Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="appointmentDetailsBody">
                    <!-- Appointment details will be injected here -->
                </div>

                <div class="d-flex justify-content-end mt-3 gap-2 p-3" style="position: relative; z-index: 10;">
                    <a href="<?= base_url('counselor/appointments') ?>" class="btn btn-primary" style="pointer-events: auto;">
                        <i class="fas fa-list-alt me-1"></i> Manage
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Shared Confirmation Modal (used for logout and other confirms) -->
    <div class="modal fade appt-vibe-modal" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content appointment-modal-content">
                <div class="modal-header appointment-modal-header">
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

    <!-- Shared Alert Modal (utility compatible) -->
    <div class="modal fade appt-vibe-modal" id="alertModal" tabindex="-1" aria-labelledby="alertModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content appointment-modal-content">
                <div class="modal-header appointment-modal-header">
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

    <!-- Shared Notice Modal (utility compatible) -->
    <div class="modal fade appt-vibe-modal" id="noticeModal" tabindex="-1" aria-labelledby="noticeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content appointment-modal-content">
                <div class="modal-header appointment-modal-header reschedule-modal-header">
                    <h5 class="modal-title" id="noticeModalLabel">Notice</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body d-flex align-items-start gap-2">
                    <span id="noticeIcon"><i class="fas fa-bell text-warning"></i></span>
                    <span id="noticeMessageContent">Notice</span>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Daily Quote Submission Modal -->
    <div class="modal fade appt-vibe-modal" id="quoteSubmissionModal" tabindex="-1" aria-labelledby="quoteSubmissionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content appointment-modal-content">
                <div class="modal-header appointment-modal-header">
                    <h5 class="modal-title" id="quoteSubmissionModalLabel">
                        <i class="fas fa-quote-left me-2"></i>Share a Daily Quote
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted mb-4">
                        <i class="fas fa-info-circle me-1"></i>
                        Inspire students by submitting motivational quotes. Your submissions will be reviewed by admins before being displayed.
                    </p>

                    <form id="quoteSubmissionForm">
                        <div class="row g-3">
                            <div class="col-12">
                                <label for="quoteText" class="form-label fw-bold">
                                    Quote <span class="text-danger">*</span>
                                </label>
                                <textarea
                                    class="form-control"
                                    id="quoteText"
                                    name="quote_text"
                                    rows="4"
                                    maxlength="500"
                                    placeholder="Enter an inspirational quote..."
                                    required></textarea>
                                <div class="form-text d-flex justify-content-between">
                                    <span>Share wisdom that inspires and motivates</span>
                                    <span class="fw-bold"><span id="charCount">0</span>/500</span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label for="authorName" class="form-label fw-bold">
                                    Author <span class="text-danger">*</span>
                                </label>
                                <input
                                    type="text"
                                    class="form-control"
                                    id="authorName"
                                    name="author_name"
                                    maxlength="255"
                                    placeholder="e.g., Maya Angelou"
                                    required>
                            </div>

                            <div class="col-md-6">
                                <label for="category" class="form-label fw-bold">
                                    Category <span class="text-danger">*</span>
                                </label>
                                <select class="form-select" id="category" name="category" required>
                                    <option value="" disabled selected>Select a category</option>
                                    <option value="Inspirational">✨ Inspirational</option>
                                    <option value="Motivational">💪 Motivational</option>
                                    <option value="Wisdom">🦉 Wisdom</option>
                                    <option value="Life">🌱 Life</option>
                                    <option value="Success">🎯 Success</option>
                                    <option value="Education">📚 Education</option>
                                    <option value="Perseverance">🏔️ Perseverance</option>
                                    <option value="Courage">🦁 Courage</option>
                                    <option value="Hope">🌟 Hope</option>
                                    <option value="Kindness">💝 Kindness</option>
                                </select>
                            </div>

                            <div class="col-12">
                                <label for="source" class="form-label fw-bold">
                                    Source <span class="text-muted">(Optional)</span>
                                </label>
                                <input
                                    type="text"
                                    class="form-control"
                                    id="source"
                                    name="source"
                                    maxlength="255"
                                    placeholder="e.g., Book title, Speech, Movie">
                                <div class="form-text">Where this quote is from (optional)</div>
                            </div>
                        </div>

                        <!-- Alert Container -->
                        <div id="quoteAlertContainer" class="mt-3"></div>
                    </form>
                </div>
                <div class="modal-footer d-flex justify-content-between">
                    <button type="button" class="btn btn-outline-primary" id="viewMyQuotesBtn">
                        <i class="fas fa-history me-2"></i>My Submissions
                    </button>
                    <div>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-2"></i>Cancel
                        </button>
                        <button type="submit" class="btn btn-primary" id="submitQuoteBtn" form="quoteSubmissionForm">
                            <i class="fas fa-paper-plane me-2"></i>Submit Quote
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- My Quotes List Modal -->
    <div class="modal fade appt-vibe-modal" id="myQuotesModal" tabindex="-1" aria-labelledby="myQuotesModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
            <div class="modal-content appointment-modal-content">
                <div class="modal-header appointment-modal-header">
                    <h5 class="modal-title" id="myQuotesModalLabel">
                        <i class="fas fa-history me-2"></i>My Submitted Quotes
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3 alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Status Guide:</strong>
                        <span class="badge bg-warning ms-2">Pending Review</span>
                        <span class="badge bg-success ms-1">Approved</span>
                        <span class="badge bg-danger ms-1">Rejected</span>
                    </div>

                    <div id="myQuotesList" class="d-flex flex-column gap-3">
                        <!-- Quotes will be loaded here -->
                        <div class="text-center py-4">
                            <i class="fas fa-spinner fa-spin fa-2x text-primary"></i>
                            <p class="mt-2 text-muted">Loading your quotes...</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-between align-items-center">
                    <button type="button" class="btn btn-outline-primary" id="openQuoteSubmissionFromMyQuotes">
                        <i class="fas fa-plus me-2"></i>New Quote
                    </button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Preview Modal -->
    <div class="modal fade appt-vibe-modal" id="previewModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content appointment-modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="previewModalTitle">Resource Preview</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="previewModalBody" style="min-height: 400px;">
                    <!-- Preview content will be loaded here -->
                </div>
            </div>
        </div>
    </div>


    <?= view('counselor/partials/notifications_dropdown') ?>

    <script>
        window.BASE_URL = "<?= base_url() ?>";
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/mammoth/1.6.0/mammoth.browser.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- ADDED: Shared Resource Preview Module -->
    <script src="<?= base_url('js/utils/resource-preview.js') ?>"></script>

    <script src="<?= base_url('js/modals/student_dashboard_modals.js') ?>"></script>
    <script src="<?= base_url('js/counselor/counselor_notifications_dropdown.js?v=3') ?>"></script>
    <script src="<?= base_url('js/counselor/counselor_dashboard.js') ?>"></script>
    <script src="<?= base_url('js/utils/secureLogger.js') ?>"></script>
    <script src="<?= base_url('js/counselor/logout.js') ?>"></script>
    <script src="<?= base_url('js/utils/sidebar.js') ?>"></script>


</body>

</html>