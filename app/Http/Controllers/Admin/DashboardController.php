<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PurchaseOrder;
use App\Models\Product;
use App\Models\Warehouse;
use App\Models\CentralStock;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

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

        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth   = Carbon::now()->endOfMonth();

        $topTransactions = DB::table('transactions')
            ->join('transaction_items', 'transactions.id', '=', 'transaction_items.transaction_id')
            ->join('warehouses', 'transactions.warehouse_id', '=', 'warehouses.id')
            ->select(
                'warehouses.id',
                'warehouses.name',
                'warehouses.city',
                DB::raw('SUM(transaction_items.quantity) as total_items')
            )
            ->whereBetween('transactions.created_at', [$startOfMonth, $endOfMonth])
            ->groupBy('warehouses.id', 'warehouses.name', 'warehouses.city')
            ->orderByDesc('total_items')
            ->take(5)
            ->get();

        $topNominals = DB::table('transactions')
            ->join('warehouses', 'transactions.warehouse_id', '=', 'warehouses.id')
            ->select(
                'warehouses.id',
                'warehouses.name',
                'warehouses.city',
                DB::raw('SUM(transactions.grand_total) as total_nominal')
            )
            ->whereBetween('transactions.created_at', [$startOfMonth, $endOfMonth])
            ->groupBy('warehouses.id', 'warehouses.name', 'warehouses.city')
            ->orderByDesc('total_nominal')
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
