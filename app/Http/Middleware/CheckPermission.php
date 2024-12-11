<?php 
namespace App\Http\Middleware;
use Closure;

class CheckPermission
{
    public function handle($request, Closure $next, $permission)
    {
        if (auth()->check() && auth()->user()->hasPermissionTo($permission)) {
            return $next($request);
        }

        abort(403, 'Bạn không có quyền truy cập.');
    }
}
