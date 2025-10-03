@extends('layouts.admin')
@section('title','Detail PO')
@section('content')
<div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl p-6">
    <h2 class="text-2xl font-bold mb-6 text-gray-800 dark:text-gray-100 border-b border-gray-300 dark:border-gray-700 pb-3">
        Detail Purchase Order
    </h2>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6 text-gray-700 dark:text-gray-300">
        <p><span class="font-semibold">Kode PO:</span> {{ $po->po_code }}</p>
        <p><span class="font-semibold">Stokis:</span> {{ $po->warehouse->name }}</p>
        <p><span class="font-semibold">Tanggal:</span> {{ $po->created_at->format('d-m-Y') }}</p>
        <p>
            <span class="font-semibold">Status:</span>
            <span class="px-3 py-1 rounded-full text-sm font-semibold
                @if($po->status == 'pending') bg-yellow-100 text-yellow-700 dark:bg-yellow-900 dark:text-yellow-200
                @elseif($po->status == 'approved') bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-200
                @elseif($po->status == 'rejected') bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-200
                @else bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300 @endif">
                {{ ucfirst($po->status) }}
            </span>
        </p>
    </div>
    @if($errors->any())
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form action="{{ route('admin.purchase_orders.approve',$po->id) }}" method="POST" class="space-y-6">
        @csrf
        <div class="overflow-x-auto">
            <table class="w-full border-collapse">
                <thead>
                    <tr class="bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 text-left">
                        <th class="px-4 py-3">Kode Barang</th>
                        <th class="px-4 py-3">Produk</th>

                        {{-- Harga berbeda untuk PO Normal vs Diskon --}}
                        @if($po->discountItems->count() > 0)
                            <th class="px-4 py-3">Harga Normal</th>
                            <th class="px-4 py-3">Diskon</th>
                            <th class="px-4 py-3">Harga Diskon</th>
                        @else
                            <th class="px-4 py-3">Harga</th>
                        @endif

                        <th class="px-4 py-3">Jumlah Request</th>
                        <th class="px-4 py-3">Jumlah Approve</th>
                        <th class="px-4 py-3">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-400 dark:divide-gray-700">
                    @php
                        $grandQty = 0;
                        $grandHarga = 0;
                    @endphp

                    {{-- Loop Normal Items --}}
                    @foreach($po->items as $item)
                        @php
                            $qtyUsed = $po->status == 'approved' ? $item->quantity_approved : $item->quantity_requested;
                            $subtotal = $item->price * $qtyUsed;
                            $grandQty += $qtyUsed;
                            $grandHarga += $subtotal;
                        @endphp
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                            <td class="px-4 py-3 font-medium text-gray-800 dark:text-gray-100">{{ $item->product->code ?? '-' }}</td>
                            <td class="px-4 py-3 font-medium text-gray-800 dark:text-gray-100">{{ $item->product->name }}</td>
                            <td class="px-4 py-3 font-medium text-gray-800 dark:text-gray-100">Rp {{ number_format($item->price,0,',','.') }}</td>

                            @if($po->discountItems->count() > 0)
                                <td class="px-4 py-3 font-medium text-gray-800 dark:text-gray-100">-</td>
                                <td class="px-4 py-3 font-medium text-gray-800 dark:text-gray-100">-</td>
                            @endif

                            <td class="px-4 py-3 font-medium text-gray-800 dark:text-gray-100">{{ $item->quantity_requested }}</td>
                            <td class="px-4 py-3 font-medium text-gray-800 dark:text-gray-100">
                                @if($po->status == 'pending')
                                    <input type="number" name="items[{{ $item->id }}]"
                                        value="{{ $item->quantity_requested }}" min="0"
                                        class="border rounded px-3 py-1 w-24 dark:bg-gray-800 dark:text-gray-100">
                                @else
                                    {{ $item->quantity_approved }}
                                @endif
                            </td>
                            <td class="px-4 py-3 font-medium text-gray-800 dark:text-gray-100">Rp {{ number_format($subtotal,0,',','.') }}</td>
                        </tr>
                    @endforeach

                    {{-- Loop Discount Items --}}
                    @foreach($po->discountItems as $item)
                        @php
                            $qtyUsed = $po->status == 'approved' ? $item->quantity_approved : $item->quantity_requested;
                            $subtotal = $item->final_price * $qtyUsed;
                            $grandQty += $qtyUsed;
                            $grandHarga += $subtotal;
                        @endphp
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                            <td class="px-4 py-3 font-medium text-gray-800 dark:text-gray-100">{{ $item->product->code ?? '-' }}</td>
                            <td class="px-4 py-3 font-medium text-gray-800 dark:text-gray-100">{{ $item->product->name }}</td>
                            <td class="px-4 py-3 font-medium text-gray-800 dark:text-gray-100">Rp {{ number_format($item->price,0,',','.') }}</td>
                            <td class="px-4 py-3 font-medium text-gray-800 dark:text-gray-100">{{ $item->discount }} %</td>
                            <td class="px-4 py-3 font-medium text-gray-800 dark:text-gray-100">Rp {{ number_format($item->final_price,0,',','.') }}</td>
                            <td class="px-4 py-3 font-medium text-gray-800 dark:text-gray-100">{{ $item->quantity_requested }}</td>
                            <td class="px-4 py-3 font-medium text-gray-800 dark:text-gray-100">
                                @if($po->status == 'pending')
                                    <input type="number" name="discount_items[{{ $item->id }}]"
                                        value="{{ $item->quantity_requested }}" min="0"
                                        class="border rounded px-3 py-1 w-24 dark:bg-gray-800 dark:text-gray-100">
                                @else
                                    {{ $item->quantity_approved }}
                                @endif
                            </td>
                            <td class="px-4 py-3 font-medium text-gray-800 dark:text-gray-100">Rp {{ number_format($subtotal,0,',','.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-gray-50 dark:bg-gray-700 font-semibold text-gray-800 dark:text-gray-200">
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                        @if($po->discountItems->count() > 0)
                            <td colspan="6" class="px-4 py-3 text-right">Grand Total</td>
                        @else
                            <td colspan="4" class="px-4 py-3 text-right">Grand Total</td>
                        @endif
                        <td class="px-4 py-3 font-medium text-gray-800 dark:text-gray-100">{{ $grandQty }}</td>
                        <td class="px-4 py-3 font-medium text-gray-800 dark:text-gray-100">Rp {{ number_format($grandHarga,0,',','.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
        @if($po->status == 'pending')
        <div class="flex justify-end">
            <button type="submit"
                    class="bg-green-500 hover:bg-green-600 text-white px-6 py-2 rounded-lg shadow transition">
                Approve
            </button>
        </div>
        @endif
    </form>
</div>
@endsection
