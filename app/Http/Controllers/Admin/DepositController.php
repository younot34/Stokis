<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Deposit;
use App\Models\Notice;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class DepositController extends Controller
{
    public function index()
    {
        $deposits = Deposit::with('warehouse')->latest()->paginate(10);
        return view('admin.deposits.index', compact('deposits'));
    }

    public function create()
    {
        $warehouses = Warehouse::all();
        return view('admin.deposits.create', compact('warehouses'));
    }

    public function store(Request $request)
    {
        // hapus titik ribuan dari input
        $request->merge([
            'nominal' => str_replace('.', '', $request->nominal),
        ]);

        $request->validate([
            'warehouse_id' => 'required|exists:warehouses,id',
            'nominal' => 'required|numeric|min:1',
        ]);

        // cek apakah sudah ada deposit untuk warehouse ini
        $deposit = Deposit::where('warehouse_id', $request->warehouse_id)->first();

        if ($deposit) {
            // update nominal lama + nominal baru
            $deposit->nominal += $request->nominal;
            $deposit->save();
        } else {
            // buat deposit baru
            Deposit::create($request->only('warehouse_id', 'nominal'));
        }

        return redirect()->route('admin.deposits.index')
                        ->with('success', 'Deposit berhasil ditambahkan.');
    }

    public function edit(Deposit $deposit)
    {
        $warehouses = Warehouse::all();
        return view('admin.deposits.edit', compact('deposit', 'warehouses'));
    }

    public function update(Request $request, Deposit $deposit)
    {
        $request->validate([
            'warehouse_id' => 'required|exists:warehouses,id',
            'nominal' => 'required|numeric|min:1',
        ]);

        $deposit->update($request->only('warehouse_id', 'nominal'));

        return redirect()->route('admin.deposits.index')
                         ->with('success', 'Deposit berhasil diperbarui.');
    }

    public function destroy(Deposit $deposit)
    {
        $deposit->delete();
        return redirect()->route('admin.deposits.index')
                         ->with('success', 'Deposit berhasil dihapus.');
    }

    public function show($warehouseId)
    {
        $notices = Notice::where('warehouse_id', $warehouseId)->get();
        $warehouse = Warehouse::findOrFail($warehouseId);

        return view('admin.deposits.show', compact('notices', 'warehouse'));
    }
}
