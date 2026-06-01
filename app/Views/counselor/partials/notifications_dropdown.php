<!-- Notifications Dropdown (shared — same UI as student pages) -->
<div id="notificationsOverlay" class="snd-overlay" aria-hidden="true"></div>
<div id="notificationsDropdown" class="student-notifications-dropdown" aria-label="Notifications" style="display:none;">
    <div class="snd-head">
        <h3 class="snd-title">Notifications</h3>
        <div class="snd-actions">
            <button id="seeHistoryBtn" class="btn btn-sm btn-outline-secondary" type="button" title="See History">
                <i class="fas fa-history"></i> <span class="d-none d-sm-inline">History</span>
            </button>
            <button id="markAllReadBtn" class="btn btn-sm btn-outline-primary" type="button" title="Mark all as read">
                <i class="fas fa-check-double"></i> <span class="d-none d-sm-inline">Clear All</span>
            </button>
        </div>
    </div>
    <div class="notifications-list snd-list">
        <!-- populated by JS -->
    </div>
</div>
