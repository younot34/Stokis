<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index() {
        $categories = Category::with('children')->whereNull('parent_id')->get();
        return view('admin.categories.index', compact('categories'));
    }

    public function create() {
        $categories = Category::whereNull('parent_id')->get();
        return view('admin.categories.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'names' => 'required|array',
            'names.*' => 'required|string|max:255',
            'parent_name' => 'nullable|string|max:255',
        ]);

        $parentId = null;
        if ($request->filled('parent_name')) {
            $parent = Category::firstOrCreate(
                ['name' => $request->parent_name]
            );
            $parentId = $parent->id;
        }

        foreach ($request->names as $name) {
            Category::create([
                'name' => $name,
                'parent_id' => $parentId,
            ]);
        }

        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil dibuat');
    }

    public function edit(Category $category) {
        $category->load('parent', 'children');
        $categories = Category::where(function($q) use ($category) {
            $q->whereNull('parent_id')
            ->where('id', '!=', $category->id)
            ->orWhere('id', $category->parent_id);
        })->get();
        $subcategories = $category->children;

        return view('admin.categories.edit', compact('category','subcategories','categories'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'names' => 'nullable|array',
            'names.*' => 'nullable|string|max:255',
            'ids' => 'nullable|array',
            'parentName' => 'nullable|string|max:255',
        ]);

        // Update nama kategori itu sendiri
        if ($request->filled('parentName')) {
            $category->update(['name' => $request->parentName]);
        }

        // Update subkategori hanya jika ada
        if ($category->children->count() > 0 && !empty($request->names)) {
            foreach ($request->names as $i => $name) {
                if (!empty($request->ids[$i])) {
                    $sub = Category::find($request->ids[$i]);
                    if ($sub) $sub->update(['name' => $name]);
                } else {
                    // Subkategori baru
                    Category::create(['name' => $name, 'parent_id' => $category->id]);
                }
            }
        }

        return redirect()->route('admin.categories.index')
                        ->with('success', 'Kategori berhasil diupdate');
    }
    public function destroy(Category $category) {
        $category->delete();
        return redirect()->route('admin.categories.index')->with('success','Category deleted');
    }
}
