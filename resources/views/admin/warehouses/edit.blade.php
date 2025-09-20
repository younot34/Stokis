@extends('layouts.admin')
@section('title','Edit Stokis')

@section('content')
<div class="max-w-2xl mx-auto bg-white dark:bg-gray-800 shadow-lg rounded-xl p-8">
    <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100 mb-6">✏️ Edit Stokis</h2>

    <form action="{{ route('admin.warehouses.update', $warehouse->id) }}" method="POST" class="space-y-5">
        @csrf
        @method('PUT')

        <!-- Nama Stokis -->
        <div>
            <label for="name" class="block text-gray-700 dark:text-gray-300 font-medium mb-2">Nama Stokis</label>
            <input type="text" name="name" id="name"
                   value="{{ old('name', $warehouse->name) }}"
                   required
                   class="w-full rounded-lg border border-gray-300 dark:border-gray-600
                          bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100
                          shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 px-4 py-2 transition" />
        </div>

        <!-- Alamat -->
        <div>
            <label for="address" class="block text-gray-700 dark:text-gray-300 font-medium mb-2">Alamat</label>
            <input type="text" name="address" id="address"
                   value="{{ old('address', $warehouse->address) }}"
                   class="w-full rounded-lg border border-gray-300 dark:border-gray-600
                          bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100
                          shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 px-4 py-2 transition" />
        </div>

        <!-- Edit Stokis -->
        <div>
            <label for="province" class="block text-gray-700 dark:text-gray-300 font-medium mb-2">Provinsi</label>
            <input type="text" name="province" id="province"
                value="{{ old('province', $warehouse->province) }}"
                class="w-full rounded-lg border border-gray-300 dark:border-gray-600
                        bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100
                        shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 px-4 py-2 transition" />
        </div>

        <div>
            <label for="city" class="block text-gray-700 dark:text-gray-300 font-medium mb-2">Kota / Kabupaten</label>
            <input type="text" name="city" id="city"
                value="{{ old('city', $warehouse->city) }}"
                class="w-full rounded-lg border border-gray-300 dark:border-gray-600
                        bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100
                        shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 px-4 py-2 transition" />
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
