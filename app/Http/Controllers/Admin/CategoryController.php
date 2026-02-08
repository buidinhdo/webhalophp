<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::with('parent')->latest()->paginate(20);
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        $parentCategories = Category::whereNull('parent_id')->get();
        return view('admin.categories.create', compact('parentCategories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string',
            'order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        $slug = Str::slug($validated['name']);
        $count = Category::where('slug', 'like', $slug . '%')->count();
        $validated['slug'] = $count > 0 ? $slug . '-' . ($count + 1) : $slug;
        
        Category::create($validated);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Danh mục đã được tạo thành công!');
    }

    public function edit(Category $category)
    {
        $parentCategories = Category::whereNull('parent_id')->where('id', '!=', $category->id)->get();
        return view('admin.categories.edit', compact('category', 'parentCategories'));
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string',
            'order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        $slug = Str::slug($validated['name']);
        $count = Category::where('slug', 'like', $slug . '%')
            ->where('id', '!=', $category->id)
            ->count();
        $validated['slug'] = $count > 0 ? $slug . '-' . ($count + 1) : $slug;
        
        $category->update($validated);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Danh mục đã được cập nhật thành công!');
    }

    public function destroy(Category $category)
    {
        // Kiểm tra xem danh mục có danh mục con không
        $childrenCount = Category::where('parent_id', $category->id)->count();
        if ($childrenCount > 0) {
            return redirect()->route('admin.categories.index')
                ->with('error', 'Không thể xóa danh mục này vì còn ' . $childrenCount . ' danh mục con. Vui lòng xóa hoặc chuyển danh mục con trước!');
        }
        
        // Kiểm tra xem danh mục có sản phẩm không
        $productsCount = $category->products()->count();
        if ($productsCount > 0) {
            return redirect()->route('admin.categories.index')
                ->with('error', 'Không thể xóa danh mục này vì còn ' . $productsCount . ' sản phẩm. Vui lòng chuyển sản phẩm sang danh mục khác trước!');
        }
        
        $category->delete();
        return redirect()->route('admin.categories.index')
            ->with('success', 'Danh mục đã được xóa thành công!');
    }
}
