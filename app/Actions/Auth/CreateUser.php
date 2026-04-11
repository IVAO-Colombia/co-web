<?php

declare(strict_types=1);

namespace App\Actions\Auth;

use App\Models\User;
use SocialiteProviders\Manager\OAuth2\User as SocialiteUser;

class CreateUser
{
    public function handle(SocialiteUser $ivaoUser): User
    {
        if ($user = User::where('vid', $ivaoUser->id)->first()) {
            return $user;
        }

        return User::create([
            'name' => $ivaoUser->name,
            'email' => $ivaoUser->email,
            'vid' => $ivaoUser->id,
            'division' => $ivaoUser->user['divisionId'] ?? null,
            'atc_rating' => $ivaoUser->user['rating']['atcRating']['id'] ?? null,
            'pilot_rating' => $ivaoUser->user['rating']['pilotRating']['id'] ?? null,
        ]);
    }
}
