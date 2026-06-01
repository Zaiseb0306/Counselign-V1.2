/**
 * Shared counselor notification dropdown (same design/behavior as student pages).
 * Requires: #notificationIcon, #notificationsDropdown.student-notifications-dropdown, window.BASE_URL
 */
window.__counselorNotificationsDropdownInit = true;

document.addEventListener("DOMContentLoaded", function () {
  const baseUrl = window.BASE_URL || "/";
  const notificationIcon = document.getElementById("notificationIcon");
  const notificationsDropdown = document.getElementById("notificationsDropdown");
  const notificationBadge = document.getElementById("notificationBadge");
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

  function setBadge(count) {
    if (!notificationBadge) return;
    const value = parseInt(count, 10) || 0;
    if (value > 0) {
      notificationBadge.textContent = value;
      notificationBadge.style.display = "inline-block";
      notificationBadge.classList.remove("hidden");
    } else {
      notificationBadge.textContent = "";
      notificationBadge.style.display = "none";
      notificationBadge.classList.add("hidden");
    }
    if (typeof window.updateDashboardStats === "function") {
      window.updateDashboardStats({ notifications: value });
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

  function filterExpiredNotifications(notifications) {
    const now = new Date();
    return notifications.filter((notification) => {
      if (notification.type === "event" && notification.event_date) {
        return new Date(notification.event_date) > now;
      }
      if (notification.type === "appointment" && notification.appointment_date) {
        const appointmentDate = new Date(notification.appointment_date);
        const sevenDaysAfter = new Date(
          appointmentDate.getTime() + 7 * 24 * 60 * 60 * 1000
        );
        return sevenDaysAfter > now;
      }
      return true;
    });
  }

  function fetchNotifications() {
    return fetch(baseUrl + "counselor/notifications")
      .then((r) => r.json())
      .then((data) => {
        if (data && data.status === "success" && Array.isArray(data.notifications)) {
          const filtered = filterExpiredNotifications(data.notifications);
          setBadge(filtered.length);
          return filtered;
        }
        setBadge(0);
        return [];
      })
      .catch(() => {
        setBadge(0);
        return [];
      });
  }

  function markNotificationAsRead(notificationId, notificationType, relatedId) {
    const payload = {};
    if (notificationId) payload.notification_id = notificationId;
    else if (notificationType && relatedId) {
      payload.type = notificationType;
      payload.related_id = relatedId;
    } else return Promise.resolve();

    return fetch(baseUrl + "counselor/notifications/mark-read", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(payload),
    }).catch(() => {});
  }

  function handleNotificationNavigate(n) {
    if (!n) return;
    if (n.type === "appointment") {
      if (
        typeof window.showCounselorAppointmentDetailsModal === "function" &&
        n.related_id
      ) {
        window.showCounselorAppointmentDetailsModal(n.related_id);
      } else {
        window.location.href = baseUrl + "counselor/appointments";
      }
      return;
    }
    if (n.type === "event" || n.type === "announcement") {
      window.location.href = baseUrl + "counselor/announcements";
      return;
    }
    if (n.type === "message") {
      const openChatBtn = document.getElementById("openChatBtn");
      if (openChatBtn) {
        openChatBtn.click();
      } else {
        window.location.href = baseUrl + "counselor/messages";
      }
    }
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
      const isUnread =
        !n.is_read || n.is_read === 0 || n.is_read === "0" || n.is_read === false;
      if (isUnread) item.classList.add("unread");

      const markBtn = isUnread
        ? `<button class="btn btn-sm btn-outline-primary mark-read-btn" type="button" title="Mark as read">
              <i class="fas fa-check"></i>
           </button>`
        : "";

      item.innerHTML = `
        <div class="notification-header">
          <h4>${n.title || "Notification"}</h4>
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
          markNotificationAsRead(n.id || null, n.type || null, n.related_id || null).then(
            () => {
              item.classList.remove("unread");
              markReadBtn.remove();
              fetchNotifications().then((list) => renderNotifications(list));
            }
          );
        });
      }

      item.addEventListener("click", function () {
        closeDropdown();
        if (isUnread) {
          markNotificationAsRead(n.id || null, n.type || null, n.related_id || null);
        }
        handleNotificationNavigate(n);
      });

      listEl.appendChild(item);
    });
  }

  function loadNotificationsAndRender() {
    fetchNotifications().then((list) => renderNotifications(list));
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

  function openDropdown() {
    positionDropdown();
    notificationsDropdown.style.display = "block";
    notificationIcon.setAttribute("aria-expanded", "true");
    setOverlayActive(isMobileView());
    loadNotificationsAndRender();
  }

  function toggleNotificationsDropdown(e) {
    if (e) {
      e.preventDefault();
      e.stopPropagation();
    }

    const isOpen = notificationsDropdown.style.display === "block";
    if (isOpen) {
      closeDropdown();
      return;
    }

    openDropdown();
  }

  const notificationContainer = notificationIcon.closest(".notification-icon-container");
  if (notificationContainer) {
    notificationContainer.addEventListener("click", function (e) {
      if (!e.target.closest("#notificationIcon")) return;
      toggleNotificationsDropdown(e);
    });
  } else {
    notificationIcon.addEventListener("click", toggleNotificationsDropdown);
  }

  document.addEventListener("click", function (e) {
    if (
      notificationsDropdown.style.display === "block" &&
      !notificationsDropdown.contains(e.target) &&
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
      window.location.href = baseUrl + "counselor/notifications/history";
    });
  }

  if (markAllReadBtn) {
    markAllReadBtn.addEventListener("click", function (e) {
      e.preventDefault();
      e.stopPropagation();
      fetch(baseUrl + "counselor/notifications/mark-read", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ mark_all: true }),
      })
        .then(() => loadNotificationsAndRender())
        .catch(() => {});
    });
  }

  window.reloadCounselorNotificationsDropdown = loadNotificationsAndRender;

  fetchNotifications();
  setInterval(function () {
    fetchNotifications().then((list) => {
      if (notificationsDropdown.style.display === "block") {
        renderNotifications(list);
      }
    });
  }, 10000);
});
