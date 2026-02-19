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
        
        if ($request->has('genre')) {
            $query->where('genre', $request->genre);
        }
        
        if ($request->has('players')) {
            $query->where('players', $request->players);
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
        $genres = Product::active()->whereNotNull('genre')->where('genre', '!=', '')->distinct()->orderBy('genre')->pluck('genre');
        $players = Product::active()->whereNotNull('players')->where('players', '!=', '')->distinct()->orderByRaw('CAST(players AS UNSIGNED)')->pluck('players');
        
        return view('products.index', compact('products', 'categories', 'genres', 'players'));
    }
    
    public function show($slug)
    {
        $product = Product::where('slug', $slug)
            ->active()
            ->with(['category', 'images'])
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
