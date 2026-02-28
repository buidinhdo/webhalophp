<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BannerController extends Controller
{
    public function index()
    {
        $banners = Banner::orderBy('order')->orderBy('id', 'desc')->get();
        return view('admin.banners.index', compact('banners'));
    }

    public function create()
    {
        return view('admin.banners.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
            'order' => 'nullable|integer|min:0',
        ]);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '_' . Str::slug($request->title) . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/banners'), $filename);
            $validated['image'] = 'images/banners/' . $filename;
        }

        $validated['is_active'] = $request->has('is_active') ? 1 : 0;
        $validated['order'] = $request->order ?? 0;

        Banner::create($validated);

        return redirect()->route('admin.banners.index')->with('success', 'Banner đã được tạo thành công!');
    }

    public function edit(Banner $banner)
    {
        return view('admin.banners.edit', compact('banner'));
    }

    public function update(Request $request, Banner $banner)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'order' => 'nullable|integer|min:0',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image
            if ($banner->image && file_exists(public_path($banner->image))) {
                unlink(public_path($banner->image));
            }

            $image = $request->file('image');
            $filename = time() . '_' . Str::slug($request->title) . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/banners'), $filename);
            $validated['image'] = 'images/banners/' . $filename;
        }

        $validated['is_active'] = $request->has('is_active') ? 1 : 0;
        $validated['order'] = $request->order ?? 0;

        $banner->update($validated);

        return redirect()->route('admin.banners.index')->with('success', 'Banner đã được cập nhật thành công!');
    }

    public function destroy(Banner $banner)
    {
        // Delete image file
        if ($banner->image && file_exists(public_path($banner->image))) {
            unlink(public_path($banner->image));
        }

        $banner->delete();

        return redirect()->route('admin.banners.index')->with('success', 'Banner đã được xóa thành công!');
    }

    public function toggleActive(Banner $banner)
    {
        $banner->is_active = !$banner->is_active;
        $banner->save();

        return back()->with('success', 'Trạng thái banner đã được cập nhật!');
    }
}
