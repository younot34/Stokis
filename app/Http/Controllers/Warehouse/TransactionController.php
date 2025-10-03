<?php

namespace App\Http\Controllers\Warehouse;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\PurchaseOrderItem;
use App\Models\PurchaseOrderDiscountItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function index()
    {
        $warehouse = auth()->user()->warehouse;

        // Barang normal → groupBy product_id
        $items = PurchaseOrderItem::with('product.category.parent')
            ->whereHas('purchaseOrder', fn($q) => $q->where('warehouse_id', $warehouse->id))
            ->where('quantity_approved', '>', 0)
            ->get()
            ->groupBy('product_id')
            ->map(function ($group) {
                $first = $group->first();
                return [
                    'id'          => $first->product->id,
                    'code'        => $first->product->code,
                    'name'        => $first->product->name,
                    'category'    => $first->product->category->parent->name ?? '',
                    'subcategory' => $first->product->category->name ?? '',
                    'price'       => $first->price,
                    'qty_total'   => $group->sum('quantity_approved'), // total stok gabungan
                    'type'        => 'normal',
                    'sources'     => $group, // simpan semua row aslinya
                ];
            })->values();

        // Barang diskon → groupBy product_id
        $discountItems = PurchaseOrderDiscountItem::with('product.category.parent')
            ->whereHas('purchaseOrder', fn($q) => $q->where('warehouse_id', $warehouse->id))
            ->where('quantity_approved', '>', 0)
            ->get()
            ->groupBy('product_id')
            ->map(function ($group) {
                $first = $group->first();
                return [
                    'id'          => $first->product->id,
                    'code'        => $first->product->code . ' (diskon)',
                    'name'        => $first->product->name . ' (diskon)',
                    'category'    => $first->product->category->parent->name ?? '',
                    'subcategory' => $first->product->category->name ?? '',
                    'price'       => $first->final_price,
                    'qty_total'   => $group->sum('quantity_approved'),
                    'type'        => 'discount',
                    'sources'     => $group,
                ];
            })->values();

            $allItems = $items->merge($discountItems);

        $transactions = Transaction::with('items.product','creator')
            ->where('warehouse_id',$warehouse->id)
            ->latest()->paginate(10);

        return view('warehouse.transactions.index', compact('transactions','items','discountItems', 'allItems'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'code'        => 'required|string|unique:transactions,code',
            'note'        => 'nullable|string',
            'items'       => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity'   => 'required|integer|min:1',
            'items.*.type'       => 'required|in:normal,discount',
        ]);

        $warehouse = auth()->user()->warehouse;

        DB::beginTransaction();
        try {
            $grandTotal = 0;

            // 1️⃣ cek stok dulu (gabungkan normal & diskon)
            foreach ($request->items as $item) {
                $productId = $item['product_id'];
                $quantityRequested = $item['quantity'];
                $type = $item['type'];

                if ($type === 'normal') {
                    $poItems = PurchaseOrderItem::where('product_id', $productId)
                        ->whereHas('purchaseOrder', fn($q) => $q->where('warehouse_id', $warehouse->id))
                        ->orderBy('created_at') // stok lama dahulu
                        ->get();
                } else {
                    $poItems = PurchaseOrderDiscountItem::where('product_id', $productId)
                        ->whereHas('purchaseOrder', fn($q) => $q->where('warehouse_id', $warehouse->id))
                        ->orderBy('created_at')
                        ->get();
                }

                $availableQty = $poItems->sum('quantity_approved');
                $productName = $poItems->first()->product->name ?? 'Unknown';

                if ($quantityRequested > $availableQty) {
                    throw new \Exception("Stok {$productName}" . ($type==='discount' ? ' (diskon)' : '') . " tidak mencukupi");
                }
            }

            // 2️⃣ buat transaksi
            $transaction = Transaction::create([
                'code'         => $request->code,
                'warehouse_id' => $warehouse->id,
                'type'         => 'out',
                'note'         => $request->note,
                'grand_total'  => 0,
                'created_by'   => Auth::id(),
            ]);

            // 3️⃣ simpan item dan kurangi quantity_approved per batch
            foreach ($request->items as $item) {
                $productId = $item['product_id'];
                $quantityRequested = $item['quantity'];
                $type = $item['type'];

                if ($type === 'normal') {
                    $poItems = PurchaseOrderItem::where('product_id', $productId)
                        ->whereHas('purchaseOrder', fn($q) => $q->where('warehouse_id', $warehouse->id))
                        ->orderBy('created_at')
                        ->get();
                } else {
                    $poItems = PurchaseOrderDiscountItem::where('product_id', $productId)
                        ->whereHas('purchaseOrder', fn($q) => $q->where('warehouse_id', $warehouse->id))
                        ->orderBy('created_at')
                        ->get();
                }

                $remaining = $quantityRequested;
                $price = 0;

                foreach ($poItems as $poItem) {
                    if ($remaining <= 0) break;

                    $deduct = min($poItem->quantity_approved, $remaining);
                    $poItem->quantity_approved -= $deduct;
                    $poItem->save();

                    // ambil harga (pakai harga batch pertama)
                    if ($price === 0) {
                        $price = $type === 'normal' ? $poItem->price : $poItem->final_price;
                    }

                    $remaining -= $deduct;
                }

                $product = Product::findOrFail($productId);
                $totalPrice = $price * $quantityRequested;
                $grandTotal += $totalPrice;

                $transaction->items()->create([
                    'product_id'      => $product->id,
                    'product_code'    => $product->code,
                    'product_name'    => $product->name . ($type === 'discount' ? ' (diskon)' : ''),
                    'category_name'   => $product->category->parent->name ?? null,
                    'subcategory_name'=> $product->category->name ?? null,
                    'quantity'        => $quantityRequested,
                    'price'           => $price,
                    'total_price'     => $totalPrice,
                    'note'            => $item['note'] ?? null,
                ]);
            }

            $transaction->update(['grand_total' => $grandTotal]);
            DB::commit();

            return back()->with('success', 'Transaksi barang keluar berhasil dicatat');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['items' => $e->getMessage()]);
        }
    }
}
