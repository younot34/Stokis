@extends('layouts.admin')
@section('title','Edit User')
@section('content')

<div class="max-w-2xl mx-auto bg-white p-8 rounded-xl shadow-lg">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">✏️ Edit User</h2>

    <form action="{{ route('admin.users.update',$user->id) }}" method="POST" class="space-y-5">
        @csrf
        @method('PUT')

        <!-- Nama -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nama</label>
            <input type="text" name="name" value="{{ $user->name }}" required
                   class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none">
        </div>

        <!-- Email -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <input type="email" name="email" value="{{ $user->email }}" required
                   class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none">
        </div>

        <!-- Password -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Password <span class="text-gray-400 text-xs">(kosongkan jika tidak diubah)</span></label>
            <input type="password" name="password"
                   class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none">
        </div>

        <!-- Role -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
            <select name="role" required
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none">
                <option value="admin" {{ $user->role=='admin'?'selected':'' }}>Admin</option>
                <option value="stokis" {{ $user->role=='stokis'?'selected':'' }}>stokis</option>
            </select>
        </div>

        <!-- stokis -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">stokis <span class="text-gray-400 text-xs">(jika role stokis)</span></label>
            <select name="warehouse_id"
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none">
                <option value="">-- Pilih stokis --</option>
                @foreach($warehouses as $w)
                    <option value="{{ $w->id }}" {{ $user->warehouse_id==$w->id?'selected':'' }}>
                        {{ $w->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Tombol Aksi -->
        <div class="flex justify-end space-x-3">
            <a href="{{ route('admin.users.index') }}"
               class="px-5 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg transition">
                ← Kembali
            </a>
            <button type="submit"
                    class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow transition">
                💾 Update
            </button>
        </div>
    </form>
</div>

@endsection
