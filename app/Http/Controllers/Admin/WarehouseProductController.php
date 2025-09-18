<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Warehouse;
use App\Models\Product;
use Illuminate\Http\Request;

class WarehouseProductController extends Controller
{
    public function index() {
        $warehouses = Warehouse::with('products')->get();
        return view('admin.stocks.index', compact('warehouses'));
    }

    public function updateStock(Request $request, Product $product, Warehouse $warehouse){
        $request->validate(['quantity'=>'required|integer|min:0']);
        $warehouse->products()->syncWithoutDetaching([$product->id => ['quantity'=>$request->quantity]]);
        return redirect()->back()->with('success','Stock updated');
    }
}
