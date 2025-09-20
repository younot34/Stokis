<?php

namespace App\Http\Controllers\Warehouse;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class StockController extends Controller
{
    public function index()
    {
        $warehouse = Auth::user()->warehouse;

        $products = $warehouse->products()->orderBy('name')->get();

        return view('warehouse.stocks.index', compact('products'));
    }
}
