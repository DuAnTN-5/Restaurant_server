<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role)
    {
        // Lấy thông tin người dùng hiện tại
        $user = Auth::user();

        // Kiểm tra nếu người dùng chưa đăng nhập
        if (!$user) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để tiếp tục.');
        }

        // Lấy role_detail thông qua staff
        $roleDetail = optional($user->staff)->role_detail;
        // dd($roleDetail);

        // Kiểm tra quyền
        if (!$roleDetail || $roleDetail->$role > 1) {
            // Trả về lỗi nếu không có quyền 
            abort(403, 'Bạn không có quyền truy cập vào chức năng này.');
        }

        // Nếu có quyền, tiếp tục request
        return $next($request);
    }
}
