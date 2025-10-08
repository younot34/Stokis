@extends('layouts.admin')
@section('title','Edit User')
@section('content')

<div class="max-w-2xl mx-auto bg-white dark:bg-gray-800 p-8 rounded-xl shadow-lg">
    <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100 mb-6">✏️ Edit User</h2>

    <form action="{{ route('admin.users.update',$user->id) }}" method="POST" class="space-y-5">
        @csrf
        @method('PUT')
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Nama</label>
            <input type="text" name="name" value="{{ $user->name }}" required
                   class="w-full px-4 py-2 border border-gray-400 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Email</label>
            <input type="email" name="email" value="{{ $user->email }}" required
                   class="w-full px-4 py-2 border border-gray-400 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
                Password <span class="text-gray-400 dark:text-gray-500 text-xs">(kosongkan jika tidak diubah)</span>
            </label>
            <input type="password" name="password"
                   class="w-full px-4 py-2 border border-gray-400 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Role</label>
            <select id="roleSelect" name="role" required
                    class="w-full px-4 py-2 border border-gray-400 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none">
                <option value="admin" {{ $user->role=='admin'?'selected':'' }}>Super Admin</option>
                <option value="adminsecond" {{ $user->role=='adminsecond'?'selected':'' }}>Admin</option>
                <option value="stokis" {{ $user->role=='stokis'?'selected':'' }}>Stockist</option>
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
                Stockist <span class="text-gray-400 dark:text-gray-500 text-xs">(jika role Stockist)</span>
            </label>
            <select name="warehouse_id"
                    class="w-full px-4 py-2 border border-gray-400 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none">
                <option value="">-- Pilih Stockist --</option>
                @foreach($warehouses as $w)
                    <option value="{{ $w->id }}" {{ $user->warehouse_id==$w->id?'selected':'' }}>
                        {{ $w->name }}
                    </option>
                @endforeach
            </select>
        </div>
        {{-- PERMISSIONS --}}
        <div id="permissionsSection" class="{{ $user->role=='adminsecond' ? '' : 'hidden' }}">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                Pilih Permission Akses
            </label>
            <div class="grid grid-cols-2 gap-2">
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
                    $userPermissions = $user->permissions->pluck('permission')->toArray();
                @endphp
                @foreach($permissions as $key => $label)
                    <label class="flex items-center space-x-2">
                        <input type="checkbox" name="permissions[]" value="{{ $key }}"
                               {{ in_array($key,$userPermissions) ? 'checked' : '' }}
                               class="rounded border-gray-400 dark:border-gray-600">
                        <span>{{ $label }}</span>
                    </label>
                @endforeach
            </div>
        </div>
        <div class="flex justify-end space-x-3">
            <a href="{{ route('admin.users.index') }}"
               class="px-5 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-gray-200 rounded-lg transition">
                ← Kembali
            </a>
            <button type="submit"
                    class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow transition">
                💾 Update
            </button>
        </div>
    </form>
</div>
<script>
document.getElementById('roleSelect').addEventListener('change', function() {
    const permSection = document.getElementById('permissionsSection');
    if (this.value === 'adminsecond') {
        permSection.classList.remove('hidden');
    } else {
        permSection.classList.add('hidden');
    }
});
</script>
@endsection
