/** Set before DOMContentLoaded so student_dashboard.js skips duplicate handlers */
window.__studentNotificationsDropdownInit = true;

document.addEventListener("DOMContentLoaded", function () {
  const baseUrl = window.BASE_URL || "/";
  const notificationIcon = document.getElementById("notificationIcon");
  const notificationsDropdown = document.getElementById("notificationsDropdown");
  const notificationBadge = document.getElementById("notificationBadge");
  const messageBadge = document.getElementById("messageBadge");
  const seeHistoryBtn = document.getElementById("seeHistoryBtn");
  const markAllReadBtn = document.getElementById("markAllReadBtn");
  const listEl = document.querySelector("#notificationsDropdown .notifications-list");
  const notificationsOverlay = document.getElementById("notificationsOverlay");

  if (!notificationIcon || !notificationsDropdown) {
    return;
  }

  const mobileMq = window.matchMedia("(max-width: 768px)");

  function isMobileView() {
    return mobileMq.matches;
  }

  function setOverlayActive(active) {
    if (!notificationsOverlay) return;
    notificationsOverlay.classList.toggle("is-active", active);
    notificationsOverlay.setAttribute("aria-hidden", active ? "false" : "true");
  }

  function closeDropdown() {
    notificationsDropdown.style.display = "none";
    notificationIcon.setAttribute("aria-expanded", "false");
    setOverlayActive(false);
  }

  function setBadge(el, count) {
    if (!el) return;
    const value = parseInt(count, 10) || 0;
    if (value > 0) {
      el.textContent = value;
      el.style.display = "inline-block";
      el.classList.remove("hidden");
    } else {
      el.textContent = "";
      el.style.display = "none";
      el.classList.add("hidden");
    }
  }

  function formatDate(ts) {
    const d = new Date(ts);
    if (Number.isNaN(d.getTime())) return "";
    return d.toLocaleDateString() + " " + d.toLocaleTimeString();
  }

  function showEmpty(msg) {
    if (!listEl) return;
    listEl.innerHTML = `<div class="empty-notifications"><p>${msg}</p></div>`;
  }

  function fetchNotificationCount() {
    return fetch(baseUrl + "student/notifications")
      .then((r) => r.json())
      .then((data) => {
        if (data && data.status === "success" && Array.isArray(data.notifications)) {
          const visible = data.notifications.filter((n) => !(n && n.type === "message"));
          setBadge(notificationBadge, visible.length);
          return visible;
        }
        return [];
      })
      .catch(() => []);
  }

  function fetchMessageCount() {
    return fetch(baseUrl + "student/message/operations?action=get_unread_count")
      .then((r) => r.json())
      .then((data) => {
        if (data && data.success) {
          setBadge(messageBadge, data.unread_count || 0);
        }
      })
      .catch(() => {});
  }

  function markNotificationAsRead(notificationId, notificationType, relatedId) {
    const payload = {};
    if (notificationId) payload.notification_id = notificationId;
    else if (notificationType && relatedId) {
      payload.type = notificationType;
      payload.related_id = relatedId;
    } else return Promise.resolve();

    return fetch(baseUrl + "student/notifications/mark-read", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(payload),
    }).catch(() => {});
  }

  function renderNotifications(notifications) {
    if (!listEl) return;
    if (!notifications || notifications.length === 0) {
      showEmpty("No notifications");
      return;
    }

    listEl.innerHTML = "";

    notifications.forEach((n) => {
      if (!n) return;
      const item = document.createElement("div");
      item.className = "notification-item";
      if (!n.is_read || n.is_read === 0 || n.is_read === "0") item.classList.add("unread");

      const markBtn = (!n.is_read || n.is_read === 0 || n.is_read === "0")
        ? `<button class="btn btn-sm btn-outline-primary mark-read-btn" type="button" title="Mark as read">
              <i class="fas fa-check"></i>
           </button>`
        : "";

      item.innerHTML = `
        <div class="notification-header">
          <h4>${(n.title || "Notification")}</h4>
          <div class="notification-actions">
            <span class="notification-time">${formatDate(n.created_at)}</span>
            ${markBtn}
          </div>
        </div>
        <p>${n.message || ""}</p>
      `;

      const markReadBtn = item.querySelector(".mark-read-btn");
      if (markReadBtn) {
        markReadBtn.addEventListener("click", function (e) {
          e.preventDefault();
          e.stopPropagation();
          markNotificationAsRead(n.id || null, n.type || null, n.related_id || null).then(() => {
            item.classList.remove("unread");
            markReadBtn.remove();
            fetchNotificationCount();
          });
        });
      }

      item.addEventListener("click", function () {
        // close dropdown
        closeDropdown();

        // mark read best-effort
        if (!n.is_read) {
          markNotificationAsRead(n.id || null, n.type || null, n.related_id || null);
        }

        // navigation behavior like dashboard
        if (n.type === "event" || n.type === "announcement") {
          window.location.href = baseUrl + "student/announcements";
          return;
        }
        if (n.type === "appointment") {
          // If appointment modal exists (from student_dashboard_modals), open it via dashboard JS function if present
          // Otherwise fallback to My Appointments
          if (typeof window.showAppointmentDetailsModal === "function" && n.related_id) {
            window.showAppointmentDetailsModal(n.related_id);
          } else {
            window.location.href = baseUrl + "student/my-appointments";
          }
          return;
        }
      });

      listEl.appendChild(item);
    });
  }

  function loadNotificationsAndRender() {
    fetchNotificationCount().then((visible) => {
      renderNotifications(visible);
    });
  }

  function getDropdownTop() {
    const topBar = document.querySelector("header.top-bar");
    if (topBar) {
      return topBar.getBoundingClientRect().bottom + 8;
    }
    return notificationIcon.getBoundingClientRect().bottom + 10;
  }

  function positionDropdown() {
    const top = getDropdownTop();
    notificationsDropdown.style.top = top + "px";

    if (isMobileView()) {
      notificationsDropdown.style.left = "50%";
      notificationsDropdown.style.right = "auto";
      notificationsDropdown.style.transform = "translateX(-50%)";
      notificationsDropdown.style.width = "";
      return;
    }

    const rect = notificationIcon.getBoundingClientRect();
    const right = Math.max(12, window.innerWidth - rect.right);
    notificationsDropdown.style.left = "auto";
    notificationsDropdown.style.transform = "";
    notificationsDropdown.style.right = right + "px";
  }

  notificationIcon.addEventListener("click", function (e) {
    e.preventDefault();
    e.stopPropagation();

    const isOpen = notificationsDropdown.style.display === "block";
    if (isOpen) {
      closeDropdown();
      return;
    }

    positionDropdown();
    notificationsDropdown.style.display = "block";
    notificationIcon.setAttribute("aria-expanded", "true");
    setOverlayActive(isMobileView());
    loadNotificationsAndRender();
  });

  document.addEventListener("click", function (e) {
    if (
      notificationsDropdown.style.display === "block" &&
      !notificationsDropdown.contains(e.target) &&
      e.target !== notificationIcon &&
      !notificationIcon.contains(e.target)
    ) {
      closeDropdown();
    }
  });

  if (notificationsOverlay) {
    notificationsOverlay.addEventListener("click", closeDropdown);
  }

  window.addEventListener("resize", function () {
    if (notificationsDropdown.style.display === "block") {
      positionDropdown();
      setOverlayActive(isMobileView());
    }
  });

  mobileMq.addEventListener("change", function () {
    if (notificationsDropdown.style.display === "block") {
      positionDropdown();
      setOverlayActive(isMobileView());
    }
  });

  if (seeHistoryBtn) {
    seeHistoryBtn.addEventListener("click", function (e) {
      e.preventDefault();
      e.stopPropagation();
      window.location.href = baseUrl + "student/notifications/history";
    });
  }

  if (markAllReadBtn) {
    markAllReadBtn.addEventListener("click", function (e) {
      e.preventDefault();
      e.stopPropagation();
      fetch(baseUrl + "student/notifications/mark-read", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ mark_all: true }),
      })
        .then(() => loadNotificationsAndRender())
        .then(() => fetchNotificationCount())
        .catch(() => {});
    });
  }

  // initial badges + polling (dashboard-like)
  fetchMessageCount();
  fetchNotificationCount();
  setInterval(function () {
    fetchMessageCount();
    fetchNotificationCount();
    if (notificationsDropdown.style.display === "block") {
      loadNotificationsAndRender();
    }
  }, 10000);
});

