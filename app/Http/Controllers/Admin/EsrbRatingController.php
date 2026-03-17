<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EsrbRating;
use Illuminate\Http\Request;

class EsrbRatingController extends Controller
{
    public function index()
    {
        $ratings = EsrbRating::orderBy('order')->orderBy('code')->paginate(20);
        return view('admin.esrb_ratings.index', compact('ratings'));
    }

    public function create()
    {
        return view('admin.esrb_ratings.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'code'        => 'required|string|max:10|unique:esrb_ratings,code',
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'min_age'     => 'nullable|integer|min:0|max:99',
            'order'       => 'integer|min:0',
        ], [
            'code.required' => 'Mã ESRB không được để trống',
            'code.unique'   => 'Mã ESRB này đã tồn tại',
            'name.required' => 'Tên phân loại không được để trống',
        ]);

        EsrbRating::create([
            'code'        => strtoupper(trim($request->code)),
            'name'        => $request->name,
            'description' => $request->description,
            'min_age'     => $request->min_age,
            'is_active'   => $request->boolean('is_active', true),
            'order'       => $request->input('order', 0),
        ]);

        return redirect()->route('admin.esrb-ratings.index')
            ->with('success', 'Thêm phân loại ESRB thành công!');
    }

    public function edit(EsrbRating $esrbRating)
    {
        return view('admin.esrb_ratings.edit', compact('esrbRating'));
    }

    public function update(Request $request, EsrbRating $esrbRating)
    {
        $request->validate([
            'code'        => 'required|string|max:10|unique:esrb_ratings,code,' . $esrbRating->id,
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'min_age'     => 'nullable|integer|min:0|max:99',
            'order'       => 'integer|min:0',
        ], [
            'code.required' => 'Mã ESRB không được để trống',
            'code.unique'   => 'Mã ESRB này đã tồn tại',
            'name.required' => 'Tên phân loại không được để trống',
        ]);

        $oldCode = $esrbRating->code;
        $newCode = strtoupper(trim($request->code));

        $esrbRating->update([
            'code'        => $newCode,
            'name'        => $request->name,
            'description' => $request->description,
            'min_age'     => $request->min_age,
            'is_active'   => $request->boolean('is_active'),
            'order'       => $request->input('order', 0),
        ]);

        // Cập nhật esrb_rating trong products nếu code thay đổi
        if ($oldCode !== $newCode) {
            \App\Models\Product::where('esrb_rating', $oldCode)
                ->update(['esrb_rating' => $newCode]);
        }

        return redirect()->route('admin.esrb-ratings.index')
            ->with('success', 'Cập nhật phân loại ESRB thành công!');
    }

    public function destroy(EsrbRating $esrbRating)
    {
        $productCount = \App\Models\Product::where('esrb_rating', $esrbRating->code)->count();

        if ($productCount > 0) {
            return redirect()->route('admin.esrb-ratings.index')
                ->with('error', "Không thể xóa vì có {$productCount} sản phẩm đang dùng phân loại này!");
        }

        $esrbRating->delete();

        return redirect()->route('admin.esrb-ratings.index')
            ->with('success', 'Xóa phân loại ESRB thành công!');
    }

    public function toggle(EsrbRating $esrbRating)
    {
        $esrbRating->is_active = !$esrbRating->is_active;
        $esrbRating->save();

        return redirect()->route('admin.esrb-ratings.index')
            ->with('success', 'Cập nhật trạng thái thành công!');
    }
}
