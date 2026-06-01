window.ACCOUNT_PROFILE_CONFIG = {
    rolePrefix: 'counselor',
    updateUrl: 'counselor/profile/update',
    pictureUrl: 'counselor/profile/picture',
    loadUrl: 'counselor/profile/get',
    passwordUrl: 'update-password',
    storageKey: 'counselor_profile_picture',
    emailPreviewId: 'counselor-email-preview',
    usernamePreviewId: 'counselor-username-preview',
    displayNameId: 'counselor-display-name',
    accountIdId: 'display-userid',
    redirectOnAuthFail: 'counselor/dashboard',
    notify: function (message, type) {
        if (typeof openAlertModal === 'function') {
            openAlertModal(message, type === 'error' ? 'error' : (type === 'success' ? 'success' : 'warning'));
        } else if (window.AccountProfileActions) {
            window.AccountProfileActions.showNotification(message, type);
        }
    },
    onFieldUpdated: function (field, value) {
        const username = field === 'username'
            ? value
            : (document.querySelector('[data-field="username"] .acct-field-value')?.textContent || '');
        const email = field === 'email'
            ? value
            : (document.querySelector('[data-field="email"] .acct-field-value')?.textContent || '');
        const displayName = document.getElementById('counselor-display-name')?.textContent || username;
        syncCounselorAccountDisplay(username, email, displayName);
        if (field === 'email') {
            const piEmail = document.getElementById('pi-email');
            const upiEmail = document.getElementById('upi-email');
            if (piEmail) piEmail.value = value;
            if (upiEmail) upiEmail.value = value;
        }
    },
    onProfileDataLoaded: function (data) {
        const c = data.counselor || null;
        const displayName = data.full_name || data.name || (c && c.name) || data.username || 'Counselor';
        syncCounselorAccountDisplay(data.username || '', data.email || '', displayName);
        populateCounselorProfessionalDetails(data);
    },
};

