@extends('layouts.admin')
@section('title','Edit Kategori')
@section('content')

<div class="bg-white p-6 rounded-xl shadow-lg max-w-lg mx-auto">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">✏️ Edit Subkategori</h2>

    <form action="{{ route('admin.categories.update', $category->id) }}" method="POST" class="space-y-5">
        @csrf
        @method('PUT')

        <!-- Pilih Parent Kategori -->
        <div class="mb-4">
            <label class="block text-gray-700">Parent Kategori</label>
            <input type="text" list="parentCategories" id="parentName" name="parentName"
                class="border rounded w-full p-2"
                value="{{ old('parent_name', $category->parent ? $category->parent->name : $category->name) }}">
            <input type="hidden" name="parent_id" id="parentId" value="{{ $category->parent_id }}">

            <datalist id="parentCategories">
                @foreach($categories as $cat)
                    <option data-id="{{ $cat->id }}" value="{{ $cat->name }}"></option>
                @endforeach
            </datalist>
        </div>

        <!-- Daftar Subkategori -->
        <div id="subcategories">
            @foreach($subcategories as $i => $sub)
            <div class="mb-2 flex space-x-2 items-center">
                <input type="hidden" name="ids[]" value="{{ $sub->id }}">
                <input type="text" name="names[]" value="{{ old("names.$i", $sub->name) }}" required
                       class="border rounded w-full p-2">
                <button type="button" class="remove px-3 py-1 bg-red-200 text-white rounded">❌</button>
            </div>
            @endforeach
        </div>

        <button type="button" id="add-subcategory" class="px-3 py-1 bg-blue-500 text-white rounded">➕ Tambah Subkategori</button>

        <!-- Tombol Aksi -->
        <div class="flex items-center justify-between mt-4">
            <a href="{{ route('admin.categories.index') }}"
               class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg shadow transition">
                ⬅️ Kembali
            </a>
            <button type="submit"
                    class="inline-flex items-center px-5 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg shadow transition">
                💾 Update Semua
            </button>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const parentInput = document.getElementById('parentName');
        const parentIdInput = document.getElementById('parentId');
        const options = document.querySelectorAll('#parentCategories option');

        // Set parentId default
        const val = parentInput.value;
        const match = Array.from(options).find(opt => opt.value === val);
        if (match) parentIdInput.value = match.dataset.id;

        // Update parentId saat user input
        parentInput.addEventListener('input', () => {
            const val = parentInput.value;
            const match = Array.from(options).find(opt => opt.value === val);
            if (match) parentIdInput.value = match.dataset.id;
            else parentIdInput.value = '';
        });
    });
    const container = document.getElementById('subcategories');
    const addBtn = document.getElementById('add-subcategory');

    addBtn.addEventListener('click', () => {
        const div = document.createElement('div');
        div.className = 'mb-2 flex space-x-2 items-center';
        div.innerHTML = `
            <input type="hidden" name="ids[]" value="">
            <input type="text" name="names[]" required class="border rounded w-full p-2">
            <button type="button" class="remove px-3 py-1 bg-red-200 text-white rounded">❌</button>
        `;
        container.appendChild(div);
    });

    container.addEventListener('click', e => {
        if(e.target.classList.contains('remove')) {
            e.target.parentElement.remove();
        }
    });
</script>
@endsection
