@extends('layouts.admin')
@section('title','Tambah Stok Pusat')

@section('content')
<div class="max-w-2xl mx-auto bg-white dark:bg-gray-800 p-6 rounded-xl shadow-lg">
    <h2 class="text-xl font-bold mb-4 text-gray-800 dark:text-gray-100">➕ Tambah Stok Pusat</h2>

    <form action="{{ route('admin.central_stocks.store') }}" method="POST" class="space-y-4">
        @csrf

        <div id="stock-items">
            <div class="stock-row flex gap-4 mb-3">
                <div class="flex-1">
                    <label class="block mb-1 text-gray-700 dark:text-gray-300">Produk</label>
                    <input list="product-list" name="product_id[]" required
                        placeholder="Ketik / pilih produk"
                        class="border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 w-full
                               bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100">
                </div>
                <div class="w-32">
                    <label class="block mb-1 text-gray-700 dark:text-gray-300">Jumlah</label>
                    <input type="number" name="quantity[]" required min="1"
                           class="border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 w-full
                                  bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100">
                </div>
                <button type="button" onclick="removeRow(this)"
                    class="self-end bg-red-500 text-white px-3 py-2 rounded-lg">✖</button>
            </div>
        </div>
        <button type="button" onclick="addRow()"
            class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">+ Tambah Produk</button>
        <div class="flex justify-between mt-6">
            <a href="{{ route('admin.central_stocks.index') }}" class="bg-gray-500 hover:bg-gray-600 px-4 py-2 rounded-lg text-white">⬅️ Kembali</a>
            <button type="submit" class="bg-green-600 hover:bg-green-700 px-6 py-2 rounded-lg text-white">✅ Simpan</button>
        </div>
    </form>

    <datalist id="product-list">
        @foreach($products as $product)
            <option value="{{ $product->code }} - {{ $product->name }}">
        @endforeach
    </datalist>
</div>

<script>
function addRow() {
    let container = document.getElementById('stock-items');
    let row = container.querySelector('.stock-row').cloneNode(true);

    row.querySelectorAll('input').forEach(input => input.value = '');
    container.appendChild(row);
}

function removeRow(btn) {
    let container = document.getElementById('stock-items');
    if (container.querySelectorAll('.stock-row').length > 1) {
        btn.parentElement.remove();
    }
}
</script>
@endsection
