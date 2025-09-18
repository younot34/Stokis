@extends('layouts.admin')
@section('title','Tambah Stokis')
@section('content')

<div class="max-w-2xl mx-auto bg-white shadow-lg rounded-lg p-8">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">🏬 Tambah Stokis</h2>

    <form action="{{ route('admin.warehouses.store') }}" method="POST" class="space-y-5">
        @csrf

        <!-- Nama Stokis -->
        <div>
            <label for="name" class="block text-gray-700 font-medium mb-2">Nama Stokis</label>
            <input type="text" name="name" id="name"
                   required
                   class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 px-4 py-2 transition" />
        </div>

        <!-- Alamat -->
        <div>
            <label for="address" class="block text-gray-700 font-medium mb-2">Alamat</label>
            <input type="text" name="address" id="address"
                   class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 px-4 py-2 transition" />
        </div>

        <!-- Tombol Aksi -->
        <div class="flex items-center justify-between">
            <a href="{{ route('admin.warehouses.index') }}"
               class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg shadow transition">
                ⬅️ Kembali
            </a>
            <button type="submit"
                    class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg shadow transition">
                ✅ Simpan
            </button>
        </div>
    </form>
</div>

@endsection
