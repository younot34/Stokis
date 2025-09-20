@extends('layouts.warehouse')
@section('title','Detail PO')
@section('content')
<div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl p-6">
    <h2 class="text-2xl font-bold mb-6 text-gray-800 dark:text-gray-100 border-b border-gray-300 dark:border-gray-600 pb-3">
        Detail Purchase Order
    </h2>

    <!-- Informasi PO -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6 text-gray-700 dark:text-gray-300">
        <p><span class="font-semibold dark:text-gray-200">Kode PO:</span> {{ $po->po_code }}</p>
        <p><span class="font-semibold dark:text-gray-200">Stokis:</span> {{ $po->warehouse->name }}</p>
        <p><span class="font-semibold dark:text-gray-200">Tanggal:</span> {{ $po->created_at->format('d-m-Y') }}</p>
        <p>
            <span class="font-semibold dark:text-gray-200">Status:</span>
            <span class="px-3 py-1 rounded-full text-sm font-semibold
                @if($po->status == 'pending') bg-yellow-100 text-yellow-700 dark:bg-yellow-900 dark:text-yellow-300
                @elseif($po->status == 'approved') bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300
                @elseif($po->status == 'rejected') bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300
                @else bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300 @endif">
                {{ ucfirst($po->status) }}
            </span>
        </p>
    </div>

    <!-- Tabel Item PO -->
    <div class="overflow-x-auto">
        <table class="w-full border-collapse">
            <thead>
                <tr class="bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 text-left">
                    <th class="px-4 py-3">Kode Barang</th>
                    <th class="px-4 py-3">Produk</th>
                    <th class="px-4 py-3">Harga</th>
                    <th class="px-4 py-3">Jumlah Request</th>
                    <th class="px-4 py-3">Jumlah Approve</th>
                    <th class="px-4 py-3">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $totalQty = 0;
                    $totalHarga = 0;
                @endphp
                @foreach($po->items as $item)
                    @php
                        $qtyUsed = $po->status == 'approved'
                            ? $item->quantity_approved
                            : $item->quantity_requested;
                        $subtotal = $item->price * $qtyUsed;
                        $totalQty += $qtyUsed;
                        $totalHarga += $subtotal;
                    @endphp
                    <tr class="border border-gray-400 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                        <td class="border border-gray-400 dark:border-gray-600 px-4 py-3 text-gray-700 dark:text-gray-300">
                            {{ $item->product->code ?? '-' }}
                        </td>
                        <td class="border border-gray-400 dark:border-gray-600 px-4 py-3 font-medium text-gray-800 dark:text-gray-100">
                            {{ $item->product->name }}
                        </td>
                        <td class="border border-gray-400 dark:border-gray-600 px-4 py-3 text-gray-700 dark:text-gray-300">
                            Rp {{ number_format($item->price,0,',','.') }}
                        </td>
                        <td class="border border-gray-400 dark:border-gray-600 px-4 py-3 text-gray-700 dark:text-gray-300">
                            {{ $item->quantity_requested }}
                        </td>
                        <td class="border border-gray-400 dark:border-gray-600 px-4 py-3 text-gray-700 dark:text-gray-300">
                            {{ $item->quantity_approved ?? '-' }}
                        </td>
                        <td class="border border-gray-400 dark:border-gray-600 px-4 py-3 text-gray-700 dark:text-gray-300">
                            Rp {{ number_format($subtotal,0,',','.') }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot class="bg-gray-50 dark:bg-gray-700 font-semibold text-gray-800 dark:text-gray-200">
                <tr>
                    <td colspan="4" class="px-4 py-3 text-right"></td>
                    <td class="px-4 py-3">Total : {{ $totalQty }}</td>
                    <td class="px-4 py-3">Rp {{ number_format($totalHarga,0,',','.') }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
@endsection