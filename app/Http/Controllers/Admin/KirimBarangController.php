<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use App\Models\Warehouse;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderRecap;
use App\Models\CentralStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class KirimBarangController extends Controller
{

    private function generateKirimCode($warehouse)
    {
        $prefix = 'KIRIM';
        $month = date('m');
        $year = date('Y');

        $count = PurchaseOrderRecap::where('warehouse_id', $warehouse->id)
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->count() + 1;

        $sequence = str_pad($count, 5, '0', STR_PAD_LEFT);

        return "{$prefix}/{$month}/{$year}/{$sequence}/{$warehouse->name}";
    }
    public function generateCodeAjax(Warehouse $warehouse)
    {
        $code = $this->generateKirimCode($warehouse);
        return response()->json(['code' => $code]);
    }
    public function create()
    {
        $warehouses = Warehouse::all();
        $products = Product::all();

        return view('admin.kirims.create', compact('warehouses', 'products'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'warehouse_id' => 'required|exists:warehouses,id',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.qty' => 'required|integer|min:1',
        ]);

        $warehouse = Warehouse::findOrFail($request->warehouse_id);

        // Buat header recap (langsung approved)
        $recap = PurchaseOrderRecap::create([
            'po_code' => $this->generateKirimCode($warehouse),
            'warehouse_id' => $warehouse->id,
            'requested_by' => Auth::id(),
            'approved_by'  => Auth::id(),
            'status' => 'approved',
        ]);

        // Buat header PO normal (mirror dari recap)
        $po = PurchaseOrder::create([
            'po_code' => $recap->po_code, // samakan kode biar nyambung
            'warehouse_id' => $warehouse->id,
            'requested_by' => Auth::id(),
            'approved_by'  => Auth::id(),
            'status' => 'approved',
        ]);

        // Simpan item
        foreach ($request->items as $item) {
            $product = Product::find($item['product_id']);
            $useDiscount = $item['use_discount'] ?? false;

            if ($useDiscount && ($product->discount || $product->discount_price)) {
                $finalPrice = $product->discount_price ?? ($product->price - ($product->price * $product->discount / 100));

                // simpan ke recap discount
                $recap->discountItems()->create([
                    'product_id' => $product->id,
                    'quantity_requested' => $item['qty'],
                    'quantity_approved' => $item['qty'],
                    'price' => $product->price,
                    'discount' => $product->discount,
                    'final_price' => $finalPrice,
                ]);

                // simpan ke PO discount
                $po->discountItems()->create([
                    'product_id' => $product->id,
                    'quantity_requested' => $item['qty'],
                    'quantity_approved' => $item['qty'],
                    'price' => $product->price,
                    'discount' => $product->discount,
                    'final_price' => $finalPrice,
                ]);
            } else {
                // simpan ke recap normal
                $recap->items()->create([
                    'product_id' => $product->id,
                    'quantity_requested' => $item['qty'],
                    'quantity_approved' => $item['qty'],
                    'price' => $product->price,
                ]);

                // simpan ke PO normal
                $po->items()->create([
                    'product_id' => $product->id,
                    'quantity_requested' => $item['qty'],
                    'quantity_approved' => $item['qty'],
                    'price' => $product->price,
                ]);
            }

            // Tambah stok ke warehouse tujuan
            $currentQty = $warehouse->products()->where('product_id', $product->id)->first()?->pivot->quantity ?? 0;
            $warehouse->products()->syncWithoutDetaching([
                $product->id => ['quantity' => $currentQty + $item['qty']]
            ]);

            // Kurangi stok pusat
            $centralStock = CentralStock::where('product_id', $product->id)->first();
            if ($centralStock) {
                $newQty = max(0, $centralStock->quantity - $item['qty']);
                $centralStock->update(['quantity' => $newQty]);
            }
        }

        return redirect()->route('admin.kirims.index')->with('success', 'Barang berhasil dikirim ke stokis');
    }
    public function index()
    {
        $user = auth()->user();
        $kirims = PurchaseOrderRecap::with(['items.product', 'discountItems.product', 'warehouse', 'requester'])
            ->where('po_code', 'LIKE', 'KIRIM%')
            ->latest()
            ->paginate(10);
        return view('admin.kirims.index', compact('kirims'));
    }
    public function show(PurchaseOrderRecap $kirim)
    {
        $kirim->load('items.product', 'discountItems.product', 'warehouse');
        return view('admin.kirims.show', compact('kirim'));
    }
}