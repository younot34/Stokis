@extends('layouts.admin')
@section('title','Edit Produk')
@section('content')

<div class="bg-white p-6 rounded-xl shadow-lg max-w-3xl mx-auto">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">✏️ Edit Produk</h2>

    <form action="{{ route('admin.products.update',$product->id) }}" method="POST" class="space-y-5">
        @csrf
        @method('PUT')

        <!-- Code -->
        <div>
            <label class="block font-medium text-gray-700 mb-1">Kode Produk</label>
            <input type="text" name="code" value="{{ $product->code }}" required
                   class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
        </div>

        <!-- Nama -->
        <div>
            <label class="block font-medium text-gray-700 mb-1">Nama Produk</label>
            <input type="text" name="name" value="{{ $product->name }}" required
                   class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
        </div>

        <!-- Kategori Induk -->
        <div>
            <label class="block font-medium text-gray-700 mb-1">Kategori Induk</label>
            <input list="parent-list" name="parent_name"
                   value="{{ optional($product->category->parent)->name }}"
                   placeholder="Ketik / pilih kategori induk"
                   class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 parent-input" required>
            <datalist id="parent-list">
                @foreach($parents as $parent)
                    <option value="{{ $parent->name }}">
                @endforeach
            </datalist>
        </div>

        <!-- SubKategori -->
        <div>
            <label class="block font-medium text-gray-700 mb-1">SubKategori</label>
            <input list="subcategory-list" name="subcategory_name"
                   value="{{ $product->category->name ?? '' }}"
                   placeholder="Ketik / pilih subkategori"
                   class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 subcategory-input" required>
            <datalist id="subcategory-list">
                @if($product->category && $product->category->parent)
                    @foreach($product->category->parent->children as $child)
                        <option value="{{ $child->name }}">
                    @endforeach
                @endif
            </datalist>
        </div>

        <!-- Harga -->
        <div>
            <label class="block font-medium text-gray-700 mb-1">Harga</label>
            <div class="flex items-center">
                <span class="px-3 py-2 bg-gray-100 border border-r-0 border-gray-300 rounded-l-lg text-gray-600">Rp</span>
                <input type="number" name="price" value="{{ $product->price }}" required
                       class="w-full border-gray-300 rounded-r-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
            </div>
        </div>

        <!-- Tombol -->
        <div class="flex justify-end">
            <button type="submit"
                    class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow transition">
                💾 Update Produk
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

    const parentInput = document.querySelector(".parent-input");
    const subList = document.getElementById("subcategory-list");

    function refreshSubcategories() {
        const parentName = parentInput.value.trim();
        subList.innerHTML = "";
        if (childrenMap[parentName]) {
            childrenMap[parentName].forEach(ch => {
                const opt = document.createElement("option");
                opt.value = ch;
                subList.appendChild(opt);
            });
        }
    }

    parentInput.addEventListener("input", refreshSubcategories);

    // Jalankan saat halaman pertama kali load
    if (parentInput.value) {
        refreshSubcategories();
    }
});
</script>

@endsection
