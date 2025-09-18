@extends('layouts.admin')
@section('title','Dashboard')
@section('content')
<h2 class="text-2xl font-bold mb-5">Admin Dashboard</h2><p>Selamat datang, {{ auth()->user()->name }}!</p>
    <!-- Statistik singkat -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white p-6 rounded-xl shadow">
            <h3 class="text-lg font-semibold">Total Stokis</h3>
            <p class="text-3xl font-bold mt-2">{{ $totalWarehouses ?? 0 }}</p>
        </div>
        <div class="bg-gradient-to-r from-green-500 to-green-600 text-white p-6 rounded-xl shadow">
            <h3 class="text-lg font-semibold">Total Produk</h3>
            <p class="text-3xl font-bold mt-2">{{ $totalProducts ?? 0 }}</p>
        </div>
        <div class="bg-gradient-to-r from-purple-500 to-purple-600 text-white p-6 rounded-xl shadow">
            <h3 class="text-lg font-semibold">Purchase Order</h3>
            <p class="text-3xl font-bold mt-2">{{ $totalPO ?? 0 }}</p>
        </div>
    </div>

    <!-- Recent PO -->
    <div class="bg-white rounded-xl shadow p-6 mb-8">
        <h3 class="text-xl font-semibold mb-4">Purchase Order Terbaru</h3>
        <table class="min-w-full border rounded-lg">
            <thead class="bg-gray-100">
                <tr>
                    <th class="py-2 px-3 text-left">Kode PO</th>
                    <th class="py-2 px-3 text-left">Requester</th>
                    <th class="py-2 px-3 text-left">Status</th>
                    <th class="py-2 px-3 text-left">Tanggal</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($orders as $po)
                    <tr class="border-t">
                        <td class="py-2 px-3">{{ $po->po_code }}</td>
                        <td class="py-2 px-3">{{ $po->requester->name ?? '-' }}</td>
                        <td class="py-2 px-3">
                            <span class="px-2 py-1 text-sm rounded
                                @if($po->status === 'pending') bg-yellow-100 text-yellow-800
                                @elseif($po->status === 'approved') bg-green-100 text-green-800
                                @else bg-red-100 text-red-800 @endif">
                                {{ ucfirst($po->status) }}
                            </span>
                        </td>
                        <td class="py-2 px-3">{{ $po->created_at->format('d-m-Y') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center py-4 text-gray-500">Belum ada PO terbaru.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Grafik Placeholder -->
    <div class="bg-white rounded-xl shadow p-6">
        <h3 class="text-xl font-semibold mb-4">Statistik Stok</h3>
        <div class="h-64 flex items-center justify-center text-gray-400 border-2 border-dashed rounded-lg">
            📊 Grafik Stok (Chart.js)
        </div>
    </div>
@endsection
