<!DOCTYPE html>
<html lang="en"
      x-data="{ ...darkModeHandler(), openSidebar: false }"
      x-bind:class="darkMode ? 'dark' : ''"
      x-init="init()">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Stockist - @yield('title')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = { darkMode: 'class' }
        if (localStorage.getItem('darkMode') === 'true' ||
            (!localStorage.getItem('darkMode') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        }
    </script>
    <script src="https://unpkg.com/alpinejs" defer></script>
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="bg-gray-100 dark:bg-gray-900 font-sans text-gray-800 dark:text-gray-200">

    <header class="flex items-center justify-between bg-white dark:bg-gray-800 shadow px-4 py-3
                md:ml-64 fixed w-full md:w-[calc(100%-16rem)] z-40 transition-colors">

        <div class="flex items-center space-x-3 md:hidden">
            <button @click="openSidebar = !openSidebar"
                    class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                <i data-lucide="menu" class="w-6 h-6"></i>
            </button>
            <a href="{{ route('warehouse.dashboard') }}" class="text-lg font-bold hover:underline cursor-pointer">
                Stockist Panel
            </a>
        </div>

        <div class="flex items-center space-x-4 ml-auto">
            <button @click="toggle()"
                    class="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700">
                <i x-show="!darkMode" data-lucide="moon" class="w-5 h-5"></i>
                <i x-show="darkMode" data-lucide="sun" class="w-5 h-5"></i>
            </button>
            <!-- 🔔 Notifikasi -->
            <div class="relative" x-data="{ openNotif: false }">
                <button @click="openNotif = !openNotif"
                        class="relative p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700">
                    <i data-lucide="bell" class="w-5 h-5"></i>

                    <!-- 🔴 Badge jumlah notifikasi -->
                    <span id="notifBellBadge"
                        class="absolute -top-1 -right-1 bg-red-500 text-white text-xs font-bold rounded-full w-4 h-4 flex items-center justify-center hidden">
                        0
                    </span>
                </button>

                <!-- 🧾 Dropdown daftar notifikasi -->
                <div x-show="openNotif" @click.away="openNotif = false"
                    x-transition
                    class="absolute right-0 mt-3 w-80 bg-white dark:bg-gray-800 rounded-lg shadow-xl z-50 overflow-hidden border border-gray-200 dark:border-gray-700">

                    <div class="px-4 py-2 border-b dark:border-gray-700 flex justify-between items-center">
                        <span class="font-semibold text-sm">Notifikasi</span>
                        <button id="clearNotif"
                                class="text-xs text-blue-500 hover:underline">Tandai Dibaca</button>
                    </div>

                    <ul id="notifList" class="max-h-80 overflow-y-auto divide-y dark:divide-gray-700">
                        <li class="text-center text-gray-400 text-sm py-3">Belum ada notifikasi</li>
                    </ul>
                </div>
            </div>
            <div class="relative" x-data="{ openProfile: false }">
                <button @click="openProfile = !openProfile" class="flex items-center space-x-2 focus:outline-none">
                    <img src="https://i.pravatar.cc/40" class="w-8 h-8 rounded-full" alt="profile">
                    <span class="hidden md:block font-medium">{{ auth()->user()->name }}</span>
                </button>
                <div x-show="openProfile" @click.away="openProfile = false"
                     x-transition
                     class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden z-50">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                                class="w-full text-left px-4 py-2 text-sm hover:bg-red-500 hover:text-white">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <div class="flex min-h-screen relative">
        <div class="fixed inset-0 bg-black bg-opacity-50 z-20 md:hidden"
             x-show="openSidebar"
             @click="openSidebar = false"
             x-transition.opacity>
        </div>
        <aside class="fixed top-0 left-0 w-64 h-screen bg-gradient-to-b from-gray-900 to-gray-800
            text-white p-5 z-30
            overflow-y-auto scrollbar-thin scrollbar-thumb-gray-700 scrollbar-track-gray-900
            transform md:translate-x-0 transition-all duration-300"
            :class="openSidebar ? 'translate-x-0' : '-translate-x-full'">

            <a href="{{ route('warehouse.dashboard') }}">
                <h1 class="text-2xl font-bold mb-8 hidden md:block hover:underline cursor-pointer">
                    Stockist Panel
                </h1>
            </a>
            <nav>
                <ul class="space-y-2">
                    <li>
                        <a href="{{ route('warehouse.dashboard') }}"
                           class="flex items-center gap-3 p-2 rounded-lg transition
                           {{ request()->routeIs('warehouse.dashboard.*') ? 'bg-gray-700 border-l-4 border-blue-500' : 'hover:bg-gray-700' }}">
                           <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                           Dashboard
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('warehouse.transactions.index') }}"
                           class="flex items-center gap-3 p-2 rounded-lg transition
                           {{ request()->routeIs('warehouse.transactions.*') ? 'bg-gray-700 border-l-4 border-blue-500' : 'hover:bg-gray-700' }}">
                           <i data-lucide="truck" class="w-5 h-5"></i>
                           Barang Keluar User
                        </a>
                    </li>
                    <li class="relative">
                        <a href="{{ route('warehouse.notice.index') }}"
                        class="flex items-center gap-3 p-2 rounded-lg transition relative
                        {{ request()->routeIs('warehouse.notice.*') ? 'bg-gray-700 border-l-4 border-blue-500' : 'hover:bg-gray-700' }}">
                        <i data-lucide="bell" class="w-5 h-5"></i>
                        <span>Notice</span>

                        <!-- 🔴 Badge Angka Notifikasi -->
                        <span id="noticeBadge"
                                class="absolute right-3 top-2 bg-red-500 text-white text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center hidden">
                                0
                        </span>
                        </a>
                    </li>
                    <li class="relative">
                        <a href="{{ route('warehouse.purchase_orders.index') }}"
                        class="flex items-center gap-3 p-2 rounded-lg transition relative
                        {{ request()->routeIs('warehouse.purchase_orders.*') ? 'bg-gray-700 border-l-4 border-blue-500' : 'hover:bg-gray-700' }}">
                        <i data-lucide="file-text" class="w-5 h-5"></i>
                        <span>Permintaan Barang</span>

                        <!-- 🔴 Badge Angka Notifikasi -->
                        <span id="permintaanBadge"
                                class="absolute right-3 top-2 bg-red-500 text-white text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center hidden">
                                0
                        </span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('warehouse.stocks.index') }}"
                           class="flex items-center gap-3 p-2 rounded-lg transition
                           {{ request()->routeIs('warehouse.stocks.*') ? 'bg-gray-700 border-l-4 border-blue-500' : 'hover:bg-gray-700' }}">
                           <i data-lucide="database" class="w-5 h-5"></i>
                           Stok Barang
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('tracking.index') }}"
                        class="flex items-center gap-3 p-2 rounded-lg transition
                        {{ request()->routeIs('tracking.*') ? 'bg-gray-700 border-l-4 border-blue-500' : 'hover:bg-gray-700' }}">
                        <i data-lucide="search" class="w-5 h-5"></i>
                        Track Resi
                        </a>
                    </li>
                </ul>
            </nav>
        </aside>
        <main class="flex-1 p-2 md:p-6 mt-16 md:ml-64">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 hover:shadow-xl transition">
                @yield('content')
            </div>
        </main>
    </div>

