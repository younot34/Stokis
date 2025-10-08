@extends('layouts.admin')
@section('title','Tambah User')
@section('content')

<div class="max-w-2xl mx-auto bg-white dark:bg-gray-800 p-8 rounded-xl shadow-lg">
    <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100 mb-6">➕ Tambah User</h2>

    <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-5">
        @csrf
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Nama</label>
            <input type="text" name="name" required
                   class="w-full px-4 py-2 border border-gray-400 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-green-400 focus:outline-none">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Email</label>
            <input type="email" name="email" required
                   class="w-full px-4 py-2 border border-gray-400 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-green-400 focus:outline-none">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Password</label>
            <input type="password" name="password" required
                   class="w-full px-4 py-2 border border-gray-400 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-green-400 focus:outline-none">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Role</label>
            <select name="role" required
                    class="w-full px-4 py-2 border border-gray-400 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-green-400 focus:outline-none">
                <option value="admin">Super Admin</option>
                <option value="adminsecond">Admin</option>
                <option value="stokis">Stockist</option>
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
                Stockist <span class="text-gray-400 dark:text-gray-500 text-xs">(jika role Stockist)</span>
            </label>
            <select name="warehouse_id"
                    class="w-full px-4 py-2 border border-gray-400 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-green-400 focus:outline-none">
                <option value="">-- Pilih Stockist --</option>
                @foreach($warehouses as $w)
                    <option value="{{ $w->id }}">{{ $w->name }}</option>
                @endforeach
            </select>
        </div>
        {{-- PERMISSIONS (hanya muncul jika role = adminsecond) --}}
        <div id="permissionsSection" class="hidden">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                Pilih Permission Akses
            </label>

            @php
                $permissions = [
                    'dashboard' => 'Dashboard',
                    'warehouses' => 'Stockist',
                    'categories' => 'Kategori',
                    'products' => 'Produk',
                    'purchase_orders' => 'PO Stockist',
                    'stocks' => 'Stok per Stockist',
                    'central_stocks' => 'Stok Pusat',
                    'reports' => 'Laporan',
                    'kirims' => 'Kirim Barang',
                    'transactions' => 'Notice',
                    'deposits' => 'Deposit',
                    'tracker' => 'Tracking Resi',
                    'users' => 'Manajemen User'
                ];

                $crudActions = [
                    'view' => 'Lihat',
                    'create' => 'Tambah',
                    'edit' => 'Edit',
                    'delete' => 'Hapus'
                ];
            @endphp

            <div class="space-y-3">
                @foreach($permissions as $key => $label)
                    <div class="border dark:border-gray-700 p-3 rounded-lg">
                        <p class="font-semibold mb-2">{{ $label }}</p>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                            @foreach($crudActions as $actionKey => $actionLabel)
                                <label class="flex items-center space-x-2">
                                    <input type="checkbox" name="permissions[]" value="{{ $key . '.' . $actionKey }}" class="rounded border-gray-400 dark:border-gray-600">
                                    <span class="text-sm">{{ $actionLabel }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="flex justify-end space-x-3">
            <a href="{{ route('admin.users.index') }}"
               class="px-5 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-gray-200 rounded-lg transition">
                ← Kembali
            </a>
            <button type="submit"
                    class="px-5 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg shadow transition">
                💾 Simpan
            </button>
        </div>
    </form>
</div>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const roleSelect = document.querySelector('select[name="role"]');
    const permSection = document.getElementById('permissionsSection');

    function togglePermissions() {
        if (roleSelect.value === 'adminsecond') {
            permSection.classList.remove('hidden');
        } else {
            permSection.classList.add('hidden');
        }
    }

    roleSelect.addEventListener('change', togglePermissions);
    togglePermissions(); // tampilkan jika edit form adminsecond
});
</script>

@endsection
