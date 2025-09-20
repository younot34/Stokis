@extends('layouts.admin')
@section('title','Produk')
@section('content')

<div class="space-y-8">

    <!-- Header -->
    <div class="bg-gradient-to-r from-indigo-500 to-purple-600 text-white rounded-xl p-6 shadow-lg">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <h2 class="text-2xl font-bold">📦 List Produk</h2>
            <a href="{{ route('admin.products.create') }}"
               class="mt-3 md:mt-0 inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg shadow transition">
                ➕ Tambah Produk
            </a>
        </div>
    </div>

    <!-- Card Tabel -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 hover:shadow-xl transition">
        <div class="overflow-x-auto">
            <table class="w-full border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
                <thead class="bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200">
                    <tr>
                        <th class="px-4 py-2 text-left">ID</th>
                        <th class="px-4 py-2 text-left">Kode</th>
                        <th class="px-4 py-2 text-left">Nama</th>
                        <th class="px-4 py-2 text-left">Kategori</th>
                        <th class="px-4 py-2 text-left">SubKategori</th>
                        <th class="px-4 py-2 text-left">Harga</th>
                        <th class="px-4 py-2 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
                    @forelse($products as $product)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                        <td class="px-4 py-2">{{ $product->id }}</td>
                        <td class="px-4 py-2 font-mono text-sm">{{ $product->code }}</td>
                        <td class="px-4 py-2">{{ $product->name }}</td>
                        <td class="px-4 py-2">{{ $product->parentCategory ? $product->parentCategory->name : '-' }}</td>
                        <td class="px-4 py-2">{{ $product->category ? $product->category->name : '-' }}</td>
                        <td class="px-4 py-2 font-semibold text-green-600 dark:text-green-400">
                            Rp {{ number_format($product->price,0,',','.') }}
                        </td>
                        <td class="px-4 py-2 text-center space-x-2">
                            <a href="{{ route('admin.products.edit',$product->id) }}"
                               class="inline-flex items-center px-3 py-1 bg-blue-500 hover:bg-blue-600 text-white rounded-lg shadow text-sm transition">
                                ✏️ Edit
                            </a>
                            <form action="{{ route('admin.products.destroy',$product->id) }}" method="POST" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit"
                                        onclick="return confirm('Hapus produk?')"
                                        class="inline-flex items-center px-3 py-1 bg-red-500 hover:bg-red-600 text-white rounded-lg shadow text-sm transition">
                                    🗑️ Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-4 py-6 text-center text-gray-500 dark:text-gray-400">
                            Tidak ada produk ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
