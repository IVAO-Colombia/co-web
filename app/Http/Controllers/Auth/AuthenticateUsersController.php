<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Actions\Auth\CreateUser;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Laravel\Socialite\Facades\Socialite;
use SocialiteProviders\Manager\OAuth2\User as SocialiteUser;

class AuthenticateUsersController extends Controller
{
    public function __invoke(): RedirectResponse
    {
        /** @var SocialiteUser $ivaoUser */
        $ivaoUser = Socialite::driver('ivao')->user();

        $user = (new CreateUser)->handle($ivaoUser);

        auth()->guard()->login($user);

        return redirect()->intended(route('dashboard'));
    }
}
