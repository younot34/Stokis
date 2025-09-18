<?php

namespace App\Http\Controllers\Warehouse;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function index()
    {
        $warehouse = auth()->user()->warehouse;

        $transactions = Transaction::with('product')
            ->where('warehouse_id', $warehouse->id)
            ->latest()
            ->paginate(10);

        return view('warehouse.transactions.index', compact('transactions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'required|integer|min:1',
            'note'       => 'nullable|string',
        ]);

        $warehouse = auth()->user()->warehouse;
        $productId = $request->product_id;
        $qty       = $request->quantity;

        // cek stok saat ini di pivot
        $currentQty = $warehouse->products()
            ->where('product_id', $productId)
            ->first()?->pivot->quantity ?? 0;

        if ($qty > $currentQty) {
            return back()->withErrors(['quantity' => 'Jumlah keluar melebihi stok tersedia (stok: '.$currentQty.')']);
        }

        // kurangi stok
        $warehouse->products()->updateExistingPivot($productId, [
            'quantity' => $currentQty - $qty
        ]);

        // catat transaksi barang keluar
        Transaction::create([
            'warehouse_id' => $warehouse->id,
            'product_id'   => $productId,
            'type'         => 'out',
            'quantity'     => $qty,
            'note'         => $request->note,
            'created_by'   => Auth::id(),
        ]);

        return back()->with('success', 'Barang keluar berhasil dicatat');
    }
}
