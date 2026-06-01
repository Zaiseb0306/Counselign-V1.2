document.addEventListener("DOMContentLoaded", function () {
  const baseUrl = window.BASE_URL || "/";
  const notificationIcon = document.getElementById("notificationIcon");
  const notificationBadge = document.getElementById("notificationBadge");
  const messageBadge = document.getElementById("messageBadge");
  const notificationsDropdown = document.getElementById("notificationsDropdown");

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

  function fetchNotificationCount() {
    fetch(baseUrl + "student/notifications")
      .then((response) => response.json())
      .then((data) => {
        if (data && data.status === "success" && Array.isArray(data.notifications)) {
          const visible = data.notifications.filter((n) => !(n && n.type === "message"));
          setBadge(notificationBadge, visible.length);
        }
      })
      .catch(() => {});
  }

  function fetchMessageCount() {
    fetch(baseUrl + "student/message/operations?action=get_unread_count")
      .then((response) => response.json())
      .then((data) => {
        if (data && data.success) {
          setBadge(messageBadge, data.unread_count || 0);
        }
      })
      .catch(() => {});
  }

  // On the dashboard we keep the existing dropdown behavior.
  if (notificationIcon && !notificationsDropdown) {
    notificationIcon.addEventListener("click", function (e) {
      e.preventDefault();
      window.location.href = baseUrl + "student/notifications/history";
    });
  }

  fetchNotificationCount();
  fetchMessageCount();
  // If the dashboard script is running, it already polls.
  if (!notificationsDropdown) {
    setInterval(function () {
      fetchNotificationCount();
      fetchMessageCount();
    }, 10000);
  }
});
