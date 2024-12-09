<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{

    public function handle(Request $request, Closure $next)
    {
        // Kiểm tra nếu người dùng đã đăng nhập và có role là 2 (admin), 3 (manager), hoặc 4 (staff)
        if (Auth::check() && auth()->user()->hasAnyRole(['Admin', 'Manager', 'Staff'])) {
            return $next($request);
        }

        // Nếu không thỏa mãn, trả về mã lỗi 403 với thông báo
        return abort(404, 'Bạn không có quyền truy cập vào trang này.');
    }
}


