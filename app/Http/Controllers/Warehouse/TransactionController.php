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
            'code'        => 'required|string|unique:transactions,code',
            'note'        => 'nullable|string',
            'items'       => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity'   => 'required|integer|min:1',
        ]);

        $warehouse = auth()->user()->warehouse;

        $transaction = Transaction::create([
            'code'        => $request->code,
            'warehouse_id'=> $warehouse->id,
            'type'        => 'out',
            'note'        => $request->note,
            'grand_total' => 0, // akan diupdate setelah loop
            'created_by'  => Auth::id(),
        ]);

        $grandTotal = 0;

        foreach ($request->items as $item) {
            $product = Product::with(['category.parent'])->findOrFail($item['product_id']);

            $currentQty = $warehouse->products()
                ->where('product_id',$product->id)
                ->first()?->pivot->quantity ?? 0;

            if ($item['quantity'] > $currentQty) {
                return back()->withErrors(['items' => "Stok {$product->name} tidak mencukupi"]);
            }

            $warehouse->products()->updateExistingPivot($product->id, [
                'quantity' => $currentQty - $item['quantity']
            ]);

            $totalPrice = $product->price * $item['quantity'];
            $grandTotal += $totalPrice;

            $transaction->items()->create([
                'product_id'      => $product->id,
                'product_code'    => $product->code,
                'product_name'    => $product->name,
                'category_name'   => $product->category->parent?->name ?? null,
                'subcategory_name'=> $product->category->name ?? null,
                'quantity'        => $item['quantity'],
                'price'           => $product->price,
                'total_price'     => $totalPrice,
                'note'            => $item['note'] ?? null,
            ]);
        }

        $transaction->update(['grand_total' => $grandTotal]);

        return back()->with('success','Transaksi barang keluar berhasil dicatat');
    }
}
