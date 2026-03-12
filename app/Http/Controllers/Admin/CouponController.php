<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Coupon;

class CouponController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    // Danh sách mã giảm giá
    public function index()
    {
        $coupons = Coupon::latest()->paginate(20);
        return view('admin.coupons.index', compact('coupons'));
    }

    // Trang tạo mã mới
    public function create()
    {
        return view('admin.coupons.create');
    }

    // Lưu mã mới
    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|unique:coupons|max:50',
            'description' => 'nullable|max:255',
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0',
            'min_order_value' => 'required|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'usage_per_user' => 'required|integer|min:1',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'is_active' => 'boolean',
        ], [
            'code.required' => 'Vui lòng nhập mã giảm giá',
            'code.unique' => 'Mã giảm giá này đã tồn tại',
            'type.required' => 'Vui lòng chọn loại giảm giá',
            'value.required' => 'Vui lòng nhập giá trị giảm',
            'min_order_value.required' => 'Vui lòng nhập giá trị đơn hàng tối thiểu',
            'end_date.after_or_equal' => 'Ngày kết thúc phải sau ngày bắt đầu',
        ]);

        Coupon::create([
            'code' => strtoupper($request->code),
            'description' => $request->description,
            'type' => $request->type,
            'value' => $request->value,
            'min_order_value' => $request->min_order_value,
            'max_discount' => $request->max_discount,
            'usage_limit' => $request->usage_limit,
            'usage_per_user' => $request->usage_per_user,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'is_active' => $request->has('is_active') ? 1 : 0,
        ]);

        return redirect()->route('admin.coupons.index')
            ->with('success', 'Tạo mã giảm giá thành công!');
    }

    // Trang chỉnh sửa
    public function edit($id)
    {
        $coupon = Coupon::findOrFail($id);
        return view('admin.coupons.edit', compact('coupon'));
    }

    // Cập nhật mã
    public function update(Request $request, $id)
    {
        $coupon = Coupon::findOrFail($id);

        $request->validate([
            'code' => 'required|max:50|unique:coupons,code,' . $id,
            'description' => 'nullable|max:255',
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0',
            'min_order_value' => 'required|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'usage_per_user' => 'required|integer|min:1',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'is_active' => 'boolean',
        ]);

        $coupon->update([
            'code' => strtoupper($request->code),
            'description' => $request->description,
            'type' => $request->type,
            'value' => $request->value,
            'min_order_value' => $request->min_order_value,
            'max_discount' => $request->max_discount,
            'usage_limit' => $request->usage_limit,
            'usage_per_user' => $request->usage_per_user,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'is_active' => $request->has('is_active') ? 1 : 0,
        ]);

        return redirect()->route('admin.coupons.index')
            ->with('success', 'Cập nhật mã giảm giá thành công!');
    }

    // Xóa mã
    public function destroy($id)
    {
        $coupon = Coupon::findOrFail($id);
        $coupon->delete();

        return redirect()->route('admin.coupons.index')
            ->with('success', 'Xóa mã giảm giá thành công!');
    }

    // Toggle trạng thái active
    public function toggleStatus($id)
    {
        $coupon = Coupon::findOrFail($id);
        $coupon->is_active = !$coupon->is_active;
        $coupon->save();

        return redirect()->back()
            ->with('success', 'Đã cập nhật trạng thái mã giảm giá!');
    }
}
