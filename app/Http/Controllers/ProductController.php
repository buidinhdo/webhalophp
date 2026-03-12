<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::active();
        
        if ($request->has('category') && $request->category != '') {
            $query->whereHas('category', function($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }
        
        if ($request->has('platform') && $request->platform != '') {
            $query->whereRaw('LOWER(platform) = ?', [strtolower($request->platform)]);
        }
        
        if ($request->has('genre') && $request->genre != '') {
            $query->where('genre', $request->genre);
        }
        
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                // Tìm kiếm không phân biệt hoa thường
                $searchLower = strtolower($search);
                $q->whereRaw('LOWER(name) LIKE ?', ["%{$searchLower}%"])
                  ->orWhereRaw('LOWER(description) LIKE ?', ["%{$searchLower}%"])
                  ->orWhereRaw('LOWER(short_description) LIKE ?', ["%{$searchLower}%"])
                  ->orWhereRaw('LOWER(platform) LIKE ?', ["%{$searchLower}%"]);
            });
        }
        
        // Lọc theo khoảng giá
        if ($request->has('min_price') && $request->min_price != '') {
            $query->where('price', '>=', $request->min_price);
        }
        
        if ($request->has('max_price') && $request->max_price != '') {
            $query->where('price', '<=', $request->max_price);
        }
        
        // Lọc theo năm phát hành
        if ($request->has('release_year') && $request->release_year != '') {
            $query->whereYear('release_date', $request->release_year);
        }
        
        // Lọc theo đánh giá sao (khoảng chính xác)
        if ($request->has('rating') && $request->rating != '') {
            $rating = (int) $request->rating;
            $query->whereHas('reviews');
            
            switch ($rating) {
                case 5:
                    // 5 sao: rating >= 4.5
                    $query->whereRaw('(SELECT AVG(rating) FROM reviews WHERE reviews.product_id = products.id) >= 4.5');
                    break;
                case 4:
                    // 4 sao: rating >= 3.5 và < 4.5
                    $query->whereRaw('(SELECT AVG(rating) FROM reviews WHERE reviews.product_id = products.id) >= 3.5')
                          ->whereRaw('(SELECT AVG(rating) FROM reviews WHERE reviews.product_id = products.id) < 4.5');
                    break;
                case 3:
                    // 3 sao: rating >= 2.5 và < 3.5
                    $query->whereRaw('(SELECT AVG(rating) FROM reviews WHERE reviews.product_id = products.id) >= 2.5')
                          ->whereRaw('(SELECT AVG(rating) FROM reviews WHERE reviews.product_id = products.id) < 3.5');
                    break;
                case 2:
                    // 2 sao: rating >= 1.5 và < 2.5
                    $query->whereRaw('(SELECT AVG(rating) FROM reviews WHERE reviews.product_id = products.id) >= 1.5')
                          ->whereRaw('(SELECT AVG(rating) FROM reviews WHERE reviews.product_id = products.id) < 2.5');
                    break;
                case 1:
                    // 1 sao: rating < 1.5
                    $query->whereRaw('(SELECT AVG(rating) FROM reviews WHERE reviews.product_id = products.id) < 1.5');
                    break;
            }
        }
        
        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'price_asc':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('price', 'desc');
                    break;
                case 'rating':
                    // Sắp xếp theo đánh giá trung bình cao nhất (sản phẩm chưa có đánh giá sẽ ở cuối)
                    $query->orderByRaw('(SELECT COALESCE(AVG(rating), 0) FROM reviews WHERE reviews.product_id = products.id) DESC');
                    break;
                case 'newest':
                    $query->latest();
                    break;
                default:
                    $query->latest();
            }
        } else {
            $query->latest();
        }
        
        $products = $query->paginate(20);
        
        // Load thêm rating trung bình cho tất cả sản phẩm
        $products->getCollection()->transform(function ($product) {
            if (!isset($product->avg_rating)) {
                $product->avg_rating = $product->reviews()->avg('rating');
            }
            $product->reviews_count = $product->reviews()->count();
            return $product;
        });
        
        $categories = Category::where('is_active', true)->orderBy('order')->get();
        $genres = \App\Models\Genre::active()->orderBy('order')->orderBy('name')->pluck('name');
        
        return view('products.index', compact('products', 'categories', 'genres'));
    }
    
    public function show($slug)
    {
        $product = Product::where('slug', $slug)
            ->active()
            ->with(['category', 'images'])
            ->firstOrFail();
            
        // Lấy tất cả sản phẩm cùng danh mục (tối đa 20 sản phẩm)
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->active()
            ->inRandomOrder()
            ->take(20)
            ->get();
            
        // Nếu không đủ 8 sản phẩm, lấy thêm từ các danh mục khác
        if ($relatedProducts->count() < 8) {
            $needed = 8 - $relatedProducts->count();
            $excludeIds = $relatedProducts->pluck('id')->push($product->id)->toArray();
            
            $moreProducts = Product::active()
                ->whereNotIn('id', $excludeIds)
                ->inRandomOrder()
                ->take($needed)
                ->get();
                
            $relatedProducts = $relatedProducts->merge($moreProducts);
        }
            
        return view('products.show', compact('product', 'relatedProducts'));
    }
    
    public function quickView($id)
    {
        $product = Product::with(['category', 'images'])
            ->where('id', $id)
            ->active()
            ->firstOrFail();
            
        return response()->json([
            'success' => true,
            'product' => [
                'id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'price' => $product->price,
                'sale_price' => $product->sale_price,
                'stock' => $product->stock,
                'sku' => $product->sku,
                'platform' => $product->platform,
                'short_description' => $product->short_description,
                'is_new' => $product->is_new,
                'is_preorder' => $product->is_preorder,
                'image' => $product->image ? asset($product->image) : null,
                'images' => $product->images->map(function($img) {
                    return asset('storage/' . $img->image_path);
                }),
                'category_name' => $product->category ? $product->category->name : null,
                'price_formatted' => number_format($product->price),
                'sale_price_formatted' => $product->sale_price ? number_format($product->sale_price) : null,
                'discount_percent' => $product->sale_price ? round((($product->price - $product->sale_price) / $product->price) * 100) : 0,
            ]
        ]);
    }
}
