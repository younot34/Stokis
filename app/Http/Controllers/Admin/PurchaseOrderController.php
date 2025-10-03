<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderRecap;
use App\Models\Product;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PurchaseOrderController extends Controller
{
    public function index()
    {
        $orders = PurchaseOrder::with([
            'warehouse',
            'items.product',
            'requester',
            'approver'
        ])
        ->where('po_code', 'LIKE', 'AGT%')
        ->latest()->get();

        return view('admin.purchase_orders.index', compact('orders'));
    }
    public function indexWarehouse()
    {
        $user = auth()->user();

        // Ambil data dari tabel recap, bukan PO biasa
        $purchaseOrderRecaps = \App\Models\PurchaseOrderRecap::with(['items.product', 'discountItems.product', 'warehouse', 'requester'])
            ->where('warehouse_id', $user->warehouse_id)
            ->latest()
            ->paginate(10);

        return view('warehouse.purchase_orders.index', compact('purchaseOrderRecaps'));
    }
    private function generatePoCode($warehouse)
    {
        $prefix = 'AGT';
        $month = date('m');
        $year = date('Y');

        // hitung jumlah PO bulan ini di stokis tsb
        $count = \App\Models\PurchaseOrder::where('warehouse_id', $warehouse->id)
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->count() + 1;

        $sequence = str_pad($count, 5, '0', STR_PAD_LEFT);

        return "{$prefix}/{$month}/{$year}/{$sequence}/{$warehouse->name}";
    }

    public function createWarehouse()
    {
        $warehouse = auth()->user()->warehouse;
        $poCode = $this->generatePoCode($warehouse);
        $products = \App\Models\Product::all();

        return view('warehouse.purchase_orders.create', compact('poCode', 'warehouse', 'products'));
    }

    // stokis request PO
    public function store(Request $request)
    {
        $request->validate([
            'warehouse_id' => 'required|exists:warehouses,id',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.qty' => 'required|integer|min:1',
        ]);

        $warehouse = Warehouse::findOrFail($request->warehouse_id);

        // Buat header PO
        $po = PurchaseOrder::create([
            'po_code' => $this->generatePoCode($warehouse),
            'warehouse_id' => $warehouse->id,
            'requested_by' => Auth::id(),
            'status' => 'pending',
        ]);

        // Simpan detail barang
        foreach ($request->items as $item) {
            $product = Product::find($item['product_id']);
            $useDiscount = $item['use_discount'] ?? false;

            if ($useDiscount && ($product->discount || $product->discount_price)) {
                // PO Diskon
                $finalPrice = $product->final_price;

                $po->discountItems()->create([
                    'product_id' => $product->id,
                    'quantity_requested' => $item['qty'],
                    'price' => $product->price,
                    'discount' => $product->discount,
                    'final_price' => $finalPrice,
                ]);
            } else {
                // PO Normal
                $po->items()->create([
                    'product_id' => $product->id,
                    'quantity_requested' => $item['qty'],
                    'price' => $product->price,
                ]);
            }
        }
        // Buat PO recap
        $poRecap = \App\Models\PurchaseOrderRecap::create([
            'po_code' => $po->po_code,
            'warehouse_id' => $warehouse->id,
            'requested_by' => Auth::id(),
            'status' => 'pending',
        ]);
        // Simpan item normal
        foreach ($request->items as $item) {
            $product = Product::find($item['product_id']);
            $useDiscount = $item['use_discount'] ?? false;

            if ($useDiscount && ($product->discount || $product->discount_price)) {
                $finalPrice = $product->final_price;

                $poRecap->discountItems()->create([
                    'product_id' => $product->id,
                    'quantity_requested' => $item['qty'],
                    'price' => $product->price,
                    'discount' => $product->discount,
                    'final_price' => $finalPrice,
                ]);
            } else {
                $poRecap->items()->create([
                    'product_id' => $product->id,
                    'quantity_requested' => $item['qty'],
                    'price' => $product->price,
                ]);
            }
        }

        return redirect()
            ->route('warehouse.purchase_orders.index')
            ->with('success', 'Purchase Order berhasil dibuat');
    }

    // Admin approve PO
    public function approve(Request $request, PurchaseOrder $purchaseOrder)
    {
        $poRecap = \App\Models\PurchaseOrderRecap::where('po_code', $purchaseOrder->po_code)->first();
        if (!$poRecap) {
            // fallback: buat recap kalau belum ada
            $poRecap = \App\Models\PurchaseOrderRecap::create([
                'po_code' => $purchaseOrder->po_code,
                'warehouse_id' => $purchaseOrder->warehouse_id,
                'requested_by' => $purchaseOrder->requested_by,
                'status' => 'pending',
            ]);

            // salin semua item
            foreach ($purchaseOrder->items as $item) {
                $poRecap->items()->create([
                    'product_id' => $item->product_id,
                    'quantity_requested' => $item->quantity_requested,
                    'price' => $item->price,
                ]);
            }
            foreach ($purchaseOrder->discountItems as $item) {
                $poRecap->discountItems()->create([
                    'product_id' => $item->product_id,
                    'quantity_requested' => $item->quantity_requested,
                    'price' => $item->price,
                    'discount' => $item->discount,
                    'final_price' => $item->final_price,
                ]);
            }
        }

        $errors = [];

        // ✅ Cek stok untuk items normal
        foreach ($request->items ?? [] as $itemId => $approvedQty) {
            $item = $purchaseOrder->items()->find($itemId);
            if ($item) {
                $centralStock = \App\Models\CentralStock::where('product_id', $item->product_id)->first();
                $available = $centralStock?->quantity ?? 0;

                if ($approvedQty > $available) {
                    $errors[] = "Stok pusat produk {$item->product->name} tidak mencukupi.
                                Diminta: {$approvedQty}, tersedia: {$available}";
                }
            }
        }

        // ✅ Cek stok untuk items diskon
        foreach ($request->discount_items ?? [] as $itemId => $approvedQty) {
            $item = $purchaseOrder->discountItems()->find($itemId);
            if ($item) {
                $centralStock = \App\Models\CentralStock::where('product_id', $item->product_id)->first();
                $available = $centralStock?->quantity ?? 0;

                if ($approvedQty > $available) {
                    $errors[] = "Stok pusat produk {$item->product->name} tidak mencukupi.
                                Diminta: {$approvedQty}, tersedia: {$available}";
                }
            }
        }

        // Kalau ada error stok, hentikan approve
        if (!empty($errors)) {
            return back()->withErrors($errors)->withInput();
        }

        // ✅ Kalau stok cukup, lanjut proses approve
        foreach ($request->items ?? [] as $itemId => $approvedQty) {
            $item = $purchaseOrder->items()->find($itemId);
            if ($item) {
                $item->update([
                    'quantity_approved' => $approvedQty,
                    'subtotal' => $approvedQty * $item->price,
                ]);

                $warehouse = $purchaseOrder->warehouse;
                $product = $item->product;
                $currentQty = $warehouse->products()->where('product_id',$product->id)->first()?->pivot->quantity ?? 0;
                $warehouse->products()->syncWithoutDetaching([
                    $product->id => ['quantity' => $currentQty + $approvedQty]
                ]);

                // Kurangi stok pusat
                $centralStock = \App\Models\CentralStock::where('product_id', $product->id)->first();
                if ($centralStock) {
                    $newQty = max(0, $centralStock->quantity - $approvedQty);
                    $centralStock->update(['quantity' => $newQty]);
                }
            }
        }

        foreach ($request->discount_items ?? [] as $itemId => $approvedQty) {
            $discountItem = $purchaseOrder->discountItems()->find($itemId);
            if ($discountItem) {
                $discountItem->update([
                    'quantity_approved' => $approvedQty,
                    'subtotal' => $approvedQty * $discountItem->final_price,
                ]);

                $warehouse = $purchaseOrder->warehouse;
                $product   = $discountItem->product;
                $currentQty = $warehouse->products()
                    ->where('product_id', $product->id)
                    ->first()?->pivot->quantity ?? 0;

                $warehouse->products()->syncWithoutDetaching([
                    $product->id => ['quantity' => $currentQty + $approvedQty]
                ]);

                // Kurangi stok pusat
                $centralStock = \App\Models\CentralStock::where('product_id', $product->id)->first();
                if ($centralStock) {
                    $newQty = max(0, $centralStock->quantity - $approvedQty);
                    $centralStock->update(['quantity' => $newQty]);
                }
            }
        }
        // Normal items
        foreach ($request->items ?? [] as $itemId => $approvedQty) {
            $item = $purchaseOrder->items()->find($itemId);
            if ($item) {
                $item->update([
                    'quantity_approved' => $approvedQty,
                    'subtotal' => $approvedQty * $item->price,
                ]);

                // Update recap
                $recapItem = $poRecap->items()->where('product_id', $item->product_id)->first();
                if ($recapItem) {
                    $recapItem->update(['quantity_approved' => $approvedQty]);
                }

                // Update stok warehouse & central stock
                $warehouse = $purchaseOrder->warehouse;
                $currentQty = $warehouse->products()->where('product_id',$item->product_id)->first()?->pivot->quantity ?? 0;
                $warehouse->products()->syncWithoutDetaching([
                    $item->product_id => ['quantity' => $currentQty + $approvedQty]
                ]);

                $centralStock = \App\Models\CentralStock::where('product_id', $item->product_id)->first();
                if ($centralStock) {
                    $centralStock->decrement('quantity', $approvedQty);
                }
            }
        }

        // Discount items
        foreach ($request->discount_items ?? [] as $itemId => $approvedQty) {
            $item = $purchaseOrder->discountItems()->find($itemId);
            if ($item) {
                $item->update([
                    'quantity_approved' => $approvedQty,
                    'subtotal' => $approvedQty * $item->final_price,
                ]);

                // Update recap
                $recapItem = $poRecap->discountItems()->where('product_id', $item->product_id)->first();
                if ($recapItem) {
                    $recapItem->update(['quantity_approved' => $approvedQty]);
                }

                // Update stok warehouse & central stock
                $warehouse = $purchaseOrder->warehouse;
                $currentQty = $warehouse->products()->where('product_id',$item->product_id)->first()?->pivot->quantity ?? 0;
                $warehouse->products()->syncWithoutDetaching([
                    $item->product_id => ['quantity' => $currentQty + $approvedQty]
                ]);

                $centralStock = \App\Models\CentralStock::where('product_id', $item->product_id)->first();
                if ($centralStock) {
                    $centralStock->decrement('quantity', $approvedQty);
                }
            }
        }

        // Update status PO dan recap
        $poRecap->update([
            'status' => 'approved',
            'approved_by'=>Auth::id()
        ]);
        $purchaseOrder->update([
            'status' => 'approved',
            'approved_by'=>Auth::id()
        ]);

        return back()->with('success','PO approved');
    }
    public function show(PurchaseOrderRecap $poRecap)
    {
        $poRecap->load('items.product', 'discountItems.product', 'warehouse', 'requester');
        return view('admin.purchase_orders.show', ['po' => $poRecap]);
    }
    public function showWarehouseRecap($poId)
    {
        $user = auth()->user();

        $po = \App\Models\PurchaseOrderRecap::with(['items.product', 'discountItems.product', 'warehouse'])
            ->where('id', $poId)
            ->where('warehouse_id', $user->warehouse_id)
            ->firstOrFail();

        return view('warehouse.purchase_orders.show', compact('po'));
    }

}
