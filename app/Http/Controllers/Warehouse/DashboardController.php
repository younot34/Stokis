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

        // Barang keluar hari ini
        $today = Carbon::today();
        $todayOutProducts = Transaction::where('warehouse_id', $warehouse->id)
            ->where('type', 'out')
            ->whereDate('created_at', $today)
            ->with('product')
            ->get();

        // 10 barang keluar terbanyak bulan ini
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        $topOutProducts = Transaction::selectRaw('product_id, SUM(quantity) as total_out')
            ->where('warehouse_id', $warehouse->id)
            ->where('type', 'out')
            ->whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->groupBy('product_id')
            ->with('product')
            ->orderByDesc('total_out')
            ->take(10)
            ->get();

        // 10 barang stok kurang dari 10
        $lowStockProducts = $warehouse->products()
            ->wherePivot('quantity', '<', 10)
            ->orderBy('pivot_quantity', 'asc')
            ->take(10)
            ->get();

        // Total barang keluar per bulan selama 12 bulan terakhir
        $year = Carbon::now()->year;
        $monthlyOut = Transaction::selectRaw('MONTH(created_at) as month, SUM(quantity) as total')
            ->where('warehouse_id', $warehouse->id)
            ->where('type', 'out')
            ->whereYear('created_at', $year)
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month');

        return view('warehouse.dashboard', compact(
            'todayOutProducts',
            'topOutProducts',
            'lowStockProducts',
            'monthlyOut'
        ));
    }
}
