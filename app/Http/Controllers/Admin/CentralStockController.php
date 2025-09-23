<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CentralStock;
use App\Models\Product;
use Illuminate\Http\Request;

class CentralStockController extends Controller
{
    public function index(Request $request)
    {
        $query = CentralStock::with('product');

        // Filter berdasarkan kode produk
        if ($request->filled('code')) {
            $query->whereHas('product', function ($q) use ($request) {
                $q->where('code', 'like', '%' . $request->code . '%');
            });
        }

        // Filter berdasarkan nama produk
        if ($request->filled('name')) {
            $query->whereHas('product', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->name . '%');
            });
        }

        $stocks = $query->latest()->paginate(10)->withQueryString();

        return view('admin.central_stocks.index', compact('stocks'));
    }

    public function create()
    {
        $products = Product::all();
        return view('admin.central_stocks.create', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id.*' => 'required|string',
            'quantity.*'   => 'required|integer|min:1',
        ]);

        foreach ($request->product_id as $index => $productInput) {
            // Ambil kode produk dari input
            $productCode = explode(' - ', $productInput)[0];

            // Cari product id
            $product = Product::where('code', $productCode)->first();

            if ($product) {
                CentralStock::create([
                    'product_id' => $product->id,
                    'quantity'   => $request->quantity[$index],
                ]);
            }
        }

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
