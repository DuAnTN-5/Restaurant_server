<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{

    public function handle(Request $request, Closure $next)
    {
        // Kiểm tra nếu người dùng đã đăng nhập và có role là 1 (admin), 2 (manager), hoặc 3 (staff)
        if (Auth::check() && in_array(Auth::user()->role, [1, 2, 3])) {
            return $next($request);
        }

        // Nếu không thỏa mãn, trả về mã lỗi 403 với thông báo
        return abort(404, 'Bạn không có quyền truy cập vào trang này.');
    }
}


