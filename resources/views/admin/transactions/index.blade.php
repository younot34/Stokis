@extends('layouts.admin')
@section('title','Barang Keluar')
@section('content')
<div class="mx-auto py-6 px-4 sm:px-6 lg:px-8 space-y-6">

    <div class="bg-gradient-to-r from-indigo-500 to-purple-600 text-white rounded-xl p-6 shadow-lg">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <h2 class="text-2xl font-bold">📃 Kirim Notice</h2>
            <a href="{{ route('admin.transactions.create') }}"
               class="mt-3 md:mt-0 inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg shadow transition">
                ➕ Buat Transaksi
            </a>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl overflow-hidden">
        <h3 class="text-xl font-bold text-gray-800 dark:text-gray-100 px-6 py-4 border-b border-gray-200 dark:border-gray-700">Transaksi Terakhir</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-400 dark:divide-gray-600">
                <thead class="bg-gray-200 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-300 uppercase">Kode Transaksi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-300 uppercase">Stockist</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-300 uppercase">Kode Barang</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-300 uppercase">Produk</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-300 uppercase">Jumlah</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-300 uppercase">Keterangan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-300 uppercase">Admin</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-300 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-300 uppercase">Jasa Kirim</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-300 uppercase">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-300 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-400 dark:divide-gray-600">
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
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-gray-700 dark:text-gray-200">{{ $item->quantity }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-700 dark:text-gray-200">{{ $item->note ?? '-' }}</td>

                                    @if($key == 0)
                                        <td class="px-6 py-4 whitespace-nowrap align-top text-gray-700 dark:text-gray-200" rowspan="{{ $tx->items->count() }}">
                                            {{ $tx->creator->name ?? '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap align-top text-gray-700 dark:text-gray-200" rowspan="{{ $tx->items->count() }}">
                                            <span class="px-3 py-1 text-xs font-medium rounded-full
                                                @if($tx->status === 'diproses') bg-yellow-100 text-yellow-700
                                                @elseif($tx->status === 'dikirim') bg-blue-100 text-blue-700
                                                @elseif($tx->status === 'selesai') bg-green-100 text-green-700
                                                @else bg-red-100 text-red-700 @endif">
                                                {{ $tx->status ?? '-' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap align-top text-gray-700 dark:text-gray-200" rowspan="{{ $tx->items->count() }}">
                                            {{ $tx->jasa_pengiriman ?? '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap align-top text-gray-700 dark:text-gray-200" rowspan="{{ $tx->items->count() }}">
                                            {{ $tx->updated_at->format('d M Y H:i') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap align-top" rowspan="{{ $tx->items->count() }}">
                                            <a href="{{ route('admin.transactions.show', $tx->id) }}"
                                            class="inline-flex items-center px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded shadow transition">
                                                🔍 Detail
                                            </a>
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    @empty
                        <tr>
                            <td colspan="10" class="px-6 py-4 text-center text-gray-400 dark:text-gray-500">Belum ada transaksi</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
            {{ $transactions->links() }}
        </div>
    </div>
</div>

@endsection
