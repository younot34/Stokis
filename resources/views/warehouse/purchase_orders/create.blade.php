@extends('layouts.warehouse')

@section('content')
<div class="mx-auto py-6 px-4 sm:px-6 lg:px-8 space-y-6">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <h1 class="text-3xl font-extrabold text-gray-900 mb-4 sm:mb-0">Buat Purchase Order</h1>
        <span class="text-gray-600">Stokis: <strong>{{ $warehouse->name }}</strong></span>
    </div>

    <!-- Form PO -->
    <div class="bg-white shadow-lg rounded-xl p-6">
        <form action="{{ route('warehouse.purchase_orders.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block font-semibold mb-1">Kode PO</label>
                    <input type="text" name="po_code" value="{{ $poCode }}" readonly
                           class="border border-gray-400 p-2 rounded w-full bg-gray-100">
                </div>

                <div>
                    <label class="block font-semibold mb-1">Stokis</label>
                    <input type="text" value="{{ $warehouse->name }}" readonly
                           class="border border-gray-400 p-2 rounded w-full bg-gray-100">
                    <input type="hidden" name="warehouse_id" value="{{ $warehouse->id }}">
                </div>
            </div>

            <!-- Tabel Barang -->
            <div class="overflow-x-auto">
                <table class="min-w-full border border-gray-400 rounded-lg" id="poTable">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="border border-gray-400 p-2 rounded px-3 py-2 text-left text-sm font-medium text-gray-500">Kode Barang</th>
                            <th class="border border-gray-400 p-2 rounded px-3 py-2 text-left text-sm font-medium text-gray-500">Nama Barang</th>
                            <th class="border border-gray-400 p-2 rounded px-3 py-2 text-left text-sm font-medium text-gray-500">Jumlah</th>
                            <th class="border border-gray-400 p-2 rounded px-3 py-2 text-left text-sm font-medium text-gray-500">Harga</th>
                            <th class="border border-gray-400 p-2 rounded px-3 py-2 text-left text-sm font-medium text-gray-500">Subtotal</th>
                            <th class="border border-gray-400 p-2 rounded px-3 py-2 text-left text-sm font-medium text-gray-500">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="poBody" class="bg-white divide-y divide-gray-400"></tbody>
                </table>
            </div>

            <div class="flex justify-between items-center">
                <button type="button" id="addRow"
                    class="bg-green-500 hover:bg-green-600 text-white border border-gray-400 p-2 px-4 py-2 rounded-lg shadow transition-all">
                    + Tambah Barang
                </button>

                <div class="space-x-4 font-semibold text-gray-700">
                    <span>Total Qty: <span id="totalQty">0</span></span>
                    <span>Total Harga: <span id="totalHarga">Rp 0</span></span>
                </div>
            </div>

            <div class="text-right">
                <button type="submit"
                    class="bg-blue-500 hover:bg-blue-600 text-white px-5 py-2 rounded-lg shadow transition-all">
                    Kirim PO
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Datalist untuk autocomplete -->
<datalist id="productCodes">
    @foreach($products as $product)
        <option value="{{ $product->code }}" data-id="{{ $product->id }}" data-name="{{ $product->name }}" data-price="{{ $product->price }}">
    @endforeach
</datalist>

<datalist id="productNames">
    @foreach($products as $product)
        <option value="{{ $product->name }}" data-id="{{ $product->id }}" data-code="{{ $product->code }}" data-price="{{ $product->price }}">
    @endforeach
</datalist>

<script>
let products = @json($products);
let rowId = 0;

function updateTotals() {
    let totalQty = 0;
    let totalHarga = 0;

    document.querySelectorAll('.row-item').forEach(row => {
        let qty = parseInt(row.querySelector('.qty').value) || 0;
        let harga = parseInt(row.querySelector('.harga').value) || 0;
        let subtotal = qty * harga;
        row.querySelector('.subtotal').value = subtotal;

        totalQty += qty;
        totalHarga += subtotal;
    });

    document.getElementById('totalQty').innerText = totalQty;
    document.getElementById('totalHarga').innerText = 'Rp ' + totalHarga.toLocaleString();
}

function addRow() {
    let row = `
        <tr class="border border-gray-400 row-item hover:bg-gray-50 transition">
            <td><input type="text" name="items[${rowId}][code]" class="border border-gray-400 p-1 w-full code rounded" list="productCodes">
                <input type="hidden" name="items[${rowId}][product_id]" class="product_id"></td>
            <td><input type="text" name="items[${rowId}][name]" class="border border-gray-400 p-1 w-full name rounded" list="productNames"></td>
            <td><input type="number" name="items[${rowId}][qty]" class="border border-gray-400 p-1 w-full qty rounded" min="1" value="1"></td>
            <td><input type="number" name="items[${rowId}][harga]" class="border border-gray-400 p-1 w-full harga rounded" readonly></td>
            <td><input type="number" class="border border-gray-400 p-1 w-full subtotal rounded" readonly></td>
            <td><button type="button" class="remove bg-red-500 hover:bg-red-600 text-white px-2 rounded transition">X</button></td>
        </tr>
    `;
    document.getElementById('poBody').insertAdjacentHTML('beforeend', row);
    rowId++;
}

document.getElementById('addRow').addEventListener('click', addRow);

document.addEventListener('input', e => {
    if(e.target.classList.contains('code')){
        let code = e.target.value;
        let product = products.find(p => p.code === code);
        if(product){
            let row = e.target.closest('tr');
            row.querySelector('.name').value = product.name;
            row.querySelector('.harga').value = product.price;
            row.querySelector('.product_id').value = product.id;
            updateTotals();
        }
    }
    if(e.target.classList.contains('name')){
        let name = e.target.value;
        let product = products.find(p => p.name === name);
        if(product){
            let row = e.target.closest('tr');
            row.querySelector('.code').value = product.code;
            row.querySelector('.harga').value = product.price;
            row.querySelector('.product_id').value = product.id;
            updateTotals();
        }
    }
    if(e.target.classList.contains('qty')){
        updateTotals();
    }
});

document.addEventListener('click', e => {
    if(e.target.classList.contains('remove')){
        e.target.closest('tr').remove();
        updateTotals();
    }
});
</script>
@endsection
