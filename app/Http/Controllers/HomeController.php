<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Collection;

class HomeController extends Controller
{
    public function index()
    {
        $featuredProducts = Product::where('is_featured', true)
            ->where('status', 'active')
            ->latest()
            ->take(8)
            ->get();
            
        $newProducts = Product::where('is_new', true)
            ->where('status', 'active')
            ->latest()
            ->take(8)
            ->get();
            
        $preorderProducts = Product::where('is_preorder', true)
            ->where('status', 'active')
            ->latest()
            ->take(8)
            ->get();
            
        $collections = Collection::where('is_active', true)
            ->latest()
            ->take(6)
            ->get();
            
        $categories = Category::where('is_active', true)
            ->whereNull('parent_id')
            ->orderBy('order')
            ->get();
            
        return view('home', compact(
            'featuredProducts',
            'newProducts',
            'preorderProducts',
            'collections',
            'categories'
        ));
    }
}
