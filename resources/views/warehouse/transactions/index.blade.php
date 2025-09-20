@extends('layouts.warehouse')
@section('title','Barang Keluar')
@section('content')
<div class="mx-auto py-6 px-4 sm:px-6 lg:px-8 space-y-6">

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
        <form action="{{ route('warehouse.transactions.store') }}" method="POST">
            @csrf

            <!-- Kode Transaksi + Keterangan -->
            <div class="grid grid-cols-2 gap-6 mb-4">
                <!-- Kode Transaksi -->
                <div>
                    <label class="block font-semibold mb-2 text-gray-700">Kode Transaksi</label>
                    <input type="text" name="code" class="border w-full border-gray-400 rounded-lg shadow-sm px-3 py-2"
                        placeholder="Masukkan kode transaksi (contoh: TX-20240918001)" required>
                </div>

                <!-- Keterangan Transaksi -->
                <div>
                    <label class="block font-semibold mb-2 text-gray-700">Keterangan Transaksi</label>
                    <textarea name="note" rows="1"
                            class="border w-full border-gray-400 rounded-lg shadow-sm px-3 py-2 resize"
                            placeholder="Opsional"></textarea>
                </div>
            </div>

            <!-- List produk -->
            <div id="items-wrapper" class="space-y-4 ">
                <div class="item-block">
                    <div class="item-row grid grid-cols-7 gap-3 items-start">

                        <!-- Kode Barang -->
                        <div class="flex flex-col">
                            <label class="text-sm font-medium mb-1">Kode Barang</label>
                            <select name="items[0][product_code]" class="product-code border px-2 py-1 rounded w-full border-gray-400">
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
                            <label class="text-sm font-medium mb-1">Nama Barang</label>
                            <select name="items[0][product_id]" class="product-name border px-2 py-1 rounded w-full border-gray-400">
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
                            <label class="text-sm font-medium mb-1">Kategori</label>
                            <input type="text" name="items[0][category]" class="category border px-2 py-1 rounded border-gray-400" readonly>
                        </div>
                        <!-- Subkategori -->
                        <div class="flex flex-col">
                            <label class="text-sm font-medium mb-1">Subkategori</label>
                            <input type="text" name="items[0][subcategory]"
                            class="subcategory border px-2 py-1 rounded border-gray-400" readonly>
                        </div>
                         <!-- Jumlah -->
                        <div class="flex flex-col">
                            <label class="text-sm font-medium mb-1">Qty</label>
                            <input type="number" name="items[0][quantity]" value="1"
                                class="quantity border px-2 py-1 w-20 text-center rounded border-gray-400">
                        </div>
                        <!-- Harga -->
                        <div class="flex flex-col">
                            <label class="text-sm font-medium mb-1">Harga</label>
                            <input type="number" name="items[0][price]"
                                class="price border px-2 py-1 text-right rounded border-gray-400" readonly>
                        </div>
                        <!-- Total Harga -->
                        <div class="flex flex-col">
                            <label class="text-sm font-medium mb-1">Total</label>
                            <input type="number" name="items[0][total_price]"
                                class="total-price border px-2 py-1 text-right rounded border-gray-400" readonly>
                        </div>
                    </div>
                    <!-- Keterangan -->
                    <div class="flex flex-col mt-2">
                        <label class="text-sm font-medium mb-1">Keterangan</label>
                        <input type="text" name="items[0][note]" placeholder="Keterangan" class="note border px-2 py-1 w-full rounded border-gray-400">
                    </div>
                </div>
            </div>
            <!-- Tombol tambah row -->
            <button type="button" id="addRow" class="mt-3 bg-gray-200 px-3 py-1 rounded">+ Tambah Barang</button>

            <!-- Submit -->
            <div class="mt-4">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">
                    Simpan Transaksi
                </button>
            </div>
        </form>
        <!-- Grand Total -->
        <div class="mt-6 flex justify-end">
            <div>
                <label class="block text-right font-bold mb-1">Total Transaksi:</label>
                <input type="number" id="grandTotal" name="grand_total"
                    class="border px-3 py-2 rounded text-right font-bold w-48" readonly>
            </div>
        </div>
    </div>

    <!-- Tabel Transaksi Terakhir -->
    <div class="bg-white shadow-lg rounded-xl overflow-hidden">
        <h3 class="text-xl font-bold text-gray-800 px-6 py-4 border-b border-gray-200">Transaksi Terakhir</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-400">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kode Transaksi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kode Barang</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Produk</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kategori</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Subkategori</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Harga</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Keterangan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-400">
                    @forelse($transactions as $tx)
                        @foreach($tx->items as $key => $item)
                            <tr class="hover:bg-gray-50 transition">
                                {{-- Kode Transaksi hanya muncul sekali di baris pertama --}}
                                @if($key == 0)
                                    <td class="px-6 py-4 whitespace-nowrap align-top" rowspan="{{ $tx->items->count() }}">
                                        <div class="font-semibold text-gray-800">{{ $tx->code }}</div>
                                        <div class="text-sm text-gray-500">Total: Rp {{ number_format($tx->grand_total,0,',','.') }}</div>
                                    </td>
                                @endif

                                <td class="px-6 py-4 whitespace-nowrap">{{ $item->product_code ?? '-' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $item->product_name ?? '-' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $item->category_name ?? '-' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $item->subcategory_name ?? '-' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">{{ number_format($item->price,0,',','.') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">{{ $item->quantity }}</td>

                                {{-- User hanya sekali juga (rowspan) --}}
                                @if($key == 0)
                                    <td class="px-6 py-4 whitespace-nowrap align-top" rowspan="{{ $tx->items->count() }}">
                                        {{ $tx->creator->name ?? '-' }}
                                    </td>
                                @endif

                                <td class="px-6 py-4 whitespace-nowrap">{{ $item->note ?? '-' }}</td>

                                {{-- Tanggal hanya sekali juga (rowspan) --}}
                                @if($key == 0)
                                    <td class="px-6 py-4 whitespace-nowrap align-top" rowspan="{{ $tx->items->count() }}">
                                        {{ $tx->created_at->format('d M Y H:i') }}
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    @empty
                        <tr>
                            <td colspan="10" class="px-6 py-4 text-center text-gray-400">Belum ada transaksi</td>
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
                <label class="text-sm font-medium mb-1">Kode Barang</label>
                <select name="items[${rowIndex}][product_code]" class="product-code border border-gray-400 px-2 py-1 rounded w-full">
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
                <label class="text-sm font-medium mb-1">Nama Barang</label>
                <select name="items[${rowIndex}][product_id]" class="product-name border border-gray-400 px-2 py-1 rounded w-full">
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
                <label class="text-sm font-medium mb-1">Kategori</label>
                <input type="text" name="items[${rowIndex}][category]" class="category border px-2 py-1 rounded border-gray-400" readonly>
            </div>
            <div class="flex flex-col">
                <label class="text-sm font-medium mb-1">Subkategori</label>
                <input type="text" name="items[${rowIndex}][subcategory]" class="subcategory border px-2 py-1 rounded border-gray-400" readonly>
            </div>
            <div class="flex flex-col">
                <label class="text-sm font-medium mb-1">Qty</label>
                <input type="number" name="items[${rowIndex}][quantity]" value="1" class="quantity border border-gray-400 px-2 py-1 w-20 text-center rounded">
            </div>
            <div class="flex flex-col">
                <label class="text-sm font-medium mb-1">Harga</label>
                <input type="number" step="0.01" name="items[${rowIndex}][price]" class="price border border-gray-400 px-2 py-1 rounded text-right" readonly>
            </div>
            <div class="flex flex-col">
                <label class="text-sm font-medium mb-1">Total</label>
                <input type="number" name="items[${rowIndex}][total_price]" class="total-price border border-gray-400 px-2 py-1 rounded text-right" readonly>
            </div>
        </div>
        <div class="flex flex-col mt-2">
            <label class="text-sm font-medium mb-1">Keterangan</label>
            <input type="text" name="items[${rowIndex}][note]" placeholder="Keterangan"
                class="note border px-2 py-1 w-full rounded">
        </div>
    `;
    wrapper.appendChild(newRow);
    rowIndex++;
});
</script>
@endsection
