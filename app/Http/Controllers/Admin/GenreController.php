<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Genre;
use Illuminate\Http\Request;

class GenreController extends Controller
{
    /**
     * Hiển thị danh sách thể loại
     */
    public function index()
    {
        $genres = Genre::orderBy('order')
            ->orderBy('name')
            ->paginate(20);

        return view('admin.genres.index', compact('genres'));
    }

    /**
     * Hiển thị form tạo thể loại mới
     */
    public function create()
    {
        return view('admin.genres.create');
    }

    /**
     * Lưu thể loại mới
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:genres,name',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:100',
            'is_active' => 'boolean',
            'order' => 'integer|min:0',
        ], [
            'name.required' => 'Tên thể loại không được để trống',
            'name.unique' => 'Thể loại này đã tồn tại',
        ]);

        Genre::create($request->all());

        return redirect()->route('admin.genres.index')
            ->with('success', 'Thêm thể loại game thành công!');
    }

    /**
     * Hiển thị form sửa thể loại
     */
    public function edit(Genre $genre)
    {
        return view('admin.genres.edit', compact('genre'));
    }

    /**
     * Cập nhật thể loại
     */
    public function update(Request $request, Genre $genre)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:genres,name,' . $genre->id,
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:100',
            'is_active' => 'boolean',
            'order' => 'integer|min:0',
        ], [
            'name.required' => 'Tên thể loại không được để trống',
            'name.unique' => 'Thể loại này đã tồn tại',
        ]);

        // Lưu tên cũ để cập nhật products
        $oldName = $genre->name;
        $newName = $request->name;

        $genre->update($request->all());

        // Cập nhật genre trong products nếu tên thay đổi
        if ($oldName !== $newName) {
            \App\Models\Product::where('genre', $oldName)
                ->update(['genre' => $newName]);
        }

        return redirect()->route('admin.genres.index')
            ->with('success', 'Cập nhật thể loại game thành công!');
    }

    /**
     * Xóa thể loại
     */
    public function destroy(Genre $genre)
    {
        $productCount = \App\Models\Product::where('genre', $genre->name)->count();

        if ($productCount > 0) {
            return redirect()->route('admin.genres.index')
                ->with('error', "Không thể xóa thể loại này vì có {$productCount} sản phẩm đang sử dụng!");
        }

        $genre->delete();

        return redirect()->route('admin.genres.index')
            ->with('success', 'Xóa thể loại game thành công!');
    }

    /**
     * Toggle trạng thái active
     */
    public function toggleActive(Genre $genre)
    {
        $genre->is_active = !$genre->is_active;
        $genre->save();

        return redirect()->route('admin.genres.index')
            ->with('success', 'Cập nhật trạng thái thành công!');
    }
}
