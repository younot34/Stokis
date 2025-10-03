@extends('layouts.warehouse')
@section('title','Dashboard stokis')

@section('content')
<div class="space-y-8">
    <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-2xl p-6 shadow-xl">
        <h2 class="text-2xl font-bold">Stokis Dashboard</h2>
        <p class="mt-2 text-gray-100 dark:text-gray-200">Selamat datang, <span class="font-semibold">{{ auth()->user()->name }}</span>!
        Berikut ringkasan <span class="font-semibold">{{ auth()->user()->warehouse->name ?? '-' }}</span>.</p>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-10">
        <div class="bg-gradient-to-r from-blue-400 to-blue-600 text-white p-6 rounded-2xl shadow-xl hover:shadow-2xl transition-shadow duration-300">
            <h3 class="text-lg font-semibold">Total Barang</h3>
            <p class="text-3xl font-bold mt-2">{{ $totalProducts ?? 0 }}</p>
        </div>
        <div class="bg-gradient-to-r from-green-400 to-green-600 text-white p-6 rounded-2xl shadow-xl hover:shadow-2xl transition-shadow duration-300">
            <h3 class="text-lg font-semibold">Barang Keluar Hari Ini</h3>
            <p class="text-3xl font-bold mt-2">{{ $todayOutProducts->sum('quantity') ?? 0 }}</p>
        </div>
        <div class="bg-gradient-to-r from-purple-400 to-purple-600 text-white p-6 rounded-2xl shadow-xl hover:shadow-2xl transition-shadow duration-300">
            <h3 class="text-lg font-semibold">Stok Kurang dari 10</h3>
            <p class="text-3xl font-bold mt-2">{{ $totalLowStock ?? 0 }}</p>
        </div>
        <div class="bg-gradient-to-r from-yellow-400 to-yellow-600 text-white p-6 rounded-2xl shadow-xl hover:shadow-2xl transition-shadow duration-300">
            <h3 class="text-lg font-semibold">Total Stok</h3>
            <p class="text-3xl font-bold mt-2">{{ $totalStock ?? 0 }}</p>
        </div>
        <div class="bg-gradient-to-r from-red-400 to-red-600 text-white p-6 rounded-2xl shadow-xl hover:shadow-2xl transition-shadow duration-300">
            <h3 class="text-lg font-semibold">Total Aset</h3>
            <p class="text-3xl font-bold mt-2">Rp {{ number_format($totalAsset ?? 0, 0, ',', '.') }}</p>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl p-6 hover:shadow-2xl transition-shadow duration-300">
        <h3 class="text-xl font-semibold text-gray-700 dark:text-gray-200 mb-4">Barang Keluar Hari Ini ({{ \Carbon\Carbon::today()->format('d M Y') }})</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600 rounded-lg overflow-hidden shadow-sm">
                <thead class="bg-gray-200 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Kode</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Produk</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Kategori</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Subkategori</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Jumlah Keluar</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-400 dark:divide-gray-600">
                    @forelse($todayOutProducts as $item)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                        <td class="px-6 py-4 whitespace-nowrap text-gray-800 dark:text-gray-100 font-medium">{{ $item->product->code ?? '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-800 dark:text-gray-100 font-medium">{{ $item->product->name ?? $item->product_name ?? '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-700 dark:text-gray-300">{{ $item->product->category->parent->name ?? '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-700 dark:text-gray-300">{{ $item->product->category->name ?? '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-700 dark:text-gray-300">{{ $item->quantity }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-gray-500 dark:text-gray-400">Belum ada transaksi keluar hari ini</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl p-6 hover:shadow-2xl transition-shadow duration-300">
        <h3 class="text-xl font-semibold text-gray-700 dark:text-gray-200 mb-4">10 Barang Keluar Terbanyak</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600 rounded-lg overflow-hidden shadow-sm">
                <thead class="bg-gray-200 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Kode</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Produk</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Kategori</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Subkategori</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Jumlah Keluar</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-400 dark:divide-gray-600">
                    @forelse($topOutItems as $item)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                        <td class="px-6 py-4 whitespace-nowrap text-gray-800 dark:text-gray-100 font-medium">{{ $item['product']->code ?? '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-800 dark:text-gray-100 font-medium">{{ $item['product']->name ?? $item['product']->product_name ?? '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-700 dark:text-gray-300">{{ $item['product']->category->parent->name ?? '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-700 dark:text-gray-300">{{ $item['product']->category->name ?? '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-700 dark:text-gray-300">{{ $item['total_out'] }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-gray-500 dark:text-gray-400">Belum ada transaksi keluar</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl p-6 hover:shadow-2xl transition-shadow duration-300">
        <h3 class="text-xl font-semibold text-gray-700 dark:text-gray-200 mb-4">10 Barang dengan Stok Kurang dari 10</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600 rounded-lg overflow-hidden shadow-sm">
                <thead class="bg-gray-200 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Kode</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Produk</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Kategori</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Subkategori</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Jumlah Stok</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Jenis</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-400 dark:divide-gray-600">
                    @forelse($lowStockProducts as $row)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                        <td class="px-6 py-4 whitespace-nowrap text-gray-800 dark:text-gray-100 font-medium">{{ $row['product']->code ?? '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-800 dark:text-gray-100 font-medium">{{ $row['product']->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-700 dark:text-gray-300">{{ $row['product']->category->parent->name ?? '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-700 dark:text-gray-300">{{ $row['product']->category->name ?? '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-700 dark:text-gray-300">{{ $row['qty'] }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-700 dark:text-gray-300">
                            @if($row['type'] === 'diskon')
                                <span class="px-3 py-1 text-xs font-semibold bg-green-100 text-green-800 rounded-full">Diskon</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-gray-500 dark:text-gray-400">Tidak ada barang dengan stok kurang dari 10</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
