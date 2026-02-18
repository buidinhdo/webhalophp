<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Collection;
use App\Models\Post;

class HomeController extends Controller
{
    public function index()
    {
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
        
        $ps4Products = $ps4Category ? Product::where('category_id', $ps4Category->id)
            ->active()
            ->latest()
            ->limit(12)
            ->get() : collect();
            
        $ps5Products = $ps5Category ? Product::where('category_id', $ps5Category->id)
            ->active()
            ->latest()
            ->limit(12)
            ->get() : collect();
            
        $nintendoProducts = $nintendoCategory ? Product::where('category_id', $nintendoCategory->id)
            ->active()
            ->latest()
            ->limit(12)
            ->get() : collect();
            
        $xboxProducts = $xboxCategory ? Product::where('category_id', $xboxCategory->id)
            ->active()
            ->latest()
            ->limit(12)
            ->get() : collect();
            
        $collections = Collection::where('is_active', true)
            ->latest()
            ->take(6)
            ->get();
            
        $categories = Category::where('is_active', true)
            ->whereNull('parent_id')
            ->orderBy('order')
            ->get();
            
        $posts = Post::where('is_published', true)
            ->latest('published_at')
            ->take(3)
            ->get();
            
        return view('home', compact(
            'featuredProducts',
            'newProducts',
            'preorderProducts',
            'ps4Products',
            'ps5Products',
            'nintendoProducts',
            'xboxProducts',
            'collections',
            'categories',
            'posts'
        ));
    }
}
