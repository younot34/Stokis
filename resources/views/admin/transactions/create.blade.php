@extends('layouts.admin')
@section('title','Barang keluar')
@section('content')

<div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl p-6">
    <h2 class="text-2xl font-bold mb-6 text-gray-800 dark:text-gray-100 border-b pb-3 border-gray-200 dark:border-gray-700">
        ➕ Buat Transaksi
    </h2>

    @if(session('success'))
        <div class="bg-green-50 dark:bg-green-900 border-l-4 border-green-400 text-green-700 dark:text-green-200 p-4 rounded shadow">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-50 dark:bg-red-900 border-l-4 border-red-400 text-red-700 dark:text-red-200 p-4 rounded shadow">
            <ul class="list-disc pl-5 space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <datalist id="productCodesAll">
        @foreach($allItems as $item)
            <option value="{{ $item['code'] }}"
                data-id="{{ $item['id'] }}"
                data-name="{{ $item['name'] }}"
                data-category="{{ $item['category'] }}"
                data-subcategory="{{ $item['subcategory'] }}"
                data-price="{{ $item['price'] }}"
                data-type="{{ $item['type'] }}">
            </option>
        @endforeach
    </datalist>

    <datalist id="productNamesAll">
        @foreach($allItems as $item)
            <option value="{{ $item['name'] }}"
                data-id="{{ $item['id'] }}"
                data-code="{{ $item['code'] }}"
                data-category="{{ $item['category'] }}"
                data-subcategory="{{ $item['subcategory'] }}"
                data-price="{{ $item['price'] }}"
                data-type="{{ $item['type'] }}">
            </option>
        @endforeach
    </datalist>
    <div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl p-6">

        <form action="{{ route('admin.transactions.store') }}" method="POST">
            @csrf
            @if(auth()->user()->warehouse)
                {{-- user stokis: kirim hidden warehouse_id --}}
                <input type="hidden" name="warehouse_id" value="{{ auth()->user()->warehouse->id }}">
            @else
                {{-- admin: pilih warehouse --}}
                <div class="grid grid-cols-6 gap-6 mb-4">
                    <div>
                        <label for="warehouse_id" class="block font-semibold mb-1 text-gray-800 dark:text-gray-200">Warehouse</label>
                        <select id="warehouse_id" name="warehouse_id" class="border border-gray-400 dark:border-gray-600 p-2 rounded w-full bg-gray-100 dark:bg-gray-700 dark:text-gray-100" required>
                            <option value="">-- Pilih Warehouse --</option>
                            @foreach($warehouses as $warehouse)
                                <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            @endif
            <div class="grid grid-cols-2 gap-6 mb-4">
                <div>
                    <label class="block font-semibold mb-2 text-gray-700 dark:text-gray-200">Kode Transaksi</label>
                    <input type="text" name="code"
                        class="border w-full border-gray-400 dark:border-gray-600 rounded-lg shadow-sm px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                        placeholder="Masukkan kode transaksi (contoh: TX-20240918001)" required>
                </div>

                <div>
                    <label class="block font-semibold mb-2 text-gray-700 dark:text-gray-200">Keterangan Transaksi</label>
                    <textarea name="note" rows="1"
                        class="border w-full border-gray-400 dark:border-gray-600 rounded-lg shadow-sm px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 resize"
                        placeholder="Opsional"></textarea>
                </div>
            </div>

            <div id="items-wrapper" class="space-y-4">
                <div class="item-block">
                    <div class="item-row grid grid-cols-7 gap-3 items-start">

                        <div class="flex flex-col">
                            <label class="text-sm font-medium mb-1 text-gray-700 dark:text-gray-200">Kode Barang</label>
                            <input type="text" name="items[0][product_code]"
                                class="product-code border border-gray-400 dark:border-gray-600 px-2 py-1 rounded w-full
                                        bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                                list="productCodesAll">
                            <input type="hidden" name="items[0][product_id]" class="product-id">
                            <input type="hidden" name="items[0][type]" class="item-type" value="normal">
                        </div>
                        <div class="flex flex-col">
                            <label class="text-sm font-medium mb-1 text-gray-700 dark:text-gray-200">Nama Barang</label>
                            <input type="text" name="items[0][product_name]"
                                class="product-name border border-gray-400 dark:border-gray-600 px-2 py-1 rounded w-full
                                        bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                                list="productNamesAll">
                        </div>
                        <div class="flex flex-col">
                            <label class="text-sm font-medium mb-1 text-gray-700 dark:text-gray-200">Kategori</label>
                            <input type="text" name="items[0][category]"
                                class="category border border-gray-400 dark:border-gray-600 px-2 py-1 rounded bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100" readonly>
                        </div>
                        <div class="flex flex-col">
                            <label class="text-sm font-medium mb-1 text-gray-700 dark:text-gray-200">Subkategori</label>
                            <input type="text" name="items[0][subcategory]"
                                class="subcategory border border-gray-400 dark:border-gray-600 px-2 py-1 rounded bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100" readonly>
                        </div>
                        <div class="flex flex-col">
                            <label class="text-sm font-medium mb-1 text-gray-700 dark:text-gray-200">Qty</label>
                            <input type="number" name="items[0][quantity]" value="1"
                                class="quantity border border-gray-400 dark:border-gray-600 px-2 py-1 w-20 text-center rounded bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                        </div>
                        <div class="flex flex-col">
                            <label class="text-sm font-medium mb-1 text-gray-700 dark:text-gray-200">Harga</label>
                            <input type="number" name="items[0][price]"
                                class="price border border-gray-400 dark:border-gray-600 px-2 py-1 text-right rounded bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100" readonly>
                        </div>
                        <div class="flex flex-col">
                            <label class="text-sm font-medium mb-1 text-gray-700 dark:text-gray-200">Total</label>
                            <input type="number" name="items[0][total_price]"
                                class="total-price border border-gray-400 dark:border-gray-600 px-2 py-1 text-right rounded bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100" readonly>
                        </div>
                    </div>
                    <div class="flex flex-col mt-2">
                        <label class="text-sm font-medium mb-1 text-gray-700 dark:text-gray-200">Keterangan</label>
                        <input type="text" name="items[0][note]" placeholder="Keterangan" class="note border border-gray-400 dark:border-gray-600 dark:bg-gray-800
                          dark:text-gray-100 px-2 py-1 w-full rounded">
                    </div>
                </div>
            </div>
            <button type="button" id="addRow" class="mt-3 bg-gray-200 dark:bg-gray-600 text-gray-900 dark:text-gray-100 px-3 py-1 rounded">+ Tambah Barang</button>

            <div class="flex items-center justify-between">
                <a href="{{ route('admin.transactions.index') }}"
                class="mt-3 bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg shadow transition">
                    ⬅️ Kembali
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                    Kirim Notice
                </button>
            </div>
        </form>
        <div class="mt-6 flex justify-end">
            <div>
                <label class="block text-right font-bold mb-1 text-gray-700 dark:text-gray-200">Total Transaksi:</label>
                <input type="number" id="grandTotal" name="grand_total"
                    class="border border-gray-400 dark:border-gray-600 px-3 py-2 rounded text-right font-bold w-48 bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100" readonly>
            </div>
        </div>
    </div>