<script>
function darkModeHandler() {
    return {
        darkMode: false,
        init() {
            if (localStorage.getItem('darkMode') === null) {
                this.darkMode = window.matchMedia('(prefers-color-scheme: dark)').matches;
            } else {
                this.darkMode = localStorage.getItem('darkMode') === 'true';
            }
        },
        toggle() {
            this.darkMode = !this.darkMode;
            localStorage.setItem('darkMode', this.darkMode);
        }
    }
}
lucide.createIcons();
</script>

<script src="https://js.pusher.com/7.2/pusher.min.js"></script>
@vite('resources/js/app.js')
<script>
window.Laravel = {
    userId: {{ auth()->id() ?? 'null' }},
    csrfToken: '{{ csrf_token() }}'
};
</script>

<script>
const pusher = new Pusher("{{ env('PUSHER_APP_KEY') }}", {
    cluster: "{{ env('PUSHER_APP_CLUSTER') }}",
    encrypted: true
});

const warehouseId = "{{ auth()->user()->warehouse_id ?? '' }}";
const notifList = document.getElementById('notifList');
const notifBadge = document.getElementById('notifBellBadge');
const noticeBadge = document.getElementById('noticeBadge');
const permintaanBadge = document.getElementById('permintaanBadge');

// Ambil data dari localStorage
let notifications = JSON.parse(localStorage.getItem('notifications') || '[]');
let noticeCount = parseInt(localStorage.getItem('noticeCount') || '0');
let permintaanCount = parseInt(localStorage.getItem('permintaanCount') || '0');

updateNotifUI();
updateSidebarBadges();

