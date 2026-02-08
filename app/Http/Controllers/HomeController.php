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
            ->take(8)
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
            'collections',
            'categories',
            'posts'
        ));
    }
}
