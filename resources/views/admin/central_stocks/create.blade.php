@extends('layouts.admin')
@section('title','Tambah Stok Pusat')

@section('content')
<div class="max-w-xl mx-auto bg-white dark:bg-gray-800 p-6 rounded-xl shadow-lg">
    <h2 class="text-xl font-bold mb-4 text-gray-800 dark:text-gray-100">➕ Tambah Stok Pusat</h2>
    <form action="{{ route('admin.central_stocks.store') }}" method="POST" class="space-y-4">
        @csrf

        <!-- Pilih Produk -->
        <div>
            <label for="product_id" class="block mb-2 text-gray-700 dark:text-gray-300">Produk</label>
            <input list="product-list" id="product_id" name="product_id" required
                placeholder="Ketik / pilih produk"
                class="border border-gray-300 dark:border-gray-600
                    rounded-lg px-3 py-2 w-full
                    bg-white dark:bg-gray-700
                    text-gray-800 dark:text-gray-100">
            <datalist id="product-list">
                @foreach($products as $product)
                    <option value="{{ $product->code }} - {{ $product->name }}">
                @endforeach
            </datalist>
        </div>

        <!-- Jumlah -->
        <div>
            <label for="quantity" class="block mb-2 text-gray-700 dark:text-gray-300">Jumlah Masuk</label>
            <input type="number" name="quantity" id="quantity" required min="1"
                   class="border border-gray-300 dark:border-gray-600
                               rounded-lg px-3 py-2 w-full
                               bg-white dark:bg-gray-700
                               text-gray-800 dark:text-gray-100">
        </div>

        <!-- Aksi -->
        <div class="flex justify-between">
            <a href="{{ route('admin.central_stocks.index') }}" class="bg-gray-500 hover:bg-gray-600 px-4 py-2 rounded-lg text-white">⬅️ Kembali</a>
            <button type="submit" class="bg-green-600 hover:bg-green-700 px-6 py-2 rounded-lg text-white">✅ Simpan</button>
        </div>
    </form>
</div>
@endsection
