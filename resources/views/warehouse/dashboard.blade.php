@extends('layouts.warehouse')
@section('title','Dashboard stokis')

@section('content')
<div class="space-y-8">

    <!-- Header -->
    <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-2xl p-6 shadow-xl">
        <h2 class="text-2xl font-bold">Stokis Dashboard</h2>
        <p class="mt-2 text-gray-100">Selamat datang, <span class="font-semibold">{{ auth()->user()->name }}</span>!
        Berikut ringkasan <span class="font-semibold">{{ auth()->user()->warehouse->name ?? '-' }}</span>.</p>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
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
            <p class="text-3xl font-bold mt-2">{{ $lowStockProducts->count() ?? 0 }}</p>
        </div>
    </div>

    <!-- Barang Keluar Hari Ini -->
    <div class="bg-white shadow-xl rounded-2xl p-6 hover:shadow-2xl transition-shadow duration-300">
        <h3 class="text-xl font-semibold text-gray-700 mb-4">Barang Keluar Hari Ini ({{ \Carbon\Carbon::today()->format('d M Y') }})</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 rounded-lg overflow-hidden shadow-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produk</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subkategori</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah Keluar</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($todayOutProducts as $item)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 whitespace-nowrap text-gray-800 font-medium">{{ $item->product->code ?? '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-800 font-medium">{{ $item->product->name ?? $item->product_name ?? '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $item->product->category->parent->name ?? '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $item->product->category->name ?? '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $item->quantity }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-gray-500">Belum ada transaksi keluar hari ini</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- 10 Barang Keluar Terbanyak -->
    <div class="bg-white shadow-xl rounded-2xl p-6 hover:shadow-2xl transition-shadow duration-300">
        <h3 class="text-xl font-semibold text-gray-700 mb-4">10 Barang Keluar Terbanyak</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 rounded-lg overflow-hidden shadow-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produk</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subkategori</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah Keluar</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($topOutItems as $item)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 whitespace-nowrap text-gray-800 font-medium">{{ $item['product']->code ?? '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-800 font-medium">{{ $item['product']->name ?? $item['product']->product_name ?? '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $item['product']->category->parent->name ?? '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $item['product']->category->name ?? '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $item['total_out'] }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-gray-500">Belum ada transaksi keluar</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- 10 Barang dengan Stok Kurang dari 10 -->
    <div class="bg-white shadow-xl rounded-2xl p-6 hover:shadow-2xl transition-shadow duration-300">
        <h3 class="text-xl font-semibold text-gray-700 mb-4">10 Barang dengan Stok Kurang dari 10</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 rounded-lg overflow-hidden shadow-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produk</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subkategori</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah Stok</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($lowStockProducts as $product)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 whitespace-nowrap text-gray-800 font-medium">{{ $product->code ?? '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-800 font-medium">{{ $product->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $product->category->parent->name ?? '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $product->category->name ?? '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $product->pivot->quantity }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-gray-500">Tidak ada barang dengan stok kurang dari 10</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Grafik Barang Keluar 12 Bulan Terakhir -->
    <div class="bg-white shadow-xl rounded-2xl p-6 hover:shadow-2xl transition-shadow duration-300">
        <h3 class="text-xl font-semibold text-gray-700 mb-4">Grafik Barang Keluar 12 Bulan Terakhir</h3>
        <canvas id="outChart" class="w-full h-64"></canvas>
    </div>

</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('outChart').getContext('2d');

    const labels = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
    const monthlyOut = @json($monthlyOut ?? []);
    const data = labels.map((_, index) => monthlyOut[index+1] ?? 0);

    const gradient = ctx.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, 'rgba(37, 99, 235, 0.6)');
    gradient.addColorStop(1, 'rgba(37, 99, 235, 0.1)');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Jumlah Barang Keluar',
                data: data,
                fill: true,
                backgroundColor: gradient,
                borderColor: 'rgba(37, 99, 235, 1)',
                borderWidth: 3,
                tension: 0.4,
                borderCapStyle: 'round',
                pointBackgroundColor: 'white',
                pointBorderColor: 'rgba(37, 99, 235, 1)',
                pointRadius: 5,
                pointHoverRadius: 8,
                pointHoverBackgroundColor: 'rgba(37, 99, 235, 1)',
                pointHoverBorderColor: 'white'
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: true, position: 'top', labels: { font: { size: 14 } } },
                tooltip: { mode: 'index', intersect: false }
            },
            interaction: { mode: 'nearest', axis: 'x', intersect: false },
            scales: {
                y: { beginAtZero: true, ticks: { stepSize: 1 } },
                x: { grid: { display: false } }
            }
        }
    });
});
</script>
@endpush
@endsection
