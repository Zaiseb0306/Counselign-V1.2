<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title>Student Feedback with Sentiment Analysis - Counselign</title>
    <link rel="icon" href="<?= base_url('Photos/counselign.ico') ?>" sizes="16x16 32x32" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('css/counselor/view_feedback.css?v=2') ?>">
    <?= view('admin/partials/vibe_styles') ?>
</head>

<body class="adm-vf-page-body">
    <!-- Sidebar -->
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
                <a href="<?= base_url('admin/feedback-analytics/view-feedback') ?>" class="sidebar-link active" title="View Feedback">
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
                    <i class="fas fa-comments me-2"></i>
                    Student Feedback with Sentiment Analysis
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

        <main class="appt-page vf-page">
            <div class="container-fluid px-4 vf-container">

                <section class="stats-summary vf-stats" aria-label="Sentiment statistics">
                    <article class="stat-card stat-positive">
                        <div class="stat-icon"><i class="fas fa-smile text-success"></i></div>
                        <div class="stat-details">
                            <h3><?= $sentiment_stats['positive'] ?></h3>
                            <p>Positive</p>
                            <span class="stat-pct"><?= $sentiment_stats['positive_percentage'] ?>%</span>
                        </div>
                    </article>
                    <article class="stat-card stat-negative">
                        <div class="stat-icon"><i class="fas fa-frown text-danger"></i></div>
                        <div class="stat-details">
                            <h3><?= $sentiment_stats['negative'] ?></h3>
                            <p>Negative</p>
                            <span class="stat-pct"><?= $sentiment_stats['negative_percentage'] ?>%</span>
                        </div>
                    </article>
                    <article class="stat-card stat-neutral">
                        <div class="stat-icon"><i class="fas fa-meh text-secondary"></i></div>
                        <div class="stat-details">
                            <h3><?= $sentiment_stats['neutral'] ?></h3>
                            <p>Neutral</p>
                            <span class="stat-pct"><?= $sentiment_stats['neutral_percentage'] ?>%</span>
                        </div>
                    </article>
                    <article class="stat-card stat-avg">
                        <div class="stat-icon"><i class="fas fa-chart-line text-primary"></i></div>
                        <div class="stat-details">
                            <h3><?= $sentiment_stats['average_score'] ?></h3>
                            <p>Avg Score</p>
                            <span class="stat-pct">-100 to 100</span>
                        </div>
                    </article>
                </section>

                <?php if (!empty($negative_feedback)): ?>
                    <div class="vf-alert-attention" role="alert">
                        <h5><i class="fas fa-exclamation-triangle me-2"></i>Negative Feedback Requiring Attention</h5>
                        <p>There are <?= count($negative_feedback) ?> feedback entries with negative sentiment that may need review.</p>
                    </div>
                <?php endif; ?>

                <section class="vf-filter-panel">
                    <header class="vf-filter-header">
                        <h5><i class="fas fa-filter me-2"></i>Filter Feedback</h5>
                    </header>
                    <div class="vf-filter-body">
                        <form id="filterForm" class="row g-3 align-items-end">
                            <div class="col-md-3">
                                <label class="form-label" for="counselorFilter">Counselor</label>
                                <select class="form-select" id="counselorFilter" name="counselor_id">
                                    <option value="">All Counselors</option>
                                    <?php foreach ($counselors as $counselor): ?>
                                        <option value="<?= $counselor['counselor_id'] ?>"
                                            <?= isset($filters['counselor_id']) && $filters['counselor_id'] == $counselor['counselor_id'] ? 'selected' : '' ?>>
                                            <?= esc($counselor['name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label" for="sentimentFilter">Sentiment</label>
                                <select class="form-select" id="sentimentFilter" name="sentiment_label">
                                    <option value="">All Sentiments</option>
                                    <option value="positive" <?= isset($filters['sentiment_label']) && $filters['sentiment_label'] == 'positive' ? 'selected' : '' ?>>Positive</option>
                                    <option value="negative" <?= isset($filters['sentiment_label']) && $filters['sentiment_label'] == 'negative' ? 'selected' : '' ?>>Negative</option>
                                    <option value="neutral" <?= isset($filters['sentiment_label']) && $filters['sentiment_label'] == 'neutral' ? 'selected' : '' ?>>Neutral</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label" for="startDate">Start Date</label>
                                <input type="date" class="form-control" id="startDate" name="start_date"
                                    value="<?= $filters['start_date'] ?? '' ?>">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label" for="endDate">End Date</label>
                                <input type="date" class="form-control" id="endDate" name="end_date"
                                    value="<?= $filters['end_date'] ?? '' ?>">
                            </div>
                            <div class="col-md-2">
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn vf-btn-apply flex-grow-1">
                                        <i class="fas fa-filter me-1"></i>Apply
                                    </button>
                                    <button type="button" class="btn vf-btn-clear" id="clearFilters" title="Clear filters">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </section>

                <section class="vf-feedback-list" aria-label="Feedback entries">
                    <?php if (!empty($feedbacks)): ?>
                        <?php foreach ($feedbacks as $feedback): ?>
                            <article class="feedback-card sentiment-<?= htmlspecialchars($feedback['sentiment_label']) ?>">
                                <div class="row">
                                    <div class="col-md-8">
                                        <h5 class="vf-student-name">
                                            <i class="fas fa-user-graduate me-2"></i><?= htmlspecialchars($feedback['student_name'] ?? 'Unknown') ?>
                                        </h5>
                                        <p class="vf-meta mb-1">
                                            <i class="fas fa-user-md"></i><?= htmlspecialchars($feedback['counselor_name'] ?? 'Not assigned') ?>
                                        </p>
                                        <p class="vf-meta mb-1">
                                            <i class="fas fa-calendar"></i><?= date('F j, Y', strtotime($feedback['preferred_date'])) ?>
                                            <span class="mx-2">·</span>
                                            <i class="fas fa-clock"></i><?= htmlspecialchars($feedback['preferred_time']) ?>
                                        </p>
                                        <p class="vf-meta mb-0">
                                            <i class="fas fa-comments"></i><?= htmlspecialchars($feedback['consultation_type']) ?>
                                        </p>
                                    </div>
                                    <div class="col-md-4 text-end">
                                        <span class="sentiment-badge sentiment-<?= htmlspecialchars($feedback['sentiment_label']) ?>">
                                            <i class="fas fa-<?= $feedback['sentiment_label'] == 'positive' ? 'smile' : ($feedback['sentiment_label'] == 'negative' ? 'frown' : 'meh') ?> me-1"></i>
                                            <?= ucfirst(htmlspecialchars($feedback['sentiment_label'])) ?>
                                        </span>
                                        <div class="sentiment-score score-<?= htmlspecialchars($feedback['sentiment_label']) ?>">
                                            Score: <?= htmlspecialchars($feedback['sentiment_score']) ?>
                                        </div>
                                        <span class="vf-submitted d-block mt-2">
                                            Submitted <?= date('M j, Y g:i A', strtotime($feedback['submitted_at'])) ?>
                                        </span>
                                    </div>
                                </div>

                                <?php if (!empty($feedback['additional_comments'])): ?>
                                    <div class="feedback-comment">
                                        <i class="fas fa-quote-left me-2"></i>
                                        <?= htmlspecialchars($feedback['additional_comments']) ?>
                                    </div>
                                <?php else: ?>
                                    <p class="text-muted mt-3 mb-0"><em>No additional comments provided</em></p>
                                <?php endif; ?>

                                <div class="vf-rating-row">
                                    <strong>Overall Rating:</strong>
                                    <?php
                                    $fqService = new \App\Services\FeedbackQuestionsService();
                                    $avg = $fqService->calculateAverageRating($feedback, $activeQuestions ?? []);
                                    echo ' ' . number_format($avg, 2) . ' / 5.00';
                                    ?>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="vf-empty">
                            <div class="vf-empty-icon"><i class="fas fa-comments"></i></div>
                            <h4>No feedback found</h4>
                            <p>No feedback matches the selected filters. Try adjusting your search.</p>
                        </div>
                    <?php endif; ?>
                </section>

            </div>
        </main>
    </div>

    <script>
        window.BASE_URL = "<?= base_url() ?>";
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= base_url('js/utils/secureLogger.js') ?>"></script>
    <script src="<?= base_url('js/admin/profile_sync.js') ?>"></script>
    <script src="<?= base_url('js/admin/logout.js') ?>" defer></script>
    <script src="<?= base_url('js/utils/sidebar.js') ?>"></script>
    <script>
        document.getElementById('filterForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const params = new URLSearchParams(formData).toString();
            window.location.href = '<?= base_url('admin/feedback-analytics/view-feedback') ?>?' + params;
        });

        document.getElementById('clearFilters').addEventListener('click', function() {
            window.location.href = '<?= base_url('admin/feedback-analytics/view-feedback') ?>';
        });
    </script>
</body>

</html>
