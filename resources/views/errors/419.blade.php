<x-app-layout>
    <div class="flex flex-col items-center justify-center min-h-screen bg-gray-100 dark:bg-gray-900">
        <h1 class="text-6xl font-bold text-orange-500 mb-4">419</h1>
        <p class="text-lg text-gray-700 dark:text-gray-300 mb-6">
            Sesi Anda telah berakhir. Silakan refresh halaman atau login ulang.
        </p>
        <a href="{{ route('login') }}" class="text-blue-500 hover:underline">Login Ulang</a>
    </div>
</x-app-layout>
