<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;
class AdminMiddleware
{

    public function handle($request, Closure $next)
{
    if (Auth::check()) {
        \Log::info('Role của người dùng: ' . Auth::user()->role);
        if (Auth::user()->role == 1) {
            return $next($request);
        } else {
            return redirect('/')->with('error', 'Bạn không có quyền truy cập trang admin.');
        }
    }

    return redirect('/login');
}


}
