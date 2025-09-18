<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class WarehouseController extends Controller
{
    public function index() {
        $warehouses = Warehouse::all();
        return view('admin.warehouses.index', compact('warehouses'));
    }

    public function create() {
        return view('admin.warehouses.create');
    }

    public function store(Request $request) {
        $request->validate([
            'name' => 'required|string',
            'address' => 'nullable|string',
        ]);
        Warehouse::create($request->only('name','address'));
        return redirect()->route('admin.warehouses.index')->with('success','Warehouse created');
    }

    public function edit(Warehouse $warehouse) {
        return view('admin.warehouses.edit', compact('warehouse'));
    }

    public function update(Request $request, Warehouse $warehouse) {
        $request->validate(['name'=>'required','address'=>'nullable']);
        $warehouse->update($request->only('name','address'));
        return redirect()->route('admin.warehouses.index')->with('success','Warehouse updated');
    }

    public function destroy(Warehouse $warehouse) {
        $warehouse->delete();
        return redirect()->route('admin.warehouses.index')->with('success','Warehouse deleted');
    }
}
