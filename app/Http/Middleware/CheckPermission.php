<?php 

use Closure;

class CheckPermission
{
    public function handle($request, Closure $next, $permission)
    {
        if (auth()->check() && auth()->user()->can($permission)) {
            return $next($request);
        }

        abort(403, 'Bạn không có quyền truy cập.');
    }
}
