<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index() {
        $products = Product::with('category','warehouses')->get();
        $products = Product::with(['category','parentCategory','warehouses'])->get();
        return view('admin.products.index', compact('products'));
    }

    public function create() {
        $parents = Category::whereNull('parent_id')->with('children')->get();
        $parents = Category::with('children')->whereNull('parent_id')->get();
        return view('admin.products.create', compact('parents'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'products' => 'required|array',
            'products.*.code' => 'required|string|distinct',
            'products.*.name' => 'required|string',
            'products.*.price' => 'required|numeric',
        ]);

        foreach ($request->products as $i => $p) {
            // Cari / buat kategori induk
            $parent = Category::firstOrCreate(
                ['name' => $p['parent_name'], 'parent_id' => null],
                ['name' => $p['parent_name']]
            );

            // Cari / buat subkategori
            $subcategory = Category::firstOrCreate(
                ['name' => $p['subcategory_name'], 'parent_id' => $parent->id],
                ['name' => $p['subcategory_name'], 'parent_id' => $parent->id]
            );

            // Buat produk
            Product::create([
                'code' => $p['code'],
                'name' => $p['name'],
                'parent_id' => $parent->id,
                'category_id' => $subcategory->id,
                'price' => $p['price'],
            ]);
        }

        return redirect()->route('admin.products.index')->with('success','Produk berhasil ditambahkan');
    }

    public function edit(Product $product) {
        $parents = Category::with('children')->whereNull('parent_id')->get();
        $warehouses = Warehouse::all();
        return view('admin.products.edit', compact('product','parents','warehouses'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'code'=>"required|unique:products,code,{$product->id}",
            'name'=>'required',
            'parent_name'=>'required|string',
            'subcategory_name'=>'required|string',
            'price'=>'required|numeric'
        ]);

        // Cari / buat kategori induk
        $parent = Category::firstOrCreate(
            ['name' => $request->parent_name, 'parent_id' => null],
            ['name' => $request->parent_name]
        );

        // Cari / buat subkategori
        $subcategory = Category::firstOrCreate(
            ['name' => $request->subcategory_name, 'parent_id' => $parent->id],
            ['name' => $request->subcategory_name, 'parent_id' => $parent->id]
        );

        // Update produk
        $product->update([
            'code' => $request->code,
            'name' => $request->name,
            'parent_id' => $parent->id,
            'category_id' => $subcategory->id,
            'price' => $request->price,
        ]);

        return redirect()->route('admin.products.index')->with('success','Produk berhasil diperbarui');
    }

    public function destroy(Product $product) {
        $product->delete();
        return redirect()->route('admin.products.index')->with('success','Product deleted');
    }

    public function find(Request $request)
    {
        $query = Product::with(['category.parent']);

        if ($request->code) {
            $product = $query->where('code', $request->code)->first();
        } elseif ($request->id) {
            $product = $query->find($request->id);
        } else {
            return response()->json(['error' => 'Produk tidak ditemukan'], 404);
        }

        if (!$product) {
            return response()->json(['error' => 'Produk tidak ditemukan'], 404);
        }

        return response()->json([
            'id'         => $product->id,
            'code'       => $product->code,
            'name'       => $product->name,
            'category'   => $product->parentCategory->name ?? '-',
            'subcategory'=> $product->category->name ?? '-',
            'price'      => $product->price,
        ]);
    }
}

