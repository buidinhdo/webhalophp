<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PreventAdminAccess
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Cho phép admin xem website nếu có preview=1 trong query string
        if ($request->query('preview') === '1') {
            return $next($request);
        }

        // Nếu là admin và đang truy cập trang user, redirect về admin dashboard
        if (auth()->check() && auth()->user()->is_admin) {
            return redirect()->route('admin.dashboard');
        }

        return $next($request);
    }
}