</div>
<script>
let rowIndex = 1;

// 🔹 Format angka ke ribuan (tanpa desimal)
function formatRupiah(angka) {
    if (!angka) return '0';
    angka = angka.toString().replace(/[^\d]/g, '');
    return angka.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

// 🔹 Hapus format ribuan (untuk hitung & kirim ke backend)
function unformatRupiah(angka) {
    if (!angka) return 0;
    return parseFloat(angka.toString().replace(/\./g, '')) || 0;
}

// 🔹 Hitung total per baris
function calculateRowTotal(row) {
    const qty = parseFloat(row.querySelector('.quantity').value) || 0;
    const price = unformatRupiah(row.querySelector('.price').value);
    const total = qty * price;
    row.querySelector('.total-price').value = formatRupiah(total);
    return total;
}

// 🔹 Hitung total keseluruhan
function calculateGrandTotal() {
    let sum = 0;
    document.querySelectorAll('.item-block').forEach(row => {
        sum += calculateRowTotal(row);
    });
    document.getElementById('grandTotal').value = formatRupiah(sum);
}

// 🔹 Jalankan saat qty berubah
document.addEventListener('input', (e) => {
    if (e.target.classList.contains('quantity')) {
        calculateGrandTotal();
    }
});

// 🔹 Fungsi update field produk
function updateFields(row, data) {
    row.querySelector('.product-id').value = data.id || '';
    row.querySelector('.product-code').value = data.code || '';
    row.querySelector('.product-name').value = data.name || '';
    row.querySelector('.category').value = data.category || '';
    row.querySelector('.subcategory').value = data.subcategory || '';
    row.querySelector('.price').value = formatRupiah(data.price || 0);
    row.querySelector('.quantity').value = 1;
    calculateGrandTotal();
}

// 🔹 Input listener — isi data otomatis
document.addEventListener('input', (e) => {
    const input = e.target;
    const row = input.closest('.item-block');

    if (e.target.matches('.product-code')) {
        const option = document.querySelector(`#productCodesAll option[value="${input.value}"]`);
        if (option) {
            updateFields(row, {
                id: option.dataset.id,
                name: option.dataset.name,
                category: option.dataset.category,
                subcategory: option.dataset.subcategory,
                price: parseInt(option.dataset.price),
                code: option.value
            });
        }
    }

    if (e.target.matches('.product-name')) {
        const option = document.querySelector(`#productNamesAll option[value="${input.value}"]`);
        if (option) {
            updateFields(row, {
                id: option.dataset.id,
                code: option.dataset.code,
                category: option.dataset.category,
                subcategory: option.dataset.subcategory,
                price: parseInt(option.dataset.price),
                name: option.value
            });
        }
    }
});

// 🔹 Tombol tambah baris baru
document.getElementById('addRow').addEventListener('click', () => {
    const wrapper = document.getElementById('items-wrapper');
    const newRow = document.createElement('div');
    newRow.classList.add('item-block');
    newRow.innerHTML = `
        <div class="item-row grid grid-cols-7 gap-3 items-start">
            <div class="flex flex-col">
                <label class="text-sm font-medium mb-1 text-gray-700 dark:text-gray-200">Kode Barang</label>
                <input type="text" name="items[${rowIndex}][product_code]"
                    class="product-code border border-gray-400 dark:border-gray-600 px-2 py-1 rounded w-full
                           bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100" list="productCodesAll">
                <input type="hidden" name="items[${rowIndex}][product_id]" class="product-id">
                <input type="hidden" name="items[${rowIndex}][type]" class="item-type" value="normal">
            </div>
            <div class="flex flex-col">
                <label class="text-sm font-medium mb-1 text-gray-700 dark:text-gray-200">Nama Barang</label>
                <input type="text" name="items[${rowIndex}][product_name]"
                    class="product-name border border-gray-400 dark:border-gray-600 px-2 py-1 rounded w-full
                           bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100" list="productNamesAll">
            </div>
            <div class="flex flex-col">
                <label class="text-sm font-medium mb-1 text-gray-700 dark:text-gray-200">Kategori</label>
                <input type="text" name="items[${rowIndex}][category]"
                    class="category border border-gray-400 dark:border-gray-600 px-2 py-1 rounded bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100" readonly>
            </div>
            <div class="flex flex-col">
                <label class="text-sm font-medium mb-1 text-gray-700 dark:text-gray-200">Subkategori</label>
                <input type="text" name="items[${rowIndex}][subcategory]"
                    class="subcategory border border-gray-400 dark:border-gray-600 px-2 py-1 rounded bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100" readonly>
            </div>
            <div class="flex flex-col">
                <label class="text-sm font-medium mb-1 text-gray-700 dark:text-gray-200">Qty</label>
                <input type="number" name="items[${rowIndex}][quantity]" value="1"
                    class="quantity border border-gray-400 dark:border-gray-600 px-2 py-1 w-20 text-center rounded bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
            </div>
            <div class="flex flex-col">
                <label class="text-sm font-medium mb-1 text-gray-700 dark:text-gray-200">Harga</label>
                <input type="text" name="items[${rowIndex}][price]"
                    class="price border border-gray-400 dark:border-gray-600 px-2 py-1 text-right rounded bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100" readonly>
            </div>
            <div class="flex flex-col">
                <label class="text-sm font-medium mb-1 text-gray-700 dark:text-gray-200">Total</label>
                <input type="text" name="items[${rowIndex}][total_price]"
                    class="total-price border border-gray-400 dark:border-gray-600 px-2 py-1 text-right rounded bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100" readonly>
            </div>
        </div>
        <div class="flex flex-col mt-2">
            <label class="text-sm font-medium mb-1 text-gray-700 dark:text-gray-200">Keterangan</label>
            <input type="text" name="items[${rowIndex}][note]" placeholder="Keterangan"
                class="note border border-gray-400 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100 px-2 py-1 w-full rounded">
        </div>`;
    wrapper.appendChild(newRow);
    rowIndex++;
});

// 🔹 Saat submit form → ubah format harga ke angka mentah
document.querySelector('form').addEventListener('submit', function() {
    document.querySelectorAll('.price, .total-price, #grandTotal').forEach(el => {
        el.value = unformatRupiah(el.value);
    });
});
</script>
@endsection
