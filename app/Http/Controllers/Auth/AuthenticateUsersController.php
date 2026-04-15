<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Actions\Auth\CreateUser;
use App\Actions\Auth\SyncUserRoles;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Laravel\Socialite\Facades\Socialite;
use SocialiteProviders\Manager\OAuth2\User as SocialiteUser;

class AuthenticateUsersController extends Controller
{
    public function __invoke(): RedirectResponse
    {
        Socialite::driver('ivao')->user()
            |> (fn (SocialiteUser $ivaoUser) => new CreateUser()->handle($ivaoUser))
            |> (fn (User $user) => new SyncUserRoles()->handle($user))
            |> (fn (User $user) => auth()->guard()->login($user));

        return redirect()->intended(route('dashboard'));
    }
}
