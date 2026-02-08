<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuthController extends Controller
{
    public function showLoginForm()
    {
        // Nếu đã đăng nhập là admin, redirect về dashboard
        if (Auth::check() && Auth::user()->is_admin) {
            return redirect()->route('admin.dashboard');
        }
        
        // Nếu đã đăng nhập là user, đăng xuất
        if (Auth::check() && !Auth::user()->is_admin) {
            Auth::logout();
        }
        
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ], [
            'email.required' => 'Vui lòng nhập email',
            'email.email' => 'Email không đúng định dạng',
            'password.required' => 'Vui lòng nhập mật khẩu',
        ]);

        $credentials = $request->only('email', 'password');
        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();
            
            // Kiểm tra nếu là admin
            if ($user->is_admin) {
                $request->session()->regenerate();
                return redirect()->route('admin.dashboard')->with('success', 'Chào mừng Admin!');
            }
            
            // Nếu không phải admin, đăng xuất
            Auth::logout();
            return back()->withErrors([
                'email' => 'Bạn không có quyền truy cập trang quản trị.',
            ])->onlyInput('email');
        }

        return back()->withErrors([
            'email' => 'Thông tin đăng nhập không chính xác.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')->with('success', 'Đăng xuất thành công!');
    }
}
