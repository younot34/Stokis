<?php

namespace App\Http\Controllers\Warehouse;

use App\Http\Controllers\Controller;
use App\Models\Deposit;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\PurchaseOrderItem;
use App\Models\PurchaseOrderDiscountItem;

class DashboardController extends Controller
{

public function index()
    {
        $warehouse = Auth::user()->warehouse;
        $today = Carbon::today();

        $totalProducts = $warehouse->products->count();

        // total deposit warehouse
        $totalDeposit = 0;
        if ($warehouse) {
            $totalDeposit = Deposit::where('warehouse_id', $warehouse->id)->sum('nominal');
        }

        // Hitung total stok dan total aset dari PO normal
        $normalItems = PurchaseOrderItem::whereHas('purchaseOrder', function($q) use ($warehouse) {
                $q->where('warehouse_id', $warehouse->id)->where('status', 'approved');
            })
            ->with('product')
            ->get();

        $discountItems = PurchaseOrderDiscountItem::whereHas('purchaseOrder', function($q) use ($warehouse) {
                $q->where('warehouse_id', $warehouse->id)->where('status', 'approved');
            })
            ->with('product')
            ->get();

        // Total stok normal + diskon
        $totalStock = $normalItems->sum('quantity_approved') + $discountItems->sum('quantity_approved');

        // Total aset = price * qty (normal) + final_price * qty (diskon)
        $totalAsset = $normalItems->sum(fn($i) => $i->quantity_approved * $i->price)
                    + $discountItems->sum(fn($i) => $i->quantity_approved * $i->final_price);

        // Barang dengan stok < 10 (NORMAL, digabung sesama normal)
        $lowStockNormal = $normalItems
        ->groupBy('product.id')
        ->map(function($group) {
                $first = $group->first();
                return [
                    'product' => $first->product,
                    'qty'     => $group->sum('quantity_approved'),
                    'type'    => 'normal',
                ];
            })
        ->filter(fn($row) => $row['qty'] < 10);

        // Barang dengan stok < 10 (DISKON, digabung sesama diskon)
        $lowStockDiskon = $discountItems
            ->groupBy('product.id')
            ->map(function($group) {
                $first = $group->first();
                return [
                    'product' => $first->product,
                    'qty'     => $group->sum('quantity_approved'),
                    'type'    => 'diskon',
                ];
            })
            ->filter(fn($row) => $row['qty'] < 10);

        // Satukan hasil untuk tabel (tidak digabung normal vs diskon)
        $lowStockProducts = $lowStockNormal
            ->merge($lowStockDiskon)
            ->sortBy('qty')
            ->take(10);

        // Hitung total untuk box "Stok Kurang dari 10"
        $totalLowStock = $lowStockNormal->count() + $lowStockDiskon->count();

        // Barang keluar hari ini
        $todayOutTransactions = Transaction::where('warehouse_id', $warehouse->id)
            ->where('type', 'out')
            ->whereDate('created_at', $today)
            ->with('items.product')
            ->get();
        // Flatten items untuk tabel
        $todayOutProducts = $todayOutTransactions->flatMap(fn($tx) => $tx->items);
        // 10 barang keluar terbanyak bulan ini
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        $topOutItems = Transaction::where('warehouse_id', $warehouse->id)
            ->where('type', 'out')
            ->whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->with('items.product')
            ->get()
            ->flatMap(fn($tx) => $tx->items)
            ->groupBy('product_id')
            ->map(fn($items, $productId) => [ 'product' => $items
            ->first()->product ?? $items->first(), 'total_out' => $items
            ->sum('quantity') ])
            ->sortByDesc('total_out') ->take(10);

        $year = Carbon::now()->year;
        $monthlyOut = Transaction::where('warehouse_id', $warehouse->id)
            ->where('type', 'out')
            ->whereYear('created_at', $year)
            ->with('items')
            ->get()
            ->flatMap(fn($tx) => $tx->items
            ->map(fn($item) => [ 'quantity' => $item->quantity, 'month' => $tx->created_at->month ]))
            ->groupBy('month')
            ->map(fn($items) => collect($items)
            ->sum('quantity'));
        // Pastikan semua bulan ada
        $monthlyOut = collect(range(1,12))
        ->mapWithKeys(fn($m) => [$m => $monthlyOut[$m] ?? 0]);
        return view('warehouse.dashboard', compact(
            'todayOutProducts',
            'topOutItems',
            'lowStockProducts',
            'monthlyOut',
            'totalProducts',
            'totalStock',
            'totalAsset',
            'totalLowStock',
            'totalDeposit'
        ));
    }
}
