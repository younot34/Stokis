@extends('layouts.admin')
@section('title','User')
@section('content')

<div class="flex items-center justify-between mb-6">
    <h2 class="text-2xl font-bold text-gray-800">👥 List User</h2>
    <a href="{{ route('admin.users.create') }}"
       class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded-lg shadow transition">
       ➕ Tambah User
    </a>
</div>

<div class="bg-white shadow-lg rounded-lg overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm text-left">
            <thead class="bg-gray-100 text-gray-700 uppercase text-sm">
                <tr>
                    <th class="px-6 py-3">ID</th>
                    <th class="px-6 py-3">Nama</th>
                    <th class="px-6 py-3">Email</th>
                    <th class="px-6 py-3">Role</th>
                    <th class="px-6 py-3">Stokis</th>
                    <th class="px-6 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($users as $user)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-3">{{ $user->id }}</td>
                    <td class="px-6 py-3 font-medium text-gray-800">{{ $user->name }}</td>
                    <td class="px-6 py-3">{{ $user->email }}</td>
                    <td class="px-6 py-3">
                        <span class="px-2 py-1 rounded text-xs font-semibold
                            @if($user->role === 'admin') bg-purple-100 text-purple-700
                            @elseif($user->role === 'stokis') bg-blue-100 text-blue-700
                            @else bg-gray-100 text-gray-700 @endif">
                            {{ ucfirst($user->role) }}
                        </span>
                    </td>
                    <td class="px-6 py-3">{{ $user->warehouse ? $user->warehouse->name : '-' }}</td>
                    <td class="px-6 py-3 text-center space-x-2">
                        <a href="{{ route('admin.users.edit',$user->id) }}"
                           class="inline-block bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-lg shadow transition">
                           ✏️ Edit
                        </a>
                        <form action="{{ route('admin.users.destroy',$user->id) }}" method="POST" class="inline">
                            @csrf @method('DELETE')
                            <button type="submit"
                                    onclick="return confirm('Hapus user?')"
                                    class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-lg shadow transition">
                                🗑️ Hapus
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">Belum ada user.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
