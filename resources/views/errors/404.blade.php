<x-app-layout>
    <div class="flex flex-col items-center justify-center min-h-screen bg-gray-100 dark:bg-gray-900">
        <h1 class="text-6xl font-bold text-yellow-500 mb-4">404</h1>
        <p class="text-lg text-gray-700 dark:text-gray-300 mb-6">
            Halaman yang Anda cari tidak ditemukan.
        </p>
        <a href="{{ url('/') }}" class="text-blue-500 hover:underline">Kembali ke Beranda</a>
    </div>
</x-app-layout>
