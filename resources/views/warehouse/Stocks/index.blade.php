@extends('layouts.warehouse')
@section('title','Stok stokis')

@section('content')
<h2 class="text-2xl font-bold mb-5 text-gray-900 dark:text-gray-100">Stok stokis</h2>

<div class="overflow-x-auto bg-white dark:bg-gray-800 shadow rounded">
    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">
        <thead class="bg-gray-200 dark:bg-gray-700">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Kode</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Produk</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Kategori</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Subkategori</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Harga</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Jumlah Stok</th>
            </tr>
        </thead>
        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-400 dark:divide-gray-600">
            @forelse($products as $product)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                    <td class="px-6 py-4 font-medium text-gray-800 dark:text-gray-100">{{ $product->code }}</td>
                    <td class="px-6 py-4 font-medium text-gray-800 dark:text-gray-100">{{ $product->name }}</td>
                    <td class="px-6 py-4 text-gray-700 dark:text-gray-300">{{ $product->parentCategory->name ?? '-' }}</td>
                    <td class="px-6 py-4 text-gray-700 dark:text-gray-300">{{ $product->category->name ?? '-' }}</td>
                    <td class="px-6 py-4 text-gray-700 dark:text-gray-300">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                    <td class="px-6 py-4 text-gray-700 dark:text-gray-300">{{ $product->pivot->quantity }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center py-4 text-gray-500 dark:text-gray-400">Belum ada stok di stokis ini</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