function resolveImageUrl(path) {
    if (window.AccountProfileActions && window.AccountProfileActions.resolveImageUrl) {
        return window.AccountProfileActions.resolveImageUrl(path);
    }
    if (!path) return (window.BASE_URL || '/') + 'Photos/profile.png';
    const trimmed = String(path).trim();
    if (/^https?:\/\//i.test(trimmed)) return trimmed;
    if (trimmed.startsWith('/')) return trimmed;
    return (window.BASE_URL || '/') + trimmed.replace(/^\//, '');
}

function setAcctPreview(id, text) {
    const el = document.getElementById(id);
    if (el) el.textContent = text;
}

function syncCounselorAccountDisplay(username, email, counselorName) {
    setAcctPreview('acct-username-value', username);
    setAcctPreview('acct-email-value', email);
    setAcctPreview('counselor-username-preview', username);
    setAcctPreview('counselor-email-preview', email);
    setAcctPreview('counselor-display-name', counselorName || username);
    const du = document.getElementById('display-username');
    const de = document.getElementById('display-email');
    if (du) du.value = username;
    if (de) de.value = email;
}

// Function to handle logout action
function handleLogout() {
    if (typeof window.confirmLogout === "function") {
        window.confirmLogout();
    } else {
        // Fallback (should rarely occur)
        if (confirm("Are you sure you want to log out?")) {
            window.location.href = (window.BASE_URL || "/") + "auth/logout";
        }
    }
}

function populateCounselorProfessionalDetails(data) {
    const c = data.counselor || null;
    const setVal = (id, v, defaultValue = 'N/A') => {
        const el = document.getElementById(id);
        if (el) el.value = v || defaultValue;
    };
    const setValInput = (id, v) => {
        const el = document.getElementById(id);
        if (el) el.value = v || '';
    };
    setValInput('pi-counselor-id-input', data.user_id || '');
    setVal('pi-fullname', c ? c.name : '', 'N/A');
    setVal('pi-birthdate', c ? (c.birthdate || '') : '', '');
    setVal('pi-address', c ? c.address : '', 'N/A');
    setVal('pi-degree', c ? c.degree : '', 'N/A');
    setVal('pi-email', data.email || (c ? c.email : '') || '', 'N/A');
    const specializationDisplayEl = document.getElementById('pi-specialization');
    if (specializationDisplayEl) {
        setVal('pi-specialization', c ? c.specialization : '', 'N/A');
    }
    setVal('pi-contact', c ? c.contact_number : '', 'N/A');
    setVal('pi-sex', c ? c.sex : '', '');
    setVal('pi-civil', c ? c.civil_status : '', '');

    const setModal = (id, v, defaultValue = 'N/A') => {
        const el = document.getElementById(id);
        if (el) el.value = v || defaultValue;
    };
    (function () {
        const storedName = (c && c.name) ? c.name.trim() : '';
        const parts = storedName ? storedName.split(/\s+/) : [];
        let fn = '', ln = '', mi = '';
        if (parts.length === 1) { fn = parts[0]; }
        else if (parts.length === 2) { fn = parts[0]; ln = parts[1]; }
        else if (parts.length >= 3) { fn = parts[0]; ln = parts[parts.length - 1]; mi = parts.slice(1, -1).join(' '); }
        const setField = (id, v) => { const el = document.getElementById(id); if (el) el.value = v; };
        setField('upi-firstname', fn);
        setField('upi-lastname', ln);
        setField('upi-mi', mi);
    })();
    setModal('upi-birthdate', c ? (c.birthdate || '') : '', '');
    const addressValue = (c && c.address) ? c.address : '';
    const upiAddressEl = document.getElementById('upi-address');
    if (upiAddressEl) upiAddressEl.value = addressValue;
    if (window.PsgcAddress) {
        window.PsgcAddress.init('upi-');
        if (addressValue) {
            setTimeout(function () {
                window.PsgcAddress.setValue('upi-', addressValue);
            }, 800);
        }
    }
    setModal('upi-degree', c ? c.degree : '', 'N/A');
    setModal('upi-email', data.email || (c ? c.email : '') || '', 'N/A');
    const specializationEl = document.getElementById('upi-specialization');
    if (specializationEl) {
        setModal('upi-specialization', c ? c.specialization : '', 'N/A');
    }
    setModal('upi-contact', c ? c.contact_number : '', 'N/A');
    setModal('upi-sex', c ? c.sex : '', '');
    setModal('upi-civil', c ? c.civil_status : '', '');
}

document.addEventListener('DOMContentLoaded', function() {
    SecureLogger.info("DOM loaded, setting up profile functionality");

    // Initialize availability UI
    initAvailabilityUi();

    // Get the logout button
    const logoutBtn = document.querySelector('.btn-logout');

    // Add click event listener to logout button
    if (logoutBtn) {
        SecureLogger.info("Logout button found, adding event listener");
        logoutBtn.addEventListener('click', handleLogout);
    } else {
        SecureLogger.info("Logout button not found!");
    }
    
    // Drawer toggle bindings (match landing behavior)
    const navbarDrawerToggler = document.getElementById('navbarDrawerToggler');
    const navbarDrawer = document.getElementById('navbarDrawer');
    const navbarDrawerClose = document.getElementById('navbarDrawerClose');
    const navbarOverlay = document.getElementById('navbarOverlay');

    function openDrawer() {
        if (navbarDrawer && navbarOverlay) {
            navbarDrawer.classList.add('show');
            navbarOverlay.classList.add('show');
            document.body.style.overflow = 'hidden';
        }
    }

    function closeDrawer() {
        if (navbarDrawer && navbarOverlay) {
            navbarDrawer.classList.remove('show');
            navbarOverlay.classList.remove('show');
            document.body.style.overflow = '';
        }
    }

    if (navbarDrawerToggler) navbarDrawerToggler.addEventListener('click', openDrawer);
    if (navbarDrawerClose) navbarDrawerClose.addEventListener('click', closeDrawer);
    if (navbarOverlay) navbarOverlay.addEventListener('click', closeDrawer);

    // Logout from drawer
    const logoutFromDrawer = document.getElementById('logoutFromDrawer');
    if (logoutFromDrawer) {
        logoutFromDrawer.addEventListener('click', function() {
            closeDrawer();
            setTimeout(handleLogout, 200);
        });
    }
});

// Function to toggle password visibility
function togglePassword(inputId) {
    const input = document.getElementById(inputId);
    // The DOM structure is: <div class="password-input-group"><input><i class="toggle-password"></i></div>
    // So the correct container is the parent element, not nextElementSibling.
    const container = input ? input.parentElement : null;

    if (!input || !container) return;

    // Find whatever element is currently acting as the toggle (either <i> or <img>)
    let toggleEl = container.querySelector('.toggle-password');

    if (input.type === 'password') {
        // We are about to SHOW the password. Keep the "hide" icon as Photos/eye.png
        input.type = 'text';

        // If it's already an <img>, just swap src; otherwise replace the <i> with <img>
        if (toggleEl && toggleEl.tagName.toLowerCase() === 'img') {
            toggleEl.src = (window.BASE_URL || '/') + 'Photos/close_eye.png';
            toggleEl.alt = 'Hide password';
        } else if (toggleEl) {
            const img = document.createElement('img');
            img.src = (window.BASE_URL || '/') + 'Photos/close_eye.png';
            img.alt = 'Hide password';
            img.className = 'toggle-password custom-hide-icon';
            img.style.width = '30px';
            img.style.height = '30px';
            img.style.cursor = 'pointer';
            img.onclick = () => togglePassword(inputId);
            toggleEl.replaceWith(img);
        }
    } else {
        // We are about to HIDE the password. Restore the Font Awesome eye (show icon)
        input.type = 'password';

        if (toggleEl && toggleEl.tagName.toLowerCase() === 'img') {
            const icon = document.createElement('i');
            icon.className = 'fas fa-eye toggle-password';
            icon.style.cursor = 'pointer';
            icon.onclick = () => togglePassword(inputId);
            toggleEl.replaceWith(icon);
        } else if (toggleEl) {
            // Ensure proper classes if an <i> already exists
            toggleEl.classList.remove('fa-eye-slash');
            toggleEl.classList.add('fa-eye');
        }
    }
}

// Save Personal Info changes
function savePersonalInfoChanges() {
    const form = new FormData();
    // Get values and use 'N/A' as default for empty fields (except birthdate and select fields)
    const getValueOrDefault = (fieldId, defaultValue = 'N/A') => {
        const value = document.getElementById(fieldId)?.value?.trim() || '';
        return value === '' ? defaultValue : value;
    };
    
    const _fn = document.getElementById('upi-firstname')?.value?.trim() || '';
    const _ln = document.getElementById('upi-lastname')?.value?.trim() || '';
    const _mi = document.getElementById('upi-mi')?.value?.trim() || '';
    const _fullname = _mi ? (_fn + ' ' + _mi + ' ' + _ln) : (_fn + ' ' + _ln);
    form.append('fullname', _fullname.trim() || 'N/A');
    form.append('birthdate', getValueOrDefault('upi-birthdate', ''));
    form.append('address', getValueOrDefault('upi-address'));
    form.append('degree', getValueOrDefault('upi-degree'));
    form.append('email', getValueOrDefault('upi-email'));
    form.append('contact', getValueOrDefault('upi-contact'));
    form.append('sex', getValueOrDefault('upi-sex', ''));
    form.append('civil_status', getValueOrDefault('upi-civil', ''));

    fetch((window.BASE_URL || '/') + 'counselor/profile/counselor-info', {
        method: 'POST',
        body: form,
        credentials: 'include'
    })
    .then(r => r.json())
    .then(d => {
        if (!d || !d.success) throw new Error(d?.message || 'Failed to save');
        if (window.AccountProfileActions) {
            window.AccountProfileActions.loadAccountProfileData();
        }
        const modal = bootstrap.Modal.getInstance(document.getElementById('updatePersonalInfoModal'));
        if (modal) modal.hide();
        openAlertModal('Personal information updated successfully!', 'success');
    })
    .catch(err => {
        openAlertModal(err.message || 'Failed to update personal information.', 'error');
    });
}

// ===== Availability Management =====
function initAvailabilityUi() {
    // Populate time selects with 30-min intervals from 07:00 AM to 05:30 PM in 12-hour format
    // Exclude 12:00 PM and 12:30 PM from the time options
    const timeFrom = document.getElementById('time-from');
    const timeTo = document.getElementById('time-to');
    if (timeFrom && timeTo) {
        const options = [];
        for (let h = 7; h <= 17; h++) {
            for (let m = 0; m < 60; m += 30) {
                const h24 = h;
                const m24 = m;
                const ampm = h24 >= 12 ? 'PM' : 'AM';
                const hour12 = ((h24 + 11) % 12) + 1; // 0->12, 13->1
                const val = `${hour12}:${String(m24).padStart(2,'0')} ${ampm}`;
                
                // Skip 12:00 PM and 12:30 PM times
                if (val !== '12:00 PM' && val !== '12:30 PM') {
                    options.push(val);
                }
            }
        }
        options.forEach(v => {
            const o1 = document.createElement('option'); o1.value = v; o1.textContent = v; timeFrom.appendChild(o1);
            const o2 = document.createElement('option'); o2.value = v; o2.textContent = v; timeTo.appendChild(o2);
        });
    }

    const addBtn = document.getElementById('add-time-slot');
    const daysContainer = document.getElementById('availability-days');
    if (addBtn && daysContainer) {
        addBtn.addEventListener('click', () => {
            const from = document.getElementById('time-from')?.value;
            const to = document.getElementById('time-to')?.value;
            if (!from || !to) { openAlertModal('Select both From and To time.', 'warning'); return; }
            // Use timeToMinutes() for proper time comparison instead of string comparison
            if (timeToMinutes(from) >= timeToMinutes(to)) { openAlertModal('From must be earlier than To.', 'warning'); return; }
            const days = getSelectedDays();
            if (days.length === 0) { openAlertModal('Select at least one day.', 'warning'); return; }
            days.forEach(day => addRangeForDay(day, { from, to }));
            renderAvailabilitySchedule('avail-modal-week', true);
        });
    }

    const saveBtn = document.getElementById('save-availability');
    if (saveBtn) {
        saveBtn.addEventListener('click', () => saveAvailability());
    }

    const editBtn = document.getElementById('edit-availability');
    if (editBtn) { editBtn.addEventListener('click', openAvailabilityModal); }

    const availModalEl = document.getElementById('availabilityModal');
    if (availModalEl) {
        availModalEl.addEventListener('show.bs.modal', onAvailabilityModalShow);
        availModalEl.addEventListener('hidden.bs.modal', onAvailabilityModalHidden);
    }

    loadAvailabilityFromServer();
}

// ----- Availability state and helpers -----
const DAYS_ORDER = ['Monday','Tuesday','Wednesday','Thursday','Friday'];
let availabilityState = { rangesByDay: { Monday: [], Tuesday: [], Wednesday: [], Thursday: [], Friday: [] } };
let availabilitySnapshot = null;
let availabilityModalSaved = false;

// Convert 12-hour format time to minutes for comparison
function timeToMinutes(t) { 
    if (!t) return 0;
    // Handle 12-hour format: "1:30 PM" or "12:00 AM"
    const match = t.match(/^(\d{1,2}):(\d{2})\s*(AM|PM)$/i);
    if (!match) return 0;
    
    let hours = parseInt(match[1]);
    const minutes = parseInt(match[2]);
    const ampm = match[3].toUpperCase();
    
    // Convert to 24-hour format for calculation
    if (ampm === 'PM' && hours !== 12) {
        hours += 12;
    } else if (ampm === 'AM' && hours === 12) {
        hours = 0;
    }
    
    return hours * 60 + minutes;
}

// Convert minutes back to 12-hour format
function minutesToTime(m) { 
    const hours = Math.floor(m / 60);
    const minutes = m % 60;
    
    const ampm = hours >= 12 ? 'PM' : 'AM';
    const hour12 = ((hours + 11) % 12) + 1; // 0->12, 13->1
    
    return `${hour12}:${String(minutes).padStart(2,'0')} ${ampm}`;
}

function normalizeRange(range) {
    const fromM = timeToMinutes(range.from);
    const toM = timeToMinutes(range.to);
    return fromM < toM ? { from: minutesToTime(fromM), to: minutesToTime(toM) } : null;
}

function mergeRanges(ranges) {
    if (!ranges.length) return [];
    const sorted = ranges.slice().sort((a,b) => timeToMinutes(a.from) - timeToMinutes(b.from));
    const merged = [sorted[0]];
    for (let i = 1; i < sorted.length; i++) {
        const prev = merged[merged.length - 1];
        const cur = sorted[i];
        if (timeToMinutes(cur.from) <= timeToMinutes(prev.to)) {
            if (timeToMinutes(cur.to) > timeToMinutes(prev.to)) prev.to = cur.to;
        } else {
            merged.push({ ...cur });
        }
    }
    return merged;
}

function addRangeForDay(day, range) {
    const norm = normalizeRange(range);
    if (!norm) return;
    const arr = availabilityState.rangesByDay[day] || [];
    arr.push(norm);
    availabilityState.rangesByDay[day] = mergeRanges(arr);
}

function removeRangeForDay(day, index) {
    const arr = availabilityState.rangesByDay[day] || [];
    if (index >= 0 && index < arr.length) {
        arr.splice(index, 1);
        availabilityState.rangesByDay[day] = arr;
    }
}

function expandRangesToTimes(ranges) {
    const times = [];
    ranges.forEach(r => {
        for (let t = timeToMinutes(r.from); t < timeToMinutes(r.to); t += 30) {
            times.push(minutesToTime(t));
        }
    });
    return Array.from(new Set(times));
}

function compactTimesToRanges(times) {
    const t = (times || []).slice().sort();
    const ranges = [];
    let i = 0;
    while (i < t.length) {
        const start = t[i];
        let prev = start; i++;
        while (i < t.length) {
            const cur = t[i];
            if (timeToMinutes(cur) - timeToMinutes(prev) === 30) { prev = cur; i++; } else { break; }
        }
        const end = minutesToTime(timeToMinutes(prev) + 30);
        ranges.push({ from: start, to: end });
    }
    return ranges;
}

const DAY_ABBR = {
    Monday: 'Mon',
    Tuesday: 'Tue',
    Wednesday: 'Wed',
    Thursday: 'Thu',
    Friday: 'Fri',
};

function updateAvailabilityStats() {
    let openDays = 0;
    let slotCount = 0;
    DAYS_ORDER.forEach(day => {
        const ranges = availabilityState.rangesByDay[day] || [];
        if (ranges.length) {
            openDays += 1;
            slotCount += ranges.length;
        }
    });
    const daysEl = document.getElementById('avail-stat-days');
    const slotsEl = document.getElementById('avail-stat-slots');
    if (daysEl) daysEl.textContent = String(openDays);
    if (slotsEl) slotsEl.textContent = String(slotCount);
}

function cloneAvailabilityState() {
    const copy = { Monday: [], Tuesday: [], Wednesday: [], Thursday: [], Friday: [] };
    DAYS_ORDER.forEach(day => {
        copy[day] = (availabilityState.rangesByDay[day] || []).map(r => ({ ...r }));
    });
    return copy;
}

function applyAvailabilitySnapshot(snapshot) {
    DAYS_ORDER.forEach(day => {
        availabilityState.rangesByDay[day] = (snapshot[day] || []).map(r => ({ ...r }));
    });
}

function renderAvailabilitySchedule(containerId, allowRemove) {
    const host = document.getElementById(containerId);
    if (!host) return;

    host.innerHTML = '';
    let hasAnySlot = false;

    DAYS_ORDER.forEach(day => {
        const ranges = availabilityState.rangesByDay[day] || [];
        if (ranges.length) hasAnySlot = true;

        const card = document.createElement('article');
        card.className = 'avail-day-card' + (ranges.length ? ' is-open' : ' is-off');

        const head = document.createElement('div');
        head.className = 'avail-day-card-head';
        const abbr = document.createElement('span');
        abbr.className = 'avail-day-abbr';
        abbr.textContent = containerId === 'time-slots-list' ? day : (DAY_ABBR[day] || day);
        const dot = document.createElement('span');
        dot.className = 'avail-day-dot';
        dot.setAttribute('aria-hidden', 'true');
        head.appendChild(abbr);
        head.appendChild(dot);
        card.appendChild(head);

        const body = document.createElement('div');
        body.className = 'avail-day-card-body';

        if (!ranges.length) {
            const off = document.createElement('span');
            off.className = 'avail-day-off';
            off.textContent = 'Unavailable';
            body.appendChild(off);
        } else {
            ranges.forEach((r, idx) => {
                const chip = document.createElement('span');
                chip.className = 'slot-chip';

                const label = document.createElement('span');
                label.className = 'slot-chip-label';
                label.textContent = r.from + ' – ' + r.to;
                chip.appendChild(label);

                if (allowRemove) {
                    const rm = document.createElement('button');
                    rm.type = 'button';
                    rm.className = 'chip-remove';
                    rm.setAttribute('aria-label', 'Remove ' + day + ' ' + r.from + ' to ' + r.to);
                    rm.setAttribute('title', 'Remove time slot');
                    rm.innerHTML = '<span class="chip-remove-x" aria-hidden="true">×</span>';
                    rm.onclick = () => {
                        removeRangeForDay(day, idx);
                        renderAvailabilitySchedule('avail-modal-week', true);
                    };
                    chip.appendChild(rm);
                }
                body.appendChild(chip);
            });
        }

        card.appendChild(body);
        host.appendChild(card);
    });

    if (containerId === 'time-slots-list') {
        updateAvailabilityStats();
        const emptyMsg = document.getElementById('avail-empty-msg');
        if (emptyMsg) emptyMsg.hidden = hasAnySlot;
        host.hidden = !hasAnySlot;
    }
}

function renderAvailabilityChips() {
    renderAvailabilitySchedule('time-slots-list', false);
}

function getSelectedDays() {
    const days = [];
    ['Monday','Tuesday','Wednesday','Thursday','Friday'].forEach(d => {
        const cb = document.getElementById('day-' + d);
        if (cb && cb.checked) days.push(d);
    });
    return days;
}

function loadAvailabilityFromServer() {
    fetch((window.BASE_URL || '/') + 'counselor/profile/availability', { credentials: 'include' })
        .then(r => r.json())
        .then(d => {
            if (!d.success) throw new Error(d.message || 'Failed to load availability');
            // Set checkboxes based on received days
            ['Monday','Tuesday','Wednesday','Thursday','Friday'].forEach(day => {
                const cb = document.getElementById('day-' + day);
                if (cb) cb.checked = !!(d.availability && d.availability[day] && d.availability[day].length);
            });
            // Build state rangesByDay reading consolidated ranges in 12-hour format from server
            const state = { Monday: [], Tuesday: [], Wednesday: [], Thursday: [], Friday: [] };
            (Object.keys(d.availability || {})).forEach(day => {
                const rows = (d.availability[day] || []);
                const ranges = [];
                rows.forEach(row => {
                    const ts = row.time_scheduled;
                    if (!ts) return;
                    // Handle 12-hour format ranges: "1:30 PM-3:00 PM" or "9:00 AM-11:30 AM"
                    const m = String(ts).match(/^(.+?)-(.+)$/);
                    if (m) {
                        ranges.push({ from: m[1].trim(), to: m[2].trim() });
                    }
                });
                state[day] = ranges;
            });
            availabilityState.rangesByDay = state;
            renderAvailabilityChips();
        })
        .catch(err => {
            console.error(err);
        });
}

function saveAvailability() {
    // Save based on days that actually have time ranges added, not on checkbox state
    const daysToSave = [];
    const timesByDay = {};
    
    DAYS_ORDER.forEach(day => {
        const ranges = availabilityState.rangesByDay[day] || [];
        if (ranges.length > 0) {
            daysToSave.push(day);
            // Convert ranges to 12-hour format time strings for server
            const timeStrings = ranges.map(range => `${range.from}-${range.to}`);
            timesByDay[day] = timeStrings;
        }
    });
    
    if (daysToSave.length === 0) { 
        openAlertModal('Please add at least one time slot before saving.', 'warning'); 
        return; 
    }

    fetch((window.BASE_URL || '/') + 'counselor/profile/availability', {
        method: 'POST',
        credentials: 'include',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ days: daysToSave, timesByDay })
    })
    .then(r => r.json())
    .then(d => {
        if (!d.success) throw new Error(d.message || 'Failed to save availability');
        availabilityModalSaved = true;
        availabilitySnapshot = cloneAvailabilityState();
        openAlertModal(d.message || 'Availability saved successfully!', 'success');
        const modalEl = document.getElementById('availabilityModal');
        if (modalEl) {
            const inst = bootstrap.Modal.getInstance(modalEl);
            if (inst) inst.hide();
        }
        renderAvailabilitySchedule('time-slots-list', false);
    })
    .catch(err => {
        availabilityModalSaved = false;
        openAlertModal(err.message || 'Failed to save availability', 'error');
    });
}

function openAvailabilityModal() {
    availabilityModalSaved = false;
    availabilitySnapshot = cloneAvailabilityState();
    syncAvailabilityCheckboxes();
    renderAvailabilitySchedule('avail-modal-week', true);
    const modalEl = document.getElementById('availabilityModal');
    if (modalEl) {
        bootstrap.Modal.getOrCreateInstance(modalEl).show();
    }
}

function onAvailabilityModalShow() {
    availabilityModalSaved = false;
    if (!availabilitySnapshot) {
        availabilitySnapshot = cloneAvailabilityState();
    }
    syncAvailabilityCheckboxes();
    renderAvailabilitySchedule('avail-modal-week', true);
}

function onAvailabilityModalHidden() {
    if (!availabilityModalSaved && availabilitySnapshot) {
        applyAvailabilitySnapshot(availabilitySnapshot);
    }
    availabilitySnapshot = null;
    renderAvailabilitySchedule('time-slots-list', false);
}

function syncAvailabilityCheckboxes() {
    DAYS_ORDER.forEach(day => {
        const cb = document.getElementById('day-' + day);
        if (!cb) return;
        const ranges = availabilityState.rangesByDay[day] || [];
        cb.checked = ranges.length > 0;
    });
}
