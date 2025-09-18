@extends('layouts.admin')
@section('title','Kategori')
@section('content')

<div class="bg-white p-6 rounded-xl shadow-lg">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 space-y-3 sm:space-y-0">
        <h2 class="text-2xl font-bold text-gray-800">📂 List Kategori</h2>
        <a href="{{ route('admin.categories.create') }}"
           class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg shadow transition">
            ➕ Tambah Kategori
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full border border-gray-200 rounded-lg overflow-hidden">
            <thead class="bg-gray-100 text-gray-700 text-sm">
                <tr>
                    <th class="px-4 py-2 text-left">ID</th>
                    <th class="px-4 py-2 text-left">Nama</th>
                    <th class="px-4 py-2 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-gray-600 text-sm divide-y divide-gray-200">
                @foreach($categories as $category)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-2">{{ $category->id }}</td>
                    <td class="px-4 py-2 font-medium">
                        {{ $category->name }}
                        @if($category->children->count())
                        <ul class="ml-4 text-sm text-gray-500">
                            @foreach($category->children as $child)
                                <li>↳ {{ $child->name }}</li>
                            @endforeach
                        </ul>
                        @endif
                    </td>
                    <td class="px-4 py-2 text-center space-x-2">
                        <a href="{{ route('admin.categories.edit',$category->id) }}"
                           class="inline-flex items-center px-3 py-1 bg-blue-500 hover:bg-blue-600 text-white rounded-md text-sm transition">
                            ✏️ Edit
                        </a>
                        <form action="{{ route('admin.categories.destroy',$category->id) }}" method="POST" class="inline">
                            @csrf @method('DELETE')
                            <button type="submit" onclick="return confirm('Hapus kategori ini?')"
                                    class="inline-flex items-center px-3 py-1 bg-red-500 hover:bg-red-600 text-white rounded-md text-sm transition">
                                🗑️ Hapus
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection
