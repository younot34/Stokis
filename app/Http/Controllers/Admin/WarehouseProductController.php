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
        // filter by province
        if ($request->province) {
            $query->where('province', $request->province);
        }
        // filter by city
        if ($request->city) {
            $query->where('city', $request->city);
        }

        $warehouses = $query->get();
        $allWarehouses = Warehouse::all(); // untuk isi dropdown
        $allProvinces  = Warehouse::select('province')->distinct()->pluck('province')->filter()->toArray();
        $allCities     = Warehouse::select('city')->distinct()->pluck('city')->filter()->toArray();

        return view('admin.stocks.index', compact('warehouses','allWarehouses','allProvinces', 'allCities'));
    }

    public function updateStock(Request $request, Product $product, Warehouse $warehouse){
        $request->validate(['quantity'=>'required|integer|min:0']);
        $warehouse->products()->syncWithoutDetaching([$product->id => ['quantity'=>$request->quantity]]);
        return redirect()->back()->with('success','Stock updated');
    }
}
