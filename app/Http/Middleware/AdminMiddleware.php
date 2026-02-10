<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Kiểm tra đăng nhập
        if (!auth()->check()) {
            return redirect()->route('admin.login')->with('error', 'Vui lòng đăng nhập để tiếp tục.');
        }

        // Kiểm tra quyền admin - Chỉ redirect, KHÔNG logout user
        if (!auth()->user()->is_admin) {
            // Nếu không phải admin, chỉ redirect về trang chủ (không logout)
            // Điều này cho phép user thường vẫn sử dụng chatbot bình thường
            return redirect()->route('home')->with('error', 'Bạn không có quyền truy cập vào trang quản trị.');
        }

        return $next($request);
    }
}
