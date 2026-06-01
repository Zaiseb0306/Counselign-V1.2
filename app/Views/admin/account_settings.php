<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title>Profile - Counselign</title>
    <link rel="icon" href="<?= base_url('Photos/counselign.ico') ?>" sizes="16x16 32x32" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <?= view('admin/partials/vibe_styles') ?>
    <link rel="stylesheet" href="<?= base_url('css/shared/account_profile_vibe.css?v=1') ?>">
</head>

<body class="adm-acct-page-body acct-page-body">
    <aside class="sidebar" id="uniSidebar">
        <div class="sidebar-content">
            <button class="sidebar-toggle-btn" id="sidebarToggle" title="Toggle Sidebar">
                <img src="<?= base_url('Photos/counselign_logo.png') ?>" alt="Logo" class="sidebar-logo">
                <span class="sidebar-brand-text">Counselign</span>
            </button>
            <nav class="sidebar-nav">
                <a href="<?= base_url('admin/dashboard') ?>" class="sidebar-link" title="Dashboard">
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
                    <i class="fas fa-chart-bar"></i>
                    <span class="sidebar-text">Data Analytics</span>
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

    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    <button class="floating-sidebar-toggle" id="floatingSidebarToggle" title="Open Menu">
        <img src="<?= base_url('Photos/counselign_logo.png') ?>" alt="Menu">
    </button>

    <div class="main-wrapper" id="mainWrapper">
        <header class="top-bar">
            <div class="top-bar-left">
                <h1 class="page-title-header">
                    <i class="fas fa-user-cog me-2"></i>
                    Profile
                </h1>
            </div>
            <div class="top-bar-right">
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

        <main class="main-content">
            <div class="acct-vibe-wrap">
                <!-- Profile hero -->
                <section class="acct-hero adm-vibe-panel">
                    <div class="acct-hero-mesh" aria-hidden="true"></div>
                    <div class="acct-hero-inner">
                        <div class="acct-avatar-block">
                            <div class="acct-avatar-ring">
                                <img src="<?= base_url('Photos/profile.png') ?>" alt="Profile" class="acct-avatar" id="profile-avatar">
                                <button type="button" class="acct-avatar-edit" onclick="updateProfilePicture()" title="Update photo">
                                    <i class="fas fa-camera"></i>
                                </button>
                            </div>
                        </div>
                        <div class="acct-hero-meta">
                            <span class="acct-role-badge"><i class="fas fa-shield-halved me-1"></i> Administrator</span>
                            <h2 class="acct-display-name" id="admin-username-display">Loading...</h2>
                            <p class="acct-hero-sub">Manage your account details and security</p>
                            <div class="acct-id-chip">
                                <span class="acct-id-label">Account ID</span>
                                <span class="acct-id-value" id="admin-id">—</span>
                            </div>
                        </div>
                    </div>
                    <div class="acct-quick-stats">
                        <div class="acct-stat-pill">
                            <i class="fas fa-envelope"></i>
                            <div>
                                <span class="acct-stat-label">Email</span>
                                <span class="acct-stat-value" id="admin-email-preview">Loading...</span>
                            </div>
                        </div>
                        <div class="acct-stat-pill">
                            <i class="fas fa-clock"></i>
                            <div>
                                <span class="acct-stat-label">Last login</span>
                                <span class="acct-stat-value" id="admin-last-login">—</span>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Settings panels -->
                <div class="acct-panels">
                    <section class="acct-panel adm-vibe-panel">
                        <header class="acct-panel-head">
                            <div class="acct-panel-icon"><i class="fas fa-id-card"></i></div>
                            <div>
                                <h3>Personal information</h3>
                                <p>Update how you appear across the admin portal</p>
                            </div>
                        </header>
                        <div class="acct-fields">
                            <div class="acct-field" data-field="email">
                                <div class="acct-field-icon"><i class="fas fa-envelope"></i></div>
                                <div class="acct-field-body">
                                    <span class="acct-field-label">Email address</span>
                                    <span class="acct-field-value" id="admin-email">Loading...</span>
                                </div>
                                <button type="button" class="acct-field-action" onclick="editField('email')" title="Edit email">
                                    <i class="fas fa-pen"></i>
                                </button>
                            </div>
                            <div class="acct-field" data-field="username">
                                <div class="acct-field-icon"><i class="fas fa-user"></i></div>
                                <div class="acct-field-body">
                                    <span class="acct-field-label">Username</span>
                                    <span class="acct-field-value" id="admin-username">Loading...</span>
                                </div>
                                <button type="button" class="acct-field-action" onclick="editField('username')" title="Edit username">
                                    <i class="fas fa-pen"></i>
                                </button>
                            </div>
                        </div>
                    </section>

                    <section class="acct-panel adm-vibe-panel">
                        <header class="acct-panel-head">
                            <div class="acct-panel-icon acct-panel-icon-security"><i class="fas fa-lock"></i></div>
                            <div>
                                <h3>Security</h3>
                                <p>Keep your account protected with a strong password</p>
                            </div>
                        </header>
                        <div class="acct-security-body">
                            <div class="acct-security-copy">
                                <i class="fas fa-key"></i>
                                <p>Passwords are encrypted. Use a unique passphrase you do not share elsewhere.</p>
                            </div>
                            <button type="button" class="acct-btn acct-btn-primary" onclick="changePassword()">
                                <i class="fas fa-lock me-2"></i>Change password
                            </button>
                        </div>
                    </section>
                </div>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        window.BASE_URL = "<?= base_url() ?>";
    </script>
    <script src="<?= base_url('js/admin/account_settings.js?v=2') ?>"></script>
    <script src="<?= base_url('js/utils/secureLogger.js') ?>"></script>
    <script src="<?= base_url('js/utils/sidebar.js') ?>"></script>
    <script src="<?= base_url('js/admin/logout.js') ?>"></script>
</body>

</html>
