<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Order;

class AccountController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Trang thông tin tài khoản
    public function profile()
    {
        $user = Auth::user();
        return view('account.profile', compact('user'));
    }

    // Cập nhật thông tin tài khoản
    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ], [
            'name.required' => 'Vui lòng nhập họ tên',
        ]);

        $user = Auth::user();
        $user->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        return redirect()->route('account.profile')->with('success', 'Cập nhật thông tin thành công!');
    }

    // Đổi mật khẩu
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:6|confirmed',
        ], [
            'current_password.required' => 'Vui lòng nhập mật khẩu hiện tại',
            'new_password.required' => 'Vui lòng nhập mật khẩu mới',
            'new_password.min' => 'Mật khẩu mới phải có ít nhất 6 ký tự',
            'new_password.confirmed' => 'Mật khẩu xác nhận không khớp',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Mật khẩu hiện tại không đúng']);
        }

        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        return redirect()->route('account.profile')->with('success', 'Đổi mật khẩu thành công!');
    }

    // Danh sách đơn hàng
    public function orders()
    {
        $orders = Order::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('account.orders', compact('orders'));
    }

    // Chi tiết đơn hàng
    public function orderDetail($id)
    {
        $order = Order::where('user_id', Auth::id())
            ->where('id', $id)
            ->with(['items.product'])
            ->firstOrFail();

        return view('account.order-detail', compact('order'));
    }
}
