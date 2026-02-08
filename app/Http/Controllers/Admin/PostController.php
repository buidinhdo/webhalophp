<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $query = Post::query();
        
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                // Tìm kiếm theo ID nếu là số
                if (is_numeric($search)) {
                    $q->where('id', $search)
                      ->orWhere('title', 'like', "%{$search}%")
                      ->orWhere('content', 'like', "%{$search}%")
                      ->orWhere('excerpt', 'like', "%{$search}%");
                } else {
                    $q->where('title', 'like', "%{$search}%")
                      ->orWhere('content', 'like', "%{$search}%")
                      ->orWhere('excerpt', 'like', "%{$search}%");
                }
            });
        }
        
        if ($request->has('status') && $request->status) {
            if ($request->status === 'published') {
                $query->where('is_published', true);
            } elseif ($request->status === 'draft') {
                $query->where('is_published', false);
            }
        }
        
        $posts = $query->latest()->paginate(20);
        
        return view('admin.posts.index', compact('posts'));
    }

    public function create()
    {
        return view('admin.posts.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string',
            'content' => 'required|string',
            'image' => 'nullable|image|max:2048',
            'is_published' => 'boolean',
        ]);

        $slug = Str::slug($validated['title']);
        $count = Post::where('slug', 'like', $slug . '%')->count();
        $validated['slug'] = $count > 0 ? $slug . '-' . ($count + 1) : $slug;
        
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('images/posts'), $imageName);
            $validated['image'] = 'images/posts/' . $imageName;
        }

        if (!empty($validated['is_published'])) {
            $validated['published_at'] = now();
        }

        Post::create($validated);

        return redirect()->route('admin.posts.index')
            ->with('success', 'Tin tức đã được tạo thành công!');
    }

    public function show(Post $post)
    {
        return view('admin.posts.show', compact('post'));
    }

    public function edit(Post $post)
    {
        return view('admin.posts.edit', compact('post'));
    }

    public function update(Request $request, Post $post)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string',
            'content' => 'required|string',
            'image' => 'nullable|image|max:2048',
            'is_published' => 'boolean',
        ]);

        $slug = Str::slug($validated['title']);
        $count = Post::where('slug', 'like', $slug . '%')
            ->where('id', '!=', $post->id)
            ->count();
        $validated['slug'] = $count > 0 ? $slug . '-' . ($count + 1) : $slug;
        
        if ($request->hasFile('image')) {
            if ($post->image && file_exists(public_path($post->image))) {
                unlink(public_path($post->image));
            }
            
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('images/posts'), $imageName);
            $validated['image'] = 'images/posts/' . $imageName;
        }

        if (!empty($validated['is_published']) && !$post->published_at) {
            $validated['published_at'] = now();
        } elseif (empty($validated['is_published'])) {
            $validated['published_at'] = null;
        }

        $post->update($validated);

        return redirect()->route('admin.posts.index')
            ->with('success', 'Tin tức đã được cập nhật thành công!');
    }

    public function destroy(Post $post)
    {
        if ($post->image && file_exists(public_path($post->image))) {
            unlink(public_path($post->image));
        }
        
        $post->delete();

        return redirect()->route('admin.posts.index')
            ->with('success', 'Tin tức đã được xóa thành công!');
    }
}
