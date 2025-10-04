@extends('layouts.admin')

@section('content')
<div class="mx-auto py-6 px-4 sm:px-6 lg:px-8 space-y-6">

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <h1 class="text-3xl font-extrabold text-gray-900 dark:text-gray-100 mb-4 sm:mb-0">Kirim Barang</h1>
    </div>

    <div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl p-6">
        <form action="{{ route('admin.kirims.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">

            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="po_code" class="block font-semibold mb-1 text-gray-800 dark:text-gray-200">Kode Kirim</label>
                    <input type="text" id="po_code" name="po_code" class="border border-gray-400 dark:border-gray-600 p-2 rounded w-full bg-gray-100 dark:bg-gray-700 dark:text-gray-100" readonly>
                </div>

                <div>
                    <label for="warehouse_id" class="block font-semibold mb-1 text-gray-800 dark:text-gray-200">Warehouse</label>
                    <select id="warehouse_id" name="warehouse_id" class="border border-gray-400 dark:border-gray-600 p-2 rounded w-full bg-gray-100 dark:bg-gray-700 dark:text-gray-100">
                        <option value="">-- Pilih Warehouse --</option>
                        @foreach($warehouses as $warehouse)
                            <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-6 mb-4">
                <div>
                    <label class="block font-semibold mb-2 text-gray-700 dark:text-gray-200">Jasa Pengiriman</label>
                    <select name="jasa_pengiriman" class="border border-gray-400 dark:border-gray-600 p-2 rounded w-full bg-gray-100 dark:bg-gray-700 dark:text-gray-100">
                        <option value="">-- Pilih Jasa Pengiriman --</option>
                        <option value="Instan Gojek">Instan Gojek</option>
                        <option value="Instan Grab">Instan Grab</option>
                        <option value="Jnt">Jnt</option>
                        <option value="Jne">Jne</option>
                        <option value="Sicepat">Sicepat</option>
                        <option value="Ninja Express">Ninja Express</option>
                        <option value="Tiki">Tiki</option>
                        <option value="Pos">Pos</option>
                    </select>
                </div>
                <div>
                    <label class="block font-semibold mb-2 text-gray-700 dark:text-gray-200">Nomor Resi</label>
                    <input type="text" name="resi_number"
                        class="w-full border rounded p-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                </div>
            </div>

            <div class="mb-4">
                <label class="block font-semibold mb-2 text-gray-700 dark:text-gray-200">Upload Gambar</label>
                <input type="file" name="image"
                    class="w-full border rounded p-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full border border-gray-400 dark:border-gray-600 rounded-lg" id="poTable">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="border border-gray-400 dark:border-gray-600 p-2 text-left text-sm font-medium text-gray-600 dark:text-gray-300">Kode Barang</th>
                            <th class="border border-gray-400 dark:border-gray-600 p-2 text-left text-sm font-medium text-gray-600 dark:text-gray-300">Nama Barang</th>
                            <th class="border border-gray-400 dark:border-gray-600 p-2 text-left text-sm font-medium text-gray-600 dark:text-gray-300">Jumlah</th>
                            <th class="border border-gray-400 dark:border-gray-600 p-2 text-left text-sm font-medium text-gray-600 dark:text-gray-300">Harga</th>
                            <th id="thDiskon" class="border border-gray-400 dark:border-gray-600 p-2 text-left text-sm font-medium text-gray-600 dark:text-gray-300">Diskon</th>
                            <th id="thHargaDiskon" class="border border-gray-400 dark:border-gray-600 p-2 text-left text-sm font-medium text-gray-600 dark:text-gray-300">Harga diskon</th>
                            <th class="border border-gray-400 dark:border-gray-600 p-2 text-left text-sm font-medium text-gray-600 dark:text-gray-300">Subtotal</th>
                            <th class="border border-gray-400 dark:border-gray-600 p-2 text-left text-sm font-medium text-gray-600 dark:text-gray-300">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="poBody" class="bg-white dark:bg-gray-800 divide-y divide-gray-400 dark:divide-gray-600"></tbody>
                </table>
            </div>

            <div class="flex justify-between items-center">
                <button type="button" id="addRow"
                    class="bg-green-500 hover:bg-green-600 text-white p-2 px-4 py-2 rounded-lg shadow transition-all">
                    + Tambah Barang
                </button>

                <div class="space-x-4 font-semibold text-gray-700 dark:text-gray-200">
                    <span>Total Qty: <span id="totalQty">0</span></span>
                    <span>Total Harga: <span id="totalHarga">Rp 0</span></span>
                </div>
            </div>

            <div class="flex items-center justify-between">
                <a href="{{ route('admin.kirims.index') }}"
                class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg shadow transition">
                    ⬅️ Kembali
                </a>
                <button type="submit"
                    class="bg-blue-500 hover:bg-blue-600 text-white px-5 py-2 rounded-lg shadow transition-all">
                    Kirim PO
                </button>
            </div>
        </form>
    </div>
