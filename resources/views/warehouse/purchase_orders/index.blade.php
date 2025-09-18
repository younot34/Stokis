@extends('layouts.warehouse')
@section('title','Permintaan Barang')
@section('content')
<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 space-y-6">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <h2 class="text-3xl font-extrabold text-gray-900 mb-4 sm:mb-0">Purchase Order</h2>
        <a href="{{ route('warehouse.purchase_orders.create') }}"
           class="inline-block bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white px-5 py-2 rounded-lg shadow-md transition-all duration-200">
            + Buat PO
        </a>
    </div>

    <!-- Tabel PO -->
    <div class="bg-white shadow-lg rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kode PO</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Requested</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Produk</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($purchaseOrders as $po)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 whitespace-nowrap">{{ $po->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $po->created_at->format('d-m-Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $po->po_code }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $po->requester->name ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $po->items->pluck('product.name')->join(', ') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $po->items->pluck('quantity_requested')->join(', ') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 rounded-full text-sm font-semibold
                                    @if($po->status === 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($po->status === 'approved') bg-green-100 text-green-800
                                    @elseif($po->status === 'rejected') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ ucfirst($po->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <a href="{{ route('warehouse.purchase_orders.show', $po->id) }}"
                                class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg shadow text-sm">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-gray-400">Belum ada purchase order.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200 flex justify-end">
            {{ $purchaseOrders->links() }}
        </div>
    </div>
</div>
@endsection