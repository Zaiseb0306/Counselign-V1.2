// Debug function to log events
function debug(message) {
    SecureLogger.info(`[DEBUG] ${message}`);
}

// Modal functions for editing fields and changing password
function editField(field) {
    SecureLogger.info(`editField called with field: ${field}`);

    // Check if a modal is already open
    if (document.querySelector('.modal-overlay')) {
        return; // Don't open another modal if one is already open
    }

    // Create modal
    const modal = document.createElement('div');
    modal.className = 'modal-overlay';

    let fieldLabel = field === 'email' ? 'Email' : 'Username';
    let fieldType = field === 'email' ? 'email' : 'text';
    let currentValue = '';

    const valueEl = document.querySelector(`[data-field="${field}"] .acct-field-value`);
    currentValue = valueEl ? valueEl.textContent.trim() : '';

    SecureLogger.info(`Current value for ${field}: ${currentValue}`);

    modal.innerHTML = `
        <div class="modal-container">
            <div class="modal-header">
                <h3>Change ${fieldLabel}</h3>
            </div>
            <div class="modal-body">
                <form id="edit-${field}-form">
                <div class="form-group">
                    <label>Current ${fieldLabel}:</label>
                    <input type="text" disabled value="${currentValue}">
                </div>
                <div class="form-group">
                    <label>New ${fieldLabel}:</label>
                        <input type="${fieldType}" id="new-${field}" value="${currentValue}" required>
                    </div>
                    <div class="modal-buttons">
                        <button type="button" onclick="closeModal()" class="cancel-btn">Cancel</button>
                        <button type="submit" class="submit-btn">Save Changes</button>
                </div>
                </form>
            </div>
        </div>
    `;

    // Append the modal to the body
    document.body.appendChild(modal);

    setTimeout(() => modal.classList.add('active'), 50);

    // Add form submission handler
    const form = modal.querySelector(`#edit-${field}-form`);
    form.onsubmit = function (e) {
        e.preventDefault();
        const newValue = document.getElementById(`new-${field}`).value;

        // Validate email if updating email
        if (field === 'email' && !validateEmail(newValue)) {
            showNotification('Please enter a valid email address', 'error');
            return;
        }

        // Create form data
        const formData = new FormData();
        formData.append('field', field);
        formData.append('value', newValue);

        // Send update request
        fetch((window.BASE_URL || '/') + 'admin/profile/update', {
            method: 'POST',
            body: formData,
            credentials: 'include'
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    updateFieldDisplay(field, newValue);
                    showNotification(data.message, 'success');
                    closeModal();
                } else {
                    showNotification(data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('An error occurred while updating the profile', 'error');
            });
    };
}

function changePassword() {
    // Check if a modal is already open
    if (document.querySelector('.modal-overlay')) {
        return;
    }

    // Create modal
    const modal = document.createElement('div');
    modal.className = 'modal-overlay';

    modal.innerHTML = `
        <div class="modal-container">
            <div class="modal-header">
                <h3>Change Password</h3>
            </div>
            <div class="modal-body">
                <form id="change-password-form">
                <div class="form-group">
                    <label>Current Password:</label>
                        <input type="password" id="current-password" required>
                </div>
                <div class="form-group">
                    <label>New Password:</label>
                        <input type="password" id="new-password" required>
                </div>
                <div class="form-group">
                    <label>Confirm New Password:</label>
                        <input type="password" id="confirm-password" required>
                    </div>
                    <div class="modal-buttons">
                        <button type="button" onclick="closeModal()" class="cancel-btn">Cancel</button>
                        <button type="submit" class="submit-btn">Update Password</button>
                </div>
                </form>
            </div>
        </div>
    `;

    // Append the modal to the body
    document.body.appendChild(modal);

    // Add animation class
    setTimeout(() => modal.classList.add('active'), 50);

    // Add form submission handler
    const form = modal.querySelector('#change-password-form');
    form.onsubmit = function (e) {
        e.preventDefault();
        const currentPassword = document.getElementById('current-password').value;
        const newPassword = document.getElementById('new-password').value;
        const confirmPassword = document.getElementById('confirm-password').value;

        // Validate passwords
        if (newPassword !== confirmPassword) {
            showNotification('New passwords do not match!', 'error');
            return;
        }

        // Create form data
        const formData = new FormData();
        formData.append('current_password', currentPassword);
        formData.append('new_password', newPassword);
        formData.append('confirm_password', confirmPassword);

        // Send update request
        fetch((window.BASE_URL || '/') + 'update-password', {
            method: 'POST',
            body: formData,
            credentials: 'include'
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification(data.message, 'success');
                    closeModal();
                } else {
                    showNotification(data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('An error occurred while updating the password', 'error');
            });
    };
}

function updateProfilePicture() {
    // Check if a modal is already open
    if (document.querySelector('.modal-overlay')) {
        return;
    }

    // Create modal
    const modal = document.createElement('div');
    modal.className = 'modal-overlay';

    modal.innerHTML = `
        <div class="modal-container">
            <div class="modal-header">
                <h3>Update Profile Picture</h3>
            </div>
            <div class="modal-body">
                <form id="update-profile-form" enctype="multipart/form-data">
                    <div class="form-group">
                        <label>Select a new profile picture:</label>
                        <input type="file" id="profile_picture" name="profile_picture" accept="image/*" required>
                        <small>Maximum file size: 5MB. Allowed formats: JPG, JPEG, PNG, GIF</small>
                    </div>
                    <div class="modal-buttons">
                        <button type="button" onclick="closeModal()" class="cancel-btn">Cancel</button>
                        <button type="submit" class="submit-btn">Upload Picture</button>
                    </div>
                </form>
            </div>
        </div>
    `;

    // Append the modal to the body
    document.body.appendChild(modal);

    // Add animation class
    setTimeout(() => modal.classList.add('active'), 50);

    // Add form submission handler
    const form = modal.querySelector('#update-profile-form');
    form.onsubmit = function (e) {
        e.preventDefault();
        const fileInput = document.getElementById('profile_picture');
        const file = fileInput.files[0];

        if (!file) {
            showNotification('Please select a file', 'error');
            return;
        }

        // Validate file size (5MB)
        if (file.size > 5 * 1024 * 1024) {
            showNotification('File is too large. Maximum size is 5MB', 'error');
            return;
        }

        // Validate file type
        const allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (!allowedTypes.includes(file.type)) {
            showNotification('Invalid file type. Only JPG, PNG & GIF files are allowed.', 'error');
            return;
        }

        // Create form data
        const formData = new FormData();
        formData.append('profile_picture', file);

        // Show loading state
        const submitBtn = form.querySelector('.submit-btn');
        submitBtn.disabled = true;
        submitBtn.textContent = 'Uploading...';

        // Send update request to the new endpoint
        fetch((window.BASE_URL || '/') + 'admin/profile/picture', {
            method: 'POST',
            body: formData,
            credentials: 'include'
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update profile picture using the correct selector
                    const profileImg = document.getElementById('profile-avatar');
                    if (profileImg) {
                        const newImageUrl = data.picture_url + '?t=' + new Date().getTime(); // Add timestamp to prevent caching
                        profileImg.src = newImageUrl;
                        // Store the updated profile picture URL in sessionStorage
                        sessionStorage.setItem('adminProfilePicture', newImageUrl);
                        // Broadcast the profile picture update
                        broadcastProfileUpdate(newImageUrl);
                        showNotification(data.message, 'success');
                        closeModal();
                        loadAdminData();
                    } else {
                        throw new Error('Profile image element not found');
                    }
                } else {
                    showNotification(data.message || 'Failed to update profile picture', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('An error occurred while updating the profile picture', 'error');
            })
            .finally(() => {
                // Reset button state
                submitBtn.disabled = false;
                submitBtn.textContent = 'Upload Picture';
            });
    };
}

function updateFieldDisplay(field, value) {
    const valueEl = document.querySelector(`[data-field="${field}"] .acct-field-value`);
    if (valueEl) {
        valueEl.textContent = value;
    }
    if (field === 'email') {
        const preview = document.getElementById('admin-email-preview');
        if (preview) preview.textContent = value;
    }
    if (field === 'username') {
        const display = document.getElementById('admin-username-display');
        if (display) display.textContent = value;
        const topName = document.getElementById('uniNameTop');
        const dropName = document.getElementById('uniNameDropdown');
        if (topName) topName.textContent = value;
        if (dropName) dropName.textContent = value;
    }
}

function closeModal() {
    const modal = document.querySelector('.modal-overlay');
    if (modal) {
        modal.classList.remove('active');
        setTimeout(() => {
            modal.remove();
        }, 300);
    }
}

function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    notification.textContent = message;

    document.body.appendChild(notification);

    // Add active class after a small delay to trigger animation
    setTimeout(() => {
        notification.classList.add('active');
    }, 10);

    // Remove notification after 3 seconds
    setTimeout(() => {
        notification.classList.remove('active');
        setTimeout(() => {
            notification.remove();
        }, 300);
    }, 3000);
}

function validateEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}

// Load admin data function
function loadAdminData() {
    debug('Loading admin data...');

    fetch((window.BASE_URL || '/') + 'admin/dashboard/data', {
        method: 'GET',
        credentials: 'include',
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
        .then(response => {
            if (!response.ok) {
                if (response.status === 403) {
                    window.location.href = (window.BASE_URL || '/') + 'auth/logout';
                    return;
                }
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.text().then(text => {
                try {
                    return JSON.parse(text);
                } catch (e) {
                    console.error('JSON Parse Error:', e);
                    console.error('Raw Response:', text);
                    throw new Error('Invalid JSON response');
                }
            });
        })
        .then(data => {
            if (data.success) {
                const d = data.data;
                const idEl = document.getElementById('admin-id');
                if (idEl) idEl.textContent = d.user_id || '—';

                updateFieldDisplay('email', d.email || '');
                updateFieldDisplay('username', d.username || '');

                const avatar = document.getElementById('profile-avatar');
                if (avatar && d.profile_picture) {
                    avatar.src = d.profile_picture;
                }

                const topImg = document.getElementById('profile-img-top');
                const dropImg = document.getElementById('profile-img-dropdown');
                if (d.profile_picture) {
                    if (topImg) topImg.src = d.profile_picture;
                    if (dropImg) dropImg.src = d.profile_picture;
                }

                const lastLogin = document.getElementById('admin-last-login');
                const lastLoginDrop = document.getElementById('lastLoginDropdown');
                const loginText = d.last_login || d.last_login_formatted || '—';
                if (lastLogin) lastLogin.textContent = loginText;
                if (lastLoginDrop) lastLoginDrop.textContent = loginText !== '—' ? `Last login: ${loginText}` : 'Welcome back';
            } else {
                throw new Error(data.message || 'Failed to load admin data');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Failed to load admin data. Please try again later.', 'error');
        });
}

// Add this new function after the updateProfilePicture function
function broadcastProfileUpdate(newImageUrl) {
    // Store in localStorage to persist across pages
    localStorage.setItem('adminProfilePicture', newImageUrl);
    localStorage.setItem('adminProfileUpdateTime', new Date().getTime());

    // Broadcast the update to other open tabs/windows
    window.dispatchEvent(new StorageEvent('storage', {
        key: 'adminProfilePicture',
        newValue: newImageUrl
    }));
}

// Add profile picture update listener
function initProfilePictureListener() {
    // Listen for profile picture updates from other tabs/windows
    window.addEventListener('storage', (event) => {
        if (event.key === 'adminProfilePicture') {
            updateAllProfilePictures(event.newValue);
        }
    });

    // Check for existing profile picture update
    const storedPicture = localStorage.getItem('adminProfilePicture');
    if (storedPicture) {
        updateAllProfilePictures(storedPicture);
    }
}

function updateAllProfilePictures(newImageUrl) {
    // Update all profile pictures on the current page
    const profilePictures = document.querySelectorAll('.profile-avatar, .admin-avatar, .message-avatar');
    profilePictures.forEach(img => {
        if (img.classList.contains('admin-avatar')) {
            img.src = newImageUrl;
        }
    });
}

// Initialize when the DOM is loaded
document.addEventListener('DOMContentLoaded', function () {
    debug('DOM Content Loaded');

    // Initialize profile picture listener
    initProfilePictureListener();

    // Load admin data
    loadAdminData();

    // Make functions available globally
    window.editField = editField;
    window.changePassword = changePassword;
    window.closeModal = closeModal;
    window.showNotification = showNotification;
    window.validateEmail = validateEmail;
    window.updateProfilePicture = updateProfilePicture;

});