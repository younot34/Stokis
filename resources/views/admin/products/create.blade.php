@extends('layouts.admin')
@section('title','Tambah Produk')
@section('content')

<div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl p-6">
    <h2 class="text-2xl font-bold mb-6 text-gray-800 dark:text-gray-100 border-b pb-3 border-gray-200 dark:border-gray-700">
        ➕ Tambah Produk
    </h2>

    <form action="{{ route('admin.products.store') }}" method="POST" id="productForm">
        @csrf

        <div id="product-rows" class="space-y-4">
            <div class="grid grid-cols-8 gap-3 items-start product-row">
                <div>
                    <label for="code-0" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Kode Produk
                    </label>
                    <input type="text" id="code-0" name="products[0][code]" placeholder="AB-123"
                        class="code-input border border-gray-300 dark:border-gray-600
                               rounded-lg px-3 py-2 w-full
                               bg-white dark:bg-gray-700
                               text-gray-800 dark:text-gray-100" required>
                </div>
                <div>
                    <label for="name-0" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Nama Produk
                    </label>
                    <input type="text" id="name-0" name="products[0][name]" placeholder="Nama Produk"
                        class="border border-gray-300 dark:border-gray-600
                               rounded-lg px-3 py-2 w-full
                               bg-white dark:bg-gray-700
                               text-gray-800 dark:text-gray-100" required>
                </div>
                <div>
                    <label for="parent-0" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Kategori Induk
                    </label>
                    <input list="parent-list-0" id="parent-0" name="products[0][parent_name]"
                        placeholder="Ketik / pilih kategori induk"
                        class="border border-gray-300 dark:border-gray-600
                               rounded-lg px-3 py-2 w-full
                               bg-white dark:bg-gray-700
                               text-gray-800 dark:text-gray-100 parent-input"
                        data-index="0" required>
                    <datalist id="parent-list-0">
                        @foreach($parents as $parent)
                            <option value="{{ $parent->name }}">
                        @endforeach
                    </datalist>
                </div>
                <div>
                    <label for="subcategory-0" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Subkategori
                    </label>
                    <input list="subcategory-list-0" id="subcategory-0" name="products[0][subcategory_name]"
                        placeholder="Ketik / pilih subkategori"
                        class="border border-gray-300 dark:border-gray-600
                               rounded-lg px-3 py-2 w-full
                               bg-white dark:bg-gray-700
                               text-gray-800 dark:text-gray-100 subcategory-input"
                        data-index="0" required>
                    <datalist id="subcategory-list-0"></datalist>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Harga
                    </label>
                    <input type="number" name="products[0][price]" class="price-input border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 w-full bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Diskon (%)
                    </label>
                    <input type="number" name="products[0][discount]" class="discount-input border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 w-full bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100" min="0" max="100" value="0">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Harga Diskon
                    </label>
                    <input type="number" name="products[0][discount_price]" class="discount-price-input border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 w-full bg-gray-100 dark:bg-gray-700 text-gray-300" readonly>
                </div>
                <div class="flex items-end">
                    <button type="button" class="remove-row text-red-500 font-bold" style="display:none;">&times;</button>
                </div>
            </div>
        </div>
        <div class="mt-6 flex justify-between">
            <button type="button" id="addRow"
                class="inline-flex items-center px-4 py-2 bg-blue-500 hover:bg-blue-600
                       text-white rounded-lg shadow transition">
                ➕ Tambah Baris
            </button>

            <button type="submit"
                class="inline-flex items-center px-6 py-2 bg-green-600 hover:bg-green-700
                       text-white rounded-lg shadow transition">
                💾 Simpan Semua
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const parents = @json($parents);
    const childrenMap = {};
    parents.forEach(p => {
        childrenMap[p.name] = (p.children || []).map(c => c.name);
    });

    let rowIndex = 1;
    const container = document.getElementById('product-rows');
    // Hitung otomatis harga diskon
    function calculateDiscount(row) {
        const priceInput = row.querySelector(".price-input");
        const discountInput = row.querySelector(".discount-input");
        const discountPriceInput = row.querySelector(".discount-price-input");

        const price = parseFloat(priceInput.value) || 0;
        const discount = parseFloat(discountInput.value) || 0;

        const discountPrice = price - (price * discount / 100);
        discountPriceInput.value = discountPrice > 0 ? Math.round(discountPrice) : 0;
    }

    // Event delegation
    document.getElementById("product-rows").addEventListener("input", function (e) {
        if (e.target.classList.contains("price-input") || e.target.classList.contains("discount-input")) {
            const row = e.target.closest(".product-row");
            calculateDiscount(row);
        }
    });
    function parentOptionsHtml(rowId) {
        return `
            <datalist id="parent-list-${rowId}">
                ${parents.map(p => `<option value="${p.name}">`).join('')}
            </datalist>
        `;
    }

    function formatCodeInput(input) {
        let val = input.value.toUpperCase().replace(/[^A-Z0-9]/g, "");
        if (val.length > 2) {
            val = val.slice(0,2) + "-" + val.slice(2,5);
        }
        input.value = val;
    }

    // Event delegation: format semua input code
    container.addEventListener("input", function(e) {
        if (e.target.classList.contains("code-input")) {
            formatCodeInput(e.target);
        }
    });

    // Tambah baris baru
    document.getElementById('addRow').addEventListener('click', function () {
        const row = document.createElement('div');
        row.className = 'grid grid-cols-8 gap-3 items-start product-row';

        row.innerHTML = `
            <input type="text" name="products[${rowIndex}][code]" placeholder="AB-123"
                class="code-input border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2
                       bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100" required>

            <input type="text" name="products[${rowIndex}][name]" placeholder="Nama Produk"
                class="border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2
                       bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100" required>

            <input list="parent-list-${rowIndex}"
                name="products[${rowIndex}][parent_name]"
                placeholder="Ketik / pilih kategori induk"
                class="border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2
                       bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100 parent-input"
                data-index="${rowIndex}" required>
            ${parentOptionsHtml(rowIndex)}

            <input list="subcategory-list-${rowIndex}"
                name="products[${rowIndex}][subcategory_name]"
                placeholder="Ketik / pilih subkategori"
                class="border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2
                       bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100 subcategory-input"
                data-index="${rowIndex}" required>
            <datalist id="subcategory-list-${rowIndex}"></datalist>

            <input type="number" name="products[${rowIndex}][price]" class="price-input border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 w-full bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100" required>
            <input type="number" name="products[${rowIndex}][discount]" class="discount-input border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 w-full bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100" min="0" max="100" value="0">
            <input type="number" name="products[${rowIndex}][discount_price]" class="discount-price-input border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 w-full bg-gray-100 dark:bg-gray-700 text-gray-300" readonly>
            <button type="button" class="remove-row text-red-500 font-bold">×</button>
        `;
        container.appendChild(row);
        rowIndex++;
    });

    // Update subkategori saat parent diketik
    container.addEventListener("input", function(e) {
        if (e.target.classList.contains("parent-input")) {
            const parentName = e.target.value;
            const row = e.target.closest(".product-row");
            const index = e.target.dataset.index;
            const subList = row.querySelector(`#subcategory-list-${index}`);

            subList.innerHTML = "";
            if (childrenMap[parentName]) {
                childrenMap[parentName].forEach(ch => {
                    const opt = document.createElement("option");
                    opt.value = ch;
                    subList.appendChild(opt);
                });
            }
        }
    });

    // Hapus baris
    container.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-row')) {
            e.target.closest('.product-row').remove();
        }
    });
});
</script>
@endsection
