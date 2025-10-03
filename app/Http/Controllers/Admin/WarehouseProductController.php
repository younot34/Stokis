<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Warehouse;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\PurchaseOrderItem;
use App\Models\PurchaseOrderDiscountItem;

class WarehouseProductController extends Controller
{
    public function index(Request $request)
    {
        $allWarehouses = Warehouse::all();

        $query = Warehouse::query();
        if ($request->filled('warehouse_id')) $query->where('id', $request->warehouse_id);
        if ($request->filled('province')) $query->where('province', $request->province);
        if ($request->filled('city')) $query->where('city', $request->city);

        $warehouses = $query->get();

        foreach ($warehouses as $warehouse) {
            $items = PurchaseOrderItem::with('product.parentCategory', 'product.category')
                ->whereHas('purchaseOrder', function ($q) use ($warehouse) {
                    $q->where('warehouse_id', $warehouse->id)
                    ->where('status', 'approved');
                })
                ->get()
                ->groupBy(fn($i) => $i->product->code)
                ->map(function ($group) {
                    $first = $group->first();
                    $first->setAttribute('quantity_approved', $group->sum('quantity_approved'));
                    return $first;
                });

            $discountItems = PurchaseOrderDiscountItem::with('product.parentCategory', 'product.category')
                ->whereHas('purchaseOrder', function ($q) use ($warehouse) {
                    $q->where('warehouse_id', $warehouse->id)
                    ->where('status', 'approved');
                })
                ->get()
                ->groupBy(fn($i) => $i->product->code)
                ->map(function ($group) {
                    $first = $group->first();
                    $first->setAttribute('quantity_approved', $group->sum('quantity_approved'));
                    return $first;
                });

            // simpan hasil merge ke property warehouse
            $warehouse->setAttribute('allItems', $items->concat($discountItems)
                ->sortBy(fn($item) => $item->product->code)
                ->values());
        }

        $allProvinces = Warehouse::distinct()->pluck('province')->filter()->values();
        $allCities    = Warehouse::distinct()->pluck('city')->filter()->values();

        return view('admin.stocks.index', compact('warehouses', 'allWarehouses', 'allProvinces', 'allCities'));
    }

    public function updateStock(Request $request, Product $product, Warehouse $warehouse){
        $request->validate(['quantity'=>'required|integer|min:0']);
        $warehouse->products()->syncWithoutDetaching([$product->id => ['quantity'=>$request->quantity]]);
        return redirect()->back()->with('success','Stock updated');
    }
}
