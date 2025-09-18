<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Warehouse;
use App\Models\Product;
use Illuminate\Http\Request;

class WarehouseProductController extends Controller
{
    public function index(Request $request)
    {
        // eager load product -> category & parentCategory
        $query = Warehouse::with(['products.category','products.parentCategory']);

        // jika ada filter warehouse_id
        if ($request->filled('warehouse_id')) {
            $query->where('id', $request->warehouse_id);
        }

        $warehouses = $query->get();
        $allWarehouses = Warehouse::all(); // untuk isi dropdown

        return view('admin.stocks.index', compact('warehouses','allWarehouses'));
    }

    public function updateStock(Request $request, Product $product, Warehouse $warehouse){
        $request->validate(['quantity'=>'required|integer|min:0']);
        $warehouse->products()->syncWithoutDetaching([$product->id => ['quantity'=>$request->quantity]]);
        return redirect()->back()->with('success','Stock updated');
    }
}
