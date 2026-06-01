<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Messages - Counselign</title>
    <link rel="icon" href="<?= base_url('Photos/counselign.ico') ?>" sizes="16x16 32x32" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&family=JetBrains+Mono:wght@500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="<?= base_url('css/counselor/counselor_messages.css?v=5') ?>" rel="stylesheet" />
    <link rel="stylesheet" href="<?= base_url('css/counselor/header.css') ?>">
    <link rel="stylesheet" href="<?= base_url('css/utils/sidebar.css') ?>">

    <link rel="stylesheet" href="<?= base_url('css/counselor/counselor_vibe_shared.css?v=3') ?>">
    <link rel="stylesheet" href="<?= base_url('css/student/student_notifications_dropdown.css?v=4') ?>">
    <link rel="stylesheet" href="<?= base_url('css/utils/vibe_topbar.css?v=3') ?>">
</head>

<body class="msg-page-body">
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

    <!-- Mobile Sidebar Overlay -->
    <div class="mobile-sidebar-overlay" id="mobileSidebarOverlay"></div>

    <div class="main-wrapper" id="mainWrapper">
        <!-- Interactive Profile Picture Section -->

        <!-- Top Bar -->
        <header class="top-bar">
            <div class="top-bar-left">
                <h1 class="page-title-header">
                    <i class="fas fa-comments me-2"></i>
                    Messages
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




        <main class="appt-page msg-page">
            <div class="container-fluid px-4 msg-container">
                

                <section class="stats-summary msg-stats" aria-label="Messaging statistics">
                    <article class="stat-card completed">
                        <div class="stat-icon"><i class="fas fa-comments text-primary"></i></div>
                        <div class="stat-details">
                            <h3 id="statConversationsCount">-</h3>
                            <p>Conversations</p>
                        </div>
                    </article>
                    <article class="stat-card pending">
                        <div class="stat-icon"><i class="fas fa-envelope text-danger"></i></div>
                        <div class="stat-details">
                            <h3 id="statUnreadCount">-</h3>
                            <p>Unread</p>
                        </div>
                    </article>
                    <article class="stat-card approved">
                        <div class="stat-icon"><i class="fas fa-circle text-success"></i></div>
                        <div class="stat-details">
                            <h3 id="statOnlineCount">-</h3>
                            <p>Online</p>
                        </div>
                    </article>
                    <article class="stat-card feedback-pending">
                        <div class="stat-icon"><i class="fas fa-user-clock text-secondary"></i></div>
                        <div class="stat-details">
                            <h3 id="statActiveCount">-</h3>
                            <p>Active</p>
                        </div>
                    </article>
                </section>

                <div class="messages-wrapper msg-messenger">

            <div class="messages-layout">
                <!-- Conversations Sidebar -->
                <div class="conversations-sidebar" id="conversationsSidebar">
                    <div class="sidebar-header">
                        <h3 class="sidebar-title">
                            <i class="fas fa-comments me-2"></i>
                            Conversations
                        </h3>
                    </div>

                    <!-- Search Box -->
                    <div class="search-section">
                        <div class="search-box">
                            <i class="fas fa-search search-icon"></i>
                            <input type="text" class="search-input" placeholder="Search conversations...">
                        </div>
                    </div>

                    <!-- Conversations List -->
                    <div class="conversations-list">
                        <div class="loading-state appt-state appt-loading" id="conversationsLoading">
                            <div class="appt-loader" role="status" aria-label="Loading conversations">
                                <span></span><span></span><span></span>
                            </div>
                            <p>Loading conversations...</p>
                        </div>
                    </div>
                </div>

                <!-- Chat Area -->
                <div class="chat-area">
                    <!-- Chat Header -->
                    <div class="chat-header">
                        <button type="button" class="mobile-sidebar-toggle" id="mobileSidebarToggle" aria-label="Show conversations list">
                            <i class="fas fa-list-ul" aria-hidden="true"></i>
                        </button>
                        <div class="chat-user-info">
                            <div class="user-avatar">
                                <i class="fas fa-user"></i>
                            </div>
                            <div class="user-details">
                                <h4 class="user-name">Messages</h4>
                                <span class="user-status">Select a conversation to start messaging</span>
                            </div>
                        </div>
                    </div>

                    <!-- Messages Area -->
                    <div class="messages-area" id="messages-container">
                        <div class="empty-chat appt-empty" id="empty-state">
                            <div class="empty-icon appt-empty-icon">
                                <i class="fas fa-inbox"></i>
                            </div>
                            <h5>No Messages Yet</h5>
                            <p>Select a conversation from the list to view messages.</p>
                        </div>
                    </div>

                    <!-- Message Input -->
                    <div class="message-input-section">
                        <div class="input-container">
                            <textarea id="message-input" class="message-input"
                                placeholder="Select a conversation to reply..." disabled></textarea>
                            <button id="send-button" class="send-button" disabled>
                                <i class="fas fa-paper-plane"></i>
                            </button>
                        </div>
                    </div>
                </div>
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
    <script src="<?= base_url('js/counselor/counselor_messages.js') ?>" defer></script>
    <script src="<?= base_url('js/counselor/counselor_drawer.js') ?>"></script>
    <script src="<?= base_url('js/utils/secureLogger.js') ?>"></script>
    <script src="<?= base_url('js/counselor/logout.js') ?>"></script>
    <script src="<?= base_url('js/utils/sidebar.js') ?>"></script>

</body>

</html>