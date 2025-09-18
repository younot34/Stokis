@extends('layouts.admin')
@section('title','Stok stokis')
@section('content')
<div class="bg-white shadow-md rounded-lg p-6">
    <h2 class="text-2xl font-bold mb-6 text-gray-800 border-b pb-3">Stok Per Stokis</h2>

    @foreach($warehouses as $warehouse)
    <!-- Card stokis -->
    <div class="bg-gray-50 shadow-sm rounded-lg p-5 mb-8">
        <h3 class="text-xl font-semibold text-gray-700 mb-4">{{ $warehouse->name }}</h3>

        <div class="overflow-x-auto">
            <table class="w-full border-collapse">
                <thead>
                    <tr class="bg-gray-100 text-gray-700 text-left">
                        <th class="px-4 py-3">Produk</th>
                        <th class="px-4 py-3">Jumlah</th>
                        <th class="px-4 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($warehouse->products as $product)
                    <tr class="border-b hover:bg-gray-50 transition">
                        <td class="px-4 py-3 font-medium text-gray-800">
                            {{ $product->name }}
                        </td>
                        <td class="px-4 py-3">
                            {{ $product->pivot->quantity }}
                        </td>
                        <td class="px-4 py-3">
                            <form action="{{ route('admin.stocks.update', ['warehouse'=>$warehouse->id,'product'=>$product->id]) }}"
                                  method="POST"
                                  class="flex items-center gap-2">
                                @csrf
                                <input type="number"
                                       name="quantity"
                                       value="{{ $product->pivot->quantity }}"
                                       min="0"
                                       class="border rounded-lg px-3 py-2 w-24 focus:ring focus:ring-blue-200 focus:border-blue-400">
                                <button class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg shadow transition">
                                    Update
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="text-center py-4 text-gray-500">Belum ada produk di stokis ini</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @endforeach
</div>
@endsection
