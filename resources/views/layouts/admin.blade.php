<!DOCTYPE html>
<html lang="en"
      x-data="{ ...darkModeHandler(), openSidebar: false }"
      x-bind:class="darkMode ? 'dark' : ''"
      x-init="init()">
<head>
    <meta charset="UTF-8">
    <title>Pusat - @yield('title')</title>

    <!-- ✅ Tambahkan jQuery sebelum Select2 -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- Tailwind -->
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

    <!-- Tambahkan stack agar @push('styles') bisa bekerja -->
    @stack('styles')
</head>
<body class="bg-gray-100 dark:bg-gray-900 font-sans text-gray-800 dark:text-gray-200">

    <header class="flex items-center justify-between bg-white dark:bg-gray-800 shadow px-4 py-3
                md:ml-64 fixed w-full md:w-[calc(100%-16rem)] z-40 transition-colors">

        <div class="flex items-center space-x-3 md:hidden">
            <button @click="openSidebar = !openSidebar"
                    class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                <i data-lucide="menu" class="w-6 h-6"></i>
            </button>
            <h1 class="text-lg font-bold">Pusat Panel</h1>
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
                    <a href="{{ route('profile.edit') }}"
                    class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700">Profile</a>
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

            <h1 class="text-2xl font-bold mb-8 hidden md:block">Pusat Panel</h1>
            <nav>
                <ul class="space-y-2">
                    @canView('dashboard')
                    <li>
                        <a href="{{ route('admin.dashboard') }}"
                           class="flex items-center gap-3 p-2 rounded-lg transition
                           {{ request()->routeIs('admin.dashboard.*') ? 'bg-gray-700 border-l-4 border-blue-500' : 'hover:bg-gray-700' }}">
                           <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                           Dashboard
                        </a>
                    </li>
                    @endcanView
                    @canView('warehouses')
                    <li>
                        <a href="{{ route('admin.warehouses.index') }}"
                           class="flex items-center gap-3 p-2 rounded-lg transition
                           {{ request()->routeIs('admin.warehouses.*') ? 'bg-gray-700 border-l-4 border-blue-500' : 'hover:bg-gray-700' }}">
                           <i data-lucide="building" class="w-5 h-5"></i>
                           Stockist
                        </a>
                    </li>
                    @endcanView
                    @canView('categories')
                    <li>
                        <a href="{{ route('admin.categories.index') }}"
                           class="flex items-center gap-3 p-2 rounded-lg transition
                           {{ request()->routeIs('admin.categories.*') ? 'bg-gray-700 border-l-4 border-blue-500' : 'hover:bg-gray-700' }}">
                           <i data-lucide="layers" class="w-5 h-5"></i>
                           Kategori
                        </a>
                    </li>
                    @endcanView
                    @canView('products')
                    <li>
                        <a href="{{ route('admin.products.index') }}"
                           class="flex items-center gap-3 p-2 rounded-lg transition
                           {{ request()->routeIs('admin.products.*') ? 'bg-gray-700 border-l-4 border-blue-500' : 'hover:bg-gray-700' }}">
                           <i data-lucide="package" class="w-5 h-5"></i>
                           Produk
                        </a>
                    </li>
                    @endcanView
                    @canView('purchase-orders')
                    <li>
                        <a href="{{ route('admin.purchase_orders.index') }}"
                           class="flex items-center gap-3 p-2 rounded-lg transition
                           {{ request()->routeIs('admin.purchase_orders.*') ? 'bg-gray-700 border-l-4 border-blue-500' : 'hover:bg-gray-700' }}">
                           <i data-lucide="file-text" class="w-5 h-5"></i>
                           PO Stockist
                        </a>
                    </li>
                    @endcanView
                    @canView('stocks')
                    <li>
                        <a href="{{ route('admin.stocks.index') }}"
                           class="flex items-center gap-3 p-2 rounded-lg transition
                           {{ request()->routeIs('admin.stocks.*') ? 'bg-gray-700 border-l-4 border-blue-500' : 'hover:bg-gray-700' }}">
                           <i data-lucide="database" class="w-5 h-5"></i>
                           Stok per Stockist
                        </a>
                    </li>
                    @endcanView
                    @canView('central_stocks')
                    <li>
                        <a href="{{ route('admin.central_stocks.index') }}"
                            class="flex items-center gap-3 p-2 rounded-lg transition
                            {{ request()->routeIs('admin.central_stocks.*') ? 'bg-gray-700 border-l-4 border-blue-500' : 'hover:bg-gray-700' }}">
                            <i data-lucide="database" class="w-5 h-5"></i>
                            Stok Pusat
                        </a>
                    </li>
                    @endcanView
                    @canView('reports')
                    <li>
                        <a href="{{ route('admin.reports.index') }}"
                           class="flex items-center gap-3 p-2 rounded-lg transition
                           {{ request()->routeIs('admin.reports.*') ? 'bg-gray-700 border-l-4 border-blue-500' : 'hover:bg-gray-700' }}">
                           <i data-lucide="truck" class="w-5 h-5"></i>
                           Barang Keluar Per Stockist
                        </a>
                    </li>
                    @endcanView
                    @canView('kirims')
                    <li>
                        <a href="{{ route('admin.kirims.index') }}"
                           class="flex items-center gap-3 p-2 rounded-lg transition
                           {{ request()->routeIs('admin.kirims.*') ? 'bg-gray-700 border-l-4 border-blue-500' : 'hover:bg-gray-700' }}">
                           <i data-lucide="truck" class="w-5 h-5"></i>
                           Kirim Barang Ke Stockist
                        </a>
                    </li>
                    @endcanView
                    @canView('transactions')
                    <li>
                        <a href="{{ route('admin.transactions.index') }}"
                           class="flex items-center gap-3 p-2 rounded-lg transition
                           {{ request()->routeIs('admin.transactions.*') ? 'bg-gray-700 border-l-4 border-blue-500' : 'hover:bg-gray-700' }}">
                           <i data-lucide="bell" class="w-5 h-5"></i>
                           Notice Ke Stokcis
                        </a>
                    </li>
                    @endcanView
                    @canView('deposits')
                    <li>
                        <a href="{{ route('admin.deposits.index') }}"
                        class="flex items-center gap-3 p-2 rounded-lg transition
                        {{ request()->routeIs('admin.deposits.*') ? 'bg-gray-700 border-l-4 border-blue-500' : 'hover:bg-gray-700' }}">
                        <i data-lucide="credit-card" class="w-5 h-5"></i>
                        Deposit
                        </a>
                    </li>
                    @endcanView
                    @canView('tracker')
                    <li>
                        <a href="{{ route('admin.tracker.index') }}"
                        class="flex items-center gap-3 p-2 rounded-lg transition
                        {{ request()->routeIs('admin.tracker.*') ? 'bg-gray-700 border-l-4 border-blue-500' : 'hover:bg-gray-700' }}">
                        <i data-lucide="search" class="w-5 h-5"></i>
                        Track Resi
                        </a>
                    </li>
                    @endcanView
                    @canView('user')
                    <li>
                        <a href="{{ route('admin.users.index') }}"
                           class="flex items-center gap-3 p-2 rounded-lg transition
                           {{ request()->routeIs('admin.users.*') ? 'bg-gray-700 border-l-4 border-blue-500' : 'hover:bg-gray-700' }}">
                           <i data-lucide="users" class="w-5 h-5"></i>
                           User
                        </a>
                    </li>
                    @endcanView
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
@stack('scripts')
</body>
</html>
