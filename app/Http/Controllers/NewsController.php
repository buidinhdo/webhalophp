<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $query = Post::where('is_published', true);
        
        // Tìm kiếm
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%")
                  ->orWhere('excerpt', 'like', "%{$search}%");
            });
        }
        
        $posts = $query->latest('published_at')->paginate(12);
        
        return view('news.index', compact('posts'));
    }
    
    public function show($slug)
    {
        $post = Post::where('slug', $slug)
                    ->where('is_published', true)
                    ->firstOrFail();
                    
        // Lấy tin tức liên quan
        $relatedPosts = Post::where('is_published', true)
                            ->where('id', '!=', $post->id)
                            ->latest('published_at')
                            ->take(3)
                            ->get();
        
        return view('news.show', compact('post', 'relatedPosts'));
    }
}
