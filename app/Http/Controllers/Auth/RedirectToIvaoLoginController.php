<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Laravel\Socialite\Facades\Socialite;
use Symfony\Component\HttpFoundation\Response;

class RedirectToIvaoLoginController extends Controller
{
    public function __invoke(): Response
    {
        $previousUrl = str(url()->previous())->chopEnd('/')->toString();

        if (! session()->has('url.intended') && $previousUrl !== route('home')) {
            session()->put('url.intended', url()->previous());
        }

        return Inertia::location(
            Socialite::driver('ivao')
                ->scopes(['profile', 'email', 'bookings:read', 'bookings:write', 'tracker'])
                ->redirect()
        );
    }
}
