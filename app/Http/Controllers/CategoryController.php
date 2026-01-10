<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    public function show($slug)
    {
        $category = Category::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();
            
        $products = $category->products()
            ->where('status', 'active')
            ->latest()
            ->paginate(20);
            
        return view('categories.show', compact('category', 'products'));
    }
}
