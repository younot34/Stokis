@extends('layouts.admin')
@section('title','Produk')
@section('content')

<div class="bg-white p-6 rounded-xl shadow-lg">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
        <h2 class="text-2xl font-bold text-gray-800">📦 List Produk</h2>
        <a href="{{ route('admin.products.create') }}"
           class="mt-3 md:mt-0 inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg shadow transition">
            ➕ Tambah Produk
        </a>
    </div>

    <!-- Tabel Produk -->
    <div class="overflow-x-auto">
        <table class="w-full border border-gray-200 rounded-lg overflow-hidden">
            <thead class="bg-gray-100 text-gray-700">
                <tr>
                    <th class="px-4 py-2 text-left">ID</th>
                    <th class="px-4 py-2 text-left">Code</th>
                    <th class="px-4 py-2 text-left">Nama</th>
                    <th class="px-4 py-2 text-left">Kategori</th>
                    <th class="px-4 py-2 text-left">SubKategori</th>
                    <th class="px-4 py-2 text-left">Harga</th>
                    <th class="px-4 py-2 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($products as $product)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-4 py-2">{{ $product->id }}</td>
                    <td class="px-4 py-2 font-mono text-sm">{{ $product->code }}</td>
                    <td class="px-4 py-2">{{ $product->name }}</td>
                    <td class="px-4 py-2">{{ $product->parentCategory ? $product->parentCategory->name : '-' }}</td>
                    <td class="px-4 py-2">{{ $product->category ? $product->category->name : '-' }}</td>
                    <td class="px-4 py-2 font-semibold text-green-600">
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
                    <td colspan="7" class="px-4 py-6 text-center text-gray-500">Tidak ada produk ditemukan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
