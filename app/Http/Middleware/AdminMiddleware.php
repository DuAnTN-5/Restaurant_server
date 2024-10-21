<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    // Định nghĩa vai trò là chuỗi
    const ROLE_ADMIN = 1; // Thay đổi thành số nguyên cho phù hợp với giá trị trong cơ sở dữ liệu
    const ROLE_USER = 0;  // Thêm vai trò User
    const ROLE_MANAGER = 2; // Thêm vai trò Quản lý
    const ROLE_STAFF = 3; // Thêm vai trò Nhân viên

    public function handle(Request $request, Closure $next)
{
    if (Auth::check()) {
        \Log::info('Role của người dùng: ' . Auth::user()->role);
        
        // Chỉ cho phép Admin và Manager truy cập
        if (Auth::user()->role == self::ROLE_ADMIN || 
            Auth::user()->role == self::ROLE_MANAGER) {
            return $next($request);
        } else {
            return redirect('/')->with('error', 'Bạn không có quyền truy cập trang admin.');
        }
    }

    return redirect('/login');
}

}
