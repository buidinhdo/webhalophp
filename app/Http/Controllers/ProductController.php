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
        
        if ($request->has('category')) {
            $query->whereHas('category', function($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }
        
        if ($request->has('platform')) {
            $query->whereRaw('LOWER(platform) = ?', [strtolower($request->platform)]);
        }
        
        if ($request->has('search')) {
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
        
        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'price_asc':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('price', 'desc');
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
        $categories = Category::where('is_active', true)->orderBy('order')->get();
        
        return view('products.index', compact('products', 'categories'));
    }
    
    public function show($slug)
    {
        $product = Product::where('slug', $slug)
            ->active()
            ->with(['reviews' => function($query) {
                $query->where('status', 'approved')
                      ->with('user')
                      ->latest();
            }])
            ->firstOrFail();
            
        // Lấy sản phẩm cùng danh mục trước
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->active()
            ->inRandomOrder()
            ->take(4)
            ->get();
            
        // Nếu không đủ 4 sản phẩm, lấy thêm từ các danh mục khác
        if ($relatedProducts->count() < 4) {
            $needed = 4 - $relatedProducts->count();
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
}
