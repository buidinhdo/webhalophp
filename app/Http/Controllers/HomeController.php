<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Collection;
use App\Models\Post;
use App\Models\Genre;
use App\Models\Banner;

class HomeController extends Controller
{
    public function index()
    {
        // Get active banners ordered by order field and creation time
        $banners = Banner::active()
            ->orderBy('order')
            ->orderBy('id', 'asc')
            ->get();
            
        $featuredProducts = Product::featured()
            ->active()
            ->latest()
            ->get();
            
        $newProducts = Product::new()
            ->active()
            ->latest()
            ->limit(12)
            ->get();
            
        $preorderProducts = Product::preorder()
            ->active()
            ->latest()
            ->get();
            
        // Fetch products by platform categories
        $ps4Category = Category::where('slug', 'ps4')->first();
        $ps5Category = Category::where('slug', 'ps5')->first();
        $nintendoCategory = Category::where('slug', 'nintendo-switch')->first();
        $xboxCategory = Category::where('slug', 'xbox')->first();
        
        // Fetch by category OR platform to include all related products (games + accessories)
        $ps4Products = Product::where(function($query) use ($ps4Category) {
                if ($ps4Category) {
                    $query->where('category_id', $ps4Category->id)
                          ->orWhereRaw('LOWER(platform) = ?', ['ps4']);
                } else {
                    $query->whereRaw('LOWER(platform) = ?', ['ps4']);
                }
            })
            ->active()
            ->latest()
            ->get();
            
        $ps5Products = Product::where(function($query) use ($ps5Category) {
                if ($ps5Category) {
                    $query->where('category_id', $ps5Category->id)
                          ->orWhereRaw('LOWER(platform) = ?', ['ps5']);
                } else {
                    $query->whereRaw('LOWER(platform) = ?', ['ps5']);
                }
            })
            ->active()
            ->latest()
            ->get();
            
        $nintendoProducts = Product::where(function($query) use ($nintendoCategory) {
                if ($nintendoCategory) {
                    $query->where('category_id', $nintendoCategory->id)
                          ->orWhereRaw('LOWER(platform) LIKE ?', ['%nintendo%'])
                          ->orWhereRaw('LOWER(platform) LIKE ?', ['%switch%']);
                } else {
                    $query->whereRaw('LOWER(platform) LIKE ?', ['%nintendo%'])
                          ->orWhereRaw('LOWER(platform) LIKE ?', ['%switch%']);
                }
            })
            ->active()
            ->latest()
            ->get();
            
        $xboxProducts = Product::where(function($query) use ($xboxCategory) {
                if ($xboxCategory) {
                    $query->where('category_id', $xboxCategory->id)
                          ->orWhereRaw('LOWER(platform) LIKE ?', ['%xbox%']);
                } else {
                    $query->whereRaw('LOWER(platform) LIKE ?', ['%xbox%']);
                }
            })
            ->active()
            ->orderByRaw("CASE 
                WHEN LOWER(name) NOT LIKE '%xbox 360%' AND LOWER(name) NOT LIKE '% - microsoft xbox 360%' THEN 0
                ELSE 1 
            END")
            ->latest()
            ->get();
            
        $collections = Collection::where('is_active', true)
            ->latest()
            ->take(6)
            ->get();
            
        // Get genres with representative products
        // Only show active genres from genres table
        $genreCollections = Genre::active()
            ->orderBy('order')
            ->orderBy('name')
            ->get()
            ->map(function($genre) {
                // Count products for this genre
                $productCount = Product::active()
                    ->where('genre', $genre->name)
                    ->count();
                
                // Get a representative product for the genre image
                $product = Product::active()
                    ->where('genre', $genre->name)
                    ->where('image', '!=', '')
                    ->whereNotNull('image')
                    ->inRandomOrder()
                    ->first();
                
                return [
                    'type' => 'genre',
                    'genre' => $genre->name,
                    'image' => $product ? $product->image : null,
                    'product_count' => $productCount,
                    'icon' => $genre->icon
                ];
            })
            ->filter(function($item) {
                // Only show genres that have at least one product with image
                return $item['image'] !== null && $item['product_count'] > 0;
            });
            
        // Get platform collections (Nintendo Switch, Xbox, etc.)
        $platformCollections = Product::active()
            ->whereNotNull('platform')
            ->where('platform', '!=', '')
            ->whereIn('platform', ['Nintendo Switch', 'Xbox']) // Only specific platforms
            ->select('platform')
            ->selectRaw('COUNT(*) as product_count')
            ->groupBy('platform')
            ->get()
            ->map(function($item) {
                // Get a representative product for the platform image
                $product = Product::active()
                    ->where('platform', $item->platform)
                    ->where('image', '!=', '')
                    ->whereNotNull('image')
                    ->inRandomOrder()
                    ->first();
                
                return [
                    'type' => 'platform',
                    'genre' => $item->platform, // Use genre field for display
                    'image' => $product ? $product->image : null,
                    'product_count' => $item->product_count
                ];
            })
            ->filter(function($item) {
                return $item['image'] !== null && $item['product_count'] > 0;
            });
            
        // Merge genres and platforms
        $genreCollections = $genreCollections->merge($platformCollections)->values();
            
        $categories = Category::where('is_active', true)
            ->whereNull('parent_id')
            ->orderBy('order')
            ->get();
            
        $posts = Post::where('is_published', true)
            ->latest('published_at')
            ->take(3)
            ->get();
            
        return view('home', compact(
            'banners',
            'featuredProducts',
            'newProducts',
            'preorderProducts',
            'ps4Products',
            'ps5Products',
            'nintendoProducts',
            'xboxProducts',
            'collections',
            'genreCollections',
            'categories',
            'posts'
        ));
    }
}
