@extends('layouts.admin')
@section('title','Tambah Stokis')

@section('content')
<div class="max-w-2xl mx-auto bg-white dark:bg-gray-800 shadow-lg rounded-xl p-8">
    <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100 mb-6">🏬 Tambah Stockist</h2>

    <form action="{{ route('admin.warehouses.store') }}" method="POST" class="space-y-5">
        @csrf
        <div>
            <label for="name" class="block text-gray-700 dark:text-gray-300 font-medium mb-2">Nama Stockist</label>
            <input type="text" name="name" id="name" required
                   class="w-full rounded-lg border border-gray-300 dark:border-gray-600
                          bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100
                          shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 px-4 py-2 transition" />
        </div>
        <div>
            <label for="address" class="block text-gray-700 dark:text-gray-300 font-medium mb-2">Alamat</label>
            <input type="text" name="address" id="address"
                   class="w-full rounded-lg border border-gray-300 dark:border-gray-600
                          bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100
                          shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 px-4 py-2 transition" />
        </div>
        <div>
            <label for="province" class="block text-gray-700 dark:text-gray-300 font-medium mb-2">Provinsi</label>
            <input type="text" name="province" id="province"
                class="w-full rounded-lg border border-gray-300 dark:border-gray-600
                        bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100
                        shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 px-4 py-2 transition" />
        </div>

        <div>
            <label for="city" class="block text-gray-700 dark:text-gray-300 font-medium mb-2">Kota / Kabupaten</label>
            <input type="text" name="city" id="city"
                class="w-full rounded-lg border border-gray-300 dark:border-gray-600
                        bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100
                        shadow-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 px-4 py-2 transition" />
        </div>
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
