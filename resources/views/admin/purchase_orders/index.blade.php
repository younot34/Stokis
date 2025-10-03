@extends('layouts.admin')
@section('title','PO stokis')
@section('content')
<div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl p-6">
    <h2 class="text-2xl font-bold mb-6 text-gray-800 dark:text-gray-100 border-b border-gray-300 dark:border-gray-700 pb-3">
        Purchase Orders
    </h2>

    <div class="overflow-x-auto">
        <table class="w-full border-collapse">
            <thead>
                <tr class="bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 text-left">
                    <th class="px-4 py-3">Kode PO</th>
                    <th class="px-4 py-3">Stokis</th>
                    <th class="px-4 py-3">User By</th>
                    <th class="px-4 py-3">Tanggal</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-400 dark:divide-gray-700">
                @forelse($orders as $po)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                    <td class="px-4 py-3 font-medium text-gray-800 dark:text-gray-100">{{ $po->po_code }}</td>
                    <td class="px-4 py-3 text-gray-700 dark:text-gray-200">
                        {{ $po->warehouse->name }}
                        <span class="text-sm text-gray-500 dark:text-gray-400">
                            ({{ $po->warehouse->province ?? '-' }}, {{ $po->warehouse->city ?? '-' }})
                        </span>
                    </td>
                    <td class="px-4 py-3 text-gray-700 dark:text-gray-200">{{ $po->requester->name ?? '-' }}</td>
                    <td class="px-4 py-3 text-gray-700 dark:text-gray-200">{{ $po->created_at->format('d-m-Y') }}</td>
                    <td class="px-4 py-3">
                        <span class="px-3 py-1 rounded-full text-sm font-semibold
                            @if($po->status == 'pending') bg-yellow-100 text-yellow-700 dark:bg-yellow-900 dark:text-yellow-200
                            @elseif($po->status == 'approved') bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-200
                            @elseif($po->status == 'rejected') bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-200
                            @else bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300 @endif">
                            {{ ucfirst($po->status) }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-center">
                        <a href="{{ route('admin.purchase_orders.show',$po->id) }}"
                           class="inline-block bg-blue-500 hover:bg-blue-600 text-white text-sm px-4 py-2 rounded-lg shadow transition">
                           Detail
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-6 text-gray-500 dark:text-gray-400">Belum ada PO</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
