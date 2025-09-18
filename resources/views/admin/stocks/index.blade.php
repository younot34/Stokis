@extends('layouts.admin')
@section('title','Stok stokis')
@section('content')
<div class="bg-white shadow-md rounded-lg p-6">
    <h2 class="text-2xl font-bold mb-6 text-gray-800 border-b pb-3">Stok Per Stokis</h2>

    <form method="GET" action="{{ route('admin.stocks.index') }}" class="mb-6 flex items-center gap-3">
        <label for="warehouse_id" class="text-gray-700 font-medium">Filter Stokis:</label>
        <select name="warehouse_id" id="warehouse_id" class="border rounded-lg px-3 py-2 focus:ring focus:ring-blue-200">
            <option value="">-- Semua Stokis --</option>
            @foreach($allWarehouses as $wh)
                <option value="{{ $wh->id }}" {{ request('warehouse_id') == $wh->id ? 'selected' : '' }}>
                    {{ $wh->name }}
                </option>
            @endforeach
        </select>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
            Filter
        </button>
    </form>
    @foreach($warehouses as $warehouse)
    <!-- Card stokis -->
    <div class="bg-gray-50 shadow-sm rounded-lg p-5 mb-8">
        <h3 class="text-xl font-semibold text-gray-700 mb-4">{{ $warehouse->name }}</h3>
        <div class="overflow-x-auto">
            <table class="w-full border-collapse">
                <thead>
                    <tr class="bg-gray-100 text-gray-700 text-left">
                        <th class="px-4 py-3">Kode</th>
                        <th class="px-4 py-3">Produk</th>
                        <th class="px-4 py-3">Kategori</th>
                        <th class="px-4 py-3">Subkategori</th>
                        <th class="px-4 py-3">Harga</th>
                        <th class="px-4 py-3">Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($warehouse->products as $product)
                    <tr class="border-b hover:bg-gray-50 transition">
                        <td class="px-4 py-3 text-gray-700">
                            {{ $product->code }}
                        </td>
                        <td class="px-4 py-3 font-medium text-gray-800">
                            {{ $product->name }}
                        </td>
                        <td class="px-4 py-3 text-gray-600">
                            {{ $product->parentCategory->name ?? '-' }}
                        </td>
                        <td class="px-4 py-3 text-gray-600">
                            {{ $product->category->name ?? '-' }}
                        </td>
                        <td class="px-4 py-3 text-gray-600">
                            Rp {{ number_format($product->price,0,',','.') }}
                        </td>
                        <td class="px-4 py-3">
                            {{ $product->pivot->quantity }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-gray-500">Belum ada produk di stokis ini</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @endforeach
</div>
@endsection
