/**
 * Shared account profile actions (admin-style modals) for counselor & student pages.
 * Configure via window.ACCOUNT_PROFILE_CONFIG before this script loads.
 */
(function () {
    'use strict';

    function getConfig() {
        return window.ACCOUNT_PROFILE_CONFIG || {};
    }

    function baseUrl() {
        const b = window.BASE_URL || '/';
        return b.endsWith('/') ? b : b + '/';
    }

    function resolveImageUrl(path) {
        if (!path) return baseUrl() + 'Photos/profile.png';
        const trimmed = String(path).trim();
        if (/^https?:\/\//i.test(trimmed)) return trimmed;
        if (trimmed.startsWith('/')) return trimmed;
        return baseUrl() + trimmed.replace(/^\//, '');
    }

    function notify(message, type) {
        const cfg = getConfig();
        if (typeof cfg.notify === 'function') {
            cfg.notify(message, type);
            return;
        }
        showNotification(message, type);
    }

    function validateEmail(email) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    }

    function closeModal() {
        const modal = document.querySelector('body.acct-page-body .modal-overlay, body.prof-page-body .modal-overlay, body.sp-page-body .modal-overlay');
        if (!modal) return;
        modal.classList.remove('active');
        setTimeout(() => modal.remove(), 300);
    }

    function showNotification(message, type) {
        const notification = document.createElement('div');
        notification.className = 'notification ' + (type || 'success');
        notification.textContent = message;
        document.body.appendChild(notification);
        setTimeout(() => notification.classList.add('active'), 10);
        setTimeout(() => {
            notification.classList.remove('active');
            setTimeout(() => notification.remove(), 300);
        }, 3000);
    }

    function updateFieldDisplay(field, value) {
        const valueEl = document.querySelector('[data-field="' + field + '"] .acct-field-value');
        if (valueEl) valueEl.textContent = value;

        const cfg = getConfig();
        if (field === 'email' && cfg.emailPreviewId) {
            const el = document.getElementById(cfg.emailPreviewId);
            if (el) el.textContent = value;
        }
        if (field === 'username') {
            if (cfg.usernamePreviewId) {
                const el = document.getElementById(cfg.usernamePreviewId);
                if (el) el.textContent = value;
            }
            if (cfg.displayNameId) {
                const el = document.getElementById(cfg.displayNameId);
                if (el && (!el.textContent || el.textContent === 'Loading...')) {
                    el.textContent = value;
                }
            }
            ['uniNameTop', 'uniNameDropdown'].forEach(function (id) {
                const el = document.getElementById(id);
                if (el) el.textContent = value;
            });
        }

        if (typeof cfg.onFieldUpdated === 'function') {
            cfg.onFieldUpdated(field, value);
        }
    }

    function updateAvatarImages(url) {
        const bust = url + (url.indexOf('?') >= 0 ? '&' : '?') + 't=' + Date.now();
        const avatar = document.getElementById('profile-avatar');
        if (avatar) avatar.src = bust;
        ['profile-img-top', 'profile-img-dropdown'].forEach(function (id) {
            const el = document.getElementById(id);
            if (el) el.src = bust;
        });
        const cfg = getConfig();
        if (cfg.storageKey) {
            try {
                localStorage.setItem(cfg.storageKey, bust);
            } catch (e) { /* ignore */ }
        }
    }

    function editField(field) {
        if (document.querySelector('.modal-overlay')) return;

        const fieldLabel = field === 'email' ? 'Email' : 'Username';
        const fieldType = field === 'email' ? 'email' : 'text';
        const valueEl = document.querySelector('[data-field="' + field + '"] .acct-field-value');
        const currentValue = valueEl ? valueEl.textContent.trim() : '';

        const modal = document.createElement('div');
        modal.className = 'modal-overlay';
        modal.innerHTML =
            '<div class="modal-container">' +
            '<div class="modal-header"><h3>Change ' + fieldLabel + '</h3></div>' +
            '<div class="modal-body">' +
            '<form id="edit-' + field + '-form">' +
            '<div class="form-group"><label>Current ' + fieldLabel + ':</label>' +
            '<input type="text" disabled value="' + currentValue.replace(/"/g, '&quot;') + '"></div>' +
            '<div class="form-group"><label>New ' + fieldLabel + ':</label>' +
            '<input type="' + fieldType + '" id="new-' + field + '" value="' + currentValue.replace(/"/g, '&quot;') + '" required></div>' +
            '<div class="modal-buttons">' +
            '<button type="button" class="cancel-btn">Cancel</button>' +
            '<button type="submit" class="submit-btn">Save Changes</button>' +
            '</div></form></div></div>';

        document.body.appendChild(modal);
        setTimeout(() => modal.classList.add('active'), 50);

        modal.querySelector('.cancel-btn').onclick = closeModal;

        modal.querySelector('form').onsubmit = function (e) {
            e.preventDefault();
            const newValue = document.getElementById('new-' + field).value.trim();

            if (field === 'email' && !validateEmail(newValue)) {
                notify('Please enter a valid email address', 'error');
                return;
            }

            const formData = new FormData();
            formData.append('field', field);
            formData.append('value', newValue);

            const cfg = getConfig();
            fetch(baseUrl() + (cfg.updateUrl || 'profile/update'), {
                method: 'POST',
                body: formData,
                credentials: 'include',
            })
                .then((r) => r.json())
                .then((data) => {
                    if (data.success) {
                        updateFieldDisplay(field, newValue);
                        notify(data.message || fieldLabel + ' updated successfully', 'success');
                        closeModal();
                    } else {
                        notify(data.message || 'Update failed', 'error');
                    }
                })
                .catch(() => notify('An error occurred while updating the profile', 'error'));
        };
    }

    function changePassword() {
        if (document.querySelector('.modal-overlay')) return;

        const modal = document.createElement('div');
        modal.className = 'modal-overlay';
        modal.innerHTML =
            '<div class="modal-container">' +
            '<div class="modal-header"><h3>Change Password</h3></div>' +
            '<div class="modal-body">' +
            '<form id="change-password-form">' +
            '<div class="form-group"><label>Current Password:</label><input type="password" id="acct-current-password" required></div>' +
            '<div class="form-group"><label>New Password:</label><input type="password" id="acct-new-password" required></div>' +
            '<div class="form-group"><label>Confirm New Password:</label><input type="password" id="acct-confirm-password" required></div>' +
            '<div class="modal-buttons">' +
            '<button type="button" class="cancel-btn">Cancel</button>' +
            '<button type="submit" class="submit-btn">Update Password</button>' +
            '</div></form></div></div>';

        document.body.appendChild(modal);
        setTimeout(() => modal.classList.add('active'), 50);
        modal.querySelector('.cancel-btn').onclick = closeModal;

        modal.querySelector('form').onsubmit = function (e) {
            e.preventDefault();
            const currentPassword = document.getElementById('acct-current-password').value;
            const newPassword = document.getElementById('acct-new-password').value;
            const confirmPassword = document.getElementById('acct-confirm-password').value;

            if (newPassword !== confirmPassword) {
                notify('New passwords do not match!', 'error');
                return;
            }
            if (newPassword.length < 8) {
                notify('New password must be at least 8 characters long', 'error');
                return;
            }

            const formData = new FormData();
            formData.append('current_password', currentPassword);
            formData.append('new_password', newPassword);
            formData.append('confirm_password', confirmPassword);

            const cfg = getConfig();
            fetch(baseUrl() + (cfg.passwordUrl || 'update-password'), {
                method: 'POST',
                body: formData,
                credentials: 'include',
            })
                .then((r) => r.json())
                .then((data) => {
                    if (data.success) {
                        notify(data.message || 'Password updated successfully', 'success');
                        closeModal();
                    } else {
                        notify(data.message || 'Failed to update password', 'error');
                    }
                })
                .catch(() => notify('An error occurred while updating the password', 'error'));
        };
    }

    function updateProfilePicture() {
        if (document.querySelector('.modal-overlay')) return;

        const modal = document.createElement('div');
        modal.className = 'modal-overlay';
        modal.innerHTML =
            '<div class="modal-container">' +
            '<div class="modal-header"><h3>Update Profile Picture</h3></div>' +
            '<div class="modal-body">' +
            '<form id="update-profile-picture-form" enctype="multipart/form-data">' +
            '<div class="form-group"><label>Select a new profile picture:</label>' +
            '<input type="file" id="acct_profile_picture" name="profile_picture" accept="image/*" required>' +
            '<small>Maximum file size: 5MB. Allowed formats: JPG, JPEG, PNG, GIF</small></div>' +
            '<div class="modal-buttons">' +
            '<button type="button" class="cancel-btn">Cancel</button>' +
            '<button type="submit" class="submit-btn">Upload Picture</button>' +
            '</div></form></div></div>';

        document.body.appendChild(modal);
        setTimeout(() => modal.classList.add('active'), 50);
        modal.querySelector('.cancel-btn').onclick = closeModal;

        const form = modal.querySelector('form');
        form.onsubmit = function (e) {
            e.preventDefault();
            const fileInput = document.getElementById('acct_profile_picture');
            const file = fileInput && fileInput.files[0];
            if (!file) {
                notify('Please select a file', 'error');
                return;
            }
            if (file.size > 5 * 1024 * 1024) {
                notify('File is too large. Maximum size is 5MB', 'error');
                return;
            }
            const allowed = ['image/jpeg', 'image/png', 'image/gif'];
            if (!allowed.includes(file.type)) {
                notify('Invalid file type. Only JPG, PNG & GIF files are allowed.', 'error');
                return;
            }

            const formData = new FormData();
            formData.append('profile_picture', file);
            const submitBtn = form.querySelector('.submit-btn');
            submitBtn.disabled = true;
            submitBtn.textContent = 'Uploading...';

            const cfg = getConfig();
            fetch(baseUrl() + (cfg.pictureUrl || 'profile/picture'), {
                method: 'POST',
                body: formData,
                credentials: 'include',
            })
                .then((r) => r.json())
                .then((data) => {
                    if (data.success && data.picture_url) {
                        updateAvatarImages(resolveImageUrl(data.picture_url));
                        notify(data.message || 'Profile picture updated successfully', 'success');
                        closeModal();
                        loadAccountProfileData();
                    } else {
                        notify(data.message || 'Failed to update profile picture', 'error');
                    }
                })
                .catch(() => notify('An error occurred while updating the profile picture', 'error'))
                .finally(() => {
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Upload Picture';
                });
        };
    }

    function loadAccountProfileData() {
        const cfg = getConfig();
        const url = baseUrl() + (cfg.loadUrl || 'profile/get');

        fetch(url, {
            method: 'GET',
            credentials: 'include',
            headers: { 'Cache-Control': 'no-cache', Pragma: 'no-cache' },
        })
            .then((response) => {
                if (!response.ok) {
                    if (response.status === 401 || response.status === 403) {
                        if (cfg.redirectOnAuthFail) {
                            window.location.href = baseUrl() + cfg.redirectOnAuthFail;
                        }
                        return null;
                    }
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then((data) => {
                if (!data) return;
                if (!data.success) {
                    throw new Error(data.message || 'Failed to load profile data');
                }

                const payload = data.data || data;
                if (cfg.accountIdId) {
                    const idEl = document.getElementById(cfg.accountIdId);
                    if (idEl) idEl.textContent = payload.user_id || '—';
                }

                updateFieldDisplay('email', payload.email || '');
                updateFieldDisplay('username', payload.username || '');

                if (payload.profile_picture) {
                    updateAvatarImages(resolveImageUrl(payload.profile_picture));
                }

                if (cfg.displayNameId) {
                    const nameEl = document.getElementById(cfg.displayNameId);
                    if (nameEl) {
                        const c = payload.counselor || null;
                        const displayName =
                            payload.full_name ||
                            payload.name ||
                            (c && c.name) ||
                            payload.username ||
                            'User';
                        if (displayName && displayName !== 'N/A') {
                            nameEl.textContent = displayName;
                        }
                    }
                }

                if (typeof cfg.onProfileDataLoaded === 'function') {
                    cfg.onProfileDataLoaded(payload);
                }
            })
            .catch((err) => {
                notify(err.message || 'Failed to load profile data. Please try again later.', 'error');
            });
    }

    window.AccountProfileActions = {
        editField: editField,
        changePassword: changePassword,
        updateProfilePicture: updateProfilePicture,
        closeModal: closeModal,
        showNotification: showNotification,
        loadAccountProfileData: loadAccountProfileData,
        updateFieldDisplay: updateFieldDisplay,
        resolveImageUrl: resolveImageUrl,
    };

    window.editField = editField;
    window.changePassword = changePassword;
    window.updateProfilePicture = updateProfilePicture;
    window.closeModal = closeModal;

    document.addEventListener('DOMContentLoaded', function () {
        if (!getConfig().rolePrefix) return;
        loadAccountProfileData();
    });
})();
