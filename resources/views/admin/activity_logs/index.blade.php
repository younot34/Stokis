@extends('layouts.admin')
@section('title', 'Activity Log')

@section('content')
<h1 class="text-2xl font-bold mb-4">Activity Log</h1>

<!-- Filter Form -->
<form method="GET" class="flex flex-wrap gap-4 mb-4 items-end">
    <div class="flex flex-col">
        <label for="user_id" class="text-gray-700 dark:text-gray-300 mb-1 text-sm">Nama</label>
        <select name="user_id" id="user_id" class="border rounded p-2 dark:bg-gray-900 dark:border-gray-700 dark:text-gray-200">
            <option value="">All Users</option>
            @foreach($users as $user)
            <option value="{{ $user->id }}" @selected(request('user_id') == $user->id)>{{ $user->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="flex flex-col">
        <label for="module" class="text-gray-700 dark:text-gray-300 mb-1 text-sm">Menu</label>
        <select name="module" id="module" class="border rounded p-2 dark:bg-gray-900 dark:border-gray-700 dark:text-gray-200">
            <option value="">All Modules</option>
            @foreach($modules as $module)
            <option value="{{ $module }}" @selected(request('module') == $module)>{{ ucfirst($module) }}</option>
            @endforeach
        </select>
    </div>

    <div class="flex flex-col">
        <label for="date_from" class="text-gray-700 dark:text-gray-300 mb-1 text-sm">Dari Tanggal</label>
        <input type="text" name="date_from" id="date_from" value="{{ request('date_from') }}" class="border rounded p-2 dark:bg-gray-900 dark:border-gray-700 dark:text-gray-200" placeholder="dd-mm-yyyy">
    </div>

    <div class="flex flex-col">
        <label for="date_to" class="text-gray-700 dark:text-gray-300 mb-1 text-sm">Sampai Tanggal</label>
        <input type="text" name="date_to" id="date_to" value="{{ request('date_to') }}" class="border rounded p-2 dark:bg-gray-900 dark:border-gray-700 dark:text-gray-200" placeholder="dd-mm-yyyy">
    </div>

    <div class="flex flex-col">
        <label for="search" class="text-gray-700 dark:text-gray-300 mb-1 text-sm">Aktivitas</label>
        <input type="text" name="search" id="search" value="{{ request('search') }}" class="border rounded p-2 dark:bg-gray-900 dark:border-gray-700 dark:text-gray-200" placeholder="Search description">
    </div>

    <div class="flex flex-col">
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition mt-1">Filter</button>
    </div>
</form>

<!-- Activity Log Table -->
<div class="overflow-x-auto border border-gray-200 dark:border-gray-700 rounded-lg shadow">
    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
        <thead class="bg-gray-100 dark:bg-gray-800">
            <tr>
                <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 dark:text-gray-300">Nama</th>
                <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 dark:text-gray-300">Menu</th>
                <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 dark:text-gray-300">Aktivitas</th>
                <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 dark:text-gray-300">Tanggal</th>
            </tr>
        </thead>
        <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
            @forelse($logs as $log)
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                <td class="px-4 py-2">{{ $log->causer->name ?? 'System' }}</td>
                <td class="px-4 py-2">{{ $log->log_name }}</td>
                <td class="px-4 py-2">{{ $log->description }}</td>
                <td class="px-4 py-2">{{ $log->created_at->format('d-m-Y H:i') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="px-4 py-2 text-center text-gray-500 dark:text-gray-400">No activity found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination -->
<div class="mt-4">
    {{ $logs->links() }}
</div>

@endsection

@section('scripts')
<!-- Flatpickr -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/dark.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    flatpickr("#date_from", {
        dateFormat: "d-m-Y",
        defaultDate: "{{ request('date_from') }}",
        allowInput: true, // tetap bisa ketik manual
    });

    flatpickr("#date_to", {
        dateFormat: "d-m-Y",
        defaultDate: "{{ request('date_to') }}",
        allowInput: true,
    });
});
</script>
@endsection
