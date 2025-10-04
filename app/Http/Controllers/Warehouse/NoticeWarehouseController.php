<?php

namespace App\Http\Controllers\Warehouse;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Notice;
use App\Models\PurchaseOrderDiscountItem;
use App\Models\PurchaseOrderItem;
use App\Models\Warehouse;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NoticeWarehouseController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $warehouse = $user->warehouse ?? null;

        // Barang normal → groupBy product_id, filter sesuai warehouse user
        $itemsQuery = PurchaseOrderItem::with('product.category.parent')
            ->where('quantity_approved', '>', 0);

        $discountItemsQuery = PurchaseOrderDiscountItem::with('product.category.parent')
            ->where('quantity_approved', '>', 0);

        if ($warehouse) {
            // filter sesuai warehouse user
            $itemsQuery->whereHas('purchaseOrder', fn($q) => $q->where('warehouse_id', $warehouse->id));
            $discountItemsQuery->whereHas('purchaseOrder', fn($q) => $q->where('warehouse_id', $warehouse->id));
        }

        $items = $itemsQuery->get()
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
                    'qty_total'   => $group->sum('quantity_approved'),
                    'type'        => 'normal',
                    'sources'     => $group,
                ];
            })->values();

        $discountItems = $discountItemsQuery->get()
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

        $allItems = $items->concat($discountItems);

        // transaksi → filter juga sesuai warehouse user
        $transactionsQuery = Notice::with('items.product','creator')->latest();
        if ($warehouse) {
            $transactionsQuery->where('warehouse_id', $warehouse->id);
        }
        $transactions = $transactionsQuery->paginate(10);

        if ($warehouse) {
            $warehouses = collect([$warehouse]);
        } else {
            $warehouses = Warehouse::all();
        }

        return view('warehouse.notice.index', compact('transactions','items','discountItems','allItems','warehouses'));
    }
    public function store(Request $request)
    {
        $user = auth()->user();
        $rules = [
            'code'        => 'required|string|unique:transactions,code',
            'note'        => 'nullable|string',
            'items'       => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity'   => 'required|integer|min:1',
            'items.*.type'       => 'required|in:normal,discount',
        ];
        if (!$user->warehouse) {
            $rules['warehouse_id'] = 'required|exists:warehouses,id';
        }
        $request->validate($rules);
        // tentukan warehouse objek
        if ($user->warehouse) {
            $warehouse = $user->warehouse;
        } else {
            // ambil dari request (admin memilih)
            $warehouse = Warehouse::findOrFail($request->warehouse_id);
        }

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
            $transaction = Notice::create([
                'code'         => $request->code,
                'warehouse_id' => $warehouse->id,
                'type'         => 'out',
                'note'         => $request->note,
                'grand_total'  => 0,
                'created_by'   => Auth::id(),
                'status'          => $request->status ?? 'pending',
                'jasa_pengiriman' => $request->jasa_pengiriman ?? null,
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

    public function edit(Notice $notice)
    {
        // pastikan user cuma bisa edit notice dari warehouse mereka
        $user = auth()->user();
        if ($user->warehouse && $notice->warehouse_id != $user->warehouse->id) {
            abort(403);
        }

        return view('warehouse.notice.edit', compact('notice'));
    }

    public function update(Request $request, Notice $notice)
    {
        $user = auth()->user();
        if ($user->warehouse && $notice->warehouse_id != $user->warehouse->id) {
            abort(403);
        }

        $request->validate([
            'status' => 'required|string',
            'jasa_pengiriman' => 'nullable|string|max:100',
        ]);

        $notice->update([
            'status' => $request->status,
            'jasa_pengiriman' => $request->jasa_pengiriman,
            'created_by' => $user->id,
        ]);

        return redirect()->route('warehouse.notice.index')->with('success', 'Notice berhasil diperbarui.');
    }

}
