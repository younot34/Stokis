@extends('layouts.admin')
@section('title','Tambah Kategori')

@section('content')
<div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-lg max-w-lg mx-auto">
    <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100 mb-6">➕ Tambah Kategori</h2>
    <form action="{{ route('admin.categories.store') }}" method="POST" class="space-y-5">
        @csrf
        <div>
            <label class="block text-gray-700 dark:text-gray-300 font-medium mb-2">SubKategori</label>
            <input type="text" name="names[]" value="{{ old('names.0') }}" required
                   class="w-full rounded-lg border border-gray-300 dark:border-gray-600 shadow-sm
                          focus:ring-2 focus:ring-green-500 focus:border-green-500
                          dark:bg-gray-700 dark:text-gray-100 px-4 py-2 mb-2 transition" />
            <div id="extra-names"></div>
            <button type="button" id="add-name"
                    class="px-3 py-1 bg-gray-600 hover:bg-gray-700 text-white rounded-md shadow transition text-sm">
                ➕ Tambah data
            </button>
        </div>
        <div>
            <label class="block text-gray-700 dark:text-gray-300 font-medium mb-2">Parent Kategori</label>
            <input list="parent-categories" name="parent_name" value="{{ old('parent_name') }}"
                   class="w-full rounded-lg border border-gray-300 dark:border-gray-600 shadow-sm
                          focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                          dark:bg-gray-700 dark:text-gray-100 px-4 py-2 transition"
                   placeholder="Ketik nama parent" />
            <datalist id="parent-categories">
                @foreach($categories as $cat)
                    <option value="{{ $cat->name }}">
                @endforeach
            </datalist>
        </div>
        <div class="flex items-center justify-between">
            <a href="{{ route('admin.categories.index') }}"
               class="inline-flex items-center px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg shadow transition">
                ⬅️ Kembali
            </a>
            <button type="submit"
                    class="inline-flex items-center px-5 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg shadow transition">
                💾 Simpan
            </button>
        </div>
    </form>
</div>
<script>
    const addBtn = document.getElementById('add-name');
    const container = document.getElementById('extra-names');

    addBtn.addEventListener('click', () => {
        const input = document.createElement('input');
        input.type = 'text';
        input.name = 'names[]';
        input.required = true;
        input.className =
            'w-full rounded-lg border border-gray-300 dark:border-gray-600 shadow-sm ' +
            'focus:ring-2 focus:ring-green-500 focus:border-green-500 ' +
            'dark:bg-gray-700 dark:text-gray-100 px-4 py-2 mb-2 transition';
        container.appendChild(input);
    });
</script>
@endsection
