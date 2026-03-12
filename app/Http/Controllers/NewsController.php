<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $query = Post::where('is_published', true);
        
        // Tìm kiếm theo tiêu đề
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where('title', 'like', "%{$search}%");
        }
        
        $posts = $query->latest('published_at')->paginate(9);
        
        return view('news.index', compact('posts'));
    }
    
    public function show($slug)
    {
        $post = Post::where('slug', $slug)
                    ->where('is_published', true)
                    ->firstOrFail();
                    
        // Lấy bài viết trước (mới hơn)
        $previousPost = Post::where('is_published', true)
                            ->where('published_at', '>', $post->published_at)
                            ->orderBy('published_at', 'asc')
                            ->first();
                            
        // Lấy bài viết sau (cũ hơn)
        $nextPost = Post::where('is_published', true)
                        ->where('published_at', '<', $post->published_at)
                        ->orderBy('published_at', 'desc')
                        ->first();
                    
        // Lấy tin tức liên quan
        $relatedPosts = Post::where('is_published', true)
                            ->where('id', '!=', $post->id)
                            ->latest('published_at')
                            ->take(3)
                            ->get();
        
        return view('news.show', compact('post', 'relatedPosts', 'previousPost', 'nextPost'));
    }
}
