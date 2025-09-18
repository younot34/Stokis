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

        $transactions = Transaction::with('items.product','creator')
            ->where('warehouse_id', $warehouse->id)
            ->latest()
            ->paginate(10);

        return view('warehouse.transactions.index', compact('transactions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'items'   => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity'   => 'required|integer|min:1',
            'items.*.note'       => 'nullable|string',
        ]);

        $warehouse = auth()->user()->warehouse;

        // buat transaksi induk
        $transaction = Transaction::create([
            'code'        => 'TX-' . now()->format('YmdHis'),
            'warehouse_id'=> $warehouse->id,
            'type'        => 'out',
            'note'        => $request->note,
            'created_by'  => Auth::id(),
        ]);

        foreach ($request->items as $item) {
            $product = Product::with(['category.parent'])->findOrFail($item['product_id']);

            // cek stok
            $currentQty = $warehouse->products()
                ->where('product_id',$product->id)
                ->first()?->pivot->quantity ?? 0;
            if ($item['quantity'] > $currentQty) {
                return back()->withErrors(['items' => "Stok {$product->name} tidak mencukupi"]);
            }

            // kurangi stok
            $warehouse->products()->updateExistingPivot($product->id, [
                'quantity' => $currentQty - $item['quantity']
            ]);

            // simpan detail
            $transaction->items()->create([
                'product_id'      => $product->id,
                'product_name'    => $product->name,
                'category_name'   => $product->category->name ?? null,
                'subcategory_name'=> $product->category->parent?->name ?? null,
                'quantity'        => $item['quantity'],
                'price'           => $product->price, // pastikan field ada
                'note'            => $item['note'] ?? null,
            ]);
        }

        return back()->with('success','Transaksi barang keluar berhasil dicatat');
    }
}
