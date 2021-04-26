<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Locale
{
    const SESSION_KEY = 'locale';
    const LOCALES = ['en', 'sk'];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $lang = $request->getPreferredLanguage(self::LOCALES);
        app()->setLocale($lang);
        return $next($request);
    }
}
