<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Publisher;
use App\Models\EsrbRating;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category');
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%");
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('genre')) {
            $query->where('genre', $request->genre);
        }

        if ($request->filled('publisher')) {
            $query->where('publisher', $request->publisher);
        }

        if ($request->filled('esrb_rating')) {
            $query->where('esrb_rating', $request->esrb_rating);
        }

        $products    = $query->latest()->paginate(20);
        $categories  = Category::all();
        $genres      = \App\Models\Genre::active()->orderBy('order')->orderBy('name')->pluck('name');
        $publishers  = Publisher::active()->orderBy('name')->pluck('name');
        $esrbRatings = EsrbRating::active()->orderBy('order')->get(['code', 'name']);

        return view('admin.products.index', compact('products', 'categories', 'genres', 'publishers', 'esrbRatings'));
    }

    public function create()
    {
        $categories  = Category::where('is_active', true)->get();
        $genres      = \App\Models\Genre::active()->orderBy('order')->orderBy('name')->pluck('name');
        $publishers  = Publisher::active()->orderBy('name')->pluck('name');
        $esrbRatings = EsrbRating::active()->orderBy('order')->get(['code', 'name']);
        return view('admin.products.create', compact('categories', 'genres', 'publishers', 'esrbRatings'));
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
            'genre' => 'nullable|string|max:255',
            'publisher' => 'nullable|string|max:255',
            'esrb_rating' => 'nullable|in:E,E10+,T,M,AO,RP',
            'short_description' => 'nullable|string',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive,out_of_stock',
            'image' => 'nullable|mimes:jpg,jpeg,png,gif,bmp,svg,webp,ico,tiff,tif,heic,heif|max:5120',
            'is_featured' => 'boolean',
            'is_new' => 'boolean',
            'is_preorder' => 'boolean',
            'gallery_images.*' => 'nullable|mimes:jpg,jpeg,png,gif,bmp,svg,webp,ico,tiff,tif,heic,heif|max:5120',
        ], [
            'name.required' => 'Vui lòng nhập tên sản phẩm.',
            'category_id.required' => 'Vui lòng chọn danh mục.',
            'price.required' => 'Vui lòng nhập giá sản phẩm.',
            'stock.required' => 'Vui lòng nhập số lượng.',
            'image.mimes' => 'Ảnh chính phải là file ảnh.',
            'image.max' => 'Kích thước ảnh không được vượt quá 5MB.',
            'gallery_images.*.mimes' => 'File phải là ảnh.',
            'gallery_images.*.max' => 'Kích thước ảnh không được vượt quá 5MB.',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        
        // Handle main image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('images/products'), $imageName);
            $validated['image'] = 'images/products/' . $imageName;
        }

        $product = Product::create($validated);

        // Handle gallery images upload
        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $index => $galleryImage) {
                $galleryImageName = time() . '_gallery_' . $index . '_' . $galleryImage->getClientOriginalName();
                $galleryImage->move(public_path('images/products/gallery'), $galleryImageName);
                
                $product->images()->create([
                    'image_path' => 'images/products/gallery/' . $galleryImageName,
                    'order' => $index + 1
                ]);
            }
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Sản phẩm đã được tạo thành công!');
    }

    public function edit(Product $product)
    {
        $categories  = Category::where('is_active', true)->get();
        $genres      = \App\Models\Genre::active()->orderBy('order')->orderBy('name')->pluck('name');
        $publishers  = Publisher::active()->orderBy('name')->pluck('name');
        $esrbRatings = EsrbRating::active()->orderBy('order')->get(['code', 'name']);
        return view('admin.products.edit', compact('product', 'categories', 'genres', 'publishers', 'esrbRatings'));
    }

    public function update(Request $request, Product $product)
    {
        // Nếu chỉ cập nhật ảnh phụ
        if ($request->has('update_gallery')) {
            $request->validate([
                'gallery_images.*' => 'nullable|mimes:jpg,jpeg,png,gif,bmp,svg,webp,ico,tiff,tif,heic,heif|max:5120',
            ], [
                'gallery_images.*.mimes' => 'File phải là ảnh.',
                'gallery_images.*.max' => 'Kích thước ảnh không được vượt quá 5MB.',
            ]);
            
            // Handle gallery images upload
            if ($request->hasFile('gallery_images')) {
                foreach ($request->file('gallery_images') as $index => $galleryImage) {
                    $galleryImageName = time() . '_gallery_' . $index . '_' . $galleryImage->getClientOriginalName();
                    $galleryImage->move(public_path('images/products/gallery'), $galleryImageName);
                    
                    $product->images()->create([
                        'image_path' => 'images/products/gallery/' . $galleryImageName,
                        'order' => $product->images()->count() + $index + 1
                    ]);
                }
                
                $page = $request->input('page', 1);
                return redirect()->route('admin.products.edit', ['product' => $product->id, 'page' => $page])
                    ->with('success', 'Ảnh phụ đã được cập nhật thành công!');
            } else {
                $page = $request->input('page', 1);
                return redirect()->route('admin.products.edit', ['product' => $product->id, 'page' => $page])
                    ->with('error', 'Vui lòng chọn ảnh trước khi cập nhật!');
            }
        }
        
        // Cập nhật toàn bộ sản phẩm
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'platform' => 'nullable|string|max:255',
            'genre' => 'nullable|string|max:255',
            'publisher' => 'nullable|string|max:255',
            'esrb_rating' => 'nullable|in:E,E10+,T,M,AO,RP',
            'short_description' => 'nullable|string',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive,out_of_stock',
            'image' => 'nullable|mimes:jpg,jpeg,png,gif,bmp,svg,webp,ico,tiff,tif,heic,heif|max:5120',
            'is_featured' => 'boolean',
            'is_new' => 'boolean',
            'is_preorder' => 'boolean',
            'gallery_images.*' => 'nullable|mimes:jpg,jpeg,png,gif,bmp,svg,webp,ico,tiff,tif,heic,heif|max:5120',
        ], [
            'name.required' => 'Vui lòng nhập tên sản phẩm.',
            'category_id.required' => 'Vui lòng chọn danh mục.',
            'price.required' => 'Vui lòng nhập giá sản phẩm.',
            'stock.required' => 'Vui lòng nhập số lượng.',
            'image.mimes' => 'Ảnh chính phải là file ảnh.',
            'image.max' => 'Kích thước ảnh không được vượt quá 5MB.',
            'gallery_images.*.mimes' => 'File phải là ảnh.',
            'gallery_images.*.max' => 'Kích thước ảnh không được vượt quá 5MB.',
        ]);

        // Đặt giá trị checkbox về 0 nếu không được chọn
        $validated['is_featured'] = $request->has('is_featured') ? 1 : 0;
        $validated['is_new'] = $request->has('is_new') ? 1 : 0;
        $validated['is_preorder'] = $request->has('is_preorder') ? 1 : 0;

        $validated['slug'] = Str::slug($validated['name']);
        
        // Handle main image upload
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

        // Handle gallery images upload
        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $index => $galleryImage) {
                $galleryImageName = time() . '_gallery_' . $index . '_' . $galleryImage->getClientOriginalName();
                $galleryImage->move(public_path('images/products/gallery'), $galleryImageName);
                
                $product->images()->create([
                    'image_path' => 'images/products/gallery/' . $galleryImageName,
                    'order' => $product->images()->count() + $index + 1
                ]);
            }
        }

        $page = $request->input('page', 1);
        return redirect()->route('admin.products.index', ['page' => $page])
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

    public function deleteGalleryImage($productId, $imageId)
    {
        $product = Product::findOrFail($productId);
        $image = $product->images()->findOrFail($imageId);
        
        // Delete image file
        if (file_exists(public_path($image->image_path))) {
            unlink(public_path($image->image_path));
        }
        
        // Delete database record
        $image->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Đã xóa ảnh thành công!'
        ]);
    }
}
