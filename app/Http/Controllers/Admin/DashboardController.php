<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PurchaseOrder;
use App\Models\Product;
use App\Models\Warehouse;
use App\Models\CentralStock;

class DashboardController extends Controller
{
    public function index()
    {
        $totalWarehouses = Warehouse::count();
        $totalProducts   = Product::count();
        $totalPO         = PurchaseOrder::count();
        $totalStock      = CentralStock::sum('quantity');

        $orders = PurchaseOrder::with('requester')
                    ->latest()
                    ->take(5)
                    ->get();
            // 🔹 Ambil Top Transaksi berdasarkan jumlah item
            $topTransactions = \App\Models\Transaction::withCount('items')
                                ->orderByDesc('items_count')
                                ->take(5)
                                ->get();

            // 🔹 Ambil Top Nominal Transaksi
            $topNominals = \App\Models\Transaction::orderByDesc('grand_total')
                                ->take(5)
                                ->get();

        return view('admin.dashboard', compact(
            'totalWarehouses',
            'totalProducts',
            'totalPO',
            'totalStock',
            'orders',
            'topTransactions',
            'topNominals'
        ));
    }
}
