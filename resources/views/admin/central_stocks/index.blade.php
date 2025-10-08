@extends('layouts.admin')
@section('title','Stok Pusat')
@section('content')

    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100">📦 Stok Pusat</h2>
        @canCreate('central_stocks')
        <a href="{{ route('admin.central_stocks.create') }}"
           class="inline-flex items-center bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg shadow transition">
            ➕ Tambah Stok
        </a>
        @endcanCreate
    </div>
    <div class="mb-6 bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
        <form method="GET" action="{{ route('admin.central_stocks.index') }}" class="flex flex-wrap gap-4">
            <div>
                <label for="code" class="block text-sm text-gray-700 dark:text-gray-300 mb-1">Kode Produk</label>
                <input type="text" name="code" id="code" value="{{ request('code') }}"
                    placeholder="Cari kode..."
                    class="border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2
                            bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100">
            </div>

            <div>
                <label for="name" class="block text-sm text-gray-700 dark:text-gray-300 mb-1">Nama Produk</label>
                <input type="text" name="name" id="name" value="{{ request('name') }}"
                    placeholder="Cari nama..."
                    class="border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2
                            bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100">
            </div>

            <div class="flex items-end gap-2">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                    🔍 Cari
                </button>
                <a href="{{ route('admin.central_stocks.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
                    ❌ Reset
                </a>
            </div>
        </form>
    </div>

    <div class="overflow-x-auto bg-white dark:bg-gray-800 shadow-lg rounded-lg">
        <table class="w-full border-collapse">
            <thead>
                <tr class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 uppercase text-sm">
                    <th class="px-6 py-3 text-left">ID</th>
                    <th class="px-6 py-3 text-left">Kode Produk</th>
                    <th class="px-6 py-3 text-left">Nama Produk</th>
                    <th class="px-6 py-3 text-center">Jumlah</th>
                    <th class="px-6 py-3 text-center">Tanggal Masuk</th>
                    <th class="px-6 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($stocks as $stock)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                        <td class="px-6 py-4">{{ $stock->id }}</td>
                        <td class="px-6 py-4">{{ $stock->product->code }}</td>
                        <td class="px-6 py-4">{{ $stock->product->name }}</td>
                        <td class="px-6 py-4 text-center">{{ $stock->quantity }}</td>
                        <td class="px-6 py-4 text-center">{{ $stock->created_at->format('d M Y') }}</td>
                        <td class="px-6 py-4 text-center space-x-2">
                            @canEdit('central_stocks')
                            <a href="{{ route('admin.central_stocks.edit',$stock->id) }}"
                               class="inline-block bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-lg shadow transition">
                                ✏️ Edit
                            </a>
                            @endcanEdit
                            @canDelete('central_stocks')
                            <form action="{{ route('admin.central_stocks.destroy',$stock->id) }}" method="POST" class="inline">
                                @csrf @method('DELETE')
                                <button class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-lg shadow transition"
                                        onclick="return confirm('Hapus data stok?')">
                                    🗑 Hapus
                              enbutton>
                            </form>
                            @endcanDelete
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-6 text-center text-gray-500 dark:text-gray-400">
                            Belum ada data stok pusat.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $stocks->links() }}
    </div>

@endsection
