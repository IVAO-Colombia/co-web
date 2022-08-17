<?php

namespace App\Http\Middleware;

use Closure;

class Localization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (\Session::has('locale')) {
            \App::setlocale(\Session::get('locale'));
        }
        else {
            $locale = substr($request->server('HTTP_ACCEPT_LANGUAGE'), 0, 2);

            if ($locale != 'es' && $locale != 'en') {
                $locale = 'en';
            }

            \App::setlocale($locale);
        }
        return $next($request);
    }
}
