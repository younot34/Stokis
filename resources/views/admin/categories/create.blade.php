@extends('layouts.admin')
@section('title','Tambah Kategori')
@section('content')

<div class="bg-white p-6 rounded-xl shadow-lg max-w-lg mx-auto">
    <!-- Header -->
    <h2 class="text-2xl font-bold text-gray-800 mb-6">➕ Tambah Kategori</h2>

    <!-- Form -->
    <form action="{{ route('admin.categories.store') }}" method="POST" class="space-y-5">
        @csrf

        <!-- Nama Kategori -->
        <div class="mb-4">
            <label class="block text-gray-700">Nama Kategori</label>
            <input type="text" name="names[]" value="{{ old('names.0') }}" required
                   class="border rounded w-full p-2">
            <div id="extra-names"></div>
            <button type="button" id="add-name" class="px-3 py-1 bg-gray-600 text-white rounded">➕ Tambah data</button>
        </div>
        <div class="mb-4">
            <label class="block text-gray-700">Parent Kategori</label>
            <input list="parent-categories" name="parent_name" value="{{ old('parent_name') }}"
                class="border rounded w-full p-2" placeholder="Ketik nama parent">
            <datalist id="parent-categories">
                @foreach($categories as $cat)
                    <option value="{{ $cat->name }}">
                @endforeach
            </datalist>
        </div>

        <!-- Tombol Aksi -->
        <div class="flex items-center justify-between">
            <a href="{{ route('admin.categories.index') }}"
               class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg shadow transition">
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
        input.className = 'border rounded w-full p-2 mb-2';
        container.appendChild(input);
    });
</script>

@endsection
