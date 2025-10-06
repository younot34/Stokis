@extends('layouts.admin')
@section('title','Laporan Barang Keluar')

@section('content')
<h2 class="text-2xl font-bold mb-5 text-gray-800 dark:text-gray-100">Laporan Barang Keluar</h2>
<form method="GET" class="mb-6 flex flex-wrap gap-4">
    <select name="warehouse_id"
        class="border border-gray-300 dark:border-gray-600 rounded p-2
               bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-200">
        <option value="">Semua Stockist</option>
        @foreach($warehouses as $wh)
            <option value="{{ $wh->id }}" {{ ($warehouseId == $wh->id) ? 'selected' : '' }}>
                {{ $wh->name }}
            </option>
        @endforeach
    </select>

    <input type="text" name="code" value="{{ request('code') }}"
        placeholder="Kode Transaksi"
        class="border border-gray-300 dark:border-gray-600 rounded p-2
               bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-200">

    <input type="date" name="date" value="{{ $date ?? '' }}"
        class="border border-gray-300 dark:border-gray-600 rounded p-2
               bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-200" placeholder="Per Hari">

    <input type="month" name="month" value="{{ $month ?? '' }}"
        class="border border-gray-300 dark:border-gray-600 rounded p-2
               bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-200" placeholder="Per Bulan">

    <button type="submit"
        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
        Filter
    </button>
</form>
<div class="bg-white dark:bg-gray-900 shadow-lg rounded-xl overflow-hidden">
    <h3 class="text-xl font-bold text-gray-800 dark:text-gray-100 px-6 py-4 border-b border-gray-200 dark:border-gray-700">
        Transaksi
    </h3>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-400 dark:divide-gray-700">
            <thead class="bg-gray-200 dark:bg-gray-700">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-300 uppercase">Kode Transaksi</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-300 uppercase">Stockist</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-300 uppercase">Kode Barang</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-300 uppercase">Produk</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-300 uppercase">Kategori</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-300 uppercase">Subkategori</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-300 uppercase">Harga</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-300 uppercase">Jumlah</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-300 uppercase">User</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-300 uppercase">Keterangan</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-300 uppercase">Tanggal</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-400 dark:divide-gray-700">
                @forelse($transactions as $tx)
                    <tbody class="group">
                        @foreach($tx->items as $key => $item)
                            <tr class="group-hover:bg-yellow-100 dark:group-hover:bg-gray-700 transition cursor-pointer">
                                @if($key == 0)
                                    <td class="px-6 py-4 whitespace-nowrap align-top" rowspan="{{ $tx->items->count() }}">
                                        <div class="font-semibold text-gray-800 dark:text-gray-100">{{ $tx->code }}</div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                            Total: Rp {{ number_format($tx->grand_total,0,',','.') }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap align-top text-gray-700 dark:text-gray-200" rowspan="{{ $tx->items->count() }}">
                                        {{ $tx->warehouse->name ?? '-' }}
                                    </td>
                                @endif

                                <td class="px-6 py-4 whitespace-nowrap text-gray-700 dark:text-gray-200">{{ $item->product_code ?? '-' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-700 dark:text-gray-200">{{ $item->product_name ?? '-' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-700 dark:text-gray-200">{{ $item->category_name ?? '-' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-700 dark:text-gray-200">{{ $item->subcategory_name ?? '-' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-gray-700 dark:text-gray-200">
                                    {{ number_format($item->price,0,',','.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-gray-700 dark:text-gray-200">{{ $item->quantity }}</td>

                                @if($key == 0)
                                    <td class="px-6 py-4 whitespace-nowrap align-top text-gray-700 dark:text-gray-200" rowspan="{{ $tx->items->count() }}">
                                        {{ $tx->creator->name ?? '-' }}
                                    </td>
                                @endif

                                <td class="px-6 py-4 whitespace-nowrap text-gray-700 dark:text-gray-200">{{ $item->note ?? '-' }}</td>

                                @if($key == 0)
                                    <td class="px-6 py-4 whitespace-nowrap align-top text-gray-700 dark:text-gray-200" rowspan="{{ $tx->items->count() }}">
                                        {{ $tx->created_at->format('d M Y H:i') }}
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                @empty
                    <tr>
                        <td colspan="11" class="px-6 py-4 text-center text-gray-400 dark:text-gray-500">Belum ada transaksi</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
        {{ $transactions->links() }}
    </div>
</div>
@endsection