// === Fungsi Update Dropdown Bell ===
function updateNotifUI() {
    notifList.innerHTML = '';
    if (notifications.length === 0) {
        notifList.innerHTML = '<li class="text-center text-gray-400 text-sm py-3">Belum ada notifikasi</li>';
        notifBadge.classList.add('hidden');
        return;
    }

    notifBadge.textContent = notifications.length;
    notifBadge.classList.remove('hidden');

    notifications.slice().reverse().forEach(item => {
        const li = document.createElement('li');
        li.className = "px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer";
        li.innerHTML = `
            <div class="flex flex-col">
                <span class="font-semibold text-sm">${item.title}</span>
                <span class="text-xs text-gray-500 dark:text-gray-400">${item.message}</span>
                <span class="text-[10px] text-gray-400 mt-1">${item.time}</span>
            </div>
        `;
        notifList.appendChild(li);
    });
}

// === Fungsi Update Badge Sidebar ===
function updateSidebarBadges() {
    if (noticeCount > 0) {
        noticeBadge.textContent = noticeCount;
        noticeBadge.classList.remove('hidden');
    } else {
        noticeBadge.classList.add('hidden');
    }

    if (permintaanCount > 0) {
        permintaanBadge.textContent = permintaanCount;
        permintaanBadge.classList.remove('hidden');
    } else {
        permintaanBadge.classList.add('hidden');
    }
}

// === Tangani Event dari Pusher ===
if (warehouseId) {
    const channel = pusher.subscribe(`warehouse.${warehouseId}`);

    channel.bind('notice.created', function(data) {
        const message = data.message ?? '';
        const title = data.title ?? 'Notice Baru';
        const time = new Date().toLocaleTimeString();

        // 🔔 Mainkan suara notifikasi
        new Audio('/sounds/notification.mp3').play();

        // 🔖 Tentukan kategori (Notice atau Permintaan)
        let categoryRaw = (data.category || '').toUpperCase();
        let category = (
            categoryRaw.includes('KIRIM')
                ? 'Permintaan Barang'
                : 'Notice'
        );

        // Simpan notifikasi ke localStorage
        notifications.push({
            title: `[${category}] ${title}`,
            message: message,
            time: time
        });
        localStorage.setItem('notifications', JSON.stringify(notifications));

        // Tambah counter
        if (category === 'Permintaan Barang') {
            permintaanCount++;
            localStorage.setItem('permintaanCount', permintaanCount);
        } else {
            noticeCount++;
            localStorage.setItem('noticeCount', noticeCount);
        }

        // Perbarui UI
        updateNotifUI();
        updateSidebarBadges();
    });
}

// === Fungsi untuk mark all as read (server optional) ===
function markAllAsRead() {
    fetch('/notices/mark-all', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': window.Laravel.csrfToken }
    }).then(() => console.log('✅ Semua notifikasi ditandai dibaca.'));
}

// === Tombol "Tandai Dibaca" di dropdown ===
document.getElementById('clearNotif').addEventListener('click', () => {
    notifications = [];
    localStorage.removeItem('notifications');

    // Reset badge semua
    noticeCount = 0;
    permintaanCount = 0;
    localStorage.removeItem('noticeCount');
    localStorage.removeItem('permintaanCount');

    updateSidebarBadges();
    updateNotifUI();
    markAllAsRead();
});

// === Reset badge saat menu Notice atau Permintaan diklik ===
document.querySelectorAll('a[href*="/warehouse/notice"]').forEach(link => {
    link.addEventListener('click', () => {
        noticeCount = 0;
        localStorage.removeItem('noticeCount');
        updateSidebarBadges();
        markAllAsRead();
    });
});

document.querySelectorAll('a[href*="/warehouse/purchase-orders"]').forEach(link => {
    link.addEventListener('click', () => {
        permintaanCount = 0;
        localStorage.removeItem('permintaanCount');
        updateSidebarBadges();
        markAllAsRead();
    });
});

// === Reset badge jika halaman dibuka langsung ===
document.addEventListener('DOMContentLoaded', () => {
    if (window.location.pathname.includes('/warehouse/notice')) {
        noticeCount = 0;
        localStorage.removeItem('noticeCount');
        updateSidebarBadges();
    }

    if (window.location.pathname.includes('/warehouse/purchase-orders')) {
        permintaanCount = 0;
        localStorage.removeItem('permintaanCount');
        updateSidebarBadges();
    }
});
</script>

</body>
</html>
