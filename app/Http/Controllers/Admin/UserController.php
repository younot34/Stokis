<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index() {
        $users = User::with('warehouse')->get();
        return view('admin.users.index', compact('users'));
    }

    public function create() {
        $warehouses = Warehouse::all();
        return view('admin.users.create', compact('warehouses'));
    }

    public function store(Request $request) {
        $request->validate([
            'name'=>'required|string',
            'email'=>'required|email|unique:users',
            'password'=>'required|min:6',
            'role'=>'required|in:admin,adminsecond,stokis',
            'warehouse_id'=>'nullable|exists:warehouses,id',
            'permissions'=>'nullable|array'
        ]);
        $user = User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>Hash::make($request->password),
            'role'=>$request->role,
            'warehouse_id'=>$request->warehouse_id
        ]);
        if($request->role === 'adminsecond' && $request->permissions){
            foreach ($request->permissions as $perm) {
                $user->permissions()->create(['permission' => $perm]);
            }
        }
        return redirect()->route('admin.users.index')->with('success','User created');
    }

    public function edit(User $user) {
        $warehouses = Warehouse::all();
        return view('admin.users.edit', compact('user','warehouses'));
    }

    public function update(Request $request, User $user) {
        $request->validate([
            'name'=>'required|string',
            'email'=>"required|email|unique:users,email,{$user->id}",
            'role'=>'required|in:admin,adminsecond,stokis',
            'warehouse_id'=>'nullable|exists:warehouses,id',
            'permissions'=>'nullable|array'
        ]);
        $data = $request->only('name','email','role','warehouse_id');
        if($request->password){
            $data['password'] = Hash::make($request->password);
        }
        $user->update($data);
        if($request->role === 'adminsecond'){
            $user->permissions()->delete();
            if($request->permissions){
                foreach ($request->permissions as $perm) {
                    $user->permissions()->create(['permission' => $perm]);
                }
            }
        } else {
            $user->permissions()->delete();
        }
        return redirect()->route('admin.users.index')->with('success','User updated');
    }

    public function destroy(User $user) {
        $user->delete();
        return redirect()->route('admin.users.index')->with('success','User deleted');
    }
}
