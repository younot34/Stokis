@extends('layouts.warehouse')
@section('title','Stok stokis')

@section('content')
<h2 class="text-2xl font-bold mb-5">Stok stokis</h2>

<div class="overflow-x-auto bg-white shadow rounded">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kode</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Produk</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kategori</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Subkategori</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Harga</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jumlah Stok</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($products as $product)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 font-medium text-gray-800">{{ $product->code }}</td>
                    <td class="px-6 py-4 font-medium text-gray-800">{{ $product->name }}</td>
                    <td class="px-6 py-4">{{ $product->parentCategory->name ?? '-' }}</td>
                    <td class="px-6 py-4">{{ $product->category->name ?? '-' }}</td>
                    <td class="px-6 py-4">{{ number_format($product->price, 0, ',', '.') }}</td>
                    <td class="px-6 py-4">{{ $product->pivot->quantity }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center py-4 text-gray-500">Belum ada stok di stokis ini</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
