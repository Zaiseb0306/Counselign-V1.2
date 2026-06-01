<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description"
        content="University Guidance Counseling Services - Your safe space for support and guidance" />
    <meta name="keywords" content="counseling, guidance, university, support, mental health, student wellness" />
    <title>Our Services - Counselign</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&family=JetBrains+Mono:wght@500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" href="<?= base_url('Photos/counselign.ico') ?>" sizes="16x16 32x32" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="<?= base_url('css/services.css') ?>">
    <link rel="stylesheet" href="<?= base_url('css/landing_vibe.css?v=3') ?>">
</head>

<body class="landing-vibe-page services-page">
    <header class="landing-header text-white">
        <div class="container-fluid px-4">
            <div class="lv-header-bar">
                <a href="<?= base_url() ?>" class="lv-header-brand text-white text-decoration-none">
                    <img src="<?= base_url('Photos/counselign_logo.png') ?>" alt="" class="logo" width="40" height="40" />
                    <span class="lv-brand-name">Counselign</span>
                </a>
            </div>
        </div>
    </header>

    <main class="lv-main">
        <div class="lv-container">

            <section class="appt-hero appt-hero--left" aria-labelledby="sv-hero-title">
                <h2 class="page-title" id="sv-hero-title">Our Services</h2>
                <p class="appt-hero-sub appt-hero-sub--left">
                    The University Guidance Counseling Center offers comprehensive support for your academic success,
                    personal growth, and career development. Our professional counselors are here to help you navigate
                    your university journey.
                </p>
            </section>

            <section class="rpt-panel" aria-labelledby="sv-counseling-title">
                <h3 class="rpt-section-title" id="sv-counseling-title">Counseling Services</h3>
                <div class="service-grid">
                    <article class="service-card">
                        <div class="service-icon-wrap">
                            <i class="fas fa-user-graduate service-icon" aria-hidden="true"></i>
                        </div>
                        <h4 class="service-title">Academic Counseling</h4>
                        <p class="service-desc">Expert guidance for your academic journey and success strategies.</p>
                        <ul class="service-list">
                            <li><i class="fas fa-check" aria-hidden="true"></i> Study skills development</li>
                            <li><i class="fas fa-check" aria-hidden="true"></i> Time management coaching</li>
                            <li><i class="fas fa-check" aria-hidden="true"></i> Test anxiety management</li>
                            <li><i class="fas fa-check" aria-hidden="true"></i> Academic goal setting</li>
                        </ul>
                    </article>

                    <article class="service-card">
                        <div class="service-icon-wrap">
                            <i class="fas fa-heart service-icon" aria-hidden="true"></i>
                        </div>
                        <h4 class="service-title">Personal Counseling</h4>
                        <p class="service-desc">Confidential support for personal challenges and growth.</p>
                        <ul class="service-list">
                            <li><i class="fas fa-check" aria-hidden="true"></i> Stress management</li>
                            <li><i class="fas fa-check" aria-hidden="true"></i> Anxiety &amp; depression support</li>
                            <li><i class="fas fa-check" aria-hidden="true"></i> Relationship counseling</li>
                            <li><i class="fas fa-check" aria-hidden="true"></i> Self-esteem building</li>
                        </ul>
                    </article>

                    <article class="service-card">
                        <div class="service-icon-wrap">
                            <i class="fas fa-briefcase service-icon" aria-hidden="true"></i>
                        </div>
                        <h4 class="service-title">Career Counseling</h4>
                        <p class="service-desc">Professional guidance for your career development journey.</p>
                        <ul class="service-list">
                            <li><i class="fas fa-check" aria-hidden="true"></i> Career assessment</li>
                            <li><i class="fas fa-check" aria-hidden="true"></i> Resume writing support</li>
                            <li><i class="fas fa-check" aria-hidden="true"></i> Interview preparation</li>
                            <li><i class="fas fa-check" aria-hidden="true"></i> Professional networking</li>
                        </ul>
                    </article>
                </div>
            </section>

            <section class="rpt-panel" aria-labelledby="sv-support-title">
                <h3 class="rpt-section-title" id="sv-support-title">Additional Support Programs</h3>
                <div class="support-grid">
                    <article class="support-card">
                        <div class="support-icon-wrap"><i class="fas fa-users support-icon" aria-hidden="true"></i></div>
                        <h5 class="support-card-title">Group Workshops</h5>
                        <p>Interactive sessions focusing on personal development and skill-building.</p>
                    </article>
                    <article class="support-card">
                        <div class="support-icon-wrap"><i class="fas fa-graduation-cap support-icon" aria-hidden="true"></i></div>
                        <h5 class="support-card-title">Peer Mentoring</h5>
                        <p>Connect with experienced student mentors for guidance and support.</p>
                    </article>
                    <article class="support-card">
                        <div class="support-icon-wrap"><i class="fas fa-laptop support-icon" aria-hidden="true"></i></div>
                        <h5 class="support-card-title">Online Resources</h5>
                        <p>Access our digital library of self-help materials and tools.</p>
                    </article>
                    <article class="support-card">
                        <div class="support-icon-wrap"><i class="fas fa-medkit support-icon" aria-hidden="true"></i></div>
                        <h5 class="support-card-title">Crisis Support</h5>
                        <p>24/7 emergency support for urgent mental health concerns.</p>
                    </article>
                </div>
            </section>

            <section class="lv-cta" aria-labelledby="sv-cta-title">
                <h4 id="sv-cta-title">Ready to Get Started?</h4>
                <p>Our services are confidential and available to all university students.</p>
                <div class="lv-cta-actions">
                    <a href="<?= base_url('?open=login') ?>" class="btn btn-light">Get Started</a>
                    <a href="<?= base_url() ?>" class="btn btn-outline-light">Back to home</a>
                </div>
            </section>

        </div>
    </main>

    <footer>
        <div class="footer-content">
            <div class="copyright">
                <b>© 2025 Counselign Team. All rights reserved.</b>
            </div>
        </div>
    </footer>

    <script src="<?= base_url('js/services.js') ?>"></script>
    <script src="<?= base_url('js/dark_mode.js') ?>"></script>
</body>

</html>
