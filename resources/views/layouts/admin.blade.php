<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin - @yield('title')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/alpinejs" defer></script>
    <link href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.bootstrap5.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
</head>
<body class="bg-gray-100 font-sans" x-data="{ openSidebar: false }">

    <!-- Navbar (mobile only) -->
    <header class="flex items-center justify-between bg-gray-800 text-white px-4 py-3 md:hidden">
        <h1 class="text-lg font-bold">Admin Panel</h1>
        <button @click="openSidebar = !openSidebar" class="focus:outline-none">
            <!-- Hamburger icon -->
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>
    </header>

    <div class="flex min-h-screen relative">

        <!-- Overlay (mobile only) -->
        <div
            class="fixed inset-0 bg-black bg-opacity-50 z-20 md:hidden"
            x-show="openSidebar"
            @click="openSidebar = false"
            x-transition.opacity>
        </div>

        <!-- Sidebar -->
        <aside
            class="fixed md:static top-0 left-0 w-64 h-screen bg-gradient-to-b from-gray-900 to-gray-800 text-white p-5 transform md:translate-x-0 transition-transform duration-200 z-30"
            :class="openSidebar ? 'translate-x-0' : '-translate-x-full'">

            <h1 class="text-2xl font-bold mb-8 hidden md:block">Admin Panel</h1>
            <nav>
                <ul class="space-y-2">
                    <li>
                        <a href="{{ route('admin.dashboard') }}"
                           class="block p-2 rounded
                           {{ request()->routeIs('admin.dashboard') ? 'bg-gray-700 text-white' : 'hover:bg-gray-700' }}">
                           Dashboard
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('admin.warehouses.index') }}"
                           class="block p-2 rounded
                           {{ request()->routeIs('admin.warehouses.*') ? 'bg-gray-700 text-white' : 'hover:bg-gray-700' }}">
                           Stokis
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('admin.categories.index') }}"
                           class="block p-2 rounded
                           {{ request()->routeIs('admin.categories.*') ? 'bg-gray-700 text-white' : 'hover:bg-gray-700' }}">
                           Kategori
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('admin.products.index') }}"
                           class="block p-2 rounded
                           {{ request()->routeIs('admin.products.*') ? 'bg-gray-700 text-white' : 'hover:bg-gray-700' }}">
                           Produk
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('admin.purchase_orders.index') }}"
                           class="block p-2 rounded
                           {{ request()->routeIs('admin.purchase_orders.*') ? 'bg-gray-700 text-white' : 'hover:bg-gray-700' }}">
                           PO Stokis
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('admin.stocks.index') }}"
                           class="block p-2 rounded
                           {{ request()->routeIs('admin.stocks.*') ? 'bg-gray-700 text-white' : 'hover:bg-gray-700' }}">
                           Stok
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('admin.reports.outgoing') }}"
                           class="block p-2 rounded
                           {{ request()->routeIs('admin.reports.*') ? 'bg-gray-700 text-white' : 'hover:bg-gray-700' }}">
                           Barang keluar
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('admin.users.index') }}"
                           class="block p-2 rounded
                           {{ request()->routeIs('admin.users.*') ? 'bg-gray-700 text-white' : 'hover:bg-gray-700' }}">
                           User
                        </a>
                    </li>
                </ul>
            </nav>

            <!-- Profile & Logout -->
            <div class="mt-10 border-t border-gray-700 pt-5">
                <p class="mb-3">👋 Hai, {{ auth()->user()->name }}</p>
                <a href="{{ route('profile.edit') }}" class="block py-2 px-3 bg-gray-700 hover:bg-gray-600 rounded mb-2">Profile</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full py-2 px-3 bg-red-600 hover:bg-red-500 rounded">Logout</button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-6 md:ml-0 mt-14 md:mt-0">
            <div class="bg-white rounded-xl shadow p-6">
                @yield('content')
            </div>
        </main>
    </div>

</body>
</html>
