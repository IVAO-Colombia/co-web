<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Actions\Auth\CreateUser;
use App\Actions\Auth\StoreOAuthToken;
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
        /** @var SocialiteUser $ivaoUser */
        $ivaoUser = Socialite::driver('ivao')->user();

        /** @var User $user */
        $user = Pipeline::send($ivaoUser)
            ->through([
                fn (SocialiteUser $user, Closure $next) => $next(new CreateUser()->handle($user)),
                fn (User $user, Closure $next) => $next(new SyncUserRoles()->handle($user)),
                fn (User $user, Closure $next) => $next(new StoreOAuthToken()->handle($user, $ivaoUser)),
            ])
            ->then(fn (User $user) => auth()->guard()->login($user));

        return redirect()->intended(route('dashboard'));
    }
}