</div>

<datalist id="productCodesAll">
    @foreach($products as $product)
        <option value="{{ $product->code }}"
            data-id="{{ $product->id }}"
            data-name="{{ $product->name }}"
            data-price="{{ $product->price }}"
            data-discount="{{ $product->discount }}">
    @endforeach
</datalist>

<datalist id="productNamesAll">
    @foreach($products as $product)
        <option value="{{ $product->name }}"
            data-id="{{ $product->id }}"
            data-code="{{ $product->code }}"
            data-price="{{ $product->price }}"
            data-discount="{{ $product->discount }}">
    @endforeach
</datalist>

<script>
    let products = @json($products);
    let rowId = 0;

    function addRow() {
        let row = `
            <tr class="row-item">
                <td>
                    <input type="hidden" name="items[${rowId}][use_discount]" class="use_discount" value="0">
                    <input type="text" name="items[${rowId}][code]"
                        class="code border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 w-full
                               bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100"
                        list="productCodesAll">
                    <input type="hidden" name="items[${rowId}][product_id]" class="product_id">
                </td>
                <td>
                    <input type="text" name="items[${rowId}][name]"
                        class="name border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 w-full
                               bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100"
                        list="productNamesAll">
                </td>
                <td><input type="number" name="items[${rowId}][qty]" value="1" min="1"
                        class="qty border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 w-full
                               bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100"></td>
                <td><input type="number" name="items[${rowId}][harga]" readonly
                        class="harga border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 w-full
                               bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100 bg-gray-100"></td>
                <td><input type="number" name="items[${rowId}][diskon]" readonly
                        class="diskon border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 w-full
                               bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100 bg-gray-100"></td>
                <td><input type="number" name="items[${rowId}][harga_diskon]" readonly
                        class="harga-diskon border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 w-full
                               bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100 bg-gray-100"></td>
                <td><input type="number" class="subtotal border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 w-full
                               bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100 bg-gray-100" readonly></td>
                <td class="text-center"><button type="button" class="remove text-red-500 font-bold">X</button></td>
            </tr>`;
        document.getElementById('poBody').insertAdjacentHTML('beforeend', row);
        rowId++;
    }

    // tombol tambah row
    const addRowBtn = document.getElementById('addRow');
    if(addRowBtn){
        addRowBtn.addEventListener('click', addRow);
    }

    // input listener
    document.addEventListener('input', e => {
        if(e.target.classList.contains('code') || e.target.classList.contains('name')){
            let value = e.target.value;
            let product = products.find(p => p.code === value || p.name === value);
            if(product){
                let row = e.target.closest('tr');
                row.querySelector('.name').value = product.name;
                row.querySelector('.code').value = product.code;
                row.querySelector('.harga').value = product.price;
                row.querySelector('.product_id').value = product.id;

                if(product.discount > 0){
                    row.querySelector('.diskon').value = product.discount;
                    row.querySelector('.harga-diskon').value = product.discount_price
                        ? product.discount_price
                        : (product.price - (product.price * product.discount / 100));
                    row.querySelector('.use_discount').value = 1;
                } else {
                    row.querySelector('.diskon').value = "";
                    row.querySelector('.harga-diskon').value = "";
                    row.querySelector('.use_discount').value = 0;
                }
                updateTotals();
            }
        }
        if(e.target.classList.contains('qty')){
            updateTotals();
        }
    });

    // hitung total
    function updateTotals() {
        let totalQty = 0;
        let totalHarga = 0;

        document.querySelectorAll('.row-item').forEach(row => {
            let qty = parseInt(row.querySelector('.qty').value) || 0;
            let hargaDiskon = parseInt(row.querySelector('.harga-diskon').value) || 0;
            let hargaNormal = parseInt(row.querySelector('.harga').value) || 0;

            let harga = hargaDiskon > 0 ? hargaDiskon : hargaNormal;
            let subtotal = qty * harga;
            row.querySelector('.subtotal').value = subtotal;

            totalQty += qty;
            totalHarga += subtotal;
        });

        document.getElementById('totalQty').innerText = totalQty;
        document.getElementById('totalHarga').innerText = 'Rp ' + totalHarga.toLocaleString();
    }

    // hapus row
    document.addEventListener('click', function(e){
        if(e.target.matches('.remove')){
            e.preventDefault();
            const tr = e.target.closest('tr');
            if(tr){
                tr.remove();
                updateTotals();
            }
        }
    });

    document.getElementById('warehouse_id').addEventListener('change', function () {
        let warehouseId = this.value;
        if (warehouseId) {
            fetch(`/admin/generate-kirim-code/${warehouseId}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('po_code').value = data.code;
                });
        } else {
            document.getElementById('po_code').value = '';
        }
    });
</script>

@endsection
