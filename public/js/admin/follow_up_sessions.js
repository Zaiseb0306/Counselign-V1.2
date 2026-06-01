// Global variables for follow-up functionality
let currentParentAppointmentId = null;
let currentStudentId = null;

function navigateToHome() {
    window.location.href = (window.BASE_URL || '/') + 'admin/dashboard';
}

function navigateToAbout() {
    // Add functionality for About page navigation
    alert("About page functionality not implemented yet.");
}

function navigateToServices() {
    window.location.href = (window.BASE_URL || '/') + 'admin/services';
}

function navigateToContact() {
    // Add functionality for Contact page navigation
    alert("Contact page functionality not implemented yet.");
}

function cancelAppointment() {
    // Add functionality to cancel the appointment
    alert("Appointment cancelled.");
}

function scheduleAppointment() {
    // Add functionality to schedule the appointment
    alert("Appointment scheduled.");
    scrollToTop();
}

document.addEventListener('DOMContentLoaded', function () {
    setupModalEventListeners();
    initializeSearch();
    loadCompletedAppointments();
});

function setFuStat(id, value) {
    const el = document.getElementById(id);
    if (el) el.textContent = value;
}

function updateFollowUpStats(appointments) {
    const list = appointments || [];
    setFuStat('statCompletedCount', list.length);
    setFuStat(
        'statWithFollowUpCount',
        list.filter((a) => (parseInt(a.follow_up_count, 10) || 0) > 0).length
    );
    setFuStat(
        'statPendingCount',
        list.filter((a) => (parseInt(a.pending_follow_up_count, 10) || 0) > 0).length
    );
    setFuStat(
        'statTotalFollowUpCount',
        list.reduce((sum, a) => sum + (parseInt(a.follow_up_count, 10) || 0), 0)
    );
}

function showFuLoading(show) {
    const loader = document.getElementById('fuLoading');
    const container = document.getElementById('completedAppointmentsContainer');
    if (loader) loader.style.display = show ? 'block' : 'none';
    if (show && container) container.style.display = 'none';
}

// Load all completed appointments (admin view - no counselor filtering)
async function loadCompletedAppointments(searchTerm = '') {
    showFuLoading(true);
    try {
        let url = (window.BASE_URL || '/') + 'admin/follow-up-sessions/completed-appointments';
        if (searchTerm) {
            url += '?search=' + encodeURIComponent(searchTerm);
        }

        const response = await fetch(url, {
            method: 'GET',
            credentials: 'include',
            headers: {
                'Accept': 'application/json',
                'Cache-Control': 'no-cache'
            }
        });
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const data = await response.json();
        
        if (data.status === 'success') {
            showFuLoading(false);
            displayCompletedAppointments(data.appointments, data.search_term);
        } else {
            showFuLoading(false);
            showError(data.message || 'Failed to load completed appointments');
        }
    } catch (error) {
        showFuLoading(false);
        console.error('Error loading completed appointments:', error);
        showError('Error loading completed appointments: ' + error.message);
    }
}

