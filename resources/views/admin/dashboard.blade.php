@extends('layouts.admin')

@section('title','Dashboard')

@section('content')
    <!-- Header -->
    <h2 class="text-2xl md:text-3xl font-bold mb-6 text-gray-800 dark:text-gray-100">
        Admin Dashboard
    </h2>
    <p class="text-gray-600 dark:text-gray-400 mb-8">
        Selamat datang, <span class="font-semibold">{{ auth()->user()->name }}</span> 👋
    </p>

    <!-- Statistik Singkat -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-10">
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white p-6 rounded-xl shadow hover:shadow-lg transition">
            <h3 class="text-lg font-semibold">Total Stokis</h3>
            <p class="text-4xl font-bold mt-2">{{ $totalWarehouses ?? 0 }}</p>
        </div>
        <div class="bg-gradient-to-r from-green-500 to-green-600 text-white p-6 rounded-xl shadow hover:shadow-lg transition">
            <h3 class="text-lg font-semibold">Total Produk</h3>
            <p class="text-4xl font-bold mt-2">{{ $totalProducts ?? 0 }}</p>
        </div>
        <div class="bg-gradient-to-r from-purple-500 to-purple-600 text-white p-6 rounded-xl shadow hover:shadow-lg transition">
            <h3 class="text-lg font-semibold">Total Stok</h3>
            <p class="text-4xl font-bold mt-2">{{ $totalStock ?? 0 }}</p>
        </div>
        <div class="bg-gradient-to-r from-purple-500 to-purple-600 text-white p-6 rounded-xl shadow hover:shadow-lg transition">
            <h3 class="text-lg font-semibold">Purchase Order</h3>
            <p class="text-4xl font-bold mt-2">{{ $totalPO ?? 0 }}</p>
        </div>
    </div>

    <!-- Purchase Order Terbaru -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6 mb-10">
        <h3 class="text-xl font-semibold mb-4 text-gray-800 dark:text-gray-100">Purchase Order Terbaru</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm text-left border border-gray-200 dark:border-gray-700 rounded-lg">
                <thead class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                    <tr>
                        <th class="py-3 px-4">Kode PO</th>
                        <th class="py-3 px-4">Requester</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4">Tanggal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($orders as $po)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="py-3 px-4 font-medium">{{ $po->po_code }}</td>
                            <td class="py-3 px-4">{{ $po->requester->name ?? '-' }}</td>
                            <td class="py-3 px-4">
                                <span class="px-3 py-1 text-xs font-medium rounded-full
                                    @if($po->status === 'pending') bg-yellow-100 text-yellow-700
                                    @elseif($po->status === 'approved') bg-green-100 text-green-700
                                    @else bg-red-100 text-red-700 @endif">
                                    {{ ucfirst($po->status) }}
                                </span>
                            </td>
                            <td class="py-3 px-4">{{ $po->created_at->format('d-m-Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-6 text-gray-500 dark:text-gray-400">
                                Belum ada PO terbaru.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Top Transaksi & Top Nominal -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Top Transaksi -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
            <h3 class="text-xl font-semibold mb-4 text-gray-800 dark:text-gray-100">
                🔝 Top Transaksi
            </h3>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm text-left border border-gray-200 dark:border-gray-700 rounded-lg">
                    <thead class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                        <tr>
                            <th class="py-3 px-4">Kode Transaksi</th>
                             <th class="py-3 px-4">Stokis</th>
                             <th class="py-3 px-4">Kota</th>
                            <th class="py-3 px-4 text-center">Jumlah Item</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($topTransactions as $tx)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="py-3 px-4 font-medium">{{ $tx->code }}</td>
                                <td class="py-3 px-4">{{ $tx->warehouse->name ?? '-' }}</td>
                                <td class="py-3 px-4">{{ $tx->warehouse->city ?? '-' }}</td>
                                <td class="py-3 px-4 text-center">{{ $tx->items_count }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="text-center py-6 text-gray-500 dark:text-gray-400">
                                    Belum ada data.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Top Nominal Transaksi -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
            <h3 class="text-xl font-semibold mb-4 text-gray-800 dark:text-gray-100">
                💰 Top Nominal Transaksi
            </h3>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm text-left border border-gray-200 dark:border-gray-700 rounded-lg">
                    <thead class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                        <tr>
                            <th class="py-3 px-4">Kode Transaksi</th>
                            <th class="py-3 px-4">Stokis</th>
                            <th class="py-3 px-4">Kota</th>
                            <th class="py-3 px-4 text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($topNominals as $tx)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="py-3 px-4 font-medium">{{ $tx->code }}</td>
                                <td class="py-3 px-4">{{ $tx->warehouse->name ?? '-' }}</td>
                                <td class="py-3 px-4">{{ $tx->warehouse->city ?? '-' }}</td>
                                <td class="py-3 px-4 text-right">
                                    Rp {{ number_format($tx->grand_total,0,',','.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="text-center py-6 text-gray-500 dark:text-gray-400">
                                    Belum ada data.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
