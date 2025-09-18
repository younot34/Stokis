<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PurchaseOrder;
use App\Models\Product;
use App\Models\Warehouse;

class DashboardController extends Controller
{
    public function index()
    {
        $totalWarehouses = Warehouse::count();
        $totalProducts   = Product::count();
        $totalPO         = PurchaseOrder::count();

        $orders = PurchaseOrder::with('requester')
                    ->latest()
                    ->take(5)
                    ->get();

        return view('admin.dashboard', compact(
            'totalWarehouses',
            'totalProducts',
            'totalPO',
            'orders'
        ));
    }
}
