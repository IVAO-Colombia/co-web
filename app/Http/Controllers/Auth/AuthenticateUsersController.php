<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Actions\Auth\CreateUser;
use App\Actions\Auth\SyncUserRoles;
use App\Http\Controllers\Controller;
use App\Models\User;
use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Pipeline;
use Laravel\Socialite\Facades\Socialite;
use SocialiteProviders\Manager\OAuth2\User as SocialiteUser;

class AuthenticateUsersController extends Controller
{
    public function __invoke(): RedirectResponse
    {
        Pipeline::send(Socialite::driver('ivao')->user())
            ->through([
                fn(SocialiteUser $ivaoUser, Closure $next) => $next(new CreateUser()->handle($ivaoUser)),
                fn(User $user, Closure $next) => $next(new SyncUserRoles()->handle($user)),
            ])
            ->then(fn (User $user) => auth()->guard()->login($user));

        return redirect()->intended(route('dashboard'));
    }
}
