@extends('layouts.admin')
@section('title','Laporan Barang Keluar')

@section('content')
<h2 class="text-2xl font-bold mb-5">Laporan Barang Keluar</h2>

<!-- Filter -->
<form method="GET" class="mb-6 flex flex-wrap gap-4">
    <select name="warehouse_id" class="border rounded p-2">
        <option value="">Semua Stokis</option>
        @foreach($warehouses as $wh)
            <option value="{{ $wh->id }}" {{ ($warehouseId == $wh->id) ? 'selected' : '' }}>{{ $wh->name }}</option>
        @endforeach
    </select>

    <input type="date" name="date" value="{{ $date ?? '' }}" class="border rounded p-2" placeholder="Per Hari">
    <input type="month" name="month" value="{{ $month ?? '' }}" class="border rounded p-2" placeholder="Per Bulan">

    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Filter</button>
</form>

<!-- Table -->
<div class="overflow-x-auto bg-white shadow rounded-lg p-4">
    <table class="min-w-full border-collapse">
        <thead>
            <tr class="bg-gray-100 text-gray-700">
                <th class="px-4 py-2">Tanggal</th>
                <th class="px-4 py-2">Stokis</th>
                <th class="px-4 py-2">Produk</th>
                <th class="px-4 py-2">Jumlah Keluar</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transactions as $tr)
            <tr class="border-b hover:bg-gray-50">
                <td class="px-4 py-2">{{ $tr->created_at->format('d-m-Y H:i') }}</td>
                <td class="px-4 py-2">{{ $tr->warehouse->name ?? '-' }}</td>
                <td class="px-4 py-2">{{ $tr->product->name ?? '-' }}</td>
                <td class="px-4 py-2">{{ $tr->quantity }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="text-center py-4 text-gray-500">Tidak ada data</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
