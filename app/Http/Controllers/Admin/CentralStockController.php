<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CentralStock;
use App\Models\Product;
use Illuminate\Http\Request;

class CentralStockController extends Controller
{
    public function index()
    {
        $stocks = CentralStock::with('product')->latest()->paginate(10);
        return view('admin.central_stocks.index', compact('stocks'));
    }

    public function create()
    {
        $products = Product::all();
        return view('admin.central_stocks.create', compact('products'));
    }

    public function store(Request $request)
    {
        $productInput = $request->product_id;

        // Ambil kode produk dari input
        $productCode = explode(' - ', $productInput)[0];

        // Cari product id
        $product = Product::where('code', $productCode)->firstOrFail();

        $request->validate([
            'quantity'   => 'required|integer|min:1',
        ]);

        CentralStock::create([
            'product_id' => $product->id,
            'quantity'   => $request->quantity,
        ]);

        return redirect()->route('admin.central_stocks.index')->with('success', 'Stok berhasil ditambahkan!');
    }

    public function edit(CentralStock $central_stock)
    {
        $products = Product::all();
        return view('admin.central_stocks.edit', compact('central_stock', 'products'));
    }

    public function update(Request $request, CentralStock $central_stock)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'required|integer|min:1',
        ]);

        $central_stock->update($request->all());

        return redirect()->route('admin.central_stocks.index')->with('success', 'Stok berhasil diupdate!');
    }

    public function destroy(CentralStock $central_stock)
    {
        $central_stock->delete();
        return back()->with('success', 'Stok berhasil dihapus!');
    }
}
