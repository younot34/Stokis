@extends('layouts.warehouse')
@section('title','Barang Keluar')
@section('content')
<div class="mx-auto py-6 px-4 sm:px-6 lg:px-8 space-y-6">

    <!-- Header -->
    <div class="flex items-center justify-between">
        <h2 class="text-3xl font-extrabold text-gray-900 dark:text-gray-100">Barang Keluar</h2>
    </div>

    <!-- Notifikasi -->
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

    <!-- Form Barang Keluar -->
    <div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl p-6">
        <form action="{{ route('warehouse.transactions.store') }}" method="POST">
            @csrf

            <!-- Kode Transaksi + Keterangan -->
            <div class="grid grid-cols-2 gap-6 mb-4">
                <!-- Kode Transaksi -->
                <div>
                    <label class="block font-semibold mb-2 text-gray-700 dark:text-gray-200">Kode Transaksi</label>
                    <input type="text" name="code"
                        class="border w-full border-gray-400 dark:border-gray-600 rounded-lg shadow-sm px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                        placeholder="Masukkan kode transaksi (contoh: TX-20240918001)" required>
                </div>

                <!-- Keterangan Transaksi -->
                <div>
                    <label class="block font-semibold mb-2 text-gray-700 dark:text-gray-200">Keterangan Transaksi</label>
                    <textarea name="note" rows="1"
                        class="border w-full border-gray-400 dark:border-gray-600 rounded-lg shadow-sm px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 resize"
                        placeholder="Opsional"></textarea>
                </div>
            </div>

            <!-- List produk -->
            <div id="items-wrapper" class="space-y-4">
                <div class="item-block">
                    <div class="item-row grid grid-cols-7 gap-3 items-start">

                        <!-- Kode Barang -->
                        <div class="flex flex-col">
                            <label class="text-sm font-medium mb-1 text-gray-700 dark:text-gray-200">Kode Barang</label>
                            <select name="items[0][product_code]"
                                class="product-code border border-gray-400 dark:border-gray-600 px-2 py-1 rounded w-full bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                                <option value="">-- Pilih Kode --</option>
                                @foreach(auth()->user()->warehouse->products as $product)
                                    <option value="{{ $product->code }}"
                                        data-id="{{ $product->id }}"
                                        data-name="{{ $product->name }}"
                                        data-category="{{ $product->category->parent->name ?? '' }}"
                                        data-subcategory="{{ $product->category->name ?? '' }}"
                                        data-price="{{ $product->price }}">
                                        {{ $product->code }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <!-- Nama Barang -->
                        <div class="flex flex-col">
                            <label class="text-sm font-medium mb-1 text-gray-700 dark:text-gray-200">Nama Barang</label>
                            <select name="items[0][product_id]"
                                class="product-name border border-gray-400 dark:border-gray-600 px-2 py-1 rounded w-full bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                                <option value="">-- Pilih Produk --</option>
                                @foreach(auth()->user()->warehouse->products as $product)
                                    <option value="{{ $product->id }}"
                                        data-code="{{ $product->code }}"
                                        data-category="{{ $product->category->parent->name ?? '' }}"
                                        data-subcategory="{{ $product->category->name ?? '' }}"
                                        data-price="{{ $product->price }}">
                                        {{ $product->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <!-- Kategori -->
                        <div class="flex flex-col">
                            <label class="text-sm font-medium mb-1 text-gray-700 dark:text-gray-200">Kategori</label>
                            <input type="text" name="items[0][category]"
                                class="category border border-gray-400 dark:border-gray-600 px-2 py-1 rounded bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100" readonly>
                        </div>
                        <!-- Subkategori -->
                        <div class="flex flex-col">
                            <label class="text-sm font-medium mb-1 text-gray-700 dark:text-gray-200">Subkategori</label>
                            <input type="text" name="items[0][subcategory]"
                                class="subcategory border border-gray-400 dark:border-gray-600 px-2 py-1 rounded bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100" readonly>
                        </div>
                         <!-- Jumlah -->
                        <div class="flex flex-col">
                            <label class="text-sm font-medium mb-1 text-gray-700 dark:text-gray-200">Qty</label>
                            <input type="number" name="items[0][quantity]" value="1"
                                class="quantity border border-gray-400 dark:border-gray-600 px-2 py-1 w-20 text-center rounded bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                        </div>
                        <!-- Harga -->
                        <div class="flex flex-col">
                            <label class="text-sm font-medium mb-1 text-gray-700 dark:text-gray-200">Harga</label>
                            <input type="number" name="items[0][price]"
                                class="price border border-gray-400 dark:border-gray-600 px-2 py-1 text-right rounded bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100" readonly>
                        </div>
                        <!-- Total Harga -->
                        <div class="flex flex-col">
                            <label class="text-sm font-medium mb-1 text-gray-700 dark:text-gray-200">Total</label>
                            <input type="number" name="items[0][total_price]"
                                class="total-price border border-gray-400 dark:border-gray-600 px-2 py-1 text-right rounded bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100" readonly>
                        </div>
                    </div>
                    <!-- Keterangan -->
                    <div class="flex flex-col mt-2">
                        <label class="text-sm font-medium mb-1 text-gray-700 dark:text-gray-200">Keterangan</label>
                        <input type="text" name="items[0][note]" placeholder="Keterangan" class="note border border-gray-400 dark:border-gray-600 dark:bg-gray-800
                          dark:text-gray-100 px-2 py-1 w-full rounded">
                    </div>
                </div>
            </div>

            <!-- Tombol tambah row -->
            <button type="button" id="addRow" class="mt-3 bg-gray-200 dark:bg-gray-600 text-gray-900 dark:text-gray-100 px-3 py-1 rounded">+ Tambah Barang</button>

            <!-- Submit -->
            <div class="mt-4">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                    Simpan Transaksi
                </button>
            </div>
        </form>
        <!-- Grand Total -->
        <div class="mt-6 flex justify-end">
            <div>
                <label class="block text-right font-bold mb-1 text-gray-700 dark:text-gray-200">Total Transaksi:</label>
                <input type="number" id="grandTotal" name="grand_total"
                    class="border border-gray-400 dark:border-gray-600 px-3 py-2 rounded text-right font-bold w-48 bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100" readonly>
            </div>
        </div>
    </div>

    <!-- Tabel Transaksi Terakhir -->
    <div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl overflow-hidden">
        <h3 class="text-xl font-bold text-gray-800 dark:text-gray-100 px-6 py-4 border-b border-gray-200 dark:border-gray-700">Transaksi Terakhir</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-400 dark:divide-gray-600">
                <thead class="bg-gray-200 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-300 uppercase">Kode Transaksi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-300 uppercase">Gudang / Stokis</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-300 uppercase">Kode Barang</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-300 uppercase">Produk</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-300 uppercase">Kategori</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-300 uppercase">Subkategori</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-300 uppercase">Harga</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-300 uppercase">Jumlah</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-300 uppercase">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-300 uppercase">Keterangan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-300 uppercase">Tanggal</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-400 dark:divide-gray-600">
                    @forelse($transactions as $tx)
                        <tbody class="group">
                            @foreach($tx->items as $key => $item)
                                <tr class="group-hover:bg-yellow-100 dark:group-hover:bg-gray-700 transition cursor-pointer">
                                    @if($key == 0)
                                        <td class="px-6 py-4 whitespace-nowrap align-top" rowspan="{{ $tx->items->count() }}">
                                            <div class="font-semibold text-gray-800 dark:text-gray-100">{{ $tx->code }}</div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                                Total: Rp {{ number_format($tx->grand_total,0,',','.') }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap align-top text-gray-700 dark:text-gray-200" rowspan="{{ $tx->items->count() }}">
                                            {{ $tx->warehouse->name ?? '-' }}
                                        </td>
                                    @endif

                                    <td class="px-6 py-4 whitespace-nowrap text-gray-700 dark:text-gray-200">{{ $item->product_code ?? '-' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-700 dark:text-gray-200">{{ $item->product_name ?? '-' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-700 dark:text-gray-200">{{ $item->category_name ?? '-' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-700 dark:text-gray-200">{{ $item->subcategory_name ?? '-' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-gray-700 dark:text-gray-200">
                                        {{ number_format($item->price,0,',','.') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-gray-700 dark:text-gray-200">{{ $item->quantity }}</td>

                                    @if($key == 0)
                                        <td class="px-6 py-4 whitespace-nowrap align-top text-gray-700 dark:text-gray-200" rowspan="{{ $tx->items->count() }}">
                                            {{ $tx->creator->name ?? '-' }}
                                        </td>
                                    @endif

                                    <td class="px-6 py-4 whitespace-nowrap text-gray-700 dark:text-gray-200">{{ $item->note ?? '-' }}</td>

                                    @if($key == 0)
                                        <td class="px-6 py-4 whitespace-nowrap align-top text-gray-700 dark:text-gray-200" rowspan="{{ $tx->items->count() }}">
                                            {{ $tx->created_at->format('d M Y H:i') }}
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    @empty
                        <tr>
                            <td colspan="10" class="px-6 py-4 text-center text-gray-400 dark:text-gray-500">Belum ada transaksi</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
            {{ $transactions->links() }}
        </div>
    </div>
</div>
<script>
let rowIndex = 1;

function calculateRowTotal(row) {
    const qty = parseFloat(row.querySelector('.quantity').value) || 0;
    const price = parseFloat(row.querySelector('.price').value) || 0;
    const total = qty * price;
    row.querySelector('.total-price').value = total;
    return total;
}

function calculateGrandTotal() {
    let sum = 0;
    document.querySelectorAll('.item-block').forEach(row => {
        sum += calculateRowTotal(row);
    });
    document.getElementById('grandTotal').value = sum;
}

document.addEventListener('input', (e) => {
    if (e.target.classList.contains('quantity')) {
        calculateGrandTotal();
    }
});

// Panggil sekali waktu halaman load
calculateGrandTotal();

function updateFields(row, data) {
    row.querySelector('.product-code').value = data.code || '';
    row.querySelector('.product-name').value = data.id || '';
    row.querySelector('.category').value = data.category || '';
    row.querySelector('.subcategory').value = data.subcategory || '';
    row.querySelector('.price').value = data.price || '';
    row.querySelector('.quantity').value = 1; // default 1
}

// event handler pilih kode
document.addEventListener('change', (e) => {
    if (e.target.classList.contains('product-code')) {
        const row = e.target.closest('.item-block');
        const selected = e.target.options[e.target.selectedIndex];
        updateFields(row, {
            code: selected.value,
            id: selected.dataset.id,
            category: selected.dataset.category,
            subcategory: selected.dataset.subcategory,
            price: selected.dataset.price
        });
    }
});

// event handler pilih nama
document.addEventListener('change', (e) => {
    if (e.target.classList.contains('product-name')) {
        const row = e.target.closest('.item-block');
        const selected = e.target.options[e.target.selectedIndex];
        updateFields(row, {
            code: selected.dataset.code,
            id: selected.value,
            category: selected.dataset.category,
            subcategory: selected.dataset.subcategory,
            price: selected.dataset.price
        });
    }
});

// tombol tambah row
document.getElementById('addRow').addEventListener('click', () => {
    const wrapper = document.getElementById('items-wrapper');
    const newRow = document.createElement('div');
    newRow.classList.add('item-block');
    newRow.innerHTML = `
        <div class="item-row grid grid-cols-7 gap-3 items-start">
            <div class="flex flex-col">
                <label class="text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">Kode Barang</label>
                <select name="items[${rowIndex}][product_code]"
                        class="product-code border border-gray-400 dark:border-gray-600 dark:bg-gray-800
                               dark:text-gray-100 px-2 py-1 rounded w-full">
                    <option value="">-- Pilih Kode --</option>
                    @foreach(auth()->user()->warehouse->products as $product)
                        <option value="{{ $product->code }}"
                                data-id="{{ $product->id }}"
                                data-name="{{ $product->name }}"
                                data-category="{{ $product->category->parent->name ?? '' }}"
                                data-subcategory="{{ $product->category->name ?? '' }}"
                                data-price="{{ $product->price }}">
                            {{ $product->code }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex flex-col">
                <label class="text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">Nama Barang</label>
                <select name="items[${rowIndex}][product_id]"
                        class="product-name border border-gray-400 dark:border-gray-600 dark:bg-gray-800
                               dark:text-gray-100 px-2 py-1 rounded w-full">
                    <option value="">-- Pilih Produk --</option>
                    @foreach(auth()->user()->warehouse->products as $product)
                        <option value="{{ $product->id }}"
                                data-code="{{ $product->code }}"
                                data-category="{{ $product->category->parent->name ?? '' }}"
                                data-subcategory="{{ $product->category->name ?? '' }}"
                                data-price="{{ $product->price }}">
                            {{ $product->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex flex-col">
                <label class="text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">Kategori</label>
                <input type="text" name="items[${rowIndex}][category]"
                       class="category border border-gray-400 dark:border-gray-600 dark:bg-gray-800
                              dark:text-gray-100 px-2 py-1 rounded" readonly>
            </div>
            <div class="flex flex-col">
                <label class="text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">Subkategori</label>
                <input type="text" name="items[${rowIndex}][subcategory]"
                       class="subcategory border border-gray-400 dark:border-gray-600 dark:bg-gray-800
                              dark:text-gray-100 px-2 py-1 rounded" readonly>
            </div>
            <div class="flex flex-col">
                <label class="text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">Qty</label>
                <input type="number" name="items[${rowIndex}][quantity]" value="1"
                       class="quantity border border-gray-400 dark:border-gray-600 dark:bg-gray-800
                              dark:text-gray-100 px-2 py-1 w-20 text-center rounded">
            </div>
            <div class="flex flex-col">
                <label class="text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">Harga</label>
                <input type="number" step="0.01" name="items[${rowIndex}][price]"
                       class="price border border-gray-400 dark:border-gray-600 dark:bg-gray-800
                              dark:text-gray-100 px-2 py-1 rounded text-right" readonly>
            </div>
            <div class="flex flex-col">
                <label class="text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">Total</label>
                <input type="number" name="items[${rowIndex}][total_price]"
                       class="total-price border border-gray-400 dark:border-gray-600 dark:bg-gray-800
                              dark:text-gray-100 px-2 py-1 rounded text-right" readonly>
            </div>
        </div>
        <div class="flex flex-col mt-2">
            <label class="text-sm font-medium mb-1 text-gray-700 dark:text-gray-300">Keterangan</label>
            <input type="text" name="items[${rowIndex}][note]" placeholder="Keterangan"
                   class="note border border-gray-400 dark:border-gray-600 dark:bg-gray-800
                          dark:text-gray-100 px-2 py-1 w-full rounded">
        </div>
    `;
    wrapper.appendChild(newRow);
    rowIndex++;
});
</script>
@endsection
