@extends('layouts.admin')
@section('title','Stok stokis')
@section('content')
<div class="bg-white dark:bg-gray-900 shadow-md rounded-lg p-6">
    <h2 class="text-2xl font-bold mb-6 text-gray-800 dark:text-gray-100 border-b pb-3 border-gray-300 dark:border-gray-700">
        Stok Per Stockist
    </h2>

    <form method="GET" action="{{ route('admin.stocks.index') }}" class="mb-6 flex flex-wrap items-center gap-3">
        <div>
            <label for="warehouse_id" class="text-gray-700 dark:text-gray-200 font-medium">Stockist:</label>
            <select name="warehouse_id" id="warehouse_id"
                class="border border-gray-400 dark:border-gray-600 rounded-lg px-3 py-2
                    focus:ring focus:ring-blue-200 dark:focus:ring-blue-800
                    bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-200">
                <option value="">-- Semua Stockist --</option>
                @foreach($allWarehouses as $wh)
                    <option value="{{ $wh->id }}" {{ request('warehouse_id') == $wh->id ? 'selected' : '' }}>
                        {{ $wh->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="province" class="text-gray-700 dark:text-gray-200 font-medium">Provinsi:</label>
            <select name="province" id="province"
                class="border border-gray-400 dark:border-gray-600 rounded-lg px-3 py-2
                    focus:ring focus:ring-blue-200 dark:focus:ring-blue-800
                    bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-200">
                <option value="">-- Semua Provinsi --</option>
                @foreach($allProvinces as $prov)
                    <option value="{{ $prov }}" {{ request('province') == $prov ? 'selected' : '' }}>
                        {{ $prov }}
                    </option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="city" class="text-gray-700 dark:text-gray-200 font-medium">Kota:</label>
            <select name="city" id="city"
                class="border border-gray-400 dark:border-gray-600 rounded-lg px-3 py-2
                    focus:ring focus:ring-blue-200 dark:focus:ring-blue-800
                    bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-200">
                <option value="">-- Semua Kota --</option>
                @foreach($allCities as $ct)
                    <option value="{{ $ct }}" {{ request('city') == $ct ? 'selected' : '' }}>
                        {{ $ct }}
                    </option>
                @endforeach
            </select>
        </div>
        <button type="submit"
            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition">
            Filter
        </button>
    </form>

    @foreach($warehouses as $warehouse)
    <div class="bg-gray-50 dark:bg-gray-800 shadow-sm rounded-lg p-5 mb-8">
        <h3 class="text-xl font-semibold text-gray-700 dark:text-gray-100 mb-4">{{ $warehouse->name }}
            <span class="ml-3 text-sm font-normal text-gray-600 dark:text-gray-400">
                ({{ $warehouse->province ?? '-' }}, {{ $warehouse->city ?? '-' }} - {{ $warehouse->address ?? '-' }})
            </span>
        </h3>
        <div class="overflow-x-auto">
            <table class="w-full border-collapse">
                <thead>
                    <tr class="bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 text-left">
                        <th class="px-4 py-3">Kode</th>
                        <th class="px-4 py-3">Produk</th>
                        <th class="px-4 py-3">Kategori</th>
                        <th class="px-4 py-3">Subkategori</th>
                        <th class="px-4 py-3">Harga</th>
                        <th class="px-4 py-3">Jumlah</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-400 dark:divide-gray-600">
                    @forelse($warehouse->allItems as $item)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                        <td class="px-4 py-3 text-gray-700 dark:text-gray-200">
                            {{ $item->product->code }}
                        </td>
                        <td class="px-4 py-3 font-medium text-gray-800 dark:text-gray-100">
                            {{ $item->product->name }}
                        </td>
                        <td class="px-4 py-3 text-gray-600 dark:text-gray-300">
                            {{ $item->product->parentCategory->name ?? '-' }}
                        </td>
                        <td class="px-4 py-3 text-gray-600 dark:text-gray-300">
                            {{ $item->product->category->name ?? '-' }}
                        </td>
                        <td class="px-4 py-3 text-gray-600 dark:text-gray-300">
                            Rp {{ number_format($item->final_price ?? $item->price, 0, ',', '.') }}
                            @if(isset($item->final_price))
                                <span class="ml-2 text-xs text-green-600 dark:text-green-400">(Diskon)</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-gray-700 dark:text-gray-200">
                            {{ $item->quantity_approved }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-gray-500 dark:text-gray-400">
                            Belum ada stok di Stockist ini
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @endforeach
</div>
@endsection
