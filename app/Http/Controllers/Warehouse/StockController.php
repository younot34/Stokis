<?php

namespace App\Http\Controllers\Warehouse;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\PurchaseOrderItem;
use App\Models\PurchaseOrderDiscountItem;

class StockController extends Controller
{
    public function index()
    {
        $warehouse = Auth::user()->warehouse;

        // Ambil semua item normal
        $items = PurchaseOrderItem::with('product.parentCategory', 'product.category')
            ->whereHas('purchaseOrder', function ($q) use ($warehouse) {
                $q->where('warehouse_id', $warehouse->id)
                ->where('status', 'approved');
            })
            ->get()
            // Group by kode + price agar harga berbeda tetap di baris sendiri
            ->groupBy(fn($i) => $i->product->code . '_' . $i->price)
            ->map(function ($group) {
                $first = $group->first();
                $first->setAttribute('quantity_approved', $group->sum('quantity_approved'));
                return $first;
            });

        // Ambil semua item diskon
        $discountItems = PurchaseOrderDiscountItem::with('product.parentCategory', 'product.category')
            ->whereHas('purchaseOrder', function ($q) use ($warehouse) {
                $q->where('warehouse_id', $warehouse->id)
                ->where('status', 'approved');
            })
            ->get()
            // Group by kode + final_price agar harga berbeda tetap di baris sendiri
            ->groupBy(fn($i) => $i->product->code . '_' . $i->final_price)
            ->map(function ($group) {
                $first = $group->first();
                $first->setAttribute('quantity_approved', $group->sum('quantity_approved'));
                return $first;
            });

        // Gabungkan keduanya jadi satu collection
        $allItems = $items->concat($discountItems)
            ->sortBy(fn($item) => $item->product->code)
            ->values();

        return view('warehouse.Stocks.index', compact('allItems'));
    }
}
