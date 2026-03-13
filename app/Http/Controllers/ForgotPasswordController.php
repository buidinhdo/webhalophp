<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class ForgotPasswordController extends Controller
{
    // Hiển thị form quên mật khẩu
    public function showForgotForm()
    {
        return view('auth.forgot-password');
    }

    // Đặt lại mật khẩu trực tiếp
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email'    => 'required|email|exists:users,email',
            'password' => 'required|min:6|confirmed',
        ], [
            'email.required'     => 'Vui lòng nhập email',
            'email.email'        => 'Email không đúng định dạng',
            'email.exists'       => 'Email không tồn tại trong hệ thống',
            'password.required'  => 'Vui lòng nhập mật khẩu mới',
            'password.min'       => 'Mật khẩu phải có ít nhất 6 ký tự',
            'password.confirmed' => 'Mật khẩu xác nhận không khớp',
        ]);

        $user = User::where('email', $request->email)->first();
        $user->update(['password' => Hash::make($request->password)]);

        return redirect()->route('login')->with('success', 'Đặt lại mật khẩu thành công! Vui lòng đăng nhập.');
    }
}
