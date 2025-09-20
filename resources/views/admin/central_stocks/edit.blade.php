@extends('layouts.admin')
@section('title','Edit Stok Pusat')

@section('content')
<div class="max-w-xl mx-auto bg-white dark:bg-gray-800 p-6 rounded-xl shadow-lg">
    <h2 class="text-xl font-bold mb-4 text-gray-800 dark:text-gray-100">✏️ Edit Stok Pusat</h2>
    <form action="{{ route('admin.central_stocks.update',$central_stock->id) }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')

        <!-- Pilih Produk -->
        <div>
            <label for="product_id" class="block mb-2 text-gray-700 dark:text-gray-300">Produk</label>
            <select name="product_id" id="product_id" required
                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700">
                @foreach($products as $product)
                    <option value="{{ $product->id }}" {{ $central_stock->product_id == $product->id ? 'selected' : '' }}>
                        {{ $product->code }} - {{ $product->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Jumlah -->
        <div>
            <label for="quantity" class="block mb-2 text-gray-700 dark:text-gray-300">Jumlah Masuk</label>
            <input type="number" name="quantity" id="quantity" required min="1"
                   value="{{ old('quantity',$central_stock->quantity) }}"
                   class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700">
        </div>

        <!-- Aksi -->
        <div class="flex justify-between">
            <a href="{{ route('admin.central_stocks.index') }}" class="bg-gray-500 hover:bg-gray-600 px-4 py-2 rounded-lg text-white">⬅️ Kembali</a>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 px-6 py-2 rounded-lg text-white">💾 Update</button>
        </div>
    </form>
</div>
@endsection
