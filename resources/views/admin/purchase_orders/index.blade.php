@extends('layouts.admin')
@section('title','PO stokis')
@section('content')
<div class="bg-white shadow-lg rounded-xl p-6">
    <h2 class="text-2xl font-bold mb-6 text-gray-800 border-b pb-3">Purchase Orders</h2>

    <div class="overflow-x-auto">
        <table class="w-full border-collapse">
            <thead>
                <tr class="bg-gray-100 text-gray-700 text-left">
                    <th class="px-4 py-3">Kode PO</th>
                    <th class="px-4 py-3">stokis</th>
                    <th class="px-4 py-3">Requested By</th>
                    <th class="px-4 py-3">Tanggal</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $po)
                <tr class="border-b hover:bg-gray-50 transition">
                    <td class="px-4 py-3 font-medium text-gray-800">{{ $po->po_code }}</td>
                    <td class="px-4 py-3">{{ $po->warehouse->name }}</td>
                    <td class="px-4 py-3">{{ $po->requester->name ?? '-' }}</td>
                    <td class="px-4 py-3">{{ $po->created_at->format('d-m-Y') }}</td>
                    <td class="px-4 py-3">
                        <span class="px-3 py-1 rounded-full text-sm font-semibold
                            @if($po->status == 'pending') bg-yellow-100 text-yellow-700
                            @elseif($po->status == 'approved') bg-green-100 text-green-700
                            @elseif($po->status == 'rejected') bg-red-100 text-red-700
                            @else bg-gray-100 text-gray-600 @endif">
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
                    <td colspan="6" class="text-center py-6 text-gray-500">Belum ada PO</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
