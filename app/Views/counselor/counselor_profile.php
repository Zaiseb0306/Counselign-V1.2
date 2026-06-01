<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="University Guidance Counseling Services - Counselor Profile Page" />
    <meta name="keywords"
        content="counseling, guidance, university, support, mental health, counselor wellness, profile" />
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title>Counselor Profile - Counselign</title>
    <link rel="icon" href="<?= base_url('Photos/counselign.ico') ?>" sizes="16x16 32x32" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&family=JetBrains+Mono:wght@500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="<?= base_url('css/shared/account_profile_vibe.css?v=1') ?>">
    <link href="<?= base_url('css/counselor/counselor_profile.css?v=11') ?>" rel="stylesheet" />
    <link rel="stylesheet" href="<?= base_url('css/counselor/header.css') ?>">
    <link rel="stylesheet" href="<?= base_url('css/utils/sidebar.css') ?>">

    <link rel="stylesheet" href="<?= base_url('css/counselor/counselor_vibe_shared.css?v=5') ?>">
    <link rel="stylesheet" href="<?= base_url('css/student/student_notifications_dropdown.css?v=4') ?>">
    <link rel="stylesheet" href="<?= base_url('css/utils/vibe_topbar.css?v=3') ?>">
</head>

<body class="prof-page-body acct-page-body">
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
                    <i class="fas fa-user-cog me-2"></i>
                    Profile
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

        <main class="main-content prof-page">
            <div class="acct-vibe-wrap">
                <section class="acct-hero acct-vibe-panel">
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
                            <span class="acct-role-badge"><i class="fas fa-user-md me-1"></i> Guidance Counselor</span>
                            <h2 class="acct-display-name" id="counselor-display-name">Loading...</h2>
                            <p class="acct-hero-sub">Manage your account, professional details, and availability</p>
                            <div class="acct-id-chip">
                                <span class="acct-id-label">Account ID</span>
                                <span class="acct-id-value" id="display-userid">—</span>
                            </div>
                        </div>
                    </div>
                    <div class="acct-quick-stats">
                        <div class="acct-stat-pill">
                            <i class="fas fa-envelope"></i>
                            <div>
                                <span class="acct-stat-label">Email</span>
                                <span class="acct-stat-value" id="counselor-email-preview">Loading...</span>
                            </div>
                        </div>
                        <div class="acct-stat-pill">
                            <i class="fas fa-user"></i>
                            <div>
                                <span class="acct-stat-label">Username</span>
                                <span class="acct-stat-value" id="counselor-username-preview">Loading...</span>
                            </div>
                        </div>
                    </div>
                </section>

                <div class="acct-panels">
                    <section class="acct-panel acct-vibe-panel">
                        <header class="acct-panel-head">
                            <div class="acct-panel-icon"><i class="fas fa-id-card"></i></div>
                            <div>
                                <h3>Personal information</h3>
                                <p>How you appear across the counselor portal</p>
                            </div>
                        </header>
                        <div class="acct-fields">
                            <div class="acct-field" data-field="email">
                                <div class="acct-field-icon"><i class="fas fa-envelope"></i></div>
                                <div class="acct-field-body">
                                    <span class="acct-field-label">Email address</span>
                                    <span class="acct-field-value" id="acct-email-value">Loading...</span>
                                </div>
                                <button type="button" class="acct-field-action" onclick="editField('email')" title="Edit email">
                                    <i class="fas fa-pen"></i>
                                </button>
                            </div>
                            <div class="acct-field" data-field="username">
                                <div class="acct-field-icon"><i class="fas fa-user"></i></div>
                                <div class="acct-field-body">
                                    <span class="acct-field-label">Username</span>
                                    <span class="acct-field-value" id="acct-username-value">Loading...</span>
                                </div>
                                <button type="button" class="acct-field-action" onclick="editField('username')" title="Edit username">
                                    <i class="fas fa-pen"></i>
                                </button>
                            </div>
                        </div>
                        <input type="hidden" id="display-username" value="">
                        <input type="hidden" id="display-email" value="">
                    </section>

                    <section class="acct-panel acct-vibe-panel">
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

                <section class="acct-panel acct-vibe-panel acct-panels--stacked">
                    <header class="acct-panel-head">
                        <div class="acct-panel-icon"><i class="fas fa-address-card"></i></div>
                        <div>
                            <h3>Professional details</h3>
                            <p>Your counselor information on file</p>
                        </div>
                    </header>
                    <div class="acct-panel-body">
                        <div class="prof-field-grid">
                                        <div class="prof-field prof-field--wide">
                                            <label class="form-label" for="pi-fullname">Full name</label>
                                            <input type="text" class="form-control" id="pi-fullname" readonly>
                                        </div>
                                        <div class="prof-field">
                                            <label class="form-label" for="pi-birthdate">Date of Birth</label>
                                            <input type="date" class="form-control" id="pi-birthdate" readonly>
                                        </div>
                                        <div class="prof-field prof-field--narrow">
                                            <label class="form-label" for="pi-sex">Sex</label>
                                            <input type="text" class="form-control" id="pi-sex" readonly>
                                        </div>
                                        <div class="prof-field prof-field--wide">
                                            <label class="form-label" for="pi-degree">Degree</label>
                                            <input type="text" class="form-control" id="pi-degree" readonly>
                                        </div>
                                        <div class="prof-field">
                                            <label class="form-label" for="pi-civil">Civil Status</label>
                                            <input type="text" class="form-control" id="pi-civil" readonly>
                                        </div>
                                        <div class="prof-field">
                                            <label class="form-label" for="pi-contact">Contact Number</label>
                                            <input type="text" class="form-control" id="pi-contact" readonly>
                                        </div>
                                        <div class="prof-field prof-field--wide">
                                            <label class="form-label" for="pi-email">Email</label>
                                            <input type="text" class="form-control" id="pi-email" readonly>
                                        </div>
                                        <div class="prof-field prof-field--full">
                                            <label class="form-label" for="pi-address">Address</label>
                                            <input type="text" class="form-control" id="pi-address" readonly>
                                        </div>
                                    </div>

                        <div class="acct-panel-actions">
                            <button type="button" class="acct-btn acct-btn-primary" data-bs-toggle="modal" data-bs-target="#updatePersonalInfoModal">
                                <i class="fas fa-edit me-2"></i>Edit personal info
                            </button>
                        </div>
                    </div>
                </section>

                <section class="acct-panel acct-vibe-panel acct-panels--stacked avail-panel">
                    <header class="acct-panel-head">
                        <div class="acct-panel-icon"><i class="fas fa-calendar-check"></i></div>
                        <div>
                            <h3>Availability</h3>
                            <p>Weekly schedule when students can book consultations with you</p>
                        </div>
                    </header>

                    <div class="acct-panel-body avail-shell" id="avail-shell">
                        <div class="avail-stats" id="avail-stats" aria-live="polite">
                            <div class="avail-stat">
                                <span class="avail-stat-num" id="avail-stat-days">0</span>
                                <span class="avail-stat-label">Days open</span>
                            </div>
                            <div class="avail-stat">
                                <span class="avail-stat-num" id="avail-stat-slots">0</span>
                                <span class="avail-stat-label">Time slots</span>
                            </div>
                        </div>

                        <div class="avail-week avail-week--display" id="time-slots-list" aria-live="polite"></div>

                        <div class="avail-empty" id="avail-empty-msg" hidden>
                            <div class="avail-empty-icon"><i class="fas fa-calendar-plus"></i></div>
                            <p>No availability set yet</p>
                            <span>Click Set availability to add your weekly hours.</span>
                        </div>

                        <div class="avail-toolbar">
                            <button type="button" class="avail-btn avail-btn-primary" id="edit-availability">
                                <i class="fas fa-calendar-plus"></i>
                                <span>Set availability</span>
                            </button>
                        </div>
                    </div>
                </section>
            </div>
        </main>
    </div>

    <!-- Set Availability Modal -->
    <div class="modal fade appt-vibe-modal" id="availabilityModal" tabindex="-1" aria-labelledby="availabilityModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
            <div class="modal-content appointment-modal-content">
                <div class="modal-header appointment-modal-header">
                    <h5 class="modal-title" id="availabilityModalLabel">
                        <i class="fas fa-calendar-check me-2"></i>Set availability
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body avail-modal-body">
                    <div class="avail-composer-block">
                        <label class="avail-composer-label">Select days</label>
                        <div class="avail-day-toggles" id="availability-days" role="group" aria-label="Available days">
                            <label class="avail-day-toggle" for="day-Monday">
                                <input type="checkbox" value="Monday" id="day-Monday">
                                <span>Mon</span>
                            </label>
                            <label class="avail-day-toggle" for="day-Tuesday">
                                <input type="checkbox" value="Tuesday" id="day-Tuesday">
                                <span>Tue</span>
                            </label>
                            <label class="avail-day-toggle" for="day-Wednesday">
                                <input type="checkbox" value="Wednesday" id="day-Wednesday">
                                <span>Wed</span>
                            </label>
                            <label class="avail-day-toggle" for="day-Thursday">
                                <input type="checkbox" value="Thursday" id="day-Thursday">
                                <span>Thu</span>
                            </label>
                            <label class="avail-day-toggle" for="day-Friday">
                                <input type="checkbox" value="Friday" id="day-Friday">
                                <span>Fri</span>
                            </label>
                        </div>
                    </div>
                    <div class="avail-composer-block">
                        <label class="avail-composer-label">Time range for selected days</label>
                        <div class="avail-time-builder" id="availability-times">
                            <div class="avail-time-field">
                                <label for="time-from">From</label>
                                <select class="avail-time-select" id="time-from"></select>
                            </div>
                            <span class="avail-time-sep" aria-hidden="true"><i class="fas fa-arrow-right"></i></span>
                            <div class="avail-time-field">
                                <label for="time-to">To</label>
                                <select class="avail-time-select" id="time-to"></select>
                            </div>
                            <button type="button" class="avail-add-btn" id="add-time-slot">
                                <i class="fas fa-plus"></i>
                                <span>Add to schedule</span>
                            </button>
                        </div>
                    </div>
                    <p class="avail-modal-preview-title">Preview</p>
                    <div class="avail-week avail-week--modal" id="avail-modal-week" aria-live="polite"></div>
                </div>
                <div class="modal-footer appointment-modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="cancel-availability">Cancel</button>
                    <button type="button" class="btn btn-primary" id="save-availability">
                        <i class="fas fa-save me-2"></i>Save availability
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Update Personal Info Modal -->
    <div class="modal fade appt-vibe-modal" id="updatePersonalInfoModal" tabindex="-1" aria-labelledby="updatePersonalInfoLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content appointment-modal-content">
                <div class="modal-header appointment-modal-header">
                    <h5 class="modal-title" id="updatePersonalInfoLabel">Update Personal Information</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="updatePersonalInfoForm">
                        <div class="row g-2 mb-3">
                            <div class="col-12 col-md-5">
                                <label for="upi-firstname" class="form-label">First Name</label>
                                <input type="text" class="form-control" id="upi-firstname" placeholder="First name">
                            </div>
                            <div class="col-12 col-md-5">
                                <label for="upi-lastname" class="form-label">Last Name</label>
                                <input type="text" class="form-control" id="upi-lastname" placeholder="Last name">
                            </div>
                            <div class="col-12 col-md-2">
                                <label for="upi-mi" class="form-label">M.I. <small class="text-muted">(opt.)</small></label>
                                <input type="text" class="form-control" id="upi-mi" placeholder="M.I." maxlength="3">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="upi-birthdate" class="form-label">Birthdate</label>
                            <input type="date" class="form-control" id="upi-birthdate">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Address</label>
                            <select id="upi-region" class="form-select mb-1">
                                <option value="">Select Region</option>
                            </select>
                            <select id="upi-province" class="form-select mb-1" disabled>
                                <option value="">Select Province</option>
                            </select>
                            <select id="upi-city" class="form-select mb-1" disabled>
                                <option value="">Select City/Municipality</option>
                            </select>
                            <select id="upi-barangay" class="form-select mb-1" disabled>
                                <option value="">Select Barangay</option>
                            </select>
                            <input type="text" id="upi-street" class="form-control" placeholder="Street name, building, floor/unit no. (optional)">
                            <input type="hidden" id="upi-address">
                        </div>
                        <div class="mb-3">
                            <label for="upi-degree" class="form-label">Degree</label>
                            <input type="text" class="form-control" id="upi-degree">
                        </div>
                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <label for="upi-sex" class="form-label">Sex</label>
                                <select class="form-select" id="upi-sex">
                                    <option value="">Select sex</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="upi-civil" class="form-label">Civil Status</label>
                                <select class="form-select" id="upi-civil">
                                    <option value="">Select status</option>
                                    <option value="Single">Single</option>
                                    <option value="Married">Married</option>
                                    <option value="Widowed">Widowed</option>
                                    <option value="Separated">Separated</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="upi-email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="upi-email" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="upi-contact" class="form-label">Contact Number</label>
                            <input type="text" class="form-control" id="upi-contact">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="savePersonalInfoChanges()">Save</button>
                </div>
            </div>
        </div>
    </div>

    <?php echo view('modals/student_dashboard_modals'); ?>
    <?= view('counselor/partials/notifications_dropdown') ?>
    <script>
        window.BASE_URL = "<?= base_url() ?>";
    </script>
    <script src="<?= base_url('js/modals/student_dashboard_modals.js') ?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= base_url('js/counselor/counselor_notifications_dropdown.js?v=3') ?>"></script>
    <script src="<?= base_url('js/utils/psgc_address.js') ?>"></script>
    <script src="<?= base_url('js/utils/timeFormatter.js') ?>"></script>
    <script src="<?= base_url('js/counselor/logout.js') ?>"></script>
    <script src="<?= base_url('js/utils/sidebar.js') ?>"></script>
    <script src="<?= base_url('js/counselor/counselor_profile.js?v=9') ?>"></script>
    <script src="<?= base_url('js/shared/account_profile_actions.js?v=1') ?>"></script>
    <script src="<?= base_url('js/counselor/counselor_drawer.js') ?>"></script>
    <script src="<?= base_url('js/utils/secureLogger.js') ?>"></script>
</body>

</html>