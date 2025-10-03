@extends('layouts.admin')
@section('title','Permintaan Barang')
@section('content')
<div class="mx-auto py-6 px-4 sm:px-6 lg:px-8 space-y-6">

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <h2 class="text-3xl font-extrabold text-gray-900 dark:text-gray-100 mb-4 sm:mb-0">Kirim Barang Ke Stokis</h2>
        <a href="{{ route('admin.kirims.create') }}"
           class="inline-block bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700
                  text-white px-5 py-2 rounded-lg shadow-md transition-all duration-200">
            + Kirim
        </a>
    </div>
    <div class="bg-white dark:bg-gray-900 shadow-lg rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 text-left">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-300 uppercase">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-300 uppercase">Kode PO</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-300 uppercase">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-300 uppercase">Produk</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-300 uppercase">Jumlah</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-300 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-300 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-400 dark:divide-gray-700">
                    @forelse ($kirims as $po)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                            <td class="px-6 py-4 whitespace-nowrap text-gray-800 dark:text-gray-100">{{ $po->created_at->format('d-m-Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-800 dark:text-gray-100">{{ $po->po_code }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-800 dark:text-gray-100">{{ $po->requester->name ?? '-' }}</td>
                            @php
                                $allProducts = $po->items->pluck('product.name')
                                    ->merge($po->discountItems->pluck('product.name'));

                                $allQty = $po->items->pluck('quantity_requested')
                                    ->merge($po->discountItems->pluck('quantity_requested'));
                            @endphp
                            <td class="px-6 py-4 whitespace-nowrap text-gray-800 dark:text-gray-100">{{ $allProducts->join(', ') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-800 dark:text-gray-100">{{ $allQty->join(', ') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 rounded-full text-sm font-semibold
                                    @if($po->status === 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-600 dark:text-yellow-100
                                    @elseif($po->status === 'approved') bg-green-100 text-green-800 dark:bg-green-600 dark:text-green-100
                                    @elseif($po->status === 'rejected') bg-red-100 text-red-800 dark:bg-red-600 dark:text-red-100
                                    @else bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-100 @endif">
                                    {{ ucfirst($po->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <a href="{{ route('admin.kirims.show', $po->id) }}"
                                   class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg shadow text-sm">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-4 text-center text-gray-400 dark:text-gray-500">
                                Belum ada purchase order.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 flex justify-end">
            {{ $kirims->links() }}
        </div>
    </div>
</div>
@endsection
