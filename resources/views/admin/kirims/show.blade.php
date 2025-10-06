@extends('layouts.admin')
@section('title','Detail PO')
@section('content')
<div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl p-6">
    <h2 class="text-2xl font-bold mb-6 text-gray-800 dark:text-gray-100 border-b border-gray-300 dark:border-gray-600 pb-3">
        Detail Purchase Order
    </h2>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6 text-gray-700 dark:text-gray-300">
        <p><span class="font-semibold dark:text-gray-200">Kode PO:</span> {{ $kirim->po_code }}</p>
        <p><span class="font-semibold dark:text-gray-200">Stockist:</span> {{ $kirim->warehouse->name }}</p>
        <p><span class="font-semibold dark:text-gray-200">Tanggal:</span> {{ $kirim->created_at->format('d-m-Y') }}</p>
        <p>
            <span class="font-semibold dark:text-gray-200">Status:</span>
            <span class="px-3 py-1 rounded-full text-sm font-semibold
                @if($kirim->status == 'pending') bg-yellow-100 text-yellow-700 dark:bg-yellow-900 dark:text-yellow-300
                @elseif($kirim->status == 'approved') bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300
                @elseif($kirim->status == 'rejected') bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300
                @else bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300 @endif">
                {{ ucfirst($kirim->status) }}
            </span>
        </p>
        <p><span class="font-semibold dark:text-gray-200">Jasa Pengiriman:</span> {{ $kirim->jasa_pengiriman }}</p>
        <p><span class="font-semibold dark:text-gray-200">No resi:</span> {{ $kirim->resi_number }}</p>
        <p><span class="font-semibold dark:text-gray-200">Bukti:
        @if($kirim->image)
            <img src="{{ asset($kirim->image) }}"
                alt="Bukti Kirim"
                class="mt-2 w-48 h-auto rounded shadow-md border">
        @else
            <p class="text-gray-500 dark:text-gray-400 italic">Tidak ada bukti upload</p>
        @endif
        </span></p>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full border-collapse">
            <thead>
                <tr class="bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 text-left">
                    <th class="px-4 py-3">Kode Barang</th>
                    <th class="px-4 py-3">Produk</th>
                    <th class="px-4 py-3">Harga Normal</th>
                    <th class="px-4 py-3">Diskon</th>
                    <th class="px-4 py-3">Harga Diskon</th>
                    <th class="px-4 py-3">Jumlah Request</th>
                    <th class="px-4 py-3">Jumlah Approve</th>
                    <th class="px-4 py-3">Subtotal</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-400 dark:divide-gray-700">
                @php
                    $totalQty = 0;
                    $totalHarga = 0;
                @endphp
                @foreach($kirim->items->concat($kirim->discountItems) as $item)
                    @php
                        $qtyUsed = $kirim->status == 'approved'
                            ? $item->quantity_approved
                            : $item->quantity_requested;

                        $hargaPakai = $item->discount > 0 ? $item->final_price : $item->price;
                        $subtotal = $hargaPakai * $qtyUsed;

                        $totalQty += $qtyUsed;
                        $totalHarga += $subtotal;
                    @endphp
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                        <td class="px-6 py-4 whitespace-nowrap text-gray-800 dark:text-gray-100">{{ $item->product->code ?? '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-800 dark:text-gray-100">{{ $item->product->name ?? 'Barang Diskon' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-800 dark:text-gray-100">Rp {{ number_format($item->price,0,',','.') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-800 dark:text-gray-100">{{ $item->discount > 0 ? $item->discount.' %' : '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-800 dark:text-gray-100">{{ $item->discount > 0 ? 'Rp '.number_format($item->final_price,0,',','.') : '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-800 dark:text-gray-100">{{ $item->quantity_requested }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-800 dark:text-gray-100">{{ $item->quantity_approved ?? '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-800 dark:text-gray-100">Rp {{ number_format($subtotal,0,',','.') }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot class="bg-gray-50 dark:bg-gray-700 font-semibold">
                <tr>
                    <td colspan="5" class="px-6 py-3 text-right">Total :</td>
                    <td class="px-6 py-3">{{ $totalQty }}</td>
                    <td class="px-6 py-3"></td>
                    <td colspan="2" class="px-6 py-3">Rp {{ number_format($totalHarga,0,',','.') }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
@endsection
