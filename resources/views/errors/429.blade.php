<x-app-layout>
    <div class="flex flex-col items-center justify-center min-h-screen bg-gray-100 dark:bg-gray-900">
        <h1 class="text-6xl font-bold text-purple-600 mb-4">429</h1>
        <p class="text-lg text-gray-700 dark:text-gray-300 mb-6">
            Terlalu banyak permintaan. Silakan coba lagi beberapa saat.
        </p>
        <a href="{{ url()->previous() }}" class="text-blue-500 hover:underline">Kembali</a>
    </div>
</x-app-layout>