// Display completed appointments in card format
function displayCompletedAppointments(appointments, searchTerm = '') {
    const container = document.getElementById('completedAppointmentsContainer');
    const noDataMessage = document.getElementById('noCompletedAppointments');
    const noSearchResults = document.getElementById('noSearchResults');

    if (!container) return;

    updateFollowUpStats(appointments);

    if (appointments.length === 0) {
        container.style.display = 'none';
        if (searchTerm) {
            noDataMessage.style.display = 'none';
            noSearchResults.style.display = 'block';
        } else {
            noDataMessage.style.display = 'block';
            noSearchResults.style.display = 'none';
        }
        return;
    }

    container.style.display = 'grid';
    noDataMessage.style.display = 'none';
    noSearchResults.style.display = 'none';

    container.innerHTML = appointments.map(appointment => {
        const pending = parseInt(appointment.pending_follow_up_count, 10) || 0;
        const studentName = appointment.student_name || 'Unknown Student';
        const counselorName = appointment.counselor_name || 'Not assigned';
        const consultType = appointment.consultation_type || appointment.method_type || '—';
        return `
        <div class="appointment-card fu-appointment-card status-completed">
            <div class="appointment-header">
                <div class="appointment-status status-completed">${appointment.status}</div>
                <div class="header-indicators">
                    <div class="follow-up-count">
                        <i class="fas fa-calendar-plus"></i>
                        Follow-ups: ${appointment.follow_up_count || 0}
                    </div>
                    ${pending > 0 ? `
                    <div class="pending-follow-up-indicator">
                        <i class="fas fa-exclamation-triangle"></i>
                        Pending
                    </div>
                    ` : ''}
                </div>
            </div>
            <div class="appointment-student">
                <div class="student-name">${studentName}</div>
                <div class="student-id">Student ID: ${appointment.student_id}</div>
                <div class="student-id">Counselor: ${counselorName}</div>
            </div>
            <div class="appointment-details">
                <div class="appointment-date">
                    <i class="fas fa-calendar"></i>
                    <span>${formatDate(appointment.preferred_date)}</span>
                </div>
                <div class="appointment-time">
                    <i class="fas fa-clock"></i>
                    <span>${appointment.preferred_time}</span>
                </div>
                <div class="appointment-type">
                    <i class="fas fa-comments"></i>
                    <span>${consultType}</span>
                </div>
                ${appointment.purpose ? `
                <div class="appointment-purpose">
                    <i class="fas fa-bullseye"></i>
                    <span>${appointment.purpose}</span>
                </div>
                ` : ''}
            </div>
            <button type="button" class="follow-up-btn" onclick="openFollowUpSessionsModal(${appointment.id}, '${appointment.student_id}')">
                <i class="fas fa-calendar-days"></i>
                Follow-up Sessions
            </button>
        </div>
    `;
    }).join('');
}

