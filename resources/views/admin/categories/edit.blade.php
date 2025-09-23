@extends('layouts.admin')
@section('title','Edit Kategori')

@section('content')
<div class="space-y-8">
    <div class="bg-gradient-to-r from-indigo-500 to-purple-600 text-white rounded-xl p-6 shadow-lg">
        <h2 class="text-2xl font-bold">✏️ Edit Subkategori</h2>
        <p class="text-sm text-indigo-100">Kelola parent kategori dan daftar subkategori</p>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 hover:shadow-xl transition">
        <form action="{{ route('admin.categories.update', $category->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-200 mb-1">Parent Kategori</label>
                <input type="text" list="parentCategories" id="parentName" name="parentName"
                    class="border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200
                           rounded-lg w-full p-3 focus:ring focus:ring-indigo-300"
                    value="{{ old('parent_name', $category->parent ? $category->parent->name : $category->name) }}">
                <input type="hidden" name="parent_id" id="parentId" value="{{ $category->parent_id }}">
                <datalist id="parentCategories">
                    @foreach($categories as $cat)
                        <option data-id="{{ $cat->id }}" value="{{ $cat->name }}"></option>
                    @endforeach
                </datalist>
            </div>
            <div id="subcategories" class="space-y-2">
                @foreach($subcategories as $i => $sub)
                <div class="flex space-x-2 items-center">
                    <input type="hidden" name="ids[]" value="{{ $sub->id }}">
                    <input type="text" name="names[]" value="{{ old("names.$i", $sub->name) }}" required
                           class="border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200
                                  rounded-lg w-full p-3 focus:ring focus:ring-indigo-300">
                    <button type="button"
                            class="remove px-3 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg shadow transition">
                        ❌
                    </button>
                </div>
                @endforeach
            </div>

            <button type="button" id="add-subcategory"
                    class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg shadow transition">
                ➕ Tambah Subkategori
            </button>
            <div class="flex items-center justify-between pt-6 border-t border-gray-200 dark:border-gray-700">
                <a href="{{ route('admin.categories.index') }}"
                   class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 rounded-lg shadow transition">
                    ⬅️ Kembali
                </a>
                <button type="submit"
                        class="inline-flex items-center px-5 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg shadow transition">
                    💾 Update Semua
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const parentInput = document.getElementById('parentName');
        const parentIdInput = document.getElementById('parentId');
        const options = document.querySelectorAll('#parentCategories option');

        const match = Array.from(options).find(opt => opt.value === parentInput.value);
        if (match) parentIdInput.value = match.dataset.id;

        parentInput.addEventListener('input', () => {
            const val = parentInput.value;
            const match = Array.from(options).find(opt => opt.value === val);
            parentIdInput.value = match ? match.dataset.id : '';
        });
    });

    const container = document.getElementById('subcategories');
    const addBtn = document.getElementById('add-subcategory');

    addBtn.addEventListener('click', () => {
        const div = document.createElement('div');
        div.className = 'flex space-x-2 items-center';
        div.innerHTML = `
            <input type="hidden" name="ids[]" value="">
            <input type="text" name="names[]" required
                   class="border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200
                          rounded-lg w-full p-3 focus:ring focus:ring-indigo-300">
            <button type="button"
                    class="remove px-3 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg shadow transition">❌</button>
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
