<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;

class LanguageMiddleware
{
    public function handle($request, Closure $next)
    {
        $locale = session('app_locale', config('app.fallback_locale'));
        App::setLocale($locale);
        return $next($request);
    }
}


