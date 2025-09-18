<?php

namespace App\Http\Controllers\Warehouse;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $warehouse = Auth::user()->warehouse;

        $today = Carbon::today();
        $totalProducts = $warehouse->products->count();

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
            ->map(fn($items, $productId) => [
                'product' => $items->first()->product ?? $items->first(),
                'total_out' => $items->sum('quantity')
            ])
            ->sortByDesc('total_out')
            ->take(10);

        // 10 barang stok kurang dari 10
        $lowStockProducts = $warehouse->products()
            ->wherePivot('quantity', '<', 10)
            ->orderBy('pivot_quantity', 'asc')
            ->take(10)
            ->get();

        // Total barang keluar per bulan selama 12 bulan terakhir
        $year = Carbon::now()->year;
        $monthlyOut = Transaction::where('warehouse_id', $warehouse->id)
            ->where('type', 'out')
            ->whereYear('created_at', $year)
            ->with('items')
            ->get()
            ->flatMap(fn($tx) => $tx->items)
            ->groupBy(fn($item) => $item->created_at->month)
            ->map(fn($items) => $items->sum('quantity'));

        return view('warehouse.dashboard', compact(
            'todayOutProducts',
            'topOutItems',
            'lowStockProducts',
            'monthlyOut',
            'totalProducts'
        ));
    }
}
