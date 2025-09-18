@extends('layouts.admin')
@section('title','Update Stok')
@section('content')
<h2 class="text-2xl font-bold mb-5">Update Stok Produk: {{ $product->name }}</h2>

<form action="{{ route('admin.stocks.update', ['warehouse'=>$warehouse->id,'product'=>$product->id]) }}" method="POST">
    @csrf
    <div class="mb-4">
        <label>stokis: {{ $warehouse->name }}</label>
        <input type="number" name="quantity" value="{{ $product->pivot->quantity ?? 0 }}" min="0" class="border px-2 py-1 w-32">
    </div>
    <button class="bg-blue-500 text-white px-4 py-2 rounded">Update</button>
</form>
@endsection
