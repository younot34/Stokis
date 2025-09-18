@extends('layouts.admin')
@section('title','Stokis')
@section('content')

<div class="flex items-center justify-between mb-6">
    <h2 class="text-2xl font-bold text-gray-800">📦 Daftar Stokis</h2>
    <a href="{{ route('admin.warehouses.create') }}"
       class="inline-flex items-center bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg shadow transition">
        ➕ Tambah Stokis
    </a>
</div>

<div class="overflow-x-auto bg-white shadow-lg rounded-lg">
    <table class="w-full border-collapse">
        <thead>
            <tr class="bg-gradient-to-r from-gray-100 to-gray-200 text-gray-700 uppercase text-sm">
                <th class="px-6 py-3 text-left">ID</th>
                <th class="px-6 py-3 text-left">Nama</th>
                <th class="px-6 py-3 text-left">Alamat</th>
                <th class="px-6 py-3 text-center">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @foreach($warehouses as $warehouse)
            <tr class="hover:bg-gray-50 transition">
                <td class="px-6 py-4 font-medium text-gray-800">{{ $warehouse->id }}</td>
                <td class="px-6 py-4">{{ $warehouse->name }}</td>
                <td class="px-6 py-4">{{ $warehouse->address }}</td>
                <td class="px-6 py-4 text-center space-x-2">
                    <a href="{{ route('admin.warehouses.edit',$warehouse->id) }}"
                       class="inline-block bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-lg shadow transition">
                        ✏️ Edit
                    </a>
                    <form action="{{ route('admin.warehouses.destroy',$warehouse->id) }}" method="POST" class="inline">
                        @csrf @method('DELETE')
                        <button class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-lg shadow transition"
                                onclick="return confirm('Hapus Stokis?')">
                            🗑 Hapus
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@endsection
