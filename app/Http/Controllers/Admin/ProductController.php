<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category');
        
        if ($request->has('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%");
        }
        
        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->has('genre')) {
            $query->where('genre', $request->genre);
        }
        
        $products = $query->latest()->paginate(20);
        $categories = Category::all();
        $genres = \App\Models\Genre::active()->orderBy('order')->orderBy('name')->pluck('name');
        
        return view('admin.products.index', compact('products', 'categories', 'genres'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        $genres = \App\Models\Genre::active()->orderBy('order')->orderBy('name')->pluck('name');
        return view('admin.products.create', compact('categories', 'genres'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'platform' => 'nullable|string|max:255',
            'short_description' => 'nullable|string',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive,out_of_stock',
            'image' => 'nullable|image|max:2048',
            'is_featured' => 'boolean',
            'is_new' => 'boolean',
            'is_preorder' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        
        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('images/products'), $imageName);
            $validated['image'] = 'images/products/' . $imageName;
        }

        Product::create($validated);

        return redirect()->route('admin.products.index')
            ->with('success', 'Sản phẩm đã được tạo thành công!');
    }

    public function edit(Product $product)
    {
        $categories = Category::where('is_active', true)->get();
        $genres = \App\Models\Genre::active()->orderBy('order')->orderBy('name')->pluck('name');
        return view('admin.products.edit', compact('product', 'categories', 'genres'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'platform' => 'nullable|string|max:255',
            'short_description' => 'nullable|string',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive,out_of_stock',
            'image' => 'nullable|image|max:2048',
            'is_featured' => 'boolean',
            'is_new' => 'boolean',
            'is_preorder' => 'boolean',
        ]);

        // Đặt giá trị checkbox về 0 nếu không được chọn
        $validated['is_featured'] = $request->has('is_featured') ? 1 : 0;
        $validated['is_new'] = $request->has('is_new') ? 1 : 0;
        $validated['is_preorder'] = $request->has('is_preorder') ? 1 : 0;

        $validated['slug'] = Str::slug($validated['name']);
        
        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image
            if ($product->image && file_exists(public_path($product->image))) {
                unlink(public_path($product->image));
            }
            
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('images/products'), $imageName);
            $validated['image'] = 'images/products/' . $imageName;
        }

        $product->update($validated);

        return redirect()->route('admin.products.index')
            ->with('success', 'Sản phẩm đã được cập nhật thành công!');
    }

    public function destroy(Product $product)
    {
        // Delete image
        if ($product->image && file_exists(public_path($product->image))) {
            unlink(public_path($product->image));
        }
        
        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Sản phẩm đã được xóa thành công!');
    }

    public function toggleFeatured(Product $product)
    {
        $product->update(['is_featured' => !$product->is_featured]);
        
        return response()->json([
            'success' => true,
            'is_featured' => $product->is_featured
        ]);
    }
}
