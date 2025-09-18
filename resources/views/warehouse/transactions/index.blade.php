@extends('layouts.warehouse')
@section('title','Barang Keluar')
@section('content')
<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 space-y-6">

    <!-- Header -->
    <div class="flex items-center justify-between">
        <h2 class="text-3xl font-extrabold text-gray-900">Barang Keluar</h2>
    </div>

    <!-- Notifikasi -->
    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-400 text-green-700 p-4 rounded shadow">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-50 border-l-4 border-red-400 text-red-700 p-4 rounded shadow">
            <ul class="list-disc pl-5 space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Form Barang Keluar -->
    <div class="bg-white shadow-lg rounded-xl p-6">
        <form action="{{ route('warehouse.transactions.store') }}" method="POST" class="space-y-5">
            @csrf

            <div>
                <label class="block font-semibold mb-2 text-gray-700">Produk</label>
                <select name="product_id" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-400 focus:border-blue-400 px-3 py-2">
                    @foreach(auth()->user()->warehouse->products as $product)
                        <option value="{{ $product->id }}">
                            {{ $product->name }} (Stok: {{ $product->pivot->quantity }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block font-semibold mb-2 text-gray-700">Jumlah</label>
                <input type="number" name="quantity" min="1" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-400 focus:border-blue-400 px-3 py-2" required>
            </div>

            <div>
                <label class="block font-semibold mb-2 text-gray-700">Keterangan</label>
                <input type="text" name="note" placeholder="Opsional" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-400 focus:border-blue-400 px-3 py-2">
            </div>

            <div>
                <button type="submit" class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-semibold px-5 py-2 rounded-lg shadow-md transition-all duration-200">
                    Simpan
                </button>
            </div>
        </form>
    </div>

    <!-- Tabel Transaksi Terakhir -->
    <div class="bg-white shadow-lg rounded-xl overflow-hidden">
        <h3 class="text-xl font-bold text-gray-800 px-6 py-4 border-b border-gray-200">Transaksi Terakhir</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Produk</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Keterangan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($transactions as $tx)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 whitespace-nowrap">{{ $tx->product->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $tx->quantity }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $tx->creator->name ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $tx->note ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $tx->created_at->format('d M Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-400">Belum ada transaksi</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 border-t border-gray-200">
            {{ $transactions->links() }}
        </div>
    </div>
</div>
@endsection