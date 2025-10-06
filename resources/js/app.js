import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();


const userId = window.Laravel.userId;
const badgeEl = document.getElementById('noticeBadge');

async function fetchUnreadCount() {
  try {
    const res = await fetch('/notifications/unread-count', { headers: { 'X-Requested-With': 'XMLHttpRequest' }});
    const json = await res.json();
    const count = json.count || 0;
    if (badgeEl) {
      badgeEl.textContent = count > 0 ? count : '';
      badgeEl.classList.toggle('hidden', count === 0);
    }
  } catch(e) { console.error(e); }
}

if (userId) {
  window.Echo.private(`App.Models.User.${userId}`)
    .notification((notification) => {
      // notification.data = objek yang dikirim dari toDatabase()
      const d = notification.data || {};
      const msg = d.message || d.title || 'Notifikasi baru';
      // update badge (increment)
      const current = parseInt(badgeEl?.textContent || '0') || 0;
      const newCount = current + 1;
      if (badgeEl) {
        badgeEl.textContent = newCount;
        badgeEl.classList.remove('hidden');
      }
      // coba play audio (fallback ke alert jika tidak diijinkan)
      try {
        const audio = new Audio('/sounds/notification.mp3');
        audio.play().catch(()=>{ /* autoplay blocked */ });
      } catch(e) {}
      // optional: tampilkan toast
      console.log('Notif received:', msg);
    });
  // update badge sekali saat load
  fetchUnreadCount();
}
