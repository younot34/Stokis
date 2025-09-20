<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PurchaseOrder;
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
        ])->latest()->get();

        return view('admin.purchase_orders.index', compact('orders'));
    }
    public function indexWarehouse()
    {
        $user = auth()->user();

        // Ambil hanya PO yang dibuat oleh stokis ini, dengan itemnya
        $purchaseOrders = \App\Models\PurchaseOrder::with('items.product')
            ->where('warehouse_id', $user->warehouse_id)
            ->latest()
            ->paginate(10);

        return view('warehouse.purchase_orders.index', compact('purchaseOrders'));
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
            $po->items()->create([
                'product_id' => $product->id,
                'quantity_requested' => $item['qty'],
                'price' => $product->price,
            ]);
        }

        return redirect()
            ->route('warehouse.purchase_orders.index')
            ->with('success', 'Purchase Order berhasil dibuat');
    }

    // Admin approve PO
    public function approve(Request $request, PurchaseOrder $purchaseOrder)
    {
        foreach ($request->items as $itemId => $approvedQty) {
            $item = $purchaseOrder->items()->find($itemId);
            if ($item) {
                $item->update([
                    'quantity_approved' => $approvedQty
                ]);

                // update stok per produk
                $warehouse = $purchaseOrder->warehouse;
                $product = $item->product;
                $currentQty = $warehouse->products()->where('product_id',$product->id)->first()?->pivot->quantity ?? 0;
                $warehouse->products()->syncWithoutDetaching([
                    $product->id => ['quantity' => $currentQty + $approvedQty]
                ]);

                // ✅ Kurangi stok pusat
                $centralStock = \App\Models\CentralStock::where('product_id', $product->id)->first();
                if ($centralStock) {
                    $newQty = max(0, $centralStock->quantity - $approvedQty); 
                    $centralStock->update(['quantity' => $newQty]);
                }

            }
        }

        $purchaseOrder->update([
            'status' => 'approved',
            'approved_by'=>Auth::id()
        ]);

        return back()->with('success','PO approved');
    }
    public function show(PurchaseOrder $purchaseOrder)
    {
        $purchaseOrder->load('items.product','warehouse','requester');
        return view('admin.purchase_orders.show', ['po' => $purchaseOrder]);
    }

    public function showWarehouse(PurchaseOrder $purchaseOrder)
    {
        // pastikan PO ini hanya bisa dilihat oleh warehouse yang sama
        if ($purchaseOrder->warehouse_id !== auth()->user()->warehouse_id) {
            abort(403, 'Tidak boleh akses PO ini');
        }

        $purchaseOrder->load('items.product','warehouse','requester');
        return view('warehouse.purchase_orders.show', ['po' => $purchaseOrder]);
    }
}
