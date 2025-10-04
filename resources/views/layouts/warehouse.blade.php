<!DOCTYPE html>
<html lang="en"
      x-data="{ ...darkModeHandler(), openSidebar: false }"
      x-bind:class="darkMode ? 'dark' : ''"
      x-init="init()">
<head>
    <meta charset="UTF-8">
    <title>Stokis - @yield('title')</title>
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
                Stokis Panel
            </a>
        </div>

        <div class="flex items-center space-x-4 ml-auto">
            <button @click="toggle()"
                    class="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700">
                <i x-show="!darkMode" data-lucide="moon" class="w-5 h-5"></i>
                <i x-show="darkMode" data-lucide="sun" class="w-5 h-5"></i>
            </button>
            <button class="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700">
                <i data-lucide="bell" class="w-5 h-5"></i>
            </button>
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
                    Stokis Panel
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
                    <li>
                        <a href="{{ route('warehouse.purchase_orders.index') }}"
                           class="flex items-center gap-3 p-2 rounded-lg transition
                           {{ request()->routeIs('warehouse.purchase_orders.*') ? 'bg-gray-700 border-l-4 border-blue-500' : 'hover:bg-gray-700' }}">
                           <i data-lucide="file-text" class="w-5 h-5"></i>
                           Permintaan Barang
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
<script src="{{ mix('js/app.js') }}"></script>
<script>
    // Inisialisasi Pusher (harus cocok dengan .env kamu)
    Pusher.logToConsole = false;

    const pusher = new Pusher("{{ env('PUSHER_APP_KEY') }}", {
        cluster: "{{ env('PUSHER_APP_CLUSTER') }}",
        encrypted: true
    });

    // Channel sesuai warehouse_id user login
    const warehouseId = "{{ auth()->user()->warehouse_id ?? '' }}";

    if (warehouseId) {
        // Ambil ID user login
        const userId = "{{ auth()->id() }}";

        if (userId) {
            const channel = pusher.subscribe(`private-App.Models.User.${userId}`);

            channel.bind('Illuminate\\Notifications\\Events\\BroadcastNotificationCreated', function (data) {
                // Ambil pesan dari notifikasi
                const message = data.notification.message;

                // 🔔 Mainkan suara notifikasi
                const audio = new Audio('/sounds/notification.mp3');
                audio.play();

                // 🔔 Tampilkan alert
                alert('📦 ' + message);

                // 🔢 Tambah counter badge notifikasi
                const badge = document.getElementById('noticeBadge');
                let count = parseInt(localStorage.getItem('noticeCount') || '0') + 1;
                badge.textContent = count;
                badge.classList.remove('hidden');
                localStorage.setItem('noticeCount', count);
            });
        }
    }
</script>
<script>
    const badge = document.getElementById('noticeBadge');
    let count = 0;

    if (warehouseId) {
        const channel = pusher.subscribe(`warehouse.${warehouseId}`);

        channel.bind('kirim-barang-event', function (data) {
            // 🔔 Mainkan suara notifikasi
            const audio = new Audio('/sounds/notification.mp3');
            audio.play();

            // 🔔 Tampilkan alert sederhana
            alert('📦 Kiriman baru: ' + data.message);

            // 🔢 Tambahkan counter notifikasi
            count++;
            badge.textContent = count;
            badge.classList.remove('hidden');

            // (Opsional) Simpan jumlah notifikasi di localStorage agar tetap ada setelah reload
            localStorage.setItem('noticeCount', count);
        });
    }

    // Saat halaman load, ambil jumlah notifikasi sebelumnya
    document.addEventListener('DOMContentLoaded', () => {
        const saved = localStorage.getItem('noticeCount');
        if (saved && parseInt(saved) > 0) {
            count = parseInt(saved);
            badge.textContent = count;
            badge.classList.remove('hidden');
        }
    });

    // Reset badge jika user membuka halaman notice
    if (window.location.href.includes('/warehouse/notice')) {
        localStorage.removeItem('noticeCount');
        if (badge) badge.classList.add('hidden');
    }
</script>
</body>
</html>
