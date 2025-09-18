@extends('layouts.admin')
@section('title','Tambah Produk')
@section('content')
<div class="max-w-6xl mx-auto bg-white shadow-lg rounded-xl p-6">
    <h2 class="text-2xl font-bold mb-6 text-gray-800 border-b pb-3">Tambah Produk</h2>

    <form action="{{ route('admin.products.store') }}" method="POST" id="productForm">
        @csrf

        <div id="product-rows" class="space-y-4">
            <div class="grid grid-cols-6 gap-3 items-center product-row">
                <input type="text" name="products[0][code]" placeholder="AB-123"
                    class="code-input border rounded-lg px-3 py-2" required>

                <input type="text" name="products[0][name]" placeholder="Nama Produk"
                    class="border rounded-lg px-3 py-2" required>

                <!-- Parent kategori -->
                <input list="parent-list-0" name="products[0][parent_name]"
                    placeholder="Ketik / pilih kategori induk"
                    class="border rounded-lg px-3 py-2 parent-input" data-index="0" required>

                <datalist id="parent-list-0">
                    @foreach($parents as $parent)
                        <option value="{{ $parent->name }}">
                    @endforeach
                </datalist>

                <!-- Subkategori -->
                <input list="subcategory-list-0" name="products[0][subcategory_name]"
                    placeholder="Ketik / pilih subkategori"
                    class="border rounded-lg px-3 py-2 subcategory-input" data-index="0" required>

                <datalist id="subcategory-list-0">
                    <!-- akan diisi via JS saat parent dipilih -->
                </datalist>


                <input type="number" name="products[0][price]" placeholder="Harga"
                    class="border rounded-lg px-3 py-2" required>

                <button type="button" class="remove-row text-red-500 font-bold" style="display:none;">&times;</button>
            </div>
        </div>

        <div class="mt-4 flex justify-between">
            <button type="button" id="addRow" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">+ Tambah Baris</button>

            <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-6 py-2 rounded-lg">Simpan Semua</button>
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

    function parentOptionsHtml(rowId) {
        return `
            <datalist id="parent-list-${rowId}">
                ${parents.map(p => `<option value="${p.name}">`).join('')}
            </datalist>
        `;
    }
    function formatCodeInput(input) {
        let val = input.value
        .toUpperCase()
        .replace(/[^A-Z0-9]/g, "");
        if (val.length > 2) {
            val = val.slice(0,2) + "-" + val.slice(2,5); }
            input.value = val; }
    // Event delegation: format semua input code
    container.addEventListener("input", function(e) {
        if (e.target.classList.contains("code-input")) {
            formatCodeInput(e.target); } });

    // Tambah baris baru
    document.getElementById('addRow').addEventListener('click', function () {
        const row = document.createElement('div');
        row.className = 'grid grid-cols-6 gap-3 items-center product-row';

        row.innerHTML = `
            <input type="text" name="products[${rowIndex}][code]" placeholder="AB-123"
                class="code-input border rounded-lg px-3 py-2" required>

            <input type="text" name="products[${rowIndex}][name]" placeholder="Nama Produk"
                class="border rounded-lg px-3 py-2" required>

            <input list="parent-list-${rowIndex}"
                name="products[${rowIndex}][parent_name]"
                placeholder="Ketik / pilih kategori induk"
                class="border rounded-lg px-3 py-2 parent-input" data-index="${rowIndex}" required>
            ${parentOptionsHtml(rowIndex)}

            <input list="subcategory-list-${rowIndex}"
                name="products[${rowIndex}][subcategory_name]"
                placeholder="Ketik / pilih subkategori"
                class="border rounded-lg px-3 py-2 subcategory-input" data-index="${rowIndex}" required>
            <datalist id="subcategory-list-${rowIndex}"></datalist>

            <input type="number" name="products[${rowIndex}][price]" placeholder="Harga"
                class="border rounded-lg px-3 py-2" required>

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


    // Saat parent berubah -> isi subkategori
    container.addEventListener('change', function (e) {
        if (e.target.classList.contains('parent-select')) {
            const parentId = e.target.value;
            const row = e.target.closest('.product-row');
            const subSelect = row.querySelector('.subcategory-select');

            subSelect.innerHTML = `<option value="">-- Pilih Subkategori --</option>`;
            if (parentId && childrenMap[parentId]) {
                childrenMap[parentId].forEach(ch => {
                    const opt = document.createElement('option');
                    opt.value = ch.id;
                    opt.text = ch.name;
                    subSelect.appendChild(opt);
                });
            }
        }
    });
});
</script>
@endsection
