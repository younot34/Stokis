<?php

namespace App\Http\Controllers\Warehouse;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Deposit;
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

        $rules = [
            'status' => 'required|string',
            'jasa_pengiriman' => 'nullable|string|max:100',
            'resi_number'      => 'nullable|string|max:100',
            'customer_name'    => 'nullable|string|max:255',
            'customer_phone'   => 'nullable|string|max:20',
            'customer_address' => 'nullable|string',
            'image'            => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'shipping_cost'    => 'nullable|string',
        ];
        $request->validate($rules);
        // kalau ada file baru
        $imagePath = $notice->image;
        if ($request->hasFile('image')) {
        // Hapus gambar lama kalau ada
        if ($notice->image && file_exists(public_path($notice->image))) {
            unlink(public_path($notice->image));
        }

        // Simpan file baru ke public/uploads/notices
        $file = $request->file('image');
        $filename = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path('uploads/notices'), $filename);
        $imagePath = 'uploads/notices/' . $filename;
        } else {
            $imagePath = $notice->image;
        }

        $shippingCost = $request->shipping_cost ? (int) str_replace(['.', ','], '', $request->shipping_cost) : 0;

        $notice->update([
            'status'           => $request->status,
            'jasa_pengiriman'  => $request->jasa_pengiriman,
            'resi_number'      => $request->resi_number,
            'customer_name'    => $request->customer_name,
            'customer_phone'   => $request->customer_phone,
            'customer_address' => $request->customer_address,
            'image'            => $imagePath,
            'created_by'       => $user->id,
            'shipping_cost'    => $shippingCost,
        ]);

        // kurangi deposit warehouse
        if ($shippingCost > 0) {
            $deposit = Deposit::firstOrCreate(
                ['warehouse_id' => $notice->warehouse_id],
                ['nominal' => 0]
            );

            $deposit->nominal -= $shippingCost;
            if ($deposit->nominal < 0) {
                $deposit->nominal = 0; // jangan sampai minus
            }
            $deposit->save();
        }


        return redirect()->route('warehouse.notice.index')->with('success', 'Notice berhasil diperbarui.');
    }

    public function unreadCount()
    {
        return response()->json(['count' => auth()->user()->unreadNotifications()->count()]);
    }

    public function markAsRead($id)
    {
        $n = auth()->user()->notifications()->where('id', $id)->first();
        if ($n) {
            $n->markAsRead();
            return response()->json(['status' => 'ok']);
        }
        return response()->json(['status' => 'not_found'], 404);
    }

    public function markAllAsRead()
    {
        auth()->user()->unreadNotifications()->update(['read_at' => now()]);
        return back();
    }

}
