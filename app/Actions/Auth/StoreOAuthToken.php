<?php

declare(strict_types=1);

namespace App\Actions\Auth;

use App\Models\User;
use App\Models\UserOAuthToken;
use SocialiteProviders\Manager\OAuth2\User as SocialiteUser;

class StoreOAuthToken
{
    public function handle(User $user, SocialiteUser $ivaoUser): User
    {
        UserOAuthToken::updateOrCreate(
            ['user_id' => $user->id],
            [
                'access_token' => $ivaoUser->token,
                'refresh_token' => $ivaoUser->refreshToken ?: null,
                'expires_at' => $ivaoUser->expiresIn > 0 ? now()->addSeconds($ivaoUser->expiresIn) : null,
                'scopes' => $ivaoUser->approvedScopes ?: null,
            ],
        );

        return $user;
    }
}
