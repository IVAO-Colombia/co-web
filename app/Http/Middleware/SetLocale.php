<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /** @var string[] */
    private const array SUPPORTED_LOCALES = ['es', 'en'];

    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->cookie('locale', config('app.locale'));

        app()->setLocale(in_array($locale, self::SUPPORTED_LOCALES, true) ? $locale : config('app.locale'));

        return $next($request);
    }
}
