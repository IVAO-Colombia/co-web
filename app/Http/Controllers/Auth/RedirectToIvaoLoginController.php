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
        session()->put('url.intended', url()->previous());

        return Inertia::location(
            Socialite::driver('ivao')
                ->scopes(['profile', 'email', 'bookings:read', 'bookings:write'])
                ->redirect()
        );
    }
}
