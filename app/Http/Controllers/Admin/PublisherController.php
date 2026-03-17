<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Publisher;
use Illuminate\Http\Request;

class PublisherController extends Controller
{
    public function index()
    {
        $publishers = Publisher::orderBy('name')->paginate(20);
        return view('admin.publishers.index', compact('publishers'));
    }

    public function create()
    {
        return view('admin.publishers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:publishers,name',
        ], [
            'name.required' => 'Tên nhà phát hành không được để trống',
            'name.unique'   => 'Nhà phát hành này đã tồn tại',
        ]);

        Publisher::create([
            'name'      => $request->name,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.publishers.index')
            ->with('success', 'Thêm nhà phát hành thành công!');
    }

    public function edit(Publisher $publisher)
    {
        return view('admin.publishers.edit', compact('publisher'));
    }

    public function update(Request $request, Publisher $publisher)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:publishers,name,' . $publisher->id,
        ], [
            'name.required' => 'Tên nhà phát hành không được để trống',
            'name.unique'   => 'Nhà phát hành này đã tồn tại',
        ]);

        $oldName = $publisher->name;
        $newName = $request->name;

        $publisher->update([
            'name'      => $newName,
            'is_active' => $request->boolean('is_active'),
        ]);

        // Cập nhật tên nhà phát hành trong bảng products nếu đổi tên
        if ($oldName !== $newName) {
            \App\Models\Product::where('publisher', $oldName)
                ->update(['publisher' => $newName]);
        }

        return redirect()->route('admin.publishers.index')
            ->with('success', 'Cập nhật nhà phát hành thành công!');
    }

    public function destroy(Publisher $publisher)
    {
        $productCount = \App\Models\Product::where('publisher', $publisher->name)->count();

        if ($productCount > 0) {
            return redirect()->route('admin.publishers.index')
                ->with('error', "Không thể xóa vì có {$productCount} sản phẩm đang dùng nhà phát hành này!");
        }

        $publisher->delete();

        return redirect()->route('admin.publishers.index')
            ->with('success', 'Xóa nhà phát hành thành công!');
    }

    public function toggle(Publisher $publisher)
    {
        $publisher->is_active = !$publisher->is_active;
        $publisher->save();

        return redirect()->route('admin.publishers.index')
            ->with('success', 'Cập nhật trạng thái thành công!');
    }
}
