@extends('layouts.admin')
@section('title','Edit Stokis')
@section('content')

<div class="max-w-2xl mx-auto bg-white shadow-lg rounded-lg p-8">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">✏️ Edit Stokis</h2>

    <form action="{{ route('admin.warehouses.update', $warehouse->id) }}" method="POST" class="space-y-5">
        @csrf
        @method('PUT')

        <!-- Nama Stokis -->
        <div>
            <label for="name" class="block text-gray-700 font-medium mb-2">Nama Stokis</label>
            <input type="text" name="name" id="name"
                   value="{{ old('name', $warehouse->name) }}"
                   required
                   class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 px-4 py-2 transition" />
        </div>

        <!-- Alamat -->
        <div>
            <label for="address" class="block text-gray-700 font-medium mb-2">Alamat</label>
            <input type="text" name="address" id="address"
                   value="{{ old('address', $warehouse->address) }}"
                   class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 px-4 py-2 transition" />
        </div>

        <!-- Tombol Aksi -->
        <div class="flex items-center justify-between">
            <a href="{{ route('admin.warehouses.index') }}"
               class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg shadow transition">
                ⬅️ Kembali
            </a>
            <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg shadow transition">
                💾 Update
            </button>
        </div>
    </form>
</div>

@endsection
