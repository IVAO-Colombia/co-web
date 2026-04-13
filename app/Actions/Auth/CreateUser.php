<?php

declare(strict_types=1);

namespace App\Actions\Auth;

use App\Models\User;
use SocialiteProviders\Manager\OAuth2\User as SocialiteUser;

class CreateUser
{
    public function handle(SocialiteUser $ivaoUser): User
    {
        $userData = [
            'country_id' => $ivaoUser->user['countryId'] ?? null,
            'division_id' => $ivaoUser->user['divisionId'] ?? null,
            'language_id' => $ivaoUser->user['languageId'] ?? null,
            'network_rating' => $ivaoUser->user['rating']['networkRating']['id'] ?? null,
            'atc_rating' => $ivaoUser->user['rating']['atcRating']['id'] ?? null,
            'pilot_rating' => $ivaoUser->user['rating']['pilotRating']['id'] ?? null,
        ];

        if ($user = User::where('vid', $ivaoUser->id)->first()) {
            $user->update($userData);

            return $user;
        }

        return User::create([
            'name' => $ivaoUser->name,
            'email' => $ivaoUser->email,
            'vid' => $ivaoUser->id,
        ] + $userData);
    }
}
