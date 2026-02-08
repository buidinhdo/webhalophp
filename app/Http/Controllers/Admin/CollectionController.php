<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Collection;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CollectionController extends Controller
{
    public function index()
    {
        $collections = Collection::withCount('products')->latest()->paginate(20);
        return view('admin.collections.index', compact('collections'));
    }

    public function create()
    {
        $products = Product::all();
        return view('admin.collections.create', compact('products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'products' => 'array',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $collection = Collection::create($validated);
        
        if ($request->has('products')) {
            $collection->products()->sync($request->products);
        }

        return redirect()->route('admin.collections.index')
            ->with('success', 'Bộ sưu tập đã được tạo thành công!');
    }

    public function edit(Collection $collection)
    {
        $collection->load('products');
        $products = Product::all();
        return view('admin.collections.edit', compact('collection', 'products'));
    }

    public function update(Request $request, Collection $collection)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'products' => 'array',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $collection->update($validated);
        
        if ($request->has('products')) {
            $collection->products()->sync($request->products);
        }

        return redirect()->route('admin.collections.index')
            ->with('success', 'Bộ sưu tập đã được cập nhật thành công!');
    }

    public function destroy(Collection $collection)
    {
        $collection->delete();
        return redirect()->route('admin.collections.index')
            ->with('success', 'Bộ sưu tập đã được xóa thành công!');
    }
}