// Open follow-up sessions modal
async function openFollowUpSessionsModal(parentAppointmentId, studentId) {
    currentParentAppointmentId = parentAppointmentId;
    currentStudentId = studentId;

    try {
        // Load existing follow-up sessions
        const response = await fetch((window.BASE_URL || '/') + `admin/follow-up-sessions/sessions?parent_appointment_id=${parentAppointmentId}`, {
            method: 'GET',
            credentials: 'include',
            headers: {
                'Accept': 'application/json',
                'Cache-Control': 'no-cache'
            }
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const data = await response.json();
        
        if (data.status === 'success') {
            displayFollowUpSessions(data.follow_up_sessions);
            // Update modal header with student full name if available from the completed card context
            try {
                const label = document.getElementById('followUpSessionsModalLabel');
                if (label) {
                    // Find the card for this parent appointment to read the displayed student_name text
                    const card = document.querySelector(`.appointment-card button.follow-up-btn[onclick*="(${parentAppointmentId},"]`)?.closest('.appointment-card');
                    const nameEl = card?.querySelector('.student-name');
                    const fullName = nameEl?.textContent?.trim();
                    if (fullName) {
                        label.innerHTML = `<i class="fas fa-calendar-alt me-2"></i> Follow-up Sessions - ${fullName}`;
                    }
                }
            } catch (_) {}
            // Show the modal
            const modal = new bootstrap.Modal(document.getElementById('followUpSessionsModal'));
            modal.show();
        } else {
            showError(data.message || 'Failed to load follow-up sessions');
        }
    } catch (error) {
        console.error('Error loading follow-up sessions:', error);
        showError('Error loading follow-up sessions: ' + error.message);
    }
}

// Display follow-up sessions in the modal (read-only view for admin)
function displayFollowUpSessions(sessions) {
    const container = document.getElementById('followUpSessionsContainer');
    const noDataMessage = document.getElementById('noFollowUpSessions');

    if (!container) return;

    if (sessions.length === 0) {
        container.style.display = 'none';
        noDataMessage.style.display = 'block';
        return;
    }

    // Ensure grid layout for desktop (CSS sets columns)
    container.style.display = 'grid';
    noDataMessage.style.display = 'none';

    container.innerHTML = sessions.map(session => `
        <div class="follow-up-session-card">
            <div class="session-header">
                <div class="session-sequence">Follow-up #${session.follow_up_sequence}</div>
                <div class="session-status ${session.status}">${session.status}</div>
            </div>
            <div class="session-details">
                <div class="session-date">
                    <i class="fas fa-calendar"></i>
                    <span>${formatDate(session.preferred_date)}</span>
                </div>
                <div class="session-time">
                    <i class="fas fa-clock"></i>
                    <span>${session.preferred_time}</span>
                </div>
                <div class="session-type">
                    <i class="fas fa-comments"></i>
                    <span>${session.consultation_type}</span>
                </div>
                ${session.description ? `<div class="session-description"><strong>Description:</strong> ${session.description}</div>` : ''}
                ${session.reason ? `<div class="session-reason"><strong>${session.status === 'cancelled' ? 'Reason For Cancellation:' : 'Reason For Follow-up:'}</strong> ${session.reason}</div>` : ''}
            </div>
            <!-- No action buttons for admin view - read-only -->
        </div>
    `).join('');
}

// Utility functions
function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
}

function showError(message) {
    const errorModal = document.getElementById('errorModal');
    const errorBody = document.getElementById('errorModalBody');
    
    if (errorModal && errorBody) {
        errorBody.textContent = message;
        const modal = new bootstrap.Modal(errorModal);
        modal.show();
    } else {
        // Fallback to alert if modal not found
        alert('Error: ' + message);
    }
}

function showSuccess(message) {
    const successModal = document.getElementById('successModal');
    const successBody = document.getElementById('successModalBody');
    
    if (successModal && successBody) {
        successBody.textContent = message;
        const modal = new bootstrap.Modal(successModal);
        modal.show();
    } else {
        // Fallback to alert if modal not found
        alert('Success: ' + message);
    }
}

// Modal stacking / cleanup (fixed top bar — no sticky header scroll handler)
function setupModalEventListeners() {
    document.addEventListener('hidden.bs.modal', function () {
        const openModals = document.querySelectorAll('.modal.show');
        if (openModals.length === 0) {
            document.body.classList.remove('modal-open');
            document.body.style.removeProperty('padding-right');
            // Remove stray backdrops if any remained
            document.querySelectorAll('.modal-backdrop').forEach(b => b.remove());
        }
    });

    // Ensure only one backdrop exists when multiple modals are shown
    document.addEventListener('shown.bs.modal', function () {
        const backdrops = document.querySelectorAll('.modal-backdrop');
        if (backdrops.length > 1) {
            // Keep the last one (top-most modal), remove extra
            for (let i = 0; i < backdrops.length - 1; i++) {
                backdrops[i].remove();
            }
        }
    });
}

// Helper function to scroll to top
function scrollToTop() {
    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });
}

// Search functionality
let searchTimeout;

// Initialize search functionality
function initializeSearch() {
    const searchInput = document.getElementById('searchInput');
    const clearSearchBtn = document.getElementById('clearSearchBtn');

    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.trim();
            
            // Show/hide clear button
            if (searchTerm) {
                clearSearchBtn.style.display = 'block';
            } else {
                clearSearchBtn.style.display = 'none';
            }

            // Debounce search to avoid too many requests
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                loadCompletedAppointments(searchTerm);
            }, 300);
        });

        // Clear search functionality
        if (clearSearchBtn) {
            clearSearchBtn.addEventListener('click', function() {
                searchInput.value = '';
                clearSearchBtn.style.display = 'none';
                loadCompletedAppointments('');
            });
        }
    }
}

